<?php

namespace App\Http\Controllers;

use App\Models\DokumenRetur;
use App\Models\CustomerComplaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenReturController extends Controller
{
    public function index()
    {
        $dokumenReturs = DokumenRetur::with(['customerComplaint', 'staffExim', 'warehouseVerification'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get statistics
        $totalDokumen = DokumenRetur::count();
        $draftCount = DokumenRetur::where('status', 'draft')->count();
        $submittedCount = DokumenRetur::where('status', 'dikirim_ke_warehouse')->count();
        $approvedCount = DokumenRetur::where('status', 'diterima_warehouse')->count();

        return view('menu-sidebar.dokumen-retur.index', compact(
            'dokumenReturs',
            'totalDokumen',
            'draftCount',
            'submittedCount',
            'approvedCount'
        ));
    }

    public function create()
    {
        // Get complaints that are ready for return document (submitted, approved, or processing)
        // and don't have a return document yet
        $availableComplaints = CustomerComplaint::whereIn('status', ['submitted', 'approved', 'processing'])
            ->whereDoesntHave('dokumenRetur')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('menu-sidebar.dokumen-retur.create', compact('availableComplaints'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_complaint_id' => 'required|exists:customer_complaints,id',
            'nomor_referensi' => 'nullable|string|max:255',
            'instruksi_retur' => 'required|string',
            'jenis_retur' => 'required|in:full_return,partial_return,replacement,credit_note',
            'dokumen_retur.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'catatan_tambahan' => 'nullable|string'
        ]);

        // Handle document uploads
        $dokumenRetur = [];
        if ($request->hasFile('dokumen_retur')) {
            foreach ($request->file('dokumen_retur') as $file) {
                $path = $file->store('dokumen-retur', 'public');
                $dokumenRetur[] = $path;
            }
        }

        $validated['dokumen_retur'] = $dokumenRetur;
        $validated['tanggal_dokumen'] = now();
        $validated['staff_exim_id'] = Auth::id();
        $validated['status'] = 'draft';

        DokumenRetur::create($validated);

        return redirect()->route('dokumen-retur.index')
            ->with('success', 'Dokumen retur berhasil dibuat');
    }

    public function show($id)
    {
        $dokumenRetur = DokumenRetur::with([
            'customerComplaint', 
            'staffExim', 
            'warehouseVerification'
        ])->findOrFail($id);
        
        return view('menu-sidebar.dokumen-retur.show', compact('dokumenRetur'));
    }

    public function edit($id)
    {
        $dokumenRetur = DokumenRetur::findOrFail($id);
        $availableComplaints = CustomerComplaint::where('status', 'processing')->get();
        
        return view('menu-sidebar.dokumen-retur.edit', compact('dokumenRetur', 'availableComplaints'));
    }

    public function update(Request $request, $id)
    {
        $dokumenRetur = DokumenRetur::findOrFail($id);
        
        $validated = $request->validate([
            'nomor_referensi' => 'nullable|string|max:255',
            'instruksi_retur' => 'required|string',
            'jenis_retur' => 'required|in:full_return,partial_return,replacement,credit_note',
            'status' => 'required|in:draft,sent_to_warehouse,received_by_warehouse',
            'catatan_tambahan' => 'nullable|string'
        ]);

        // Handle new document uploads
        $dokumenFiles = $dokumenRetur->dokumen_retur ?? [];
        if ($request->hasFile('dokumen_retur')) {
            foreach ($request->file('dokumen_retur') as $file) {
                $path = $file->store('dokumen-retur', 'public');
                $dokumenFiles[] = $path;
            }
        }

        $validated['dokumen_retur'] = $dokumenFiles;

        if ($validated['status'] == 'sent_to_warehouse' && !$dokumenRetur->tanggal_kirim) {
            $validated['tanggal_kirim'] = now();
        }

        $dokumenRetur->update($validated);

        return redirect()->route('dokumen-retur.show', $dokumenRetur->id)
            ->with('success', 'Dokumen retur berhasil diupdate');
    }

    public function sendToWarehouse($id)
    {
        $dokumenRetur = DokumenRetur::findOrFail($id);
        
        $dokumenRetur->update([
            'status' => 'sent_to_warehouse',
            'tanggal_kirim' => now()
        ]);

        // Update complaint status
        $dokumenRetur->customerComplaint->update(['status' => 'processing']);

        return redirect()->back()->with('success', 'Dokumen retur berhasil dikirim ke warehouse');
    }

    public function destroy($id)
    {
        $dokumenRetur = DokumenRetur::findOrFail($id);
        
        // Delete uploaded files
        if ($dokumenRetur->dokumen_retur) {
            foreach ($dokumenRetur->dokumen_retur as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $dokumenRetur->delete();

        return redirect()->route('dokumen-retur.index')
            ->with('success', 'Dokumen retur berhasil dihapus');
    }
}