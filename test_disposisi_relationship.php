<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\PenyimpananNg;
use App\Models\MasterDisposisi;
use App\Models\DisposisiAssignment;

echo "=== TEST: DISPOSISI RELATIONSHIPS WITH PENYIMPANAN NG ===\n\n";

// Test 1: Check if PenyimpananNg has disposisiAssignments relationship
echo "✓ TEST 1: PenyimpananNg → DisposisiAssignments\n";
$penyimpananNg = PenyimpananNg::first();
if ($penyimpananNg) {
    echo "  Penyimpanan NG Found: {$penyimpananNg->nomor_storage}\n";
    try {
        $assignments = $penyimpananNg->disposisiAssignments()->get();
        echo "  ✓ Relationship works! Total assignments: " . count($assignments) . "\n";
        foreach ($assignments as $assignment) {
            echo "    - Assignment ID: {$assignment->id}, Status: {$assignment->status}\n";
        }
    } catch (\Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "  No penyimpanan NG records found\n";
}

echo "\n✓ TEST 2: PenyimpananNg → MasterDisposisi (Through)\n";
if ($penyimpananNg) {
    try {
        $disposisis = $penyimpananNg->disposisis()->get();
        echo "  Total disposisi linked: " . count($disposisis) . "\n";
        foreach ($disposisis as $disp) {
            echo "    - Kode: {$disp->kode_disposisi}, Nama: {$disp->nama_disposisi}\n";
        }
    } catch (\Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n✓ TEST 3: MasterDisposisi → PenyimpananNgs (Inverse)\n";
$disposisi = MasterDisposisi::first();
if ($disposisi) {
    echo "  Disposisi Found: {$disposisi->nama_disposisi}\n";
    try {
        $penyimpananNgs = $disposisi->penyimpananNgs()->get();
        echo "  ✓ Relationship works! Total penyimpanan NG: " . count($penyimpananNgs) . "\n";
        foreach ($penyimpananNgs->take(3) as $png) {
            echo "    - Nomor Storage: {$png->nomor_storage}, Status: {$png->status_barang}\n";
        }
    } catch (\Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "  No master disposisi records found\n";
}

echo "\n✓ TEST 4: DisposisiAssignment (Junction Table)\n";
$assignment = DisposisiAssignment::first();
if ($assignment) {
    echo "  Assignment Found: ID {$assignment->id}\n";
    echo "  ✓ PenyimpananNg relation: {$assignment->penyimpananNg->nomor_storage}\n";
    echo "  ✓ Disposisi relation: {$assignment->disposisi->nama_disposisi}\n";
    echo "  ✓ Status: {$assignment->status}\n";
} else {
    echo "  No assignment records found\n";
}

echo "\n✅ RELATIONSHIP VERIFICATION COMPLETE\n";
