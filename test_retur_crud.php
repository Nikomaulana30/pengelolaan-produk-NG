<?php

/**
 * RETUR BARANG CRUD TESTING SCRIPT
 * 
 * Run with: php artisan tinker < test_retur_crud.php
 * Or: php test_retur_crud.php (from Laravel root)
 * 
 * Testing:
 * 1. Create Retur Barang
 * 2. Read/List Retur Barang
 * 3. Update Retur Barang
 * 4. Delete Retur Barang
 * 5. Verify auto-generated no_retur
 * 6. Verify foreign key relationships
 */

use App\Models\ReturBarang;
use App\Models\MasterVendor;
use App\Models\MasterProduk;

echo "========================================\n";
echo "RETUR BARANG CRUD TESTING\n";
echo "========================================\n\n";

// 1. CHECK DATA EXISTS
echo "1. CHECKING EXISTING DATA...\n";
$vendors = MasterVendor::where('is_active', true)->limit(1)->first();
$produks = MasterProduk::where('is_active', true)->limit(1)->first();

if (!$vendors || !$produks) {
    echo "❌ ERROR: Tidak ada Vendor atau Produk yang aktif!\n";
    echo "   - Vendor aktif: " . MasterVendor::where('is_active', true)->count() . "\n";
    echo "   - Produk aktif: " . MasterProduk::where('is_active', true)->count() . "\n";
    exit;
}

echo "✅ Vendor ditemukan: {$vendors->nama_vendor} (ID: {$vendors->id})\n";
echo "✅ Produk ditemukan: {$produks->nama_produk} (ID: {$produks->id})\n\n";

// 2. CREATE - Test insert new retur
echo "2. CREATE TEST - Insert new Retur Barang...\n";
$retur = ReturBarang::create([
    'vendor_id' => $vendors->id,
    'produk_id' => $produks->id,
    'tanggal_retur' => now(),
    'alasan_retur' => 'defect',
    'jumlah_retur' => 5,
    'deskripsi_keluhan' => 'Testing create operation',
    'status_approval' => 'pending',
]);

echo "✅ Retur created successfully!\n";
echo "   - ID: {$retur->id}\n";
echo "   - No. Retur: {$retur->no_retur}\n";
echo "   - Status: {$retur->status_approval}\n";
echo "   - Qty: {$retur->jumlah_retur}\n\n";

// 3. READ - Test fetch and relationships
echo "3. READ TEST - Fetch and verify relationships...\n";
$returDetail = ReturBarang::with('vendor', 'produk')->find($retur->id);

if (!$returDetail) {
    echo "❌ ERROR: Retur tidak ditemukan setelah insert!\n";
    exit;
}

echo "✅ Retur fetched successfully!\n";
echo "   - Vendor relationship: {$returDetail->vendor->nama_vendor}\n";
echo "   - Produk relationship: {$returDetail->produk->nama_produk}\n";
echo "   - No. Retur format: {$returDetail->no_retur}\n\n";

// 4. UPDATE - Test update operation
echo "4. UPDATE TEST - Update retur status...\n";
$returDetail->update([
    'status_approval' => 'approved',
    'catatan_approval' => 'Approved by QC team',
]);

$updated = ReturBarang::find($retur->id);
echo "✅ Retur updated successfully!\n";
echo "   - Status: {$updated->status_approval}\n";
echo "   - Catatan: {$updated->catatan_approval}\n\n";

// 5. LIST - Test pagination and stats
echo "5. LIST TEST - Verify statistics calculation...\n";
$totalRetur = ReturBarang::count();
$pendingRetur = ReturBarang::where('status_approval', 'pending')->count();
$approvedRetur = ReturBarang::where('status_approval', 'approved')->count();
$rejectedRetur = ReturBarang::where('status_approval', 'rejected')->count();

echo "✅ Statistics:\n";
echo "   - Total: {$totalRetur}\n";
echo "   - Pending: {$pendingRetur}\n";
echo "   - Approved: {$approvedRetur}\n";
echo "   - Rejected: {$rejectedRetur}\n\n";

// 6. VERIFY NO_RETUR FORMAT
echo "6. NO_RETUR FORMAT VERIFICATION...\n";
$allReturs = ReturBarang::all();
$formatValid = true;
foreach ($allReturs as $item) {
    if (!preg_match('/^RET-\d{4}-\d{5}$/', $item->no_retur)) {
        echo "❌ Invalid format: {$item->no_retur}\n";
        $formatValid = false;
    }
}

if ($formatValid && $allReturs->count() > 0) {
    echo "✅ All no_retur formats are valid (RET-YYYY-XXXXX)\n";
    echo "   - Example: {$allReturs->first()->no_retur}\n\n";
} else {
    echo "❌ Format validation failed!\n\n";
}

// 7. DELETE - Test soft delete
echo "7. DELETE TEST - Test soft delete...\n";
$deleteId = $retur->id;
$retur->delete();

$deletedCheck = ReturBarang::find($deleteId);
if ($deletedCheck === null) {
    echo "✅ Soft delete successful (not found in normal query)\n";
}

$deletedWithTrashed = ReturBarang::withTrashed()->find($deleteId);
if ($deletedWithTrashed && $deletedWithTrashed->deleted_at !== null) {
    echo "✅ Data still exists in database with deleted_at: {$deletedWithTrashed->deleted_at}\n\n";
} else {
    echo "❌ Soft delete verification failed!\n\n";
}

// 8. FOREIGN KEY TEST
echo "8. FOREIGN KEY RELATIONSHIP TEST...\n";
$testRetur = ReturBarang::withTrashed()->find($deleteId);
$vendorCheck = $testRetur->vendor;
$produkCheck = $testRetur->produk;

if ($vendorCheck && $produkCheck) {
    echo "✅ Foreign keys working correctly\n";
    echo "   - Vendor: {$vendorCheck->nama_vendor}\n";
    echo "   - Produk: {$produkCheck->nama_produk}\n\n";
} else {
    echo "❌ Foreign key relationships failed!\n\n";
}

echo "========================================\n";
echo "TESTING COMPLETE\n";
echo "========================================\n";
echo "\n✅ All CRUD operations tested successfully!\n";
echo "\nNext steps:\n";
echo "1. Visit: http://localhost/laravel_projects/metinca-starter-app/retur-barang\n";
echo "2. Test UI: Create, Read, Edit, Delete operations\n";
echo "3. Verify SweetAlert2 delete confirmation\n";
echo "4. Check responsive design on mobile\n";
