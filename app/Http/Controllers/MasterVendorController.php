<?php

namespace App\Http\Controllers;

use App\Models\MasterVendor;
use App\Models\MasterProduk;
use App\Models\QualityReinspection;
use Illuminate\Http\Request;

class MasterVendorController extends Controller
{
    public function index()
    {
        $vendors = MasterVendor::withCount('produks')->paginate(15);
        
        // Dynamic statistics
        $totalVendor = MasterVendor::count();
        $vendorAktif = MasterVendor::where('is_active', true)->count();
        
        // Count total products from all vendors
        $totalProdukVendor = MasterProduk::whereNotNull('vendor_id')->count();
        
        // Count quality issues - just count all quality reinspections for now
        $totalQualityIssues = QualityReinspection::count();
        
        return view('menu-sidebar.master-data.master-vendor', compact(
            'vendors',
            'totalVendor',
            'vendorAktif',
            'totalProdukVendor',
            'totalQualityIssues'
        ));
    }

    public function create()
    {
        return view('menu-sidebar.master-data.master-vendor-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_vendor' => 'required|unique:master_vendors|max:50',
            'nama_vendor' => 'required|max:255',
            'alamat_vendor' => 'required|string',
            'kota' => 'nullable|max:100',
            'provinsi' => 'nullable|max:100',
            'kode_pos' => 'nullable|max:10',
            'telepon' => 'nullable|max:20',
            'email' => 'nullable|email',
            'person_in_charge' => 'nullable|max:255',
            'kebijakan_retur' => 'required|in:retur_fisik,debit_note,keduanya',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox is_active properly
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        MasterVendor::create($validated);

        return redirect()->route('master-vendor.index')->with('success', 'Master Vendor berhasil ditambahkan!');
    }

    public function show(MasterVendor $masterVendor)
    {
        return view('menu-sidebar.master-data.master-vendor-show', compact('masterVendor'));
    }

    public function edit(MasterVendor $masterVendor)
    {
        return view('menu-sidebar.master-data.master-vendor-edit', compact('masterVendor'));
    }

    public function update(Request $request, MasterVendor $masterVendor)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|max:255',
            'alamat_vendor' => 'required|string',
            'kota' => 'nullable|max:100',
            'provinsi' => 'nullable|max:100',
            'kode_pos' => 'nullable|max:10',
            'telepon' => 'nullable|max:20',
            'email' => 'nullable|email',
            'person_in_charge' => 'nullable|max:255',
            'kebijakan_retur' => 'required|in:retur_fisik,debit_note,keduanya',
            'deskripsi' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox is_active properly
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $masterVendor->update($validated);

        return redirect()->route('master-vendor.show', $masterVendor)->with('success', 'Master Vendor berhasil diperbarui!');
    }

    public function destroy(MasterVendor $masterVendor)
    {
        $masterVendor->delete();

        return redirect()->route('master-vendor.index')->with('success', 'Master Vendor berhasil dihapus!');
    }
}
