<?php

// Test script for Master Produk and Vendor integration

require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle($request = \Illuminate\Http\Request::capture());

// Start testing
echo "=== Master Produk & Vendor Integration Test ===\n\n";

// Test 1: Check database tables
echo "Database Tables:\n";
$tables = \Illuminate\Support\Facades\DB::connection()->getDoctrineSchemaManager()->listTableNames();
$produk_tables = array_filter($tables, fn($t) => str_contains($t, 'produk'));
$vendor_tables = array_filter($tables, fn($t) => str_contains($t, 'vendor'));
echo "  master_products: " . (in_array('master_products', $tables) ? '✓' : '✗') . "\n";
echo "  master_vendors: " . (in_array('master_vendors', $tables) ? '✓' : '✗') . "\n";

// Test 2: Check models
echo "\nModel Methods:\n";
echo "  MasterProduk->vendor(): " . (method_exists(\App\Models\MasterProduk::class, 'vendor') ? '✓' : '✗') . "\n";
echo "  MasterVendor->produks(): " . (method_exists(\App\Models\MasterVendor::class, 'produks') ? '✓' : '✗') . "\n";

// Test 3: Check controller methods
echo "\nMasterProdukController Methods:\n";
$methods = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
foreach ($methods as $method) {
    echo "  " . $method . "(): " . (method_exists(\App\Http\Controllers\MasterProdukController::class, $method) ? '✓' : '✗') . "\n";
}

// Test 4: Record counts
echo "\nData Summary:\n";
echo "  Total Vendors: " . \App\Models\MasterVendor::count() . "\n";
echo "  Total Produks: " . \App\Models\MasterProduk::count() . "\n";

echo "\n=== All Checks Complete ===\n";
