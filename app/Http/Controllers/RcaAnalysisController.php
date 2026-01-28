<?php

namespace App\Http\Controllers;

use App\Models\RcaAnalysis;
use App\Models\MasterDefect;
use App\Models\MasterProduk;
use App\Models\ReturBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RcaAnalysisController extends Controller
{
    /**
     * Display a listing of RCA Analyses
     */
    public function index()
    {
        // Get all active defects for dropdown
        $masterDefects = MasterDefect::where('is_active', true)
            ->orderBy('criticality_level', 'desc')
            ->get();

        // Get all active products for dropdown
        $masterProduk = MasterProduk::where('is_active', true)
            ->orderBy('nama_produk', 'asc')
            ->get();

        // Calculate Top Defects for Pareto Chart (Top 10)
        // Check if quality_inspections table exists first
        $topDefects = collect();
        if (Schema::hasTable('quality_inspections')) {
            $topDefects = DB::table('quality_inspections')
                ->join('master_defects', 'quality_inspections.kode_defect', '=', 'master_defects.kode_defect')
                ->select(
                    'master_defects.kode_defect',
                    'master_defects.nama_defect',
                    'master_defects.criticality_level',
                    'master_defects.sumber_masalah',
                    DB::raw('count(*) as total')
                )
                ->where('quality_inspections.hasil', 'NG')
                ->groupBy('master_defects.kode_defect', 'master_defects.nama_defect', 'master_defects.criticality_level', 'master_defects.sumber_masalah')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();
        }

        // Calculate Top Products with most NG (Top 5)
        // Check if quality_inspections table exists first
        $topProduk = collect();
        if (Schema::hasTable('quality_inspections')) {
            $topProduk = DB::table('quality_inspections')
                ->join('master_products', 'quality_inspections.kode_barang', '=', 'master_products.kode_produk')
                ->select(
                    'master_products.kode_produk',
                    'master_products.nama_produk',
                    DB::raw('count(*) as total')
                )
                ->where('quality_inspections.hasil', 'NG')
                ->groupBy('master_products.kode_produk', 'master_products.nama_produk')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
        }

        // Get all RCA records with pagination
        $rcaAnalyses = RcaAnalysis::with(['masterDefect', 'masterProduk'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get approved or pending retur barang for optional linking
        $returBarangList = ReturBarang::with(['vendor', 'produk'])
            ->whereIn('status_approval', ['approved', 'pending'])
            ->orderBy('tanggal_retur', 'desc')
            ->get();

        return view('menu-sidebar.RCA-Analysis', compact(
            'masterDefects',
            'masterProduk',
            'topDefects',
            'topProduk',
            'rcaAnalyses',
            'returBarangList'
        ));
    }

    /**
     * Show the form for creating a new RCA
     */
    public function create()
    {
        $masterDefects = MasterDefect::where('is_active', true)
            ->orderBy('criticality_level', 'desc')
            ->get();

        $masterProduk = MasterProduk::where('is_active', true)
            ->orderBy('nama_produk', 'asc')
            ->get();

        // Get approved or pending retur barang for optional linking
        $returBarangList = ReturBarang::with(['vendor', 'produk'])
            ->whereIn('status_approval', ['approved', 'pending'])
            ->orderBy('tanggal_retur', 'desc')
            ->get();

        return view('menu-sidebar.rca-create', compact('masterDefects', 'masterProduk', 'returBarangList'));
    }

    /**
     * Store a newly created RCA in storage
     */
    public function store(Request $request)
    {
        // Debug: Log request data
        \Log::info('RCA Store Request:', $request->all());
        
        try {
            $validated = $request->validate(
                [
                    'metode_rca' => 'required|in:5_why,fishbone,kombinasi',
                    'kode_defect' => 'nullable|exists:master_defects,kode_defect',
                    'kode_barang' => 'nullable|exists:master_products,kode_produk',
                    'retur_barang_id' => 'nullable|exists:retur_barangs,id',
                    'penyebab_utama' => 'required|in:human_error,metode_kerja,material,mesin,lingkungan,pengukuran',
                    'deskripsi_masalah' => 'required|string|min:10',
                    'analisa_detail' => 'required|string|min:20',
                    'corrective_action' => 'required|string|min:10',
                    'preventive_action' => 'required|string|min:10',
                    'pic_analisa' => 'required|in:qc,engineering,warehouse,production,maintenance',
                    'due_date' => 'required|date|after_or_equal:today',
                    'catatan' => 'nullable|string',
                ],
                [
                    'kode_barang.exists' => 'Produk dengan kode ini tidak ditemukan di master products',
                    'kode_defect.exists' => 'Defect dengan kode ini tidak ditemukan di master defects',
                    'retur_barang_id.exists' => 'Retur barang dengan ID ini tidak ditemukan',
                    'due_date.after_or_equal' => 'Tanggal due date harus hari ini atau kemudian',
                    'deskripsi_masalah.min' => 'Deskripsi masalah minimal 10 karakter',
                    'analisa_detail.min' => 'Analisa detail minimal 20 karakter',
                ]
            );
            
            \Log::info('Validation passed, validated data:', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        }

        // Get defect details if selected
        if (!empty($validated['kode_defect'])) {
            $defect = MasterDefect::where('kode_defect', $validated['kode_defect'])->first();
            if ($defect) {
                $validated['criticality_level'] = $defect->criticality_level;
                $validated['sumber_masalah'] = $defect->sumber_masalah;
            }
        }

        // Generate nomor RCA
        $validated['nomor_rca'] = RcaAnalysis::generateNomorRca();
        $validated['tanggal_analisa'] = now();
        $validated['nama_analis'] = auth()->user()->name;
        $validated['status_rca'] = 'open';

        try {
            $rca = RcaAnalysis::create($validated);
            \Log::info('RCA created successfully with ID: ' . $rca->id);
            
            return redirect()->route('rca-analysis.index', ['page' => 1])
                ->with('success', 'RCA Analysis berhasil dibuat! (ID: ' . $rca->id . ')');
        } catch (\Exception $e) {
            \Log::error('Error creating RCA: ' . $e->getMessage(), $e->getTrace());
            return back()->with('error', 'Error menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified RCA
     */
    public function show(RcaAnalysis $rcaAnalysis)
    {
        $rcaAnalysis->load(['masterDefect', 'masterProduk', 'returBarang.vendor', 'returBarang.produk']);
        return view('menu-sidebar.rca-show', compact('rcaAnalysis'));
    }

    /**
     * Show the form for editing the specified RCA
     */
    public function edit(RcaAnalysis $rcaAnalysis)
    {
        // Load related data dengan eager loading
        $rcaAnalysis->load(['returBarang' => function($query) {
            $query->with(['vendor', 'produk']);
        }]);

        $masterDefects = MasterDefect::where('is_active', true)
            ->orderBy('criticality_level', 'desc')
            ->get();

        $masterProduk = MasterProduk::where('is_active', true)
            ->orderBy('nama_produk', 'asc')
            ->get();

        // Get approved or pending retur barang for linking (dengan eager loading)
        $returBarangList = ReturBarang::with(['vendor', 'produk'])
            ->whereIn('status_approval', ['approved', 'pending'])
            ->orderBy('tanggal_retur', 'desc')
            ->get();

        return view('menu-sidebar.rca-edit', compact('rcaAnalysis', 'masterDefects', 'masterProduk', 'returBarangList'));
    }

    /**
     * Update the specified RCA in storage
     */
    public function update(Request $request, RcaAnalysis $rcaAnalysis)
    {
        $validated = $request->validate([
            'metode_rca' => 'required|in:5_why,fishbone,kombinasi',
            'kode_defect' => 'nullable|exists:master_defects,kode_defect',
            'kode_barang' => 'nullable|exists:master_products,kode_produk',
            'retur_barang_id' => 'nullable|exists:retur_barangs,id',
            'penyebab_utama' => 'required|in:human_error,metode_kerja,material,mesin,lingkungan,pengukuran',
            'deskripsi_masalah' => 'required|string|min:10',
            'analisa_detail' => 'required|string|min:20',
            'corrective_action' => 'required|string|min:10',
            'preventive_action' => 'required|string|min:10',
            'pic_analisa' => 'required|in:qc,engineering,warehouse,production,maintenance',
            'due_date' => 'required|date',
            'status_rca' => 'required|in:open,in_progress,closed',
            'catatan' => 'nullable|string',
        ]);

        // Get defect details if selected
        if ($validated['kode_defect']) {
            $defect = MasterDefect::where('kode_defect', $validated['kode_defect'])->first();
            $validated['criticality_level'] = $defect->criticality_level;
            $validated['sumber_masalah'] = $defect->sumber_masalah;
        }

        $rcaAnalysis->update($validated);

        return redirect()->route('rca-analysis.show', $rcaAnalysis)
            ->with('success', 'RCA Analysis berhasil diperbarui!');
    }

    /**
     * Remove the specified RCA from storage
     */
    public function destroy(RcaAnalysis $rcaAnalysis)
    {
        $rcaAnalysis->delete();

        return redirect()->route('rca-analysis.index')
            ->with('success', 'RCA Analysis berhasil dihapus!');
    }

    /**
     * Get defect details via AJAX
     */
    public function getDefectDetails($kode_defect)
    {
        $defect = MasterDefect::where('kode_defect', $kode_defect)->first();

        if (!$defect) {
            return response()->json(['error' => 'Defect not found'], 404);
        }

        return response()->json([
            'nama_defect' => $defect->nama_defect,
            'criticality_level' => $defect->criticality_level,
            'sumber_masalah' => $defect->sumber_masalah,
            'solusi_standar' => $defect->solusi_standar,
            'is_rework_possible' => $defect->is_rework_possible,
        ]);
    }

    /**
     * Get product details via AJAX
     */
    public function getProductDetails($kode_produk)
    {
        $produk = MasterProduk::where('kode_produk', $kode_produk)->first();

        if (!$produk) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'nama_produk' => $produk->nama_produk,
            'unit' => $produk->unit,
            'kategori' => $produk->kategori,
            'harga' => $produk->harga,
        ]);
    }

    /**
     * Get retur details via AJAX
     */
    public function getReturDetails($id)
    {
        $retur = ReturBarang::with(['vendor', 'produk'])->find($id);

        if (!$retur) {
            return response()->json(['error' => 'Retur not found'], 404);
        }

        return response()->json([
            'no_retur' => $retur->no_retur,
            'vendor_name' => $retur->vendor->nama_vendor ?? 'N/A',
            'vendor_phone' => $retur->vendor->telepon ?? '-',
            'vendor_email' => $retur->vendor->email ?? '-',
            'product_name' => $retur->produk->nama_produk ?? 'N/A',
            'quantity' => $retur->jumlah_retur,
            'unit' => $retur->produk->unit ?? 'unit',
            'tanggal_retur' => $retur->tanggal_retur->format('d M Y'),
            'deskripsi' => $retur->deskripsi_keluhan,
            'status_approval' => $retur->status_approval,
            'kode_produk' => $retur->produk->kode_produk ?? null,
        ]);
    }
}
