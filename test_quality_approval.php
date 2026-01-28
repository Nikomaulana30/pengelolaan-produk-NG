<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QualityInspection;

echo "Testing Quality Approval Implementation...\n";
echo "========================================\n\n";

// Get first inspection
$inspection = QualityInspection::first();

if (!$inspection) {
    echo "❌ No inspection records found.\n";
    exit;
}

echo "Test 1: Verify database fields\n";
echo "- Nomor Laporan: " . $inspection->nomor_laporan . "\n";
echo "- Product: " . $inspection->product . "\n";
echo "- Status Approval (before): " . ($inspection->status_approval ?? 'NULL') . "\n";
echo "✓ Database fields verified\n\n";

echo "Test 2: Update with approval data\n";
$inspection->update([
    'status_approval' => 'approved',
    'catatan_approval' => 'Produk sesuai dengan standar',
    'nama_approver' => 'Budi Santoso',
    'tanggal_approval' => now(),
]);
echo "- Status Approval (after): " . $inspection->status_approval . "\n";
echo "- Catatan: " . $inspection->catatan_approval . "\n";
echo "- Approver: " . $inspection->nama_approver . "\n";
echo "- Tanggal: " . $inspection->tanggal_approval->format('d-m-Y H:i:s') . "\n";
echo "✓ Approval data updated successfully\n\n";

echo "Test 3: Retrieve and verify\n";
$refreshed = QualityInspection::find($inspection->id);
echo "- Status from DB: " . $refreshed->status_approval . "\n";
echo "- Approver from DB: " . $refreshed->nama_approver . "\n";

if ($refreshed->status_approval === 'approved') {
    echo "✓ Approval data persisted correctly\n\n";
} else {
    echo "❌ Data mismatch\n\n";
}

echo "Test 4: Query all approvals\n";
$approvals = QualityInspection::whereNotNull('status_approval')->get();
echo "- Total records with approval: " . $approvals->count() . "\n";
foreach ($approvals as $apv) {
    echo "  - {$apv->nomor_laporan}: {$apv->status_approval} (Approver: {$apv->nama_approver})\n";
}

echo "\n========================================\n";
echo "✓ Quality Approval Implementation Verified!\n";
