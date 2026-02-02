<?php

namespace App\Http\Controllers;

use App\Models\CustomerComplaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerComplaintController extends Controller
{
    public function index()
    {
        $complaints = CustomerComplaint::with('staffExim')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('menu-sidebar.customer-complaint.index', compact('complaints'));
    }

    public function create()
    {
        $staffEximOptions = User::where('role', 'staff_exim')->where('is_active', true)->get();
        $masterCustomers = \App\Models\MasterCustomer::active()->orderBy('nama_customer')->get();
        return view('menu-sidebar.customer-complaint.create', compact('staffEximOptions', 'masterCustomers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'master_customer_id' => 'required|exists:master_customers,id',
            'produk' => 'required|string|max:255',
            'quantity_ng' => 'required|integer|min:1',
            'deskripsi_complaint' => 'required|string',
            'foto_complaint.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_pendukung.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'priority' => 'required|in:low,medium,high,critical',
            'catatan_staff' => 'nullable|string'
        ]);

        // Get customer data from master
        $customer = \App\Models\MasterCustomer::findOrFail($request->master_customer_id);

        // Handle file uploads
        $fotoComplaint = [];
        if ($request->hasFile('foto_complaint')) {
            foreach ($request->file('foto_complaint') as $file) {
                $path = $file->store('complaints/photos', 'public');
                $fotoComplaint[] = $path;
            }
        }

        $dokumenPendukung = [];
        if ($request->hasFile('dokumen_pendukung')) {
            foreach ($request->file('dokumen_pendukung') as $file) {
                $path = $file->store('complaints/documents', 'public');
                $dokumenPendukung[] = $path;
            }
        }

        // Prepare data for creation
        $complaintData = [
            'master_customer_id' => $request->master_customer_id,
            'nama_customer' => $customer->nama_customer,
            'email_customer' => $customer->email_customer,
            'telepon_customer' => $customer->telepon_customer,
            'alamat_customer' => $customer->alamat_customer,
            'produk' => $validated['produk'],
            'quantity_ng' => $validated['quantity_ng'],
            'deskripsi_complaint' => $validated['deskripsi_complaint'],
            'priority' => $validated['priority'],
            'catatan_staff' => $validated['catatan_staff'] ?? null,
            'foto_complaint' => $fotoComplaint,
            'dokumen_pendukung' => $dokumenPendukung,
            'tanggal_complaint' => now(),
            'staff_exim_id' => Auth::id(),
            'status' => 'submitted',
        ];

        CustomerComplaint::create($complaintData);

        return redirect()->route('customer-complaint.index')
            ->with('success', '✅ Customer complaint berhasil dibuat! Data customer otomatis terisi dari master.');
    }

    public function show($id)
    {
        $complaint = CustomerComplaint::with(['staffExim', 'dokumenRetur'])->findOrFail($id);
        return view('menu-sidebar.customer-complaint.show', compact('complaint'));
    }

    public function edit($id)
    {
        $complaint = CustomerComplaint::findOrFail($id);
        $staffEximOptions = User::where('role', 'staff_exim')->where('is_active', true)->get();
        
        return view('menu-sidebar.customer-complaint.edit', compact('complaint', 'staffEximOptions'));
    }

    public function update(Request $request, $id)
    {
        $complaint = CustomerComplaint::findOrFail($id);
        
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email_customer' => 'required|email|max:255',
            'telepon_customer' => 'required|string|max:20',
            'alamat_customer' => 'required|string',
            'produk' => 'required|string|max:255',
            'quantity_ng' => 'required|integer|min:1',
            'deskripsi_complaint' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:draft,submitted,processing,completed,cancelled',
            'catatan_staff' => 'nullable|string'
        ]);

        // Handle new file uploads
        $fotoComplaint = $complaint->foto_complaint ?? [];
        if ($request->hasFile('foto_complaint')) {
            foreach ($request->file('foto_complaint') as $file) {
                $path = $file->store('complaints/photos', 'public');
                $fotoComplaint[] = $path;
            }
        }

        $dokumenPendukung = $complaint->dokumen_pendukung ?? [];
        if ($request->hasFile('dokumen_pendukung')) {
            foreach ($request->file('dokumen_pendukung') as $file) {
                $path = $file->store('complaints/documents', 'public');
                $dokumenPendukung[] = $path;
            }
        }

        $validated['foto_complaint'] = $fotoComplaint;
        $validated['dokumen_pendukung'] = $dokumenPendukung;

        $complaint->update($validated);

        return redirect()->route('customer-complaint.show', $complaint->id)
            ->with('success', 'Customer complaint berhasil diupdate');
    }

    public function updateStatus(Request $request, $id)
    {
        $complaint = CustomerComplaint::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:draft,submitted,processing,completed,cancelled',
            'catatan_staff' => 'nullable|string'
        ]);

        $complaint->update($validated);

        return redirect()->back()->with('success', 'Status complaint berhasil diupdate');
    }

    public function destroy($id)
    {
        $complaint = CustomerComplaint::findOrFail($id);
        
        // Delete uploaded files
        if ($complaint->foto_complaint) {
            foreach ($complaint->foto_complaint as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        
        if ($complaint->dokumen_pendukung) {
            foreach ($complaint->dokumen_pendukung as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $complaint->delete();

        return redirect()->route('customer-complaint.index')
            ->with('success', 'Customer complaint berhasil dihapus');
    }
}
