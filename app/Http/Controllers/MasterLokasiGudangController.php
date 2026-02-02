<?php

namespace App\Http\Controllers;

use App\Models\MasterLokasiGudang;
use Illuminate\Http\Request;

class MasterLokasiGudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MasterLokasiGudang::query()
            ->withCount(['penyimpananNgs']); // Count items only (penerimaanBarangs removed - model doesn't exist)

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_lokasi', 'like', "%{$search}%")
                  ->orWhere('nama_lokasi', 'like', "%{$search}%")
                  ->orWhere('lokasi_lengkap', 'like', "%{$search}%");
            });
        }

        // Filter by zone
        if ($request->has('zone') && $request->zone != '') {
            $query->where('zone', $request->zone);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status);
        }

        $masterLokasiGudangs = $query->latest()->paginate(10);

        return view('menu-sidebar.master-data.master-lokasi-gudang-index', compact('masterLokasiGudangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('menu-sidebar.master-data.master-lokasi-gudang-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_lokasi' => 'required|string|max:50|unique:master_lokasi_gudangs,kode_lokasi',
            'nama_lokasi' => 'required|string|max:255',
            'zone' => 'required|in:zona_a,zona_b,zona_c,zona_d',
            'rack' => 'required|string|max:50',
            'bin' => 'required|string|max:50',
            'kapasitas_max' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        // Auto-generate lokasi_lengkap
        $validated['lokasi_lengkap'] = strtoupper(substr($validated['zone'], 5, 1)) . 
                                       $validated['rack'] . '-' . 
                                       $validated['bin'];
        
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $lokasiGudang = MasterLokasiGudang::create($validated);

        // Check if opened from popup (via warehouse verification)
        if ($request->has('from_popup')) {
            return redirect()
                ->route('master-lokasi-gudang.show', $lokasiGudang)
                ->with('success', 'Lokasi gudang berhasil ditambahkan!')
                ->with('close_popup', true);
        }

        return redirect()
            ->route('master-lokasi-gudang.show', $lokasiGudang)
            ->with('success', 'Lokasi gudang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterLokasiGudang $masterLokasiGudang)
    {
        // Load relasi penyimpanan NG dengan eager loading
        $masterLokasiGudang->load([
            'penyimpananNgs' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'penyimpananNgs.produk',
            'penyimpananNgs.defects',
            'penyimpananNgs.disposisi',
            // penerimaanBarangs removed - PenerimaanBarang model doesn't exist
        ]);

        // Hitung statistik
        $stats = [
            'total_items' => $masterLokasiGudang->penyimpananNgs->count(),
            'total_quantity' => $masterLokasiGudang->penyimpananNgs->sum('qty_sisa'),
            'utilization_percentage' => $masterLokasiGudang->kapasitas_max > 0 
                ? round(($masterLokasiGudang->penyimpananNgs->sum('qty_sisa') / $masterLokasiGudang->kapasitas_max) * 100, 2)
                : 0,
            'status_breakdown' => $masterLokasiGudang->penyimpananNgs->groupBy('status_barang')->map->count(),
            'recent_additions' => $masterLokasiGudang->penyimpananNgs->take(10),
            'defect_summary' => $masterLokasiGudang->penyimpananNgs->pluck('defects')->flatten()->groupBy('nama_defect')->map->count()->sortDesc()->take(5),
            // Penerimaan Barang statistics removed - model doesn't exist
        ];

        return view('menu-sidebar.master-data.master-lokasi-gudang-show', compact('masterLokasiGudang', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterLokasiGudang $masterLokasiGudang)
    {
        return view('menu-sidebar.master-data.master-lokasi-gudang-edit', compact('masterLokasiGudang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterLokasiGudang $masterLokasiGudang)
    {
        $validated = $request->validate([
            'kode_lokasi' => 'required|string|max:50|unique:master_lokasi_gudangs,kode_lokasi,' . $masterLokasiGudang->id,
            'nama_lokasi' => 'required|string|max:255',
            'zone' => 'required|in:zona_a,zona_b,zona_c,zona_d',
            'rack' => 'required|string|max:50',
            'bin' => 'required|string|max:50',
            'kapasitas_max' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
        ]);

        // Auto-generate lokasi_lengkap
        $validated['lokasi_lengkap'] = strtoupper(substr($validated['zone'], 5, 1)) . 
                                       $validated['rack'] . '-' . 
                                       $validated['bin'];
        
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $masterLokasiGudang->update($validated);

        return redirect()
            ->route('master-lokasi-gudang.show', $masterLokasiGudang)
            ->with('success', 'Lokasi gudang berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterLokasiGudang $masterLokasiGudang)
    {
        // Check if ada barang tersimpan
        if ($masterLokasiGudang->penyimpananNgs()->count() > 0) {
            return redirect()
                ->route('master-lokasi-gudang.index')
                ->with('error', 'Tidak dapat menghapus lokasi yang masih memiliki barang tersimpan!');
        }

        $masterLokasiGudang->delete();

        return redirect()
            ->route('master-lokasi-gudang.index')
            ->with('success', 'Lokasi gudang berhasil dihapus!');
    }
}
