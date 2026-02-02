<?php

namespace App\Http\Controllers;

use App\Models\MasterDefect;
use Illuminate\Http\Request;

class MasterDefectController extends Controller
{
    public function index()
    {
        $defects = MasterDefect::withCount([
            'qualityReinspections'
        ])->paginate(15);
        
        // Statistics
        $totalDefect = MasterDefect::count();
        $defectAktif = MasterDefect::where('is_active', true)->count();
        $criticalDefect = MasterDefect::where('criticality_level', 'critical')->count();
        $reworkable = MasterDefect::where('is_rework_possible', true)->count();
        
        return view('menu-sidebar.master-data.master-defect', compact(
            'defects',
            'totalDefect',
            'defectAktif',
            'criticalDefect',
            'reworkable'
        ));
    }

    public function create()
    {
        return view('menu-sidebar.master-data.master-defect-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_defect' => 'required|unique:master_defects|max:50',
            'nama_defect' => 'required|max:255',
            'deskripsi' => 'nullable|string',
            'criticality_level' => 'required|in:minor,major,critical',
            'sumber_masalah' => 'required|in:supplier,customer,proses_produksi,handling_gudang,lainnya',
            'solusi_standar' => 'nullable|string',
            'is_rework_possible' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
        ]);

        // Convert string to boolean for storage
        $validated['is_rework_possible'] = (bool) $validated['is_rework_possible'];
        $validated['is_active'] = (bool) $validated['is_active'];

        MasterDefect::create($validated);

        return redirect()->route('master-defect.index')->with('success', 'Master Defect berhasil ditambahkan!');
    }

    public function show(MasterDefect $masterDefect)
    {
        return view('menu-sidebar.master-data.master-defect-show', compact('masterDefect'));
    }

    public function edit(MasterDefect $masterDefect)
    {
        return view('menu-sidebar.master-data.master-defect-edit', compact('masterDefect'));
    }

    public function update(Request $request, MasterDefect $masterDefect)
    {
        $validated = $request->validate([
            'nama_defect' => 'required|max:255',
            'deskripsi' => 'nullable|string',
            'criticality_level' => 'required|in:minor,major,critical',
            'sumber_masalah' => 'required|in:supplier,customer,proses_produksi,handling_gudang,lainnya',
            'solusi_standar' => 'nullable|string',
            'is_rework_possible' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
        ]);

        // Convert string to boolean for storage
        $validated['is_rework_possible'] = (bool) $validated['is_rework_possible'];
        $validated['is_active'] = (bool) $validated['is_active'];

        // Debug logging
        \Log::info('Master Defect Update', [
            'id' => $masterDefect->id,
            'request_all' => $request->all(),
            'validated' => $validated,
            'is_rework_possible' => $validated['is_rework_possible'],
            'is_active' => $validated['is_active'],
        ]);

        $masterDefect->update($validated);

        return redirect()->route('master-defect.show', $masterDefect)->with('success', 'Master Defect berhasil diperbarui!');
    }

    public function destroy(MasterDefect $masterDefect)
    {
        $masterDefect->delete();

        return redirect()->route('master-defect.index')->with('success', 'Master Defect berhasil dihapus!');
    }
}
