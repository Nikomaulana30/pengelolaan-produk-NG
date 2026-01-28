<?php

namespace App\Http\Controllers;

use App\Models\DisposisiAssignment;
use App\Models\MasterDisposisi;
use App\Models\PenyimpananNg;
use App\Models\MasterLokasiGudang;
use Illuminate\Http\Request;

class DisposisiAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = DisposisiAssignment::with(['penyimpananNg', 'disposisi', 'assignedBy', 'executedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by penyimpanan_ng
        if ($request->filled('penyimpanan_ng_id')) {
            $query->where('penyimpanan_ng_id', $request->get('penyimpanan_ng_id'));
        }

        // Filter by disposisi
        if ($request->filled('master_disposisi_id')) {
            $query->where('master_disposisi_id', $request->get('master_disposisi_id'));
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('penyimpananNg', function($q) use ($search) {
                $q->where('nomor_storage', 'like', "%{$search}%");
            });
        }

        $assignments = $query->latest()->paginate(20);
        $penyimpananNgs = PenyimpananNg::all();
        $disposisis = MasterDisposisi::all();

        // Calculate stats
        $totalAssignments = DisposisiAssignment::count();
        $pendingCount = DisposisiAssignment::pending()->count();
        $inProgressCount = DisposisiAssignment::inProgress()->count();
        $completedCount = DisposisiAssignment::completed()->count();

        return view('menu-sidebar.disposisi-assignment.index', compact(
            'assignments', 
            'penyimpananNgs', 
            'disposisis',
            'totalAssignments',
            'pendingCount',
            'inProgressCount',
            'completedCount'
        ));
    }

    public function create()
    {
        // Get penyimpanan NG yang belum didisposisi dan masih bisa diassign
        $penyimpananNgs = PenyimpananNg::whereIn('status_barang', ['disimpan', 'dalam_perbaikan', 'menunggu_approval'])
            ->whereNull('master_disposisi_id')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $disposisis = MasterDisposisi::where('is_active', true)
            ->orderBy('nama_disposisi')
            ->get();

        // Get active lokasi gudang untuk lokasi tujuan
        $lokasiGudangs = MasterLokasiGudang::where('is_active', true)
            ->orderBy('lokasi_lengkap')
            ->get();

        return view('menu-sidebar.disposisi-assignment.create', compact('penyimpananNgs', 'disposisis', 'lokasiGudangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'penyimpanan_ng_id' => 'required|exists:penyimpanan_ngs,id',
            'master_disposisi_id' => 'required|exists:master_disposisis,id',
            'zone_tujuan' => 'nullable|in:zona_a,zona_b,zona_c,zona_d,zona_e',
            'rack_tujuan' => 'nullable|string',
            'bin_tujuan' => 'nullable|string',
            'lokasi_lengkap_tujuan' => 'nullable|string',
            'tanggal_relokasi' => 'nullable|date',
            'alasan_relokasi' => 'nullable|string',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $validated['assigned_by'] = auth()->id();
        $validated['assigned_at'] = now();
        $validated['status'] = 'pending';

        DisposisiAssignment::create($validated);

        return redirect()->route('disposisi-assignment.index')->with('success', 'Disposisi berhasil diassign!');
    }

    public function show(DisposisiAssignment $disposisiAssignment)
    {
        $disposisiAssignment->load(['penyimpananNg', 'disposisi', 'assignedBy', 'executedBy']);
        return view('menu-sidebar.disposisi-assignment.show', compact('disposisiAssignment'));
    }

    public function markInProgress(DisposisiAssignment $disposisiAssignment)
    {
        $disposisiAssignment->markInProgress(auth()->id());
        return redirect()->route('disposisi-assignment.show', $disposisiAssignment)->with('success', 'Status diubah menjadi In Progress!');
    }

    public function markCompleted(Request $request, DisposisiAssignment $disposisiAssignment)
    {
        $validated = $request->validate([
            'hasil_eksekusi' => 'required|string|max:1000',
        ]);

        $disposisiAssignment->markCompleted($validated['hasil_eksekusi'], auth()->id());

        // Update penyimpanan_ng status if necessary
        if ($disposisiAssignment->disposisi->jenis_tindakan === 'scrap') {
            $disposisiAssignment->penyimpananNg->update(['status' => 'disposed']);
        }

        return redirect()->route('disposisi-assignment.show', $disposisiAssignment)->with('success', 'Disposisi berhasil diselesaikan!');
    }

    public function markCancelled(Request $request, DisposisiAssignment $disposisiAssignment)
    {
        $validated = $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $disposisiAssignment->markCancelled($validated['alasan'], auth()->id());
        return redirect()->route('disposisi-assignment.show', $disposisiAssignment)->with('success', 'Disposisi dibatalkan!');
    }

    public function destroy(DisposisiAssignment $disposisiAssignment)
    {
        if ($disposisiAssignment->status !== 'pending') {
            return redirect()->route('disposisi-assignment.index')->with('error', 'Hanya dapat menghapus disposisi yang belum diproses!');
        }

        $disposisiAssignment->delete();
        return redirect()->route('disposisi-assignment.index')->with('success', 'Disposisi berhasil dihapus!');
    }
}
