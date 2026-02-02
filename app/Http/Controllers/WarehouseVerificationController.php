<?php

namespace App\Http\Controllers;

use App\Models\WarehouseVerification;
use App\Models\DokumenRetur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WarehouseVerificationController extends Controller
{
    public function index()
    {
        $warehouseVerifications = WarehouseVerification::with([
            'dokumenRetur.customerComplaint', 
            'warehouseStaff',
            'qualityReinspection'
        ])->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics
        $totalVerifikasi = WarehouseVerification::count();
        $pendingCount = WarehouseVerification::where('status', 'pending')->count();
        $verifiedCount = WarehouseVerification::where('status', 'verified')->count();
        $rejectedCount = WarehouseVerification::where('status', 'rejected')->count();

        return view('menu-sidebar.warehouse-verification.index', compact(
            'warehouseVerifications',
            'totalVerifikasi',
            'pendingCount',
            'verifiedCount',
            'rejectedCount'
        ));
    }

    public function create()
    {
        $availableDokumens = DokumenRetur::where('status', 'sent_to_warehouse')
            ->whereDoesntHave('warehouseVerification')
            ->with('customerComplaint')
            ->get();
        
        $lokasiGudangs = \App\Models\MasterLokasiGudang::where('is_active', true)
            ->orderBy('lokasi_lengkap')
            ->get();
        
        return view('menu-sidebar.warehouse-verification.create', compact('availableDokumens', 'lokasiGudangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dokumen_retur_id' => 'required|exists:dokumen_returs,id',
            'tanggal_terima' => 'required|date',
            'quantity_diterima' => 'required|integer|min:0',
            'quantity_ng_confirmed' => 'required|integer|min:0',
            'quantity_ok' => 'required|integer|min:0',
            'kondisi_fisik_barang' => 'required|string',
            'catatan_penerimaan' => 'required|string',
            'foto_barang_ng.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'master_lokasi_gudang_id' => 'required|exists:master_lokasi_gudangs,id',
            'lokasi_penyimpanan' => 'nullable|string|max:255'
        ]);

        // Validate total quantity
        if (($validated['quantity_ng_confirmed'] + $validated['quantity_ok']) != $validated['quantity_diterima']) {
            return back()->withErrors(['quantity_diterima' => 'Total quantity NG dan OK harus sama dengan quantity diterima'])->withInput();
        }

        // Handle photo uploads
        $fotoBarangNg = [];
        if ($request->hasFile('foto_barang_ng')) {
            foreach ($request->file('foto_barang_ng') as $file) {
                $path = $file->store('warehouse/verification-photos', 'public');
                $fotoBarangNg[] = $path;
            }
        }

        $validated['foto_barang_ng'] = $fotoBarangNg;
        $validated['warehouse_staff_id'] = Auth::id();
        $validated['status_verifikasi'] = 'received';
        $validated['status'] = 'draft';

        $verification = WarehouseVerification::create($validated);

        // Update dokumen retur status
        $verification->dokumenRetur->update(['status' => 'received_by_warehouse']);

        return redirect()->route('warehouse-verification.index')
            ->with('success', 'Verifikasi warehouse berhasil dibuat');
    }

    public function show($id)
    {
        $verification = WarehouseVerification::with([
            'dokumenRetur.customerComplaint',
            'warehouseStaff',
            'qualityReinspection'
        ])->findOrFail($id);
        
        return view('menu-sidebar.warehouse-verification.show', compact('verification'));
    }

    public function edit($id)
    {
        $verification = WarehouseVerification::with(['dokumenRetur.customerComplaint', 'lokasiGudang'])->findOrFail($id);
        
        $lokasiGudangs = \App\Models\MasterLokasiGudang::where('is_active', true)
            ->orderBy('lokasi_lengkap')
            ->get();
        
        return view('menu-sidebar.warehouse-verification.edit', compact('verification', 'lokasiGudangs'));
    }

    public function update(Request $request, $id)
    {
        $verification = WarehouseVerification::findOrFail($id);
        
        $validated = $request->validate([
            'tanggal_terima' => 'required|date',
            'quantity_diterima' => 'required|integer|min:0',
            'quantity_ng_confirmed' => 'required|integer|min:0',
            'quantity_ok' => 'required|integer|min:0',
            'kondisi_fisik_barang' => 'required|string',
            'catatan_penerimaan' => 'required|string',
            'master_lokasi_gudang_id' => 'required|exists:master_lokasi_gudangs,id',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'foto_barang_ng.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Validate total quantity
        if (($validated['quantity_ng_confirmed'] + $validated['quantity_ok']) != $validated['quantity_diterima']) {
            return back()->withErrors(['quantity_diterima' => 'Total quantity NG dan OK harus sama dengan quantity diterima'])->withInput();
        }

        // Handle new photo uploads
        $fotoBarangNg = $verification->foto_barang_ng ?? [];
        if ($request->hasFile('foto_barang_ng')) {
            foreach ($request->file('foto_barang_ng') as $file) {
                $path = $file->store('warehouse/verification-photos', 'public');
                $fotoBarangNg[] = $path;
            }
            $validated['foto_barang_ng'] = $fotoBarangNg;
        }

        $verification->update($validated);

        return redirect()->route('warehouse-verification.show', $verification->id)
            ->with('success', 'Verifikasi warehouse berhasil diupdate');
    }

    public function verify($id)
    {
        $verification = WarehouseVerification::findOrFail($id);
        
        $verification->update([
            'status_verifikasi' => 'verified',
            'status' => 'verified',
            'verified_at' => now()
        ]);

        return redirect()->back()->with('success', 'Verifikasi berhasil dikonfirmasi');
    }

    public function sendToQuality($id)
    {
        $verification = WarehouseVerification::findOrFail($id);
        
        $verification->update([
            'status_verifikasi' => 'forwarded_to_quality',
            'status' => 'sent_to_quality'
        ]);

        return redirect()->back()->with('success', 'Verifikasi berhasil dikirim ke Quality');
    }

    public function destroy($id)
    {
        $verification = WarehouseVerification::findOrFail($id);
        
        // Delete uploaded photos
        if ($verification->foto_barang_ng) {
            foreach ($verification->foto_barang_ng as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $verification->delete();

        return redirect()->route('warehouse-verification.index')
            ->with('success', 'Verifikasi warehouse berhasil dihapus');
    }
}
