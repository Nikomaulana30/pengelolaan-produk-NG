<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\MasterProduk;
use App\Models\MasterDefect;
use App\Models\MasterVendor;

echo "========================================\n";
echo "DEMO PENGGUNAAN RELATIONSHIP BARU\n";
echo "========================================\n\n";

// Demo 1: Tracking Produk dengan semua issue-nya
echo "📦 DEMO 1: Track Produk dengan Semua Issues\n";
echo "-------------------------------------------\n";
$produk = MasterProduk::with(['returBarangs', 'rcaAnalyses', 'scrapDisposals', 'inspeksi'])->first();

if ($produk) {
    echo "Produk: {$produk->nama_produk} ({$produk->kode_produk})\n";
    echo "├─ Quality Inspections: " . $produk->inspeksi->count() . " records\n";
    echo "├─ Retur Barang: " . $produk->returBarangs->count() . " records\n";
    echo "├─ RCA Analyses: " . $produk->rcaAnalyses->count() . " records\n";
    echo "└─ Scrap Disposals: " . $produk->scrapDisposals->count() . " records\n";
    
    echo "\n💡 Usage Example:\n";
    echo "   \$produk->returBarangs  // Get all returns for this product\n";
    echo "   \$produk->rcaAnalyses   // Get all RCA for this product\n";
    echo "   \$produk->scrapDisposals // Get all scraps for this product\n";
}
echo "\n";

// Demo 2: Defect Tracking
echo "⚠️  DEMO 2: Track Defect Occurrence\n";
echo "-------------------------------------------\n";
$defect = MasterDefect::with(['qualityInspections', 'rcaAnalyses'])->first();

if ($defect) {
    echo "Defect: {$defect->nama_defect} ({$defect->kode_defect})\n";
    echo "├─ Found in QC Inspections: " . $defect->qualityInspections->count() . " times\n";
    echo "└─ RCA Analyses Created: " . $defect->rcaAnalyses->count() . " analyses\n";
    
    echo "\n💡 Usage Example:\n";
    echo "   \$defect->qualityInspections  // All QC records with this defect\n";
    echo "   \$defect->rcaAnalyses         // All RCA for this defect\n";
}
echo "\n";

// Demo 3: Vendor Quality Tracking
echo "🏭 DEMO 3: Vendor Quality Performance\n";
echo "-------------------------------------------\n";
$vendor = MasterVendor::with(['produks', 'returBarangs', 'qualityInspections'])->first();

if ($vendor) {
    echo "Vendor: {$vendor->nama_vendor}\n";
    echo "├─ Total Products Supplied: " . $vendor->produks->count() . " products\n";
    echo "├─ Total Returns: " . $vendor->returBarangs->count() . " returns\n";
    echo "└─ QC Inspections (via products): " . $vendor->qualityInspections->count() . " inspections\n";
    
    echo "\n💡 Usage Example:\n";
    echo "   \$vendor->qualityInspections  // All QC issues from vendor's products\n";
    echo "   // Calculate vendor quality score based on QC & returns\n";
}
echo "\n";

// Demo 4: Complete Flow Example
echo "🔄 DEMO 4: Complete Process Flow\n";
echo "-------------------------------------------\n";
echo "Scenario: Track barang dari penerimaan sampai scrap\n\n";

echo "Code Example:\n";
echo "```php\n";
echo "// 1. Penerimaan Barang masuk\n";
echo "\$penerimaan = PenerimaanBarang::find(1);\n\n";

echo "// 2. Ada defect → Create NG Storage\n";
echo "\$ngStorage = \$penerimaan->penyimpananNgs()->first();\n\n";

echo "// 3. QC Inspect → Create QC Record\n";
echo "\$qcRecord = \$ngStorage->qualityInspection;\n\n";

echo "// 4. Assign disposisi\n";
echo "\$assignment = \$ngStorage->disposisiAssignments()->first();\n\n";

echo "// 5. Execute disposisi → Scrap\n";
echo "\$scrap = \$assignment->scrapDisposals()->first();\n\n";

echo "// 6. Track scrap back to source\n";
echo "\$originalStorage = \$scrap->penyimpananNg;\n";
echo "\$originalPenerimaan = \$originalStorage->penerimaanBarang;\n";
echo "```\n\n";

echo "✅ COMPLETE TRACEABILITY ACHIEVED!\n";

echo "\n========================================\n";
echo "RELATIONSHIP BENEFITS\n";
echo "========================================\n";
echo "✓ Bi-directional Navigation\n";
echo "✓ Easy Data Aggregation\n";
echo "✓ Efficient Eager Loading\n";
echo "✓ Clean Business Logic\n";
echo "✓ Better Reporting Capability\n";
echo "✓ Full Audit Trail\n";
echo "\n";
