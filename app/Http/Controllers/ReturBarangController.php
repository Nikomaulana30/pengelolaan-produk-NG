<?php

namespace App\Http\Controllers;

use App\Models\ReturBarang;
use App\Models\MasterVendor;
use App\Models\MasterProduk;
use Illuminate\Http\Request;

class ReturBarangController extends Controller
{
    public function index()
    {
        $query = ReturBarang::with(['vendor', 'produk']);
        
        // Filter by vendor jika ada query parameter
        if (request('vendor_id')) {
            $query->where('vendor_id', request('vendor_id'));
        }
        
        $returs = $query->paginate(15);
        return view('menu-sidebar.retur-barang.retur-barang', compact('returs'));
    }

    public function create()
    {
        $vendors = MasterVendor::where('is_active', true)->get();
        $produks = MasterProduk::where('is_active', true)->get();
        return view('menu-sidebar.retur-barang.retur-barang-create', compact('vendors', 'produks'));
    }

    public function store(Request $request)
    {
        // Validate vendor kebijakan_retur
        $vendor = MasterVendor::find($request->vendor_id);
        
        if (!$vendor) {
            return back()->withInput()->with('error', 'Vendor tidak ditemukan!');
        }
        
        if ($vendor->kebijakan_retur === 'no_return') {
            return back()->withInput()->with('error', 
                'Vendor ' . $vendor->nama_vendor . ' tidak memiliki kebijakan retur (no_return). Barang harus disimpan di Penyimpanan NG.');
        }

        $validated = $request->validate([
            'vendor_id' => 'required|exists:master_vendors,id',
            'produk_id' => 'required|exists:master_products,id',
            'tanggal_retur' => 'required|date',
            'alasan_retur' => 'required|in:defect',
            'jumlah_retur' => 'required|integer|min:1',
            'deskripsi_keluhan' => 'required|string|min:10',
        ]);

        // Generate nomor retur
        $lastRetur = ReturBarang::latest('id')->first();
        $no = ($lastRetur?->id ?? 0) + 1;
        $validated['no_retur'] = 'RET-' . date('Y') . '-' . str_pad($no, 5, '0', STR_PAD_LEFT);
        
        // Set status based on kebijakan
        if ($vendor->kebijakan_retur === 'full_return') {
            $validated['status_approval'] = 'approved'; // Auto-approved
        } else {
            $validated['status_approval'] = 'pending'; // Partial return, needs approval
        }

        ReturBarang::create($validated);

        return redirect()->route('retur-barang.index')->with('success', 'Retur barang berhasil ditambahkan!');
    }

    public function show(ReturBarang $retur_barang)
    {
        $retur_barang->load(['vendor', 'produk']);
        return view('menu-sidebar.retur-barang.retur-barang-show', compact('retur_barang'));
    }

    public function edit(ReturBarang $retur_barang)
    {
        $retur_barang->load(['vendor', 'produk']);
        $vendors = MasterVendor::where('is_active', true)->get();
        $produks = MasterProduk::where('is_active', true)->get();
        return view('menu-sidebar.retur-barang.retur-barang-edit', compact('retur_barang', 'vendors', 'produks'));
    }

    public function update(Request $request, ReturBarang $retur_barang)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:master_vendors,id',
            'produk_id' => 'required|exists:master_products,id',
            'tanggal_retur' => 'required|date',
            'alasan_retur' => 'required|in:defect,qty_tidak_sesuai,kualitas_buruk,expired,rusak_pengiriman,lainnya',
            'jumlah_retur' => 'required|integer|min:1',
            'deskripsi_keluhan' => 'nullable|string',
            'status_approval' => 'required|in:pending,approved,rejected',
            'catatan_approval' => 'nullable|string',
        ]);

        $retur_barang->update($validated);

        return redirect()->route('retur-barang.show', $retur_barang)->with('success', 'Retur barang berhasil diperbarui!');
    }

    public function destroy(ReturBarang $retur_barang)
    {
        $retur_barang->delete();

        return redirect()->route('retur-barang.index')->with('success', 'Retur barang berhasil dihapus!');
    }
}
