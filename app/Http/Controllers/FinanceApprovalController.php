<?php

namespace App\Http\Controllers;

use App\Models\FinanceApproval;
use App\Models\RcaAnalysis;
use App\Models\Notification;
use Illuminate\Http\Request;

class FinanceApprovalController extends Controller
{
    public function index()
    {
        // Get all finance approvals with relationships, ordered by approval date (newest first)
        $approvals = FinanceApproval::with(['user', 'rcaAnalysis'])
            ->latest('tanggal_approval')
            ->paginate(20);
        
        // Get all RCA analyses for dropdown in form
        $rcaAnalyses = RcaAnalysis::with(['masterDefect', 'masterProduk'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('menu-sidebar.ppic.approval', compact('approvals', 'rcaAnalyses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_referensi' => 'required|string|max:100',
            'deskripsi_pengajuan' => 'required|string',
            'jenis_dampak' => 'required|in:claim,retur,scrap,rework_cost,tidak_ada',
            'estimasi_biaya' => 'required|numeric|min:0',
            'asal_permohonan' => 'required|in:qc,warehouse,manager,internal_ppic',
            'referensi_permohonan' => 'nullable|string|max:100',
            'status_approval' => 'required|in:pending,approved,rejected,need_revision,not_applicable',
            'tanggal_approval' => 'required|date',
            'nama_approver' => 'required|string|max:100',
            'budget_approval' => 'required|in:dalam_budget,melebihi_budget,perlu_persetujuan_lebih_tinggi',
            'catatan' => 'nullable|string',
        ]);

        $approval = FinanceApproval::create([
            ...$validated,
            'nomor_approval' => 'APV-' . date('Ymd') . '-' . str_pad(FinanceApproval::count() + 1, 4, '0', STR_PAD_LEFT),
            'pengaju' => auth()->user()->name,
            'user_id' => auth()->id(),
        ]);

        // Create notification for approval users
        if (auth()->user()->approval_notifications) {
            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'PPIC Approval Created',
                'message' => 'Approval finance ' . $approval->nomor_approval . ' untuk ' . $validated['nomor_referensi'],
                'type' => 'approval',
                'approval_type' => 'ppic',
                'status' => 'unread',
                'action_url' => route('ppic.approval.show', $approval),
                'icon' => 'bi-check-circle',
                'badge_color' => 'primary',
            ]);
        }

        return redirect()->route('ppic.approval.index')
            ->with('success', 'Approval Finance berhasil disimpan!');
    }

    public function show(FinanceApproval $approval)
    {
        $approval->load(['user', 'rcaAnalysis']);
        return view('menu-sidebar.ppic.approval-show', compact('approval'));
    }

    public function edit(FinanceApproval $approval)
    {
        $approval->load(['user', 'rcaAnalysis']);
        return view('menu-sidebar.ppic.approval-edit', compact('approval'));
    }

    public function update(Request $request, FinanceApproval $approval)
    {
        $validated = $request->validate([
            'status_approval' => 'required|in:pending,approved,rejected,need_revision,not_applicable',
            'budget_approval' => 'required|in:dalam_budget,melebihi_budget,perlu_persetujuan_lebih_tinggi',
            'catatan' => 'nullable|string',
        ]);

        $approval->update($validated);

        return redirect()->route('ppic.approval.show', $approval)
            ->with('success', 'Approval Finance berhasil diperbarui!');
    }

    public function destroy(FinanceApproval $approval)
    {
        $approval->delete();

        return redirect()->route('ppic.approval.index')
            ->with('success', 'Approval Finance berhasil dihapus!');
    }
}
