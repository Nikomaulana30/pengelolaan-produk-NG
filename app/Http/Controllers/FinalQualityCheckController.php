<?php

namespace App\Http\Controllers;

use App\Models\FinalQualityCheck;
use App\Models\ProductionRework;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinalQualityCheckController extends Controller
{
    public function index()
    {
        $finalQualityChecks = FinalQualityCheck::with([
            'productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint',
            'staffExim',
            'returnShipment'
        ])->orderBy('created_at', 'desc')->paginate(10);

        // Calculate stats
        $totalQC = FinalQualityCheck::count();
        $pendingCount = FinalQualityCheck::where('status', 'draft')->orWhere('status', 'pending')->count();
        $passedCount = FinalQualityCheck::where('keputusan_final', 'approved_for_shipment')->count();
        $failedCount = FinalQualityCheck::where('keputusan_final', 'rejected')->count();

        return view('menu-sidebar.final-quality-check.index', compact(
            'finalQualityChecks',
            'totalQC',
            'pendingCount',
            'passedCount',
            'failedCount'
        ));
    }

    public function create()
    {
        $availableReworks = ProductionRework::where('status', 'sent_to_warehouse')
            ->whereDoesntHave('finalQualityCheck')
            ->with('qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.final-quality-check.create', compact('availableReworks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_rework_id' => 'required|exists:production_reworks,id',
            'tanggal_check' => 'required|date',
            'quantity_checked' => 'required|integer|min:1',
            'quantity_passed' => 'required|integer|min:0',
            'quantity_failed' => 'required|integer|min:0',
            'hasil_pemeriksaan' => 'required|string',
            'catatan_quality' => 'required|string',
            'keputusan_final' => 'required|in:approved_for_shipment,need_rework,rejected',
            'foto_hasil_check.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_quality.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        // Validate quantity totals
        if (($validated['quantity_passed'] + $validated['quantity_failed']) != $validated['quantity_checked']) {
            return back()->withErrors(['quantity_checked' => 'Total quantity passed dan failed harus sama dengan quantity checked'])->withInput();
        }

        // Handle photo uploads
        $fotoHasilCheck = [];
        if ($request->hasFile('foto_hasil_check')) {
            foreach ($request->file('foto_hasil_check') as $file) {
                $path = $file->store('quality/final-check-photos', 'public');
                $fotoHasilCheck[] = $path;
            }
        }

        // Handle quality document uploads
        $dokumenQuality = [];
        if ($request->hasFile('dokumen_quality')) {
            foreach ($request->file('dokumen_quality') as $file) {
                $path = $file->store('quality/final-documents', 'public');
                $dokumenQuality[] = $path;
            }
        }

        $validated['foto_hasil_check'] = $fotoHasilCheck;
        $validated['dokumen_quality'] = $dokumenQuality;
        $validated['staff_exim_id'] = Auth::id();
        $validated['status'] = 'draft';

        $qualityCheck = FinalQualityCheck::create($validated);

        return redirect()->route('final-quality-check.show', $qualityCheck->id)
            ->with('success', 'Final quality check berhasil dibuat');
    }

    public function show($id)
    {
        $qualityCheck = FinalQualityCheck::with([
            'productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint',
            'staffExim',
            'returnShipment'
        ])->findOrFail($id);
        
        return view('menu-sidebar.final-quality-check.show', compact('qualityCheck'));
    }

    public function edit($id)
    {
        $qualityCheck = FinalQualityCheck::findOrFail($id);
        $availableReworks = ProductionRework::where('status', 'sent_to_quality_check')
            ->with('qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.final-quality-check.edit', compact('qualityCheck', 'availableReworks'));
    }

    public function update(Request $request, $id)
    {
        $qualityCheck = FinalQualityCheck::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal_check' => 'required|date',
            'quantity_checked' => 'required|integer|min:1',
            'quantity_passed' => 'required|integer|min:0',
            'quantity_failed' => 'required|integer|min:0',
            'hasil_pemeriksaan' => 'required|string',
            'catatan_quality' => 'required|string',
            'keputusan_final' => 'required|in:approved_for_shipment,need_rework,rejected',
            'status' => 'required|in:draft,checked,approved_for_shipment'
        ]);

        // Validate quantity totals
        if (($validated['quantity_passed'] + $validated['quantity_failed']) != $validated['quantity_checked']) {
            return back()->withErrors(['quantity_checked' => 'Total quantity passed dan failed harus sama dengan quantity checked'])->withInput();
        }

        // Handle new photo uploads
        $fotoHasilCheck = $qualityCheck->foto_hasil_check ?? [];
        if ($request->hasFile('foto_hasil_check')) {
            foreach ($request->file('foto_hasil_check') as $file) {
                $path = $file->store('quality/final-check-photos', 'public');
                $fotoHasilCheck[] = $path;
            }
        }

        // Handle new quality document uploads
        $dokumenQuality = $qualityCheck->dokumen_quality ?? [];
        if ($request->hasFile('dokumen_quality')) {
            foreach ($request->file('dokumen_quality') as $file) {
                $path = $file->store('quality/final-documents', 'public');
                $dokumenQuality[] = $path;
            }
        }

        $validated['foto_hasil_check'] = $fotoHasilCheck;
        $validated['dokumen_quality'] = $dokumenQuality;

        if ($validated['keputusan_final'] == 'approved_for_shipment' && $validated['status'] == 'approved_for_shipment') {
            $validated['approved_at'] = now();
        }

        $qualityCheck->update($validated);

        return redirect()->route('final-quality-check.show', $qualityCheck->id)
            ->with('success', 'Final quality check berhasil diupdate');
    }

    public function approve($id)
    {
        $qualityCheck = FinalQualityCheck::findOrFail($id);
        
        $qualityCheck->update([
            'status' => 'checked',
            'keputusan_final' => 'approved_for_shipment',
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Quality check berhasil disetujui');
    }

    public function approveForShipment($id)
    {
        $qualityCheck = FinalQualityCheck::findOrFail($id);
        
        $qualityCheck->update([
            'status' => 'approved_for_shipment',
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Quality check berhasil disetujui untuk pengiriman');
    }

    public function reject($id, Request $request)
    {
        $qualityCheck = FinalQualityCheck::findOrFail($id);
        
        $validated = $request->validate([
            'catatan_quality' => 'required|string'
        ]);

        $qualityCheck->update([
            'keputusan_final' => 'rejected',
            'status' => 'checked',
            'catatan_quality' => $validated['catatan_quality']
        ]);

        return redirect()->back()->with('success', 'Quality check ditolak');
    }

    public function destroy($id)
    {
        $qualityCheck = FinalQualityCheck::findOrFail($id);
        
        // Delete uploaded files
        if ($qualityCheck->foto_hasil_check) {
            foreach ($qualityCheck->foto_hasil_check as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        
        if ($qualityCheck->dokumen_quality) {
            foreach ($qualityCheck->dokumen_quality as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $qualityCheck->delete();

        return redirect()->route('final-quality-check.index')
            ->with('success', 'Final quality check berhasil dihapus');
    }
}