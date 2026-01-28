<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QualityInspection;

echo "Testing DateTime Casting...\n";
echo "============================\n\n";

$inspection = QualityInspection::first();

if (!$inspection) {
    echo "❌ No records found.\n";
    exit;
}

echo "Test 1: Check tanggal_approval type and format\n";
echo "- Nomor: " . $inspection->nomor_laporan . "\n";
echo "- Tanggal Approval: " . $inspection->tanggal_approval . "\n";
echo "- Type: " . gettype($inspection->tanggal_approval) . "\n";

if ($inspection->tanggal_approval) {
    echo "- Class: " . get_class($inspection->tanggal_approval) . "\n";
    echo "- Formatted: " . $inspection->tanggal_approval->format('d-m-Y H:i:s') . "\n";
    echo "✓ DateTime casting working correctly\n";
} else {
    echo "- Value is NULL\n";
}

echo "\n✓ Test completed!\n";
