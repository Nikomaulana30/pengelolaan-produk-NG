<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Insert test data dengan hasil dan defects
echo "Populating test data untuk RCA Analysis...\n";
echo "=========================================\n\n";

// First, get sample kode_defect dan kode_barang
$defects = DB::table('master_defects')->limit(3)->get();
$produks = DB::table('master_produks')->limit(3)->get();

if ($defects->count() > 0 && $produks->count() > 0) {
    $defectArray = $defects->pluck('kode_defect')->toArray();
    $produkArray = $produks->pluck('kode_barang')->toArray();
    
    // Update existing records
    $inspections = DB::table('quality_inspections')->get();
    
    foreach ($inspections as $inspection) {
        $idx = 0;
        DB::table('quality_inspections')
            ->where('id', $inspection->id)
            ->update([
                'hasil' => 'NG',
                'kode_defect' => $defectArray[$idx % count($defectArray)],
                'kode_barang' => $produkArray[$idx % count($produkArray)],
            ]);
        $idx++;
    }
    
    echo "✓ Updated " . $inspections->count() . " records dengan hasil='NG'\n";
} else {
    echo "✗ Tidak ada master_defects atau master_produks\n";
    echo "  master_defects count: " . DB::table('master_defects')->count() . "\n";
    echo "  master_produks count: " . DB::table('master_produks')->count() . "\n";
}

// Test query lagi
echo "\n\nRe-testing query setelah populate data:\n";
echo "========================================\n\n";

$topDefects = DB::table('quality_inspections')
    ->join('master_defects', 'quality_inspections.kode_defect', '=', 'master_defects.kode_defect')
    ->select(
        'master_defects.kode_defect',
        'master_defects.nama_defect',
        'master_defects.criticality_level',
        DB::raw('count(*) as total')
    )
    ->where('quality_inspections.hasil', 'NG')
    ->groupBy('master_defects.kode_defect', 'master_defects.nama_defect', 'master_defects.criticality_level')
    ->orderBy('total', 'desc')
    ->limit(10)
    ->get();

echo "Top Defects:\n";
foreach ($topDefects as $item) {
    echo "  - {$item->nama_defect} ({$item->kode_defect}): {$item->total} kasus [Criticality: {$item->criticality_level}]\n";
}

echo "\n";
$topProduk = DB::table('quality_inspections')
    ->join('master_produks', 'quality_inspections.kode_barang', '=', 'master_produks.kode_barang')
    ->select(
        'master_produks.kode_barang',
        'master_produks.nama_barang',
        DB::raw('count(*) as total')
    )
    ->where('quality_inspections.hasil', 'NG')
    ->groupBy('master_produks.kode_barang', 'master_produks.nama_barang')
    ->orderBy('total', 'desc')
    ->limit(5)
    ->get();

echo "Top Products:\n";
foreach ($topProduk as $item) {
    echo "  - {$item->nama_barang} ({$item->kode_barang}): {$item->total} kasus\n";
}
