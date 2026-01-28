<?php

namespace App\Http\Controllers;

use App\Models\MasterProduk;
use App\Models\MasterVendor;
use Illuminate\Http\Request;

class MasterProdukController extends Controller
{
    public function index()
    {
        $produks = MasterProduk::with('vendor')->paginate(15);
        return view('menu-sidebar.master-data.master-produk', compact('produks'));
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
            'is_active' => 'boolean',
        ]);

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
            'is_active' => 'boolean',
        ]);

        $masterProduk->update($validated);

        return redirect()->route('master-produk.show', $masterProduk)->with('success', 'Master Produk berhasil diperbarui!');
    }

    public function destroy(MasterProduk $masterProduk)
    {
        $masterProduk->delete();

        return redirect()->route('master-produk.index')->with('success', 'Master Produk berhasil dihapus!');
    }
}
