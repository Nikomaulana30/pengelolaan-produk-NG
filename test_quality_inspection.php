<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QualityInspection;
use App\Models\User;

// Get or create user for testing
$user = User::first() ?? User::create([
    'name' => 'Test User',
    'email' => 'test@test.com',
    'password' => bcrypt('password'),
]);

echo "Testing Quality Inspection Data Insertion...\n";
echo "============================================\n\n";

// Test 1: Insert first record
echo "Test 1: Inserting first QC Inspection record...\n";
$qi1 = QualityInspection::create([
    'nomor_laporan' => 'QC-' . date('Ymd') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT),
    'tanggal_inspeksi' => date('Y-m-d'),
    'product' => 'Product A',
    'part_no' => 'PA-001',
    'material' => 'Steel',
    'drawing_no' => 'DRW-001',
    'drawing_rev' => 'Rev 1',
    'customer' => 'Customer X',
    'batch_no' => 'BATCH-001',
    'made_by' => 'Worker 1',
    'approved_by' => 'Supervisor 1',
    'user_id' => $user->id,
]);
echo "✓ Record 1 inserted: ID={$qi1->id}, nomor_laporan={$qi1->nomor_laporan}\n\n";

// Test 2: Insert second record (should have different nomor_laporan)
echo "Test 2: Inserting second QC Inspection record...\n";
$qi2 = QualityInspection::create([
    'nomor_laporan' => 'QC-' . date('Ymd') . '-' . str_pad(2, 4, '0', STR_PAD_LEFT),
    'tanggal_inspeksi' => date('Y-m-d'),
    'product' => 'Product B',
    'part_no' => 'PB-002',
    'material' => 'Aluminum',
    'drawing_no' => 'DRW-002',
    'drawing_rev' => 'Rev 2',
    'customer' => 'Customer Y',
    'batch_no' => 'BATCH-002',
    'made_by' => 'Worker 2',
    'approved_by' => 'Supervisor 2',
    'user_id' => $user->id,
]);
echo "✓ Record 2 inserted: ID={$qi2->id}, nomor_laporan={$qi2->nomor_laporan}\n\n";

// Verify records
$count = QualityInspection::count();
echo "Verification:\n";
echo "Total records in quality_inspections table: $count\n";

if ($count >= 2) {
    echo "\n✓ SUCCESS! Both records inserted correctly.\n";
    
    $records = QualityInspection::all();
    echo "\nAll records:\n";
    foreach ($records as $rec) {
        echo "  - {$rec->nomor_laporan}: {$rec->product} (User: {$rec->user_id})\n";
    }
} else {
    echo "\n✗ ERROR: Expected 2 records, found $count\n";
}
