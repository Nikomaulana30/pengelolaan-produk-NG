<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Check if fields exist
echo "Checking quality_inspections table columns:\n";
echo "===========================================\n";

if (Schema::hasTable('quality_inspections')) {
    $columns = Schema::getColumnListing('quality_inspections');
    foreach ($columns as $col) {
        echo "- $col\n";
    }
    
    echo "\n\nChecking untuk fields kritis:\n";
    echo "hasil: " . (in_array('hasil', $columns) ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
    echo "kode_defect: " . (in_array('kode_defect', $columns) ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
    echo "kode_barang: " . (in_array('kode_barang', $columns) ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
} else {
    echo "Table quality_inspections tidak ditemukan!\n";
}
