<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PenerimaanBarang;
use App\Models\PenyimpananNg;

echo "=== TESTING PENERIMAAN BARANG & PENYIMPANAN NG RELATIONSHIP ===\n\n";

// Test 1: Check if column exists
echo "✅ Test 1: Checking if penerimaan_barang_id column exists...\n";
try {
    $test = \DB::select("SHOW COLUMNS FROM penyimpanan_ngs LIKE 'penerimaan_barang_id'");
    if (count($test) > 0) {
        echo "   ✅ Column 'penerimaan_barang_id' exists in penyimpanan_ngs table\n";
    } else {
        echo "   ❌ Column 'penerimaan_barang_id' NOT found\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check if relationships are defined
echo "✅ Test 2: Testing model relationships...\n";

$penerimaanCount = PenerimaanBarang::count();
echo "   Total Penerimaan Barang records: {$penerimaanCount}\n";

if ($penerimaanCount > 0) {
    $penerimaan = PenerimaanBarang::first();
    echo "   Testing relationship on: {$penerimaan->nomor_dokumen}\n";
    
    // Test hasMany relationship
    try {
        $penyimpananCount = $penerimaan->penyimpananNgs()->count();
        echo "   ✅ PenerimaanBarang->penyimpananNgs() works! Found {$penyimpananCount} linked records\n";
    } catch (\Exception $e) {
        echo "   ❌ Error in hasMany: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 3: Check reverse relationship
echo "✅ Test 3: Testing reverse relationship...\n";

$penyimpananCount = PenyimpananNg::count();
echo "   Total Penyimpanan NG records: {$penyimpananCount}\n";

if ($penyimpananCount > 0) {
    $penyimpanan = PenyimpananNg::first();
    echo "   Testing relationship on: {$penyimpanan->nomor_storage}\n";
    
    // Test belongsTo relationship
    try {
        $linkedPenerimaan = $penyimpanan->penerimaanBarang;
        if ($linkedPenerimaan) {
            echo "   ✅ PenyimpananNg->penerimaanBarang() works! Linked to: {$linkedPenerimaan->nomor_dokumen}\n";
        } else {
            echo "   ℹ️  PenyimpananNg->penerimaanBarang() works but no linked record (NULL)\n";
        }
    } catch (\Exception $e) {
        echo "   ❌ Error in belongsTo: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 4: Check fillable array
echo "✅ Test 4: Checking if penerimaan_barang_id is fillable...\n";
$model = new PenyimpananNg();
$fillable = $model->getFillable();
if (in_array('penerimaan_barang_id', $fillable)) {
    echo "   ✅ 'penerimaan_barang_id' is in fillable array\n";
} else {
    echo "   ❌ 'penerimaan_barang_id' NOT in fillable array\n";
}

echo "\n=== TESTS COMPLETED ===\n";
