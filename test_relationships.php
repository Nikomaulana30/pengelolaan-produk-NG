<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\MasterProduk;
use App\Models\MasterDefect;
use App\Models\MasterVendor;
use App\Models\PenyimpananNg;
use App\Models\ScrapDisposal;
use App\Models\RcaAnalysis;
use App\Models\DisposisiAssignment;

echo "========================================\n";
echo "TESTING RELATIONSHIP YANG DITAMBAHKAN\n";
echo "========================================\n\n";

// Test 1: MasterProduk -> ReturBarang, RcaAnalysis, ScrapDisposal
echo "1. Testing MasterProduk Relationships:\n";
$produk = MasterProduk::first();
if ($produk) {
    echo "   ✓ Produk: {$produk->nama_produk}\n";
    echo "   ✓ returBarangs() method exists: " . (method_exists($produk, 'returBarangs') ? 'YES' : 'NO') . "\n";
    echo "   ✓ rcaAnalyses() method exists: " . (method_exists($produk, 'rcaAnalyses') ? 'YES' : 'NO') . "\n";
    echo "   ✓ scrapDisposals() method exists: " . (method_exists($produk, 'scrapDisposals') ? 'YES' : 'NO') . "\n";
    
    try {
        $returCount = $produk->returBarangs()->count();
        $rcaCount = $produk->rcaAnalyses()->count();
        $scrapCount = $produk->scrapDisposals()->count();
        echo "   ✓ Retur Barang count: {$returCount}\n";
        echo "   ✓ RCA Analysis count: {$rcaCount}\n";
        echo "   ✓ Scrap Disposal count: {$scrapCount}\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Test 2: MasterDefect -> QualityInspection, RcaAnalysis
echo "2. Testing MasterDefect Relationships:\n";
$defect = MasterDefect::first();
if ($defect) {
    echo "   ✓ Defect: {$defect->nama_defect}\n";
    echo "   ✓ qualityInspections() method exists: " . (method_exists($defect, 'qualityInspections') ? 'YES' : 'NO') . "\n";
    echo "   ✓ rcaAnalyses() method exists: " . (method_exists($defect, 'rcaAnalyses') ? 'YES' : 'NO') . "\n";
    
    try {
        $qcCount = $defect->qualityInspections()->count();
        $rcaCount = $defect->rcaAnalyses()->count();
        echo "   ✓ Quality Inspection count: {$qcCount}\n";
        echo "   ✓ RCA Analysis count: {$rcaCount}\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Test 3: ScrapDisposal -> PenyimpananNg, DisposisiAssignment
echo "3. Testing ScrapDisposal Relationships:\n";
$scrap = ScrapDisposal::first();
if ($scrap) {
    echo "   ✓ Scrap: {$scrap->nomor_scrap}\n";
    echo "   ✓ penyimpananNg() method exists: " . (method_exists($scrap, 'penyimpananNg') ? 'YES' : 'NO') . "\n";
    echo "   ✓ disposisiAssignment() method exists: " . (method_exists($scrap, 'disposisiAssignment') ? 'YES' : 'NO') . "\n";
    
    try {
        $ngStorage = $scrap->penyimpananNg;
        $assignment = $scrap->disposisiAssignment;
        echo "   ✓ Linked to Penyimpanan NG: " . ($ngStorage ? 'YES' : 'NO') . "\n";
        echo "   ✓ Linked to Disposisi Assignment: " . ($assignment ? 'YES' : 'NO') . "\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Test 4: RcaAnalysis -> FinanceApproval
echo "4. Testing RcaAnalysis Relationships:\n";
$rca = RcaAnalysis::first();
if ($rca) {
    echo "   ✓ RCA: {$rca->nomor_rca}\n";
    echo "   ✓ financeApprovals() method exists: " . (method_exists($rca, 'financeApprovals') ? 'YES' : 'NO') . "\n";
    
    try {
        $financeCount = $rca->financeApprovals()->count();
        echo "   ✓ Finance Approval count: {$financeCount}\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Test 5: MasterVendor -> QualityInspection (hasManyThrough)
echo "5. Testing MasterVendor Relationships:\n";
$vendor = MasterVendor::first();
if ($vendor) {
    echo "   ✓ Vendor: {$vendor->nama_vendor}\n";
    echo "   ✓ qualityInspections() method exists: " . (method_exists($vendor, 'qualityInspections') ? 'YES' : 'NO') . "\n";
    
    try {
        $qcCount = $vendor->qualityInspections()->count();
        echo "   ✓ Quality Inspection (through products) count: {$qcCount}\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Test 6: PenyimpananNg -> ScrapDisposal
echo "6. Testing PenyimpananNg Relationships:\n";
$ngStorage = PenyimpananNg::first();
if ($ngStorage) {
    echo "   ✓ NG Storage: {$ngStorage->nomor_storage}\n";
    echo "   ✓ scrapDisposals() method exists: " . (method_exists($ngStorage, 'scrapDisposals') ? 'YES' : 'NO') . "\n";
    
    try {
        $scrapCount = $ngStorage->scrapDisposals()->count();
        echo "   ✓ Scrap Disposal count: {$scrapCount}\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// Test 7: DisposisiAssignment -> ScrapDisposal
echo "7. Testing DisposisiAssignment Relationships:\n";
$assignment = DisposisiAssignment::first();
if ($assignment) {
    echo "   ✓ Assignment ID: {$assignment->id}\n";
    echo "   ✓ scrapDisposals() method exists: " . (method_exists($assignment, 'scrapDisposals') ? 'YES' : 'NO') . "\n";
    
    try {
        $scrapCount = $assignment->scrapDisposals()->count();
        echo "   ✓ Scrap Disposal count: {$scrapCount}\n";
    } catch (\Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
}
echo "\n";

echo "========================================\n";
echo "TESTING SELESAI ✅\n";
echo "========================================\n";
