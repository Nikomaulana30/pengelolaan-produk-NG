<?php

namespace App\Http\Controllers;

use App\Models\MasterProduk;
use App\Models\MasterVendor;
use App\Models\CustomerComplaint;
use App\Models\ProductionRework;
use Illuminate\Http\Request;

class MasterProdukController extends Controller
{
    public function index()
    {
        $produks = MasterProduk::with('vendor')->paginate(15);
        
        // Dynamic statistics
        $totalProduk = MasterProduk::count();
        $produkAktif = MasterProduk::where('is_active', true)->count();
        
        // Count active complaints (status: pending, in_review, approved)
        $complaintAktif = CustomerComplaint::whereIn('status', ['pending', 'in_review', 'approved'])->count();
        
        // Count active reworks (status: pending, in_progress)
        $dalamRework = ProductionRework::whereIn('status', ['pending', 'in_progress'])->count();
        
        return view('menu-sidebar.master-data.master-produk', compact(
            'produks',
            'totalProduk',
            'produkAktif',
            'complaintAktif',
            'dalamRework'
        ));
    }

    public function create()
    {
        $vendors = MasterVendor::active()->orderBy('nama_vendor')->get();
        return view('menu-sidebar.master-data.master-produk-create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|unique:master_products|max:50',
            'nama_produk' => 'required|max:255',
            'kategori' => 'required|in:raw_material,wip,finished_goods',
            'unit' => 'required|max:20',
            'harga' => 'nullable|numeric|min:0',
            'vendor_id' => 'nullable|exists:master_vendors,id',
            'spesifikasi' => 'nullable|string',
            'drawing_file' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox is_active properly
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        MasterProduk::create($validated);

        return redirect()->route('master-produk.index')->with('success', 'Master Produk berhasil ditambahkan!');
    }

    public function show(MasterProduk $masterProduk)
    {
        $masterProduk->load('vendor', 'inspeksi');
        return view('menu-sidebar.master-data.master-produk-show', compact('masterProduk'));
    }

    public function edit(MasterProduk $masterProduk)
    {
        $vendors = MasterVendor::active()->orderBy('nama_vendor')->get();
        return view('menu-sidebar.master-data.master-produk-edit', compact('masterProduk', 'vendors'));
    }

    public function update(Request $request, MasterProduk $masterProduk)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|max:255',
            'kategori' => 'required|in:raw_material,wip,finished_goods',
            'unit' => 'required|max:20',
            'harga' => 'nullable|numeric|min:0',
            'vendor_id' => 'nullable|exists:master_vendors,id',
            'spesifikasi' => 'nullable|string',
            'drawing_file' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox is_active properly
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $masterProduk->update($validated);

        return redirect()->route('master-produk.show', $masterProduk)->with('success', 'Master Produk berhasil diperbarui!');
    }

    public function destroy(MasterProduk $masterProduk)
    {
        $masterProduk->delete();

        return redirect()->route('master-produk.index')->with('success', 'Master Produk berhasil dihapus!');
    }
}
