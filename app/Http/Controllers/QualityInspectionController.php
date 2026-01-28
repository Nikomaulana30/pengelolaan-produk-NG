<?php

namespace App\Http\Controllers;

use App\Models\QualityInspection;
use Illuminate\Http\Request;

class QualityInspectionController extends Controller
{
    public function index()
    {
        $inspections = QualityInspection::latest()->paginate(20);
        return view('menu-sidebar.inspeksi-qc.index', compact('inspections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_laporan' => 'nullable|string|max:100',
            'tanggal_inspeksi' => 'required|string',
            'product' => 'required|string|max:100',
            'part_no' => 'required|string|max:100',
            'material' => 'required|string|max:100',
            'drawing_no' => 'required|string|max:100',
            'drawing_rev' => 'nullable|string|max:50',
            'customer' => 'required|string|max:100',
            'batch_no' => 'required|string|max:100',
            'made_by' => 'required|string|max:100',
            'approved_by' => 'nullable|string|max:100',
        ]);

        // Generate unique nomor_laporan
        $counter = QualityInspection::count() + 1;
        $nomor_laporan = 'QC-' . date('Ymd') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);

        QualityInspection::create([
            ...$validated,
            'nomor_laporan' => $nomor_laporan,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('inspeksi-qc.index')
            ->with('success', 'Inspeksi QC berhasil disimpan!');
    }

    public function show(QualityInspection $inspection)
    {
        return view('menu-sidebar.inspeksi-qc.show', compact('inspection'));
    }

    public function edit(QualityInspection $inspection)
    {
        return view('menu-sidebar.inspeksi-qc.edit', compact('inspection'));
    }

    public function update(Request $request, QualityInspection $inspection)
    {
        $validated = $request->validate([
            'tanggal_inspeksi' => 'required|string',
            'product' => 'required|string|max:100',
            'part_no' => 'required|string|max:100',
            'material' => 'required|string|max:100',
            'drawing_no' => 'required|string|max:100',
            'drawing_rev' => 'nullable|string|max:50',
            'customer' => 'required|string|max:100',
            'batch_no' => 'required|string|max:100',
            'made_by' => 'required|string|max:100',
            'approved_by' => 'nullable|string|max:100',
        ]);

        $inspection->update($validated);

        return redirect()->route('inspeksi-qc.index')
            ->with('success', 'Inspeksi QC berhasil diperbarui!');
    }

    public function destroy(QualityInspection $inspection)
    {
        $nomor_laporan = $inspection->nomor_laporan;
        $inspection->delete();

        return redirect()->route('inspeksi-qc.index')
            ->with('success', "Inspeksi QC {$nomor_laporan} berhasil dihapus!");
    }
}
