<?php

namespace App\Http\Controllers;

use App\Models\MasterApprovalAuthority;
use App\Models\User;
use Illuminate\Http\Request;

class MasterApprovalAuthorityController extends Controller
{
    public function index()
    {
        $authorities = MasterApprovalAuthority::with('user')->paginate(15);
        return view('menu-sidebar.master-data.master-approval', compact('authorities'));
    }

    public function create()
    {
        $users = User::active()->get();
        return view('menu-sidebar.master-data.master-approval-create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'departemen' => 'required|in:warehouse,quality,ppic',
            'role_level' => 'required|in:supervisor,manager,director',
            'approval_limit' => 'required|numeric|min:0',
            'jenis_approval' => 'required|in:penerimaan_barang,penyimpanan_ng,scrap_disposal,retur_vendor,rework,rca_analysis',
            'can_approve_self' => 'boolean',
            'deskripsi' => 'nullable|string',
        ]);

        MasterApprovalAuthority::create($validated);

        return redirect()->route('master-approval.index')->with('success', 'Master Approval Authority berhasil ditambahkan!');
    }

    public function show(MasterApprovalAuthority $masterApproval)
    {
        return view('menu-sidebar.master-data.master-approval-show', compact('masterApproval'));
    }

    public function edit(MasterApprovalAuthority $masterApproval)
    {
        $users = User::active()->get();
        return view('menu-sidebar.master-data.master-approval-edit', compact('masterApproval', 'users'));
    }

    public function update(Request $request, MasterApprovalAuthority $masterApproval)
    {
        $validated = $request->validate([
            'departemen' => 'required|in:warehouse,quality,ppic',
            'role_level' => 'required|in:supervisor,manager,director',
            'approval_limit' => 'required|numeric|min:0',
            'jenis_approval' => 'required|in:penerimaan_barang,penyimpanan_ng,scrap_disposal,retur_vendor,rework,rca_analysis',
            'can_approve_self' => 'boolean',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $masterApproval->update($validated);

        return redirect()->route('master-approval.show', $masterApproval)->with('success', 'Master Approval Authority berhasil diperbarui!');
    }

    public function destroy(MasterApprovalAuthority $masterApproval)
    {
        $masterApproval->delete();

        return redirect()->route('master-approval.index')->with('success', 'Master Approval Authority berhasil dihapus!');
    }
}
