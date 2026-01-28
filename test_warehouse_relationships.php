<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PenyimpananNg;
use App\Models\QualityInspection;
use App\Models\StockMovement;

echo "=== TESTING WAREHOUSE RELATIONSHIPS ===\n\n";

// Test 1: Check if columns exist
echo "✅ Test 1: Checking database columns...\n";

try {
    $qcColumns = \DB::select("SHOW COLUMNS FROM quality_inspections LIKE 'penyimpanan_ng_id'");
    if (count($qcColumns) > 0) {
        echo "   ✅ quality_inspections.penyimpanan_ng_id exists\n";
    } else {
        echo "   ❌ quality_inspections.penyimpanan_ng_id NOT found\n";
    }
    
    $qcAutoCreate = \DB::select("SHOW COLUMNS FROM quality_inspections LIKE 'auto_create_storage'");
    if (count($qcAutoCreate) > 0) {
        echo "   ✅ quality_inspections.auto_create_storage exists\n";
    } else {
        echo "   ❌ quality_inspections.auto_create_storage NOT found\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error checking quality_inspections: " . $e->getMessage() . "\n";
}

try {
    $stockTable = \DB::select("SHOW TABLES LIKE 'stock_movements'");
    if (count($stockTable) > 0) {
        echo "   ✅ stock_movements table exists\n";
        
        $stockColumns = \DB::select("DESCRIBE stock_movements");
        $columnCount = count($stockColumns);
        echo "   ✅ stock_movements has {$columnCount} columns\n";
    } else {
        echo "   ❌ stock_movements table NOT found\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error checking stock_movements: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check model relationships
echo "✅ Test 2: Testing model relationships...\n";

// PenyimpananNg relationships
try {
    $model = new PenyimpananNg();
    echo "   ✅ PenyimpananNg model loaded\n";
    
    // Check if methods exist
    if (method_exists($model, 'qualityInspection')) {
        echo "   ✅ PenyimpananNg->qualityInspection() method exists\n";
    }
    if (method_exists($model, 'stockMovements')) {
        echo "   ✅ PenyimpananNg->stockMovements() method exists\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error with PenyimpananNg: " . $e->getMessage() . "\n";
}

// QualityInspection relationship
try {
    $qc = new QualityInspection();
    echo "   ✅ QualityInspection model loaded\n";
    
    if (method_exists($qc, 'penyimpananNg')) {
        echo "   ✅ QualityInspection->penyimpananNg() method exists\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error with QualityInspection: " . $e->getMessage() . "\n";
}

// StockMovement relationships
try {
    $stock = new StockMovement();
    echo "   ✅ StockMovement model loaded\n";
    
    if (method_exists($stock, 'penyimpananNg')) {
        echo "   ✅ StockMovement->penyimpananNg() method exists\n";
    }
    if (method_exists($stock, 'user')) {
        echo "   ✅ StockMovement->user() method exists\n";
    }
    if (method_exists($stock, 'fromLokasi')) {
        echo "   ✅ StockMovement->fromLokasi() method exists\n";
    }
    if (method_exists($stock, 'toLokasi')) {
        echo "   ✅ StockMovement->toLokasi() method exists\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error with StockMovement: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Check fillable arrays
echo "✅ Test 3: Checking fillable arrays...\n";

$qcFillable = (new QualityInspection())->getFillable();
if (in_array('penyimpanan_ng_id', $qcFillable)) {
    echo "   ✅ 'penyimpanan_ng_id' in QualityInspection fillable\n";
}
if (in_array('auto_create_storage', $qcFillable)) {
    echo "   ✅ 'auto_create_storage' in QualityInspection fillable\n";
}

$stockFillable = (new StockMovement())->getFillable();
$requiredFields = ['penyimpanan_ng_id', 'movement_type', 'qty_before', 'qty_moved', 'qty_after'];
$allPresent = true;
foreach ($requiredFields as $field) {
    if (!in_array($field, $stockFillable)) {
        echo "   ❌ '{$field}' NOT in StockMovement fillable\n";
        $allPresent = false;
    }
}
if ($allPresent) {
    echo "   ✅ All required fields in StockMovement fillable\n";
}

echo "\n";

// Test 4: Test actual data queries
echo "✅ Test 4: Testing data queries...\n";

$penyimpananCount = PenyimpananNg::count();
echo "   Total Penyimpanan NG: {$penyimpananCount}\n";

if ($penyimpananCount > 0) {
    try {
        $first = PenyimpananNg::first();
        
        // Test relationships
        $qc = $first->qualityInspection;
        echo "   ✅ qualityInspection relationship query works (result: " . ($qc ? 'linked' : 'null') . ")\n";
        
        $movements = $first->stockMovements;
        echo "   ✅ stockMovements relationship query works (count: {$movements->count()})\n";
    } catch (\Exception $e) {
        echo "   ❌ Error querying relationships: " . $e->getMessage() . "\n";
    }
}

$qcCount = QualityInspection::count();
echo "   Total Quality Inspections: {$qcCount}\n";

if ($qcCount > 0) {
    try {
        $firstQc = QualityInspection::first();
        $linked = $firstQc->penyimpananNg;
        echo "   ✅ QC->penyimpananNg relationship query works (result: " . ($linked ? 'linked' : 'null') . ")\n";
    } catch (\Exception $e) {
        echo "   ❌ Error querying QC relationship: " . $e->getMessage() . "\n";
    }
}

$stockCount = StockMovement::count();
echo "   Total Stock Movements: {$stockCount}\n";

echo "\n=== RELATIONSHIP ARCHITECTURE ===\n\n";

echo "PenyimpananNg (Central)\n";
echo "  ├─→ belongsTo: MasterLokasiGudang\n";
echo "  ├─→ belongsTo: PenerimaanBarang\n";
echo "  ├─→ belongsTo: MasterDisposisi\n";
echo "  ├─→ hasOne: QualityInspection (reverse) ⭐ NEW\n";
echo "  ├─→ hasMany: StockMovement ⭐ NEW\n";
echo "  └─→ hasMany: DisposisiAssignment\n\n";

echo "QualityInspection\n";
echo "  └─→ belongsTo: PenyimpananNg ⭐ NEW\n\n";

echo "StockMovement ⭐ NEW TABLE\n";
echo "  ├─→ belongsTo: PenyimpananNg\n";
echo "  ├─→ belongsTo: User\n";
echo "  ├─→ belongsTo: MasterLokasiGudang (from)\n";
echo "  └─→ belongsTo: MasterLokasiGudang (to)\n\n";

echo "=== ALL TESTS COMPLETED ===\n";
