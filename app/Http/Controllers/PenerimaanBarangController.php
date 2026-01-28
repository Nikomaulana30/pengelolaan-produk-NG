<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBarang;
use App\Models\MasterLokasiGudang;
use Illuminate\Http\Request;

class PenerimaanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = PenerimaanBarang::latest()->paginate(15);
        return view('menu-sidebar.penerimaan-barang', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get master lokasi gudang yang aktif
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->with('penyimpananNgs')
            ->orderBy('lokasi_lengkap')
            ->get();
            
        return view('menu-sidebar.penerimaan-barang.create', compact('lokasiGudangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_dokumen' => 'required|string|unique:penerimaan_barangs',
            'tanggal_input' => 'required|date_format:Y-m-d H:i:s',
            'jenis_pengembalian' => 'required|in:internal,customer_return,supplier',
            'lokasi_temuan' => 'required|in:produksi,gudang,customer,supplier',
            'nama_barang' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'batch_number' => 'nullable|string|max:100',
            'qty_baik' => 'required|integer|min:0',
            'qty_rusak' => 'required|integer|min:1',
            'penginput' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            // Master Lokasi Gudang Integration
            'master_lokasi_gudang_id' => 'nullable|exists:master_lokasi_gudangs,id',
            'status_penerimaan' => 'required|in:diterima,sedang_inspeksi,selesai_inspeksi,disimpan,ditolak',
            'hasil_inspeksi' => 'nullable|string',
            'ada_defect' => 'nullable|boolean',
        ]);

        // Auto-populate lokasi dari master lokasi gudang
        if ($request->master_lokasi_gudang_id) {
            $lokasi = MasterLokasiGudang::find($request->master_lokasi_gudang_id);
            if ($lokasi) {
                $validated['zone'] = $lokasi->zone;
                $validated['rack'] = $lokasi->rack;
                $validated['bin'] = $lokasi->bin;
                $validated['lokasi_lengkap'] = $lokasi->lokasi_lengkap;
            }
        }

        // Add user_id and status
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'submitted';
        $validated['submitted_at'] = now();
        $validated['ada_defect'] = $request->has('ada_defect');

        $barang = PenerimaanBarang::create($validated);

        return redirect()
            ->route('penerimaan-barang.index')
            ->with('success', 'Data penerimaan barang berhasil disimpan! (No: ' . $barang->nomor_dokumen . ')');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenerimaanBarang $penerimaanBarang)
    {
        return view('menu-sidebar.penerimaan-barang.show', compact('penerimaanBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenerimaanBarang $penerimaanBarang)
    {
        // Only allow editing if status is draft
        if ($penerimaanBarang->status !== 'draft') {
            return redirect()
                ->route('penerimaan-barang.index')
                ->with('error', 'Hanya data dengan status draft yang bisa diedit!');
        }

        // Get master lokasi gudang (aktif + yang sedang digunakan)
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->orWhere('id', $penerimaanBarang->master_lokasi_gudang_id)
            ->with('penyimpananNgs')
            ->orderBy('lokasi_lengkap')
            ->get();

        return view('menu-sidebar.penerimaan-barang.edit', compact('penerimaanBarang', 'lokasiGudangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenerimaanBarang $penerimaanBarang)
    {
        // Only allow updating if status is draft
        if ($penerimaanBarang->status !== 'draft') {
            return redirect()
                ->route('penerimaan-barang.index')
                ->with('error', 'Hanya data dengan status draft yang bisa diubah!');
        }

        $validated = $request->validate([
            'nomor_dokumen' => 'required|string|unique:penerimaan_barangs,nomor_dokumen,' . $penerimaanBarang->id,
            'tanggal_input' => 'required|date_format:Y-m-d H:i:s',
            'jenis_pengembalian' => 'required|in:internal,customer_return,supplier',
            'lokasi_temuan' => 'required|in:produksi,gudang,customer,supplier',
            'nama_barang' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'batch_number' => 'nullable|string|max:100',
            'qty_baik' => 'required|integer|min:0',
            'qty_rusak' => 'required|integer|min:1',
            'penginput' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            // Master Lokasi Gudang Integration
            'master_lokasi_gudang_id' => 'nullable|exists:master_lokasi_gudangs,id',
            'status_penerimaan' => 'required|in:diterima,sedang_inspeksi,selesai_inspeksi,disimpan,ditolak',
            'hasil_inspeksi' => 'nullable|string',
            'ada_defect' => 'nullable|boolean',
        ]);

        // Auto-populate lokasi dari master lokasi gudang
        if ($request->master_lokasi_gudang_id) {
            $lokasi = MasterLokasiGudang::find($request->master_lokasi_gudang_id);
            if ($lokasi) {
                $validated['zone'] = $lokasi->zone;
                $validated['rack'] = $lokasi->rack;
                $validated['bin'] = $lokasi->bin;
                $validated['lokasi_lengkap'] = $lokasi->lokasi_lengkap;
            }
        }

        $validated['ada_defect'] = $request->has('ada_defect');

        $penerimaanBarang->update($validated);

        return redirect()
            ->route('penerimaan-barang.index')
            ->with('success', 'Data penerimaan barang berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenerimaanBarang $penerimaanBarang)
    {
        $penerimaanBarang->delete();

        return redirect()
            ->route('penerimaan-barang.index')
            ->with('success', 'Data penerimaan barang berhasil dihapus!');
    }

    /**
     * Submit record for approval
     */
    public function submit(PenerimaanBarang $penerimaanBarang)
    {
        if ($penerimaanBarang->status !== 'draft') {
            return redirect()
                ->route('penerimaan-barang.index')
                ->with('error', 'Data sudah disubmit sebelumnya!');
        }

        $penerimaanBarang->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('penerimaan-barang.index')
            ->with('success', 'Data berhasil disubmit untuk approval!');
    }

    /**
     * Approve record
     */
    public function approve(PenerimaanBarang $penerimaanBarang)
    {
        $penerimaanBarang->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->user()->name,
        ]);

        return redirect()
            ->route('penerimaan-barang.index')
            ->with('success', 'Data berhasil diapprove!');
    }
}
