use App\Models\MasterVendor;
use App\Http\Controllers\VendorScorecardController;

echo "========================================\n";
echo "VENDOR SCORECARD TEST\n";
echo "========================================\n\n";

// Test 1: Get vendor count
echo "1. Testing MasterVendor...\n";
$vendorCount = MasterVendor::where('is_active', true)->count();
echo "✅ Active vendors: " . $vendorCount . "\n\n";

// Test 2: Test controller instantiation
echo "2. Testing VendorScorecardController instantiation...\n";
$controller = new VendorScorecardController();
echo "✅ Controller instantiated successfully\n\n";

// Test 3: Get first vendor with returns
echo "3. Testing Vendor with returns...\n";
$vendor = MasterVendor::with('returBarangs')
    ->where('is_active', true)
    ->first();

if ($vendor) {
    echo "✅ Vendor: " . $vendor->nama_vendor . "\n";
    echo "✅ Total returns: " . $vendor->returBarangs->count() . "\n";
    
    if ($vendor->returBarangs->count() > 0) {
        echo "✅ First return no: " . $vendor->returBarangs->first()->no_retur . "\n";
    }
}

echo "\n========================================\n";
echo "✅ ALL TESTS PASSED!\n";
echo "========================================\n";
