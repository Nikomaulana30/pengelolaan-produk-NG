<?php

namespace App\Http\Controllers;

use App\Models\ReturnShipment;
use App\Models\FinalQualityCheck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReturnShipmentController extends Controller
{
    public function index()
    {
        $returnShipments = ReturnShipment::with([
            'finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint',
            'warehouseStaff'
        ])->orderBy('created_at', 'desc')->paginate(10);

        // Calculate stats
        $totalShipments = ReturnShipment::count();
        $pendingCount = ReturnShipment::where('status_pengiriman', 'preparing')->count();
        $shippedCount = ReturnShipment::where('status_pengiriman', 'shipped')->count();
        $deliveredCount = ReturnShipment::where('status_pengiriman', 'delivered')->count();

        return view('menu-sidebar.return-shipment.index', compact(
            'returnShipments',
            'totalShipments',
            'pendingCount',
            'shippedCount',
            'deliveredCount'
        ));
    }

    public function create()
    {
        // Only check keputusan_final, not status
        $availableQualityChecks = FinalQualityCheck::where('keputusan_final', 'approved_for_shipment')
            ->whereDoesntHave('returnShipment')
            ->with('productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.return-shipment.create', compact('availableQualityChecks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'final_quality_check_id' => 'required|exists:final_quality_checks,id',
            'tanggal_pengiriman' => 'required|date',
            'quantity_shipped' => 'required|integer|min:1',
            'ekspedisi' => 'required|string|max:255',
            'nomor_resi' => 'nullable|string|max:255',
            'alamat_pengiriman' => 'required|string',
            'catatan_pengiriman' => 'nullable|string',
            'dokumen_pengiriman.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'biaya_pengiriman' => 'nullable|numeric|min:0'
        ]);

        // Handle shipment document uploads
        $dokumenPengiriman = [];
        if ($request->hasFile('dokumen_pengiriman')) {
            foreach ($request->file('dokumen_pengiriman') as $file) {
                $path = $file->store('shipment/documents', 'public');
                $dokumenPengiriman[] = $path;
            }
        }

        $validated['dokumen_pengiriman'] = $dokumenPengiriman;
        $validated['warehouse_staff_id'] = Auth::id();
        $validated['status_pengiriman'] = 'preparing';
        $validated['status'] = 'draft';

        $shipment = ReturnShipment::create($validated);

        return redirect()->route('return-shipment.show', $shipment)
            ->with('success', 'Return shipment berhasil dibuat');
    }

    public function show($id)
    {
        $shipment = ReturnShipment::with([
            'finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint',
            'warehouseStaff'
        ])->findOrFail($id);
        
        return view('menu-sidebar.return-shipment.show', compact('shipment'));
    }

    public function edit($id)
    {
        $shipment = ReturnShipment::findOrFail($id);
        // Only check keputusan_final, not status
        $availableQualityChecks = FinalQualityCheck::where('keputusan_final', 'approved_for_shipment')
            ->with('productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.return-shipment.edit', compact('shipment', 'availableQualityChecks'));
    }

    public function update(Request $request, $id)
    {
        $shipment = ReturnShipment::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal_pengiriman' => 'required|date',
            'quantity_shipped' => 'required|integer|min:1',
            'ekspedisi' => 'required|string|max:255',
            'nomor_resi' => 'nullable|string|max:255',
            'alamat_pengiriman' => 'required|string',
            'catatan_pengiriman' => 'nullable|string',
            'biaya_pengiriman' => 'nullable|numeric|min:0',
            'status_pengiriman' => 'required|in:preparing,shipped,delivered,failed',
            'status' => 'required|in:draft,shipped,completed',
            'catatan_delivery' => 'nullable|string',
            'rating_customer' => 'nullable|integer|min:1|max:5'
        ]);

        // Handle new document uploads
        $dokumenPengiriman = $shipment->dokumen_pengiriman ?? [];
        if ($request->hasFile('dokumen_pengiriman')) {
            foreach ($request->file('dokumen_pengiriman') as $file) {
                $path = $file->store('shipment/documents', 'public');
                $dokumenPengiriman[] = $path;
            }
        }

        $validated['dokumen_pengiriman'] = $dokumenPengiriman;

        if ($validated['status_pengiriman'] == 'delivered' && !$shipment->delivered_at) {
            $validated['delivered_at'] = now();
            $validated['status'] = 'completed';
        }

        $shipment->update($validated);

        return redirect()->route('return-shipment.show', $shipment->id)
            ->with('success', 'Return shipment berhasil diupdate');
    }

    public function ship($id)
    {
        $shipment = ReturnShipment::findOrFail($id);
        
        $shipment->update([
            'status_pengiriman' => 'shipped',
            'status' => 'shipped',
            'tanggal_pengiriman' => now()
        ]);

        return redirect()->back()->with('success', 'Shipment berhasil dikirim');
    }

    public function delivered(Request $request, $id)
    {
        $shipment = ReturnShipment::findOrFail($id);
        
        $validated = $request->validate([
            'catatan_delivery' => 'nullable|string',
            'rating_customer' => 'nullable|integer|min:1|max:5'
        ]);

        $validated['status_pengiriman'] = 'delivered';
        $validated['status'] = 'completed';
        $validated['delivered_at'] = now();

        $shipment->update($validated);

        // Update complaint status to completed
        $complaint = $shipment->finalQualityCheck
            ->productionRework
            ->qualityReinspection
            ->warehouseVerification
            ->dokumenRetur
            ->customerComplaint;
            
        $complaint->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Shipment berhasil delivered dan complaint diselesaikan');
    }

    public function trackingInfo($id)
    {
        $shipment = ReturnShipment::with([
            'finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint'
        ])->findOrFail($id);
        
        return view('menu-sidebar.return-shipment.tracking', compact('shipment'));
    }

    public function printShippingLabel($id)
    {
        $shipment = ReturnShipment::with([
            'finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint'
        ])->findOrFail($id);
        
        return view('menu-sidebar.return-shipment.shipping-label', compact('shipment'));
    }

    public function destroy($id)
    {
        $shipment = ReturnShipment::findOrFail($id);
        
        // Delete uploaded documents
        if ($shipment->dokumen_pengiriman) {
            foreach ($shipment->dokumen_pengiriman as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $shipment->delete();

        return redirect()->route('return-shipment.index')
            ->with('success', 'Return shipment berhasil dihapus');
    }
}