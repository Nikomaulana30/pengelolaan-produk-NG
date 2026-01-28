<?php

use App\Models\ReturBarang;
use App\Models\RcaAnalysis;

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST RETUR BARANG & PRODUCT/VENDOR RELATIONSHIP ===\n\n";

// Test 1: Check retur barang dengan eager loading
echo "📊 [TEST 1] ReturBarang dengan Eager Loading (vendor + produk):\n";
$returBarangs = ReturBarang::with(['vendor', 'produk'])
    ->whereIn('status_approval', ['approved', 'pending'])
    ->limit(5)
    ->get();

echo "  Total: " . $returBarangs->count() . " retur\n\n";

foreach ($returBarangs as $retur) {
    echo "  ├─ No Retur: " . $retur->no_retur . "\n";
    echo "  │  Vendor: " . ($retur->vendor?->nama_vendor ?? 'TIDAK ADA') . "\n";
    echo "  │  Produk: " . ($retur->produk?->nama_produk ?? 'TIDAK ADA') . "\n";
    echo "  │  Status: " . $retur->status_approval . "\n";
    echo "  │\n";
}

// Test 2: Check RCA dengan eager loading
echo "\n🔍 [TEST 2] RCA Analysis dengan Eager Loading:\n";
$rcas = RcaAnalysis::with(['returBarang' => function($query) {
    $query->with(['vendor', 'produk']);
}])->limit(3)->get();

echo "  Total: " . $rcas->count() . " RCA\n\n";

foreach ($rcas as $rca) {
    echo "  ├─ Nomor RCA: " . $rca->nomor_rca . "\n";
    if ($rca->returBarang) {
        echo "  │  Retur Linked: YES\n";
        echo "  │  ├─ No Retur: " . $rca->returBarang->no_retur . "\n";
        echo "  │  ├─ Vendor: " . ($rca->returBarang->vendor?->nama_vendor ?? 'TIDAK ADA') . "\n";
        echo "  │  └─ Produk: " . ($rca->returBarang->produk?->nama_produk ?? 'TIDAK ADA') . "\n";
    } else {
        echo "  │  Retur Linked: NO (Standalone)\n";
    }
    echo "  │\n";
}

// Test 3: Check data consistency
echo "\n✓ [TEST 3] Data Consistency Check:\n";
$returnBarangWithoutVendor = ReturBarang::with(['vendor', 'produk'])
    ->whereNotNull('vendor_id')
    ->whereHas('vendor')
    ->count();

$returnBarangWithoutProduk = ReturBarang::with(['vendor', 'produk'])
    ->whereNotNull('produk_id')
    ->whereHas('produk')
    ->count();

echo "  Retur dengan Vendor: " . $returnBarangWithoutVendor . "\n";
echo "  Retur dengan Produk: " . $returnBarangWithoutProduk . "\n";

// Test 4: Check blade rendering simulation
echo "\n🎯 [TEST 4] Blade Rendering Simulation:\n";
$sample = $returBarangs->first();

if ($sample) {
    echo "  Option Text:\n";
    $optionText = $sample->no_retur . ' - ' . ($sample->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan') . ' (' . ($sample->produk?->nama_produk ?? 'Produk Tidak Ditemukan') . ')';
    echo "  └─ " . $optionText . "\n";
    
    echo "\n  Data Attributes:\n";
    echo "  ├─ data-vendor: " . ($sample->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan') . "\n";
    echo "  ├─ data-produk: " . ($sample->produk?->nama_produk ?? 'Produk Tidak Ditemukan') . "\n";
    echo "  ├─ data-satuan: " . ($sample->produk?->unit ?? 'unit') . "\n";
    echo "  └─ data-kode-barang: " . ($sample->produk?->kode_produk ?? 'N/A') . "\n";
}

echo "\n✅ TEST COMPLETED\n";
