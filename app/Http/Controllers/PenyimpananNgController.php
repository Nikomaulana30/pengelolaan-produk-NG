<?php

namespace App\Http\Controllers;

use App\Models\PenyimpananNg;
use App\Models\MasterDisposisi;
use App\Models\MasterLokasiGudang;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class PenyimpananNgController extends Controller
{
    public function index()
    {
        $penyimpananNgs = PenyimpananNg::latest()->paginate(15);
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->with('penyimpananNgs')
            ->orderBy('lokasi_lengkap')
            ->get();
        return view('menu-sidebar.Penyimpanan-Barang', compact('penyimpananNgs', 'lokasiGudangs'));
    }

    public function create()
    {
        $penyimpananNgs = PenyimpananNg::latest()->paginate(15);
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->with('penyimpananNgs')
            ->orderBy('lokasi_lengkap')
            ->get();
        return view('menu-sidebar.Penyimpanan-Barang', compact('penyimpananNgs', 'lokasiGudangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_referensi' => 'required|string',
            'nama_barang' => 'required|string',
            'master_lokasi_gudang_id' => 'required|exists:master_lokasi_gudangs,id',
            'zone' => 'nullable|string',
            'rack' => 'nullable|string',
            'bin' => 'nullable|string',
            'qty_awal' => 'required|integer|min:1',
            'qty_setelah_perbaikan' => 'nullable|integer|min:0',
            'status_barang' => 'required|in:disimpan,dalam_perbaikan,menunggu_approval,siap_dipindahkan,dipindahkan',
            'catatan' => 'nullable|string',
        ]);

        // Generate nomor_storage
        $count = PenyimpananNg::whereDate('created_at', today())->count() + 1;
        $validated['nomor_storage'] = 'STR-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Set tanggal_penyimpanan
        $validated['tanggal_penyimpanan'] = now();

        // Auto-populate lokasi dari master lokasi gudang
        if ($request->master_lokasi_gudang_id) {
            $lokasi = MasterLokasiGudang::find($request->master_lokasi_gudang_id);
            $validated['zone'] = $lokasi->zone;
            $validated['rack'] = $lokasi->rack;
            $validated['bin'] = $lokasi->bin;
            $validated['lokasi_lengkap'] = $lokasi->lokasi_lengkap;
        }

        // Calculate selisih_qty
        $validated['selisih_qty'] = $validated['qty_awal'] - ($validated['qty_setelah_perbaikan'] ?? 0);

        // Set user_id
        $validated['user_id'] = auth()->id();

        // Set status default
        $validated['status'] = 'draft';

        $penyimpanan = PenyimpananNg::create($validated);

        // Log activity
        ActivityLogService::logCreated($penyimpanan, 'Penyimpanan NG barang dibuat');

        return redirect()->route('penyimpanan-ng.index')->with('success', 'Data penyimpanan berhasil disimpan!');
    }

    public function show(PenyimpananNg $penyimpananNg)
    {
        return view('menu-sidebar.warehouse.penyimpanan-ng-show', compact('penyimpananNg'));
    }

    public function edit(PenyimpananNg $penyimpananNg)
    {
        if ($penyimpananNg->status !== 'draft') {
            return redirect()->route('penyimpanan-ng.index')->with('error', 'Hanya data draft yang bisa diedit!');
        }

        $disposisis = MasterDisposisi::where('is_active', true)->get();
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->orWhere('id', $penyimpananNg->master_lokasi_gudang_id)
            ->with('penyimpananNgs')
            ->orderBy('lokasi_lengkap')
            ->get();
        return view('menu-sidebar.warehouse.penyimpanan-ng-edit', compact('penyimpananNg', 'disposisis', 'lokasiGudangs'));
    }

    public function update(Request $request, PenyimpananNg $penyimpananNg)
    {
        if ($penyimpananNg->status !== 'draft') {
            return redirect()->route('penyimpanan-ng.index')->with('error', 'Hanya data draft yang bisa diubah!');
        }

        $validated = $request->validate([
            'nomor_referensi' => 'required|string',
            'nama_barang' => 'required|string',
            'master_lokasi_gudang_id' => 'required|exists:master_lokasi_gudangs,id',
            'zone' => 'nullable|string',
            'rack' => 'nullable|string',
            'bin' => 'nullable|string',
            'qty_awal' => 'required|integer|min:1',
            'qty_setelah_perbaikan' => 'nullable|integer|min:0',
            'status_barang' => 'required|in:disimpan,dalam_perbaikan,menunggu_approval,siap_dipindahkan,dipindahkan',
            'catatan' => 'nullable|string',
            'zone_tujuan' => 'nullable|in:zona_a,zona_b,zona_c,zona_d,zona_e',
            'rack_tujuan' => 'nullable|string',
            'bin_tujuan' => 'nullable|string',
            'lokasi_lengkap_tujuan' => 'nullable|string',
            'master_disposisi_id' => 'nullable|exists:master_disposisis,id',
            'tanggal_relokasi' => 'nullable|datetime',
            'alasan_relokasi' => 'nullable|string',
        ]);

        // Auto-populate lokasi dari master lokasi gudang
        if ($request->master_lokasi_gudang_id) {
            $lokasi = MasterLokasiGudang::find($request->master_lokasi_gudang_id);
            $validated['zone'] = $lokasi->zone;
            $validated['rack'] = $lokasi->rack;
            $validated['bin'] = $lokasi->bin;
            $validated['lokasi_lengkap'] = $lokasi->lokasi_lengkap;
        }

        // Calculate selisih_qty
        $validated['selisih_qty'] = $validated['qty_awal'] - ($validated['qty_setelah_perbaikan'] ?? 0);

        $penyimpananNg->update($validated);

        // Log activity
        ActivityLogService::logStatusChange($penyimpananNg, 'status_barang', 'draft', $validated['status_barang'], 'Status barang diperbarui');

        return redirect()->route('penyimpanan-ng.index')->with('success', 'Data penyimpanan berhasil diperbarui!');
    }

    public function destroy(PenyimpananNg $penyimpananNg)
    {
        $penyimpananNg->delete();
        
        // Log activity
        ActivityLogService::logStatusChange($penyimpananNg, 'status', 'draft', 'deleted', 'Data dihapus');
        
        return redirect()->route('penyimpanan-ng.index')->with('success', 'Data penyimpanan berhasil dihapus!');
    }

    public function submit(PenyimpananNg $penyimpananNg)
    {
        if ($penyimpananNg->status !== 'draft') {
            return redirect()->route('penyimpanan-ng.index')->with('error', 'Data sudah disubmit sebelumnya!');
        }

        $penyimpananNg->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Log activity
        ActivityLogService::logStatusChange($penyimpananNg, 'status', 'draft', 'submitted', 'Data disubmit untuk approval');

        return redirect()->route('penyimpanan-ng.index')->with('success', 'Data berhasil disubmit untuk approval!');
    }

    public function approve(PenyimpananNg $penyimpananNg)
    {
        $penyimpananNg->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->user()->name,
        ]);

        // Log activity
        ActivityLogService::logApproved($penyimpananNg, 'Data penyimpanan NG diapprove');

        return redirect()->route('penyimpanan-ng.index')->with('success', 'Data berhasil diapprove!');
    }
}
