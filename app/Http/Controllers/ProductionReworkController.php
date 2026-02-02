<?php

namespace App\Http\Controllers;

use App\Models\ProductionRework;
use App\Models\QualityReinspection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductionReworkController extends Controller
{
    public function index()
    {
        $productionReworks = ProductionRework::with([
            'qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint',
            'productionManager',
            'finalQualityCheck'
        ])->orderBy('created_at', 'desc')->paginate(10);

        // Calculate stats
        $totalRework = ProductionRework::count();
        $inProgressCount = ProductionRework::where('status', 'in_progress')->count();
        $completedCount = ProductionRework::where('status', 'completed')->count();
        $pendingCount = ProductionRework::where('status', 'draft')->orWhere('status', 'pending')->count();

        return view('menu-sidebar.production-rework.index', compact(
            'productionReworks',
            'totalRework',
            'inProgressCount',
            'completedCount',
            'pendingCount'
        ));
    }

    public function create()
    {
        $availableInspections = QualityReinspection::where('status', 'sent_to_production')
            ->where('disposisi', 'rework')
            ->whereDoesntHave('productionRework')
            ->with('warehouseVerification.dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.production-rework.create', compact('availableInspections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quality_reinspection_id' => 'required|exists:quality_reinspections,id',
            'tanggal_mulai_rework' => 'required|date',
            'metode_rework' => 'required|in:melting,welding,machining,surface_treatment,assembly,other',
            'deskripsi_rework' => 'required|string',
            'instruksi_rework' => 'required|string',
            'quantity_rework' => 'required|integer|min:1',
            'dokumen_proses.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'estimasi_biaya' => 'required|numeric|min:0',
            'estimasi_waktu_hari' => 'required|integer|min:1',
            'pic_rework' => 'nullable|string|max:255'
        ]);

        // Handle document uploads
        $dokumenProses = [];
        if ($request->hasFile('dokumen_proses')) {
            foreach ($request->file('dokumen_proses') as $file) {
                $path = $file->store('production/rework-documents', 'public');
                $dokumenProses[] = $path;
            }
        }

        $validated['dokumen_proses'] = $dokumenProses;
        $validated['production_manager_id'] = Auth::id();
        $validated['status'] = 'draft';

        ProductionRework::create($validated);

        return redirect()->route('production-rework.index')
            ->with('success', 'Production rework berhasil dibuat');
    }

    public function show($id)
    {
        $rework = ProductionRework::with([
            'qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint',
            'productionManager',
            'finalQualityCheck'
        ])->findOrFail($id);
        
        return view('menu-sidebar.production-rework.show', compact('rework'));
    }

    public function edit($id)
    {
        $rework = ProductionRework::findOrFail($id);
        $availableInspections = QualityReinspection::where('status', 'sent_to_production')
            ->where('disposisi', 'rework')
            ->with('warehouseVerification.dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.production-rework.edit', compact('rework', 'availableInspections'));
    }

    public function update(Request $request, $id)
    {
        $rework = ProductionRework::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal_mulai_rework' => 'required|date',
            'tanggal_selesai_rework' => 'nullable|date|after_or_equal:tanggal_mulai_rework',
            'metode_rework' => 'required|in:melting,welding,machining,surface_treatment,assembly,other',
            'deskripsi_rework' => 'required|string',
            'instruksi_rework' => 'required|string',
            'quantity_rework' => 'required|integer|min:1',
            'quantity_hasil_ok' => 'nullable|integer|min:0',
            'quantity_hasil_ng' => 'nullable|integer|min:0',
            'catatan_proses' => 'nullable|string',
            'estimasi_biaya' => 'required|numeric|min:0',
            'actual_biaya' => 'nullable|numeric|min:0',
            'estimasi_waktu_hari' => 'required|integer|min:1',
            'actual_waktu_hari' => 'nullable|integer|min:0',
            'status' => 'required|in:draft,in_progress,completed,sent_to_warehouse',
            'pic_rework' => 'nullable|string|max:255'
        ]);

        // Validate result quantities if status is completed
        if ($validated['status'] == 'completed' || $validated['status'] == 'sent_to_warehouse') {
            if (($validated['quantity_hasil_ok'] ?? 0) + ($validated['quantity_hasil_ng'] ?? 0) != $validated['quantity_rework']) {
                return back()->withErrors(['quantity_hasil_ok' => 'Total quantity hasil OK dan NG harus sama dengan quantity rework'])->withInput();
            }
        }

        // Handle new document uploads
        $dokumenProses = $rework->dokumen_proses ?? [];
        if ($request->hasFile('dokumen_proses')) {
            foreach ($request->file('dokumen_proses') as $file) {
                $path = $file->store('production/rework-documents', 'public');
                $dokumenProses[] = $path;
            }
        }

        $validated['dokumen_proses'] = $dokumenProses;

        $rework->update($validated);

        return redirect()->route('production-rework.show', $rework->id)
            ->with('success', 'Production rework berhasil diupdate');
    }

    public function start($id)
    {
        $rework = ProductionRework::findOrFail($id);
        
        $rework->update([
            'status' => 'in_progress',
            'tanggal_mulai_rework' => now(),
        ]);
        
        return redirect()->route('production-rework.show', $rework->id)
            ->with('success', 'Proses rework berhasil dimulai!');
    }

    public function complete($id)
    {
        $rework = ProductionRework::findOrFail($id);
        
        // Validate that quantity OK and NG are filled
        if (is_null($rework->quantity_hasil_ok) && is_null($rework->quantity_hasil_ng)) {
            return redirect()->back()
                ->with('error', 'Silakan update quantity OK dan NG sebelum menyelesaikan rework.');
        }
        
        $rework->update([
            'status' => 'completed',
            'tanggal_selesai_rework' => now(),
        ]);
        
        return redirect()->route('production-rework.show', $rework->id)
            ->with('success', 'Rework berhasil diselesaikan!');
    }

    public function sendToWarehouse($id)
    {
        $rework = ProductionRework::findOrFail($id);
        
        $rework->update([
            'status' => 'sent_to_warehouse',
        ]);
        
        // Update quality reinspection status
        $rework->qualityReinspection->update([
            'status' => 'rework_completed',
        ]);
        
        return redirect()->route('production-rework.show', $rework->id)
            ->with('success', 'Barang sudah dilaporkan ke Warehouse. Menunggu Export/Import Staff membuat Final Decision.');
    }

    public function destroy($id)
    {
        $rework = ProductionRework::findOrFail($id);
        
        // Delete uploaded documents
        if ($rework->dokumen_proses) {
            foreach ($rework->dokumen_proses as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $rework->delete();

        return redirect()->route('production-rework.index')
            ->with('success', 'Production rework berhasil dihapus');
    }
}