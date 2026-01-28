<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QualityInspection;
use App\Models\User;

echo "Testing Quality Inspection Edit/Delete Workflow...\n";
echo "=================================================\n\n";

// Get existing record
$inspection = QualityInspection::first();

if (!$inspection) {
    echo "❌ No inspection records found. Please create one first.\n";
    exit;
}

echo "Test 1: Get existing record\n";
echo "- Nomor Laporan: " . $inspection->nomor_laporan . "\n";
echo "- Product: " . $inspection->product . "\n";
echo "- Material: " . $inspection->material . "\n";
echo "✓ Record retrieved successfully\n\n";

echo "Test 2: Update record\n";
$old_material = $inspection->material;
$inspection->update([
    'material' => 'Updated Material - ' . date('YmdHis'),
    'customer' => 'Updated Customer - ' . date('YmdHis'),
]);
echo "- Old Material: $old_material\n";
echo "- New Material: " . $inspection->material . "\n";
echo "- Updated At: " . $inspection->updated_at->format('d-m-Y H:i:s') . "\n";
echo "✓ Record updated successfully\n\n";

echo "Test 3: Verify update in database\n";
$refreshed = QualityInspection::find($inspection->id);
echo "- Material (from DB): " . $refreshed->material . "\n";
echo "- Updated At (from DB): " . $refreshed->updated_at->format('d-m-Y H:i:s') . "\n";
if ($refreshed->material === $inspection->material) {
    echo "✓ Update verified in database\n\n";
} else {
    echo "❌ Update verification failed\n\n";
}

echo "Test 4: Check all records before delete\n";
$count_before = QualityInspection::count();
echo "- Total records: $count_before\n";
$records = QualityInspection::all();
foreach ($records as $rec) {
    echo "  - {$rec->nomor_laporan}: {$rec->product}\n";
}
echo "\n";

echo "Test 5: Delete record\n";
$nomor_to_delete = $inspection->nomor_laporan;
$id_to_delete = $inspection->id;
$inspection->delete();
echo "- Deleted Nomor: $nomor_to_delete\n";
echo "- Deleted ID: $id_to_delete\n";
echo "✓ Record deleted successfully\n\n";

echo "Test 6: Check all records after delete\n";
$count_after = QualityInspection::count();
echo "- Total records before: $count_before\n";
echo "- Total records after: $count_after\n";
echo "- Records deleted: " . ($count_before - $count_after) . "\n";
$records = QualityInspection::all();
foreach ($records as $rec) {
    echo "  - {$rec->nomor_laporan}: {$rec->product}\n";
}

if ($count_after === $count_before - 1) {
    echo "\n✓ SUCCESS! Delete workflow verified.\n";
} else {
    echo "\n❌ ERROR: Delete count mismatch\n";
}

echo "\n=================================================\n";
echo "All tests completed!\n";
