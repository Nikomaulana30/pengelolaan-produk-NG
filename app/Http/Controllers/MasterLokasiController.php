<?php

namespace App\Http\Controllers;

use App\Models\MasterLokasi;
use Illuminate\Http\Request;

class MasterLokasiController extends Controller
{
    public function index()
    {
        $lokasis = MasterLokasi::paginate(15);
        return view('menu-sidebar.master-data.master-lokasi', compact('lokasis'));
    }

    public function create()
    {
        return view('menu-sidebar.master-data.master-lokasi-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_lokasi' => 'required|unique:master_lokasis|max:50',
            'nama_lokasi' => 'required|max:255',
            'zona_gudang' => 'required|in:zona_a,zona_b,zona_c,zona_d,zona_e',
            'rack' => 'required|max:50',
            'bin' => 'required|max:50',
            'tipe_lokasi' => 'required|in:regular,karantina,ng_storage,scrap',
            'status_lokasi' => 'required|in:available,full,maintenance,blocked',
            'kapasitas_maksimal' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        // Convert checkbox to boolean (if unchecked, it won't be in request)
        $validated['is_active'] = $request->has('is_active') ? true : false;

        MasterLokasi::create($validated);

        return redirect()->route('master-lokasi.index')->with('success', 'Master Lokasi berhasil ditambahkan!');
    }

    public function show(MasterLokasi $masterLokasi)
    {
        return view('menu-sidebar.master-data.master-lokasi-show', compact('masterLokasi'));
    }

    public function edit(MasterLokasi $masterLokasi)
    {
        return view('menu-sidebar.master-data.master-lokasi-edit', compact('masterLokasi'));
    }

    public function update(Request $request, MasterLokasi $masterLokasi)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|max:255',
            'zona_gudang' => 'required|in:zona_a,zona_b,zona_c,zona_d,zona_e',
            'rack' => 'required|max:50',
            'bin' => 'required|max:50',
            'tipe_lokasi' => 'required|in:regular,karantina,ng_storage,scrap',
            'status_lokasi' => 'required|in:available,full,maintenance,blocked',
            'kapasitas_maksimal' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        // Convert checkbox to boolean (if unchecked, it won't be in request)
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $masterLokasi->update($validated);

        return redirect()->route('master-lokasi.show', $masterLokasi)->with('success', 'Master Lokasi berhasil diperbarui!');
    }

    public function destroy(MasterLokasi $masterLokasi)
    {
        $masterLokasi->delete();

        return redirect()->route('master-lokasi.index')->with('success', 'Master Lokasi berhasil dihapus!');
    }
}
