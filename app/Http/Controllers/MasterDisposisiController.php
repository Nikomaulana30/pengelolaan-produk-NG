<?php

namespace App\Http\Controllers;

use App\Models\MasterDisposisi;
use App\Models\PenyimpananNg;
use App\Models\MasterLokasiGudang;
use Illuminate\Http\Request;

class MasterDisposisiController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterDisposisi::query();

        // Search by kode or nama
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('kode_disposisi', 'like', "%{$search}%")
                  ->orWhere('nama_disposisi', 'like', "%{$search}%");
            });
        }

        // Filter by jenis_tindakan
        if ($request->filled('jenis_tindakan')) {
            $query->where('jenis_tindakan', $request->get('jenis_tindakan'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            $query->where('is_active', $status === 'active' ? 1 : 0);
        }

        $disposisis = $query->paginate(15);
        
        $jenisTindakan = ['rework', 'scrap_disposal', 'return_to_vendor', 'downgrade', 'repurpose'];
        
        // Statistics
        $totalDisposisi = MasterDisposisi::count();
        $disposisiAktif = MasterDisposisi::where('is_active', true)->count();
        $needApproval = MasterDisposisi::where('memerlukan_approval', true)->count();
        
        // Count usage from quality_reinspections enum column
        $totalUsage = \DB::table('quality_reinspections')
            ->whereIn('disposisi', ['rework', 'scrap', 'return_to_vendor', 'return_to_customer'])
            ->whereNull('deleted_at')
            ->count();
        
        return view('menu-sidebar.master-data.master-disposisi', compact(
            'disposisis', 
            'jenisTindakan',
            'totalDisposisi',
            'disposisiAktif',
            'needApproval',
            'totalUsage'
        ));
    }

    public function create()
    {
        // Get penyimpanan NG yang masih disimpan/dalam perbaikan dan belum punya disposisi
        $penyimpanans = PenyimpananNg::whereIn('status_barang', ['disimpan', 'dalam_perbaikan', 'menunggu_approval'])
            ->whereNull('master_disposisi_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get active lokasi gudang untuk lokasi tujuan
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->orderBy('lokasi_lengkap')
            ->get();
        
        return view('menu-sidebar.master-data.master-disposisi-create', compact('penyimpanans', 'lokasiGudangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_disposisi' => 'required|max:255',
            'jenis_tindakan' => 'required|in:return_to_vendor,scrap_disposal,rework,downgrade,repurpose',
            'penyimpanan_ng_id' => 'nullable|exists:penyimpanan_ngs,id',
            'zone_tujuan' => 'nullable|in:zona_a,zona_b,zona_c,zona_d,zona_e',
            'rack_tujuan' => 'nullable|string',
            'bin_tujuan' => 'nullable|string',
            'lokasi_lengkap_tujuan' => 'nullable|string',
            'memerlukan_approval' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
        ]);

        // Convert to boolean
        $validated['memerlukan_approval'] = (bool) $validated['memerlukan_approval'];
        $validated['is_active'] = (bool) $validated['is_active'];

        // Auto-generate kode disposisi dari nama
        $validated['kode_disposisi'] = MasterDisposisi::generateKodeDisposisi($validated['nama_disposisi']);

        MasterDisposisi::create($validated);

        return redirect()->route('master-disposisi.index')
            ->with('success', 'Master Disposisi berhasil ditambahkan dengan kode: ' . $validated['kode_disposisi'] . '!');
    }

    public function show(MasterDisposisi $masterDisposisi)
    {
        return view('menu-sidebar.master-data.master-disposisi-show', compact('masterDisposisi'));
    }

    public function edit(MasterDisposisi $masterDisposisi)
    {
        // Get penyimpanan NG yang available atau yang sudah assigned ke disposisi ini
        $penyimpanans = PenyimpananNg::where(function($query) use ($masterDisposisi) {
                $query->whereIn('status_barang', ['disimpan', 'dalam_perbaikan', 'menunggu_approval'])
                      ->whereNull('master_disposisi_id')
                      ->orWhere('master_disposisi_id', $masterDisposisi->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get active lokasi gudang untuk lokasi tujuan
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->orderBy('lokasi_lengkap')
            ->get();
        
        return view('menu-sidebar.master-data.master-disposisi-edit', compact('masterDisposisi', 'penyimpanans', 'lokasiGudangs'));
    }

    public function update(Request $request, MasterDisposisi $masterDisposisi)
    {
        $validated = $request->validate([
            'nama_disposisi' => 'required|max:255',
            'jenis_tindakan' => 'required|in:return_to_vendor,scrap_disposal,rework,downgrade,repurpose',
            'penyimpanan_ng_id' => 'nullable|exists:penyimpanan_ngs,id',
            'zone_tujuan' => 'nullable|in:zona_a,zona_b,zona_c,zona_d,zona_e',
            'rack_tujuan' => 'nullable|string',
            'bin_tujuan' => 'nullable|string',
            'lokasi_lengkap_tujuan' => 'nullable|string',
            'memerlukan_approval' => 'required|in:0,1',
            'is_active' => 'required|in:0,1',
        ]);

        // Convert to boolean
        $validated['memerlukan_approval'] = (bool) $validated['memerlukan_approval'];
        $validated['is_active'] = (bool) $validated['is_active'];

        // Jika nama disposisi berubah, regenerate kode
        if ($request->nama_disposisi !== $masterDisposisi->nama_disposisi) {
            $validated['kode_disposisi'] = MasterDisposisi::generateKodeDisposisi($validated['nama_disposisi']);
        }

        $masterDisposisi->update($validated);

        return redirect()->route('master-disposisi.show', $masterDisposisi)
            ->with('success', 'Master Disposisi berhasil diperbarui!');
    }

    public function destroy(MasterDisposisi $masterDisposisi)
    {
        $masterDisposisi->delete();

        return redirect()->route('master-disposisi.index')->with('success', 'Master Disposisi berhasil dihapus!');
    }

    public function toggleStatus(MasterDisposisi $masterDisposisi)
    {
        $masterDisposisi->update([
            'is_active' => !$masterDisposisi->is_active
        ]);

        $status = $masterDisposisi->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('master-disposisi.index')->with('success', "Master Disposisi berhasil $status!");
    }
}
