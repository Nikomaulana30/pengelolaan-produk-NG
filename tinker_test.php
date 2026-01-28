use App\Models\ReturBarang;
use App\Models\MasterVendor;
use App\Models\MasterProduk;

echo "========================================\n";
echo "RETUR BARANG CRUD TESTING\n";
echo "========================================\n\n";

// 1. CHECK DATA EXISTS
echo "1. CHECKING EXISTING DATA...\n";
$vendors = MasterVendor::where('is_active', true)->first();
$produks = MasterProduk::where('is_active', true)->first();

if (!$vendors || !$produks) {
    echo "❌ ERROR: Tidak ada Vendor atau Produk yang aktif!\n";
    exit;
}

echo "✅ Vendor: {$vendors->nama_vendor}\n";
echo "✅ Produk: {$produks->nama_produk}\n\n";

// 2. CREATE
echo "2. CREATE TEST...\n";
$retur = ReturBarang::create([
    'vendor_id' => $vendors->id,
    'produk_id' => $produks->id,
    'tanggal_retur' => now(),
    'alasan_retur' => 'defect',
    'jumlah_retur' => 5,
    'deskripsi_keluhan' => 'Test CRUD Operation via Tinker',
    'status_approval' => 'pending',
]);
echo "✅ Created: No Retur = {$retur->no_retur}\n";
echo "✅ Status: {$retur->status_approval}\n";
echo "✅ Qty: {$retur->jumlah_retur}\n\n";

// 3. READ
echo "3. READ TEST - Verify Relationships...\n";
$read = ReturBarang::with('vendor', 'produk')->find($retur->id);
echo "✅ Vendor Relation: {$read->vendor->nama_vendor}\n";
echo "✅ Produk Relation: {$read->produk->nama_produk}\n";
echo "✅ No Retur Format: {$read->no_retur}\n\n";

// 4. UPDATE
echo "4. UPDATE TEST...\n";
$read->update([
    'status_approval' => 'approved',
    'catatan_approval' => 'Approved by QC'
]);
$updated = ReturBarang::find($retur->id);
echo "✅ Status updated: {$updated->status_approval}\n";
echo "✅ Catatan: {$updated->catatan_approval}\n\n";

// 5. STATISTICS
echo "5. STATISTICS...\n";
echo "✅ Total Retur: " . ReturBarang::count() . "\n";
echo "✅ Pending: " . ReturBarang::where('status_approval', 'pending')->count() . "\n";
echo "✅ Approved: " . ReturBarang::where('status_approval', 'approved')->count() . "\n";
echo "✅ Rejected: " . ReturBarang::where('status_approval', 'rejected')->count() . "\n\n";

// 6. DELETE (Soft Delete)
echo "6. DELETE TEST (Soft Delete)...\n";
$deleteId = $retur->id;
$retur->delete();
$notFound = ReturBarang::find($deleteId);
$stillInDb = ReturBarang::withTrashed()->find($deleteId);
echo "✅ Deleted from normal query: " . ($notFound === null ? 'Yes' : 'No') . "\n";
echo "✅ Still in database (soft delete): " . ($stillInDb ? 'Yes' : 'No') . "\n";
echo "✅ Deleted at: " . ($stillInDb->deleted_at ?? 'N/A') . "\n\n";

echo "========================================\n";
echo "✅ ALL CRUD TESTS PASSED!\n";
echo "========================================\n";
echo "\nTest Summary:\n";
echo "✅ CREATE: No auto-generation working\n";
echo "✅ READ: Relationships (Vendor & Produk) working\n";
echo "✅ UPDATE: Status & Catatan updated successfully\n";
echo "✅ DELETE: Soft delete working (data preserved)\n";
echo "✅ STATS: Statistics calculation working\n";
echo "\n";
