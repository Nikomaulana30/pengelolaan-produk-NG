<?php

namespace App\Http\Controllers;

use App\Models\QualityInspection;
use App\Models\Notification;
use Illuminate\Http\Request;

class QualityApprovalController extends Controller
{
    public function index()
    {
        // Get all quality inspections that have approval status (not null)
        $approvals = QualityInspection::with(['user', 'masterProduk', 'masterDefect'])
            ->whereNotNull('status_approval')
            ->latest('tanggal_approval')
            ->paginate(20);
        
        return view('menu-sidebar.quality.approval', compact('approvals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_referensi' => 'required|string|max:100',
            'deskripsi_pengajuan' => 'required|string',
            'status_approval' => 'required|in:pending,approved,rejected,need_revision',
            'catatan_approval' => 'nullable|string',
            'nama_approver' => 'required|string|max:100',
        ]);

        // Generate unique nomor_approval
        $counter = QualityInspection::count() + 1;
        $nomor_approval = 'QC-APV-' . date('Ymd') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);

        // Find the inspection record
        $inspection = QualityInspection::where('nomor_laporan', $validated['nomor_referensi'])->first();

        if (!$inspection) {
            return redirect()->back()
                ->with('error', 'Nomor referensi inspeksi tidak ditemukan!');
        }

        // Update inspection with approval info
        $inspection->update([
            'status_approval' => $validated['status_approval'],
            'catatan_approval' => $validated['catatan_approval'] ?? null,
            'nama_approver' => $validated['nama_approver'],
            'tanggal_approval' => now(),
        ]);

        // Create notification for approval users
        if (auth()->user()->approval_notifications) {
            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Quality Approval Created',
                'message' => 'Quality approval untuk ' . $validated['nomor_referensi'] . ' - Status: ' . $validated['status_approval'],
                'type' => 'approval',
                'approval_type' => 'quality',
                'status' => 'unread',
                'action_url' => route('quality.approval.show', $inspection->id),
                'icon' => 'bi-clipboard-check',
                'badge_color' => 'warning',
            ]);
        }

        return redirect()->route('quality.approval.index')
            ->with('success', 'Quality approval stored');
    }

    public function show($id)
    {
        $approval = QualityInspection::findOrFail($id);
        return view('menu-sidebar.quality.approval-show', compact('approval'));
    }

    public function edit($id)
    {
        $approval = QualityInspection::findOrFail($id);
        return view('menu-sidebar.quality.approval-edit', compact('approval'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status_approval' => 'required|in:pending,approved,rejected,need_revision',
            'catatan_approval' => 'nullable|string',
            'nama_approver' => 'required|string|max:100',
        ]);

        $approval = QualityInspection::findOrFail($id);
        $approval->update([
            'status_approval' => $validated['status_approval'],
            'catatan_approval' => $validated['catatan_approval'] ?? null,
            'nama_approver' => $validated['nama_approver'],
            'tanggal_approval' => now(),
        ]);

        return redirect()->route('quality.approval.index')
            ->with('success', 'Quality approval berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $approval = QualityInspection::findOrFail($id);
        $nomor = $approval->nomor_laporan;
        
        // Reset approval fields
        $approval->update([
            'status_approval' => null,
            'catatan_approval' => null,
            'nama_approver' => null,
            'tanggal_approval' => null,
        ]);

        return redirect()->route('quality.approval.index')
            ->with('success', "Approval untuk {$nomor} berhasil dihapus!");
    }
}
