<?php

namespace App\Http\Controllers;

use App\Models\ScrapDisposal;
use Illuminate\Http\Request;

class ScrapDisposalController extends Controller
{
    public function index()
    {
        $scraps = ScrapDisposal::with('user')->latest()->paginate(20);
        return view('menu-sidebar.scrap', compact('scraps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_referensi' => 'required|string|max:100',
            'nama_barang' => 'required|string|max:200',
            'quantity' => 'required|integer|min:1',
            'nama_petugas' => 'required|string|max:100',
            'alasan_scrap' => 'required|in:tidak_bisa_diperbaiki,obsolete,expired,cacat_permanen,tidak_ekonomis',
            'deskripsi_kondisi' => 'required|string',
            'hasil_test_qc' => 'required|string',
            'tanggal_test_qc' => 'nullable|date',
            'qc_inspector' => 'nullable|string|max:100',
            'catatan_qc' => 'nullable|string',
            'metode_pembuangan' => 'required|in:pembakaran,penguburan,daur_ulang,penjualan_scrap,lainnya',
            'tanggal_rencana_scrap' => 'nullable|date',
            'pihak_pelaksana' => 'nullable|string|max:200',
            'estimasi_biaya_pembuangan' => 'nullable|numeric|min:0',
            'dokumen_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status_approval' => 'nullable|in:pending,approved,rejected,need_revision',
            'nama_manager' => 'nullable|string|max:100',
            'catatan_manager' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('dokumen_bukti')) {
            $file = $request->file('dokumen_bukti');
            $fileName = 'scrap_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('scrap-disposals', $fileName, 'public');
            $validated['dokumen_bukti'] = $fileName;
        }

        $scrap = ScrapDisposal::create([
            ...$validated,
            'nomor_scrap' => 'SCR-' . date('Ymd') . '-' . str_pad(ScrapDisposal::count() + 1, 4, '0', STR_PAD_LEFT),
            'tanggal_scrap' => now(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('scrap-disposal.index')
            ->with('success', 'Pengajuan Scrap/Disposal berhasil disimpan!');
    }

    public function show(ScrapDisposal $scrap)
    {
        $scrap->load('user', 'masterProduk');
        return view('menu-sidebar.scrap-show', compact('scrap'));
    }

    public function edit(ScrapDisposal $scrap)
    {
        return view('menu-sidebar.scrap-edit', compact('scrap'));
    }

    public function update(Request $request, ScrapDisposal $scrap)
    {
        // Only allow edit if status is pending
        if ($scrap->status_approval !== 'pending') {
            return redirect()->back()->with('error', 'Hanya dapat edit data dengan status Pending!');
        }

        $validated = $request->validate([
            'nomor_referensi' => 'required|string|max:100',
            'nama_barang' => 'required|string|max:200',
            'quantity' => 'required|integer|min:1',
            'nama_petugas' => 'required|string|max:100',
            'alasan_scrap' => 'required|in:tidak_bisa_diperbaiki,obsolete,expired,cacat_permanen,tidak_ekonomis',
            'deskripsi_kondisi' => 'required|string',
            'hasil_test_qc' => 'required|string',
            'tanggal_test_qc' => 'nullable|date',
            'qc_inspector' => 'nullable|string|max:100',
            'catatan_qc' => 'nullable|string',
            'metode_pembuangan' => 'required|in:pembakaran,penguburan,daur_ulang,penjualan_scrap,lainnya',
            'tanggal_rencana_scrap' => 'nullable|date',
            'pihak_pelaksana' => 'nullable|string|max:200',
            'estimasi_biaya_pembuangan' => 'nullable|numeric|min:0',
            'dokumen_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status_approval' => 'nullable|in:pending,approved,rejected,need_revision',
            'nama_manager' => 'nullable|string|max:100',
            'catatan_manager' => 'nullable|string',
        ]);

        if ($request->hasFile('dokumen_bukti')) {
            $file = $request->file('dokumen_bukti');
            $fileName = 'scrap_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('scrap-disposals', $fileName, 'public');
            $validated['dokumen_bukti'] = $fileName;
        }

        $scrap->update($validated);

        return redirect()->route('scrap-disposal.show', $scrap)
            ->with('success', 'Data Scrap/Disposal berhasil diperbarui!');
    }

    public function destroy(ScrapDisposal $scrap)
    {
        if ($scrap->status_approval !== 'pending') {
            return redirect()->back()->with('error', 'Hanya dapat hapus data dengan status Pending!');
        }

        $scrap->delete();

        return redirect()->route('scrap-disposal.index')
            ->with('success', 'Data Scrap/Disposal berhasil dihapus!');
    }
}
