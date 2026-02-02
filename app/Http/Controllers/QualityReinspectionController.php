<?php

namespace App\Http\Controllers;

use App\Models\QualityReinspection;
use App\Models\WarehouseVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QualityReinspectionController extends Controller
{
    public function index()
    {
        $qualityReinspections = QualityReinspection::with([
            'warehouseVerification.dokumenRetur.customerComplaint',
            'qualityManager',
            'productionRework'
        ])->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $totalRCA = QualityReinspection::count();
        $inProgressCount = QualityReinspection::where('status', 'draft')->count();
        $completedCount = QualityReinspection::where('status', 'inspected')->count();
        $reworkCount = QualityReinspection::where('disposisi', 'rework')->count();

        return view('menu-sidebar.quality-reinspection.index', compact(
            'qualityReinspections',
            'totalRCA',
            'inProgressCount',
            'completedCount',
            'reworkCount'
        ));
    }

    public function create()
    {
        $availableVerifications = WarehouseVerification::where('status', 'sent_to_quality')
            ->whereDoesntHave('qualityReinspection')
            ->with('dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.quality-reinspection.create', compact('availableVerifications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_verification_id' => 'required|exists:warehouse_verifications,id',
            'tanggal_inspeksi' => 'required|date',
            'jenis_defect' => 'required|string|max:255',
            'deskripsi_defect' => 'required|string',
            'severity_level' => 'required|in:critical,major,minor',
            'quantity_defect' => 'required|integer|min:1',
            'root_cause_analysis' => 'required|string',
            'corrective_action' => 'required|string',
            'preventive_action' => 'required|string',
            'disposisi' => 'required|in:rework,scrap,return_to_vendor,return_to_customer',
            'foto_defect.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_rca.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'estimasi_biaya_rework' => 'nullable|numeric|min:0'
        ]);

        // Handle photo uploads
        $fotoDefect = [];
        if ($request->hasFile('foto_defect')) {
            foreach ($request->file('foto_defect') as $file) {
                $path = $file->store('quality/defect-photos', 'public');
                $fotoDefect[] = $path;
            }
        }

        // Handle RCA document uploads
        $dokumenRca = [];
        if ($request->hasFile('dokumen_rca')) {
            foreach ($request->file('dokumen_rca') as $file) {
                $path = $file->store('quality/rca-documents', 'public');
                $dokumenRca[] = $path;
            }
        }

        $validated['foto_defect'] = $fotoDefect;
        $validated['dokumen_rca'] = $dokumenRca;
        $validated['quality_manager_id'] = Auth::id();
        $validated['status'] = 'draft';

        QualityReinspection::create($validated);

        return redirect()->route('quality-reinspection.index')
            ->with('success', 'Quality reinspection berhasil dibuat');
    }

    public function show($id)
    {
        $inspection = QualityReinspection::with([
            'warehouseVerification.dokumenRetur.customerComplaint',
            'qualityManager',
            'productionRework'
        ])->findOrFail($id);
        
        return view('menu-sidebar.quality-reinspection.show', compact('inspection'));
    }

    public function edit($id)
    {
        $inspection = QualityReinspection::findOrFail($id);
        $availableVerifications = WarehouseVerification::where('status', 'sent_to_quality')
            ->with('dokumenRetur.customerComplaint')
            ->get();
        
        return view('menu-sidebar.quality-reinspection.edit', compact('inspection', 'availableVerifications'));
    }

    public function update(Request $request, $id)
    {
        $inspection = QualityReinspection::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal_inspeksi' => 'required|date',
            'jenis_defect' => 'required|string|max:255',
            'deskripsi_defect' => 'required|string',
            'severity_level' => 'required|in:critical,major,minor',
            'quantity_defect' => 'required|integer|min:1',
            'root_cause_analysis' => 'required|string',
            'corrective_action' => 'required|string',
            'preventive_action' => 'required|string',
            'disposisi' => 'required|in:rework,scrap,return_to_vendor,return_to_customer',
            'status' => 'required|in:draft,inspected,sent_to_production',
            'estimasi_biaya_rework' => 'nullable|numeric|min:0'
        ]);

        // Handle new photo uploads
        $fotoDefect = $inspection->foto_defect ?? [];
        if ($request->hasFile('foto_defect')) {
            foreach ($request->file('foto_defect') as $file) {
                $path = $file->store('quality/defect-photos', 'public');
                $fotoDefect[] = $path;
            }
        }

        // Handle new RCA document uploads
        $dokumenRca = $inspection->dokumen_rca ?? [];
        if ($request->hasFile('dokumen_rca')) {
            foreach ($request->file('dokumen_rca') as $file) {
                $path = $file->store('quality/rca-documents', 'public');
                $dokumenRca[] = $path;
            }
        }

        $validated['foto_defect'] = $fotoDefect;
        $validated['dokumen_rca'] = $dokumenRca;

        if ($validated['status'] == 'inspected' && !$inspection->inspected_at) {
            $validated['inspected_at'] = now();
        }

        $inspection->update($validated);

        return redirect()->route('quality-reinspection.show', $inspection->id)
            ->with('success', 'Quality reinspection berhasil diupdate');
    }

    public function approve($id)
    {
        $inspection = QualityReinspection::findOrFail($id);
        
        $inspection->update([
            'status' => 'inspected',
            'inspected_at' => now()
        ]);

        return redirect()->back()->with('success', 'Inspeksi berhasil disetujui');
    }

    public function sendToProduction($id)
    {
        $inspection = QualityReinspection::findOrFail($id);
        
        $inspection->update([
            'status' => 'sent_to_production'
        ]);

        return redirect()->back()->with('success', 'Inspeksi berhasil dikirim ke Production');
    }

    public function destroy($id)
    {
        $inspection = QualityReinspection::findOrFail($id);
        
        // Delete uploaded files
        if ($inspection->foto_defect) {
            foreach ($inspection->foto_defect as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        
        if ($inspection->dokumen_rca) {
            foreach ($inspection->dokumen_rca as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $inspection->delete();

        return redirect()->route('quality-reinspection.index')
            ->with('success', 'Quality reinspection berhasil dihapus');
    }
}
