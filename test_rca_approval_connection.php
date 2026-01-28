<?php

use App\Models\FinanceApproval;
use App\Models\RcaAnalysis;

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST RCA & APPROVAL CONNECTION ===\n\n";

// Test 1: Check if there are any approvals
echo "📊 [TEST 1] Total Finance Approvals:\n";
$totalApprovals = FinanceApproval::count();
echo "  Total: " . $totalApprovals . "\n\n";

// Test 2: Check approvals with RCA relationship
echo "🔗 [TEST 2] Approvals with RCA Linked:\n";
$approvalsWithRca = FinanceApproval::with(['rcaAnalysis'])->whereNotNull('nomor_referensi')->get();
echo "  Total with referensi: " . $approvalsWithRca->count() . "\n";

if ($approvalsWithRca->count() > 0) {
    foreach ($approvalsWithRca->take(5) as $approval) {
        echo "  ├─ Nomor Approval: " . $approval->nomor_approval . "\n";
        echo "  │  Nomor Referensi: " . $approval->nomor_referensi . "\n";
        echo "  │  RCA Found: " . ($approval->rcaAnalysis ? "✓ Yes" : "✗ No") . "\n";
        if ($approval->rcaAnalysis) {
            echo "  │  RCA Nomor: " . $approval->rcaAnalysis->nomor_rca . "\n";
            echo "  │  RCA Status: " . $approval->rcaAnalysis->status_rca . "\n";
        }
        echo "  │\n";
    }
} else {
    echo "  ⚠ Tidak ada approval dengan referensi\n\n";
}

// Test 3: Check RCA Analysis
echo "\n📋 [TEST 3] RCA Analysis Data:\n";
$rcaCount = RcaAnalysis::count();
echo "  Total RCA: " . $rcaCount . "\n";

if ($rcaCount > 0) {
    $rcaSamples = RcaAnalysis::with(['masterDefect', 'masterProduk', 'returBarang'])->take(3)->get();
    foreach ($rcaSamples as $rca) {
        echo "  ├─ Nomor RCA: " . $rca->nomor_rca . "\n";
        echo "  │  Status: " . $rca->status_rca . "\n";
        echo "  │  Defect: " . ($rca->masterDefect?->nama_defect ?? "N/A") . "\n";
        echo "  │  Product: " . ($rca->masterProduk?->nama_produk ?? "N/A") . "\n";
        echo "  │  Retur Linked: " . ($rca->returBarang ? "✓ Yes" : "✗ No") . "\n";
        echo "  │\n";
    }
}

// Test 4: Check table structure
echo "\n🗂️ [TEST 4] Database Tables:\n";
$tables = [
    'finance_approvals' => 'Finance Approvals',
    'rca_analyses' => 'RCA Analysis',
    'retur_barangs' => 'Retur Barang',
    'master_defects' => 'Master Defects',
    'master_products' => 'Master Products'
];

foreach ($tables as $table => $label) {
    $count = \Illuminate\Support\Facades\DB::table($table)->count();
    echo "  ├─ $label ($table): " . $count . " records\n";
}

echo "\n✅ TEST COMPLETED\n";
