<?php

namespace App\Http\Controllers;

use App\Models\WarehouseApproval;
use App\Models\Notification;
use Illuminate\Http\Request;

class WarehouseApprovalController extends Controller
{
    public function index()
    {
        $approvals = WarehouseApproval::latest()->paginate(20);
        
        // Calculate stats
        $stats = [
            'pending' => WarehouseApproval::pending()->count(),
            'ws_pending' => WarehouseApproval::WSPending()->count(),
            'pm_pending' => WarehouseApproval::PMPending()->count(),
            'approved' => WarehouseApproval::approved()->count(),
            'rejected' => WarehouseApproval::rejected()->count(),
        ];

        return view('menu-sidebar.warehouse.approval', compact('approvals', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_referensi' => 'required|string|max:100',
            'deskripsi_pengajuan' => 'required|string',
            'ws_status_approval' => 'required|in:pending,approved,rejected,need_revision',
            'ws_tanggal_approval' => 'nullable|date',
            'ws_nama_approver' => 'nullable|string|max:100',
            'ws_kondisi_barang' => 'nullable|in:aman,perlu_penanganan,tidak_layak',
            'ws_catatan' => 'nullable|string',
            'pm_status_approval' => 'required|in:pending,approved,rejected,need_revision',
            'pm_tanggal_approval' => 'nullable|date',
            'pm_nama_approver' => 'nullable|string|max:100',
            'pm_keputusan' => 'nullable|in:rework,repair,scrap,use_as_is',
            'pm_catatan' => 'nullable|string',
        ]);

        $approval = WarehouseApproval::create([
            ...$validated,
            'nomor_approval' => 'APV-' . date('Ymd') . '-' . str_pad(WarehouseApproval::count() + 1, 4, '0', STR_PAD_LEFT),
            'tanggal_pengajuan' => now(),
            'pengaju' => auth()->user()->name,
            'user_id' => auth()->id(),
        ]);

        // Update overall status
        $approval->updateOverallStatus()->save();

        // Create notification for approval users
        if (auth()->user()->approval_notifications) {
            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Warehouse Approval Created',
                'message' => 'Warehouse approval untuk ' . $validated['nomor_referensi'],
                'type' => 'approval',
                'approval_type' => 'warehouse',
                'status' => 'unread',
                'action_url' => route('warehouse.approval.show', $approval),
                'icon' => 'bi-box-seam',
                'badge_color' => 'success',
            ]);
        }

        return redirect()->route('warehouse.approval.index')
            ->with('success', 'Approval Warehouse & Manager berhasil disimpan!');
    }

    public function show(WarehouseApproval $approval)
    {
        return view('menu-sidebar.warehouse.approval-show', compact('approval'));
    }

    public function edit(WarehouseApproval $approval)
    {
        return view('menu-sidebar.warehouse.approval-edit', compact('approval'));
    }

    public function update(Request $request, WarehouseApproval $approval)
    {
        // Only allow edit if overall status is pending
        if ($approval->status_keseluruhan !== 'pending') {
            return redirect()->back()->with('error', 'Hanya dapat edit data dengan status Pending!');
        }

        $validated = $request->validate([
            'nomor_referensi' => 'required|string|max:100',
            'deskripsi_pengajuan' => 'required|string',
            'ws_status_approval' => 'required|in:pending,approved,rejected,need_revision',
            'ws_tanggal_approval' => 'nullable|date',
            'ws_nama_approver' => 'nullable|string|max:100',
            'ws_kondisi_barang' => 'nullable|in:aman,perlu_penanganan,tidak_layak',
            'ws_catatan' => 'nullable|string',
            'pm_status_approval' => 'required|in:pending,approved,rejected,need_revision',
            'pm_tanggal_approval' => 'nullable|date',
            'pm_nama_approver' => 'nullable|string|max:100',
            'pm_keputusan' => 'nullable|in:rework,repair,scrap,use_as_is',
            'pm_catatan' => 'nullable|string',
        ]);

        $approval->update($validated);
        
        // Update overall status
        $approval->updateOverallStatus()->save();

        return redirect()->route('warehouse.approval.show', $approval)
            ->with('success', 'Approval Warehouse & Manager berhasil diperbarui!');
    }

    public function destroy(WarehouseApproval $approval)
    {
        if ($approval->status_keseluruhan !== 'pending') {
            return redirect()->back()->with('error', 'Hanya dapat hapus data dengan status Pending!');
        }

        $approval->delete();

        return redirect()->route('warehouse.approval.index')
            ->with('success', 'Approval Warehouse & Manager berhasil dihapus!');
    }
}
