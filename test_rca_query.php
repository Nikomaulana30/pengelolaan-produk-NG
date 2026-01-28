<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Test updated query
echo "Testing updated RcaAnalysisController query with sumber_masalah:\n";
echo "================================================================\n\n";

$topDefects = DB::table('quality_inspections')
    ->join('master_defects', 'quality_inspections.kode_defect', '=', 'master_defects.kode_defect')
    ->select(
        'master_defects.kode_defect',
        'master_defects.nama_defect',
        'master_defects.criticality_level',
        'master_defects.sumber_masalah',
        DB::raw('count(*) as total')
    )
    ->where('quality_inspections.hasil', 'NG')
    ->groupBy('master_defects.kode_defect', 'master_defects.nama_defect', 'master_defects.criticality_level', 'master_defects.sumber_masalah')
    ->orderBy('total', 'desc')
    ->limit(10)
    ->get();

echo "✓ Query berhasil! Total hasil: " . count($topDefects) . "\n";
echo "\nData yang akan ditampilkan:\n";
foreach ($topDefects as $item) {
    echo "  Kode: {$item->kode_defect}\n";
    echo "  Nama: {$item->nama_defect}\n";
    echo "  Criticality: {$item->criticality_level}\n";
    echo "  Sumber Masalah: " . ($item->sumber_masalah ?? 'N/A') . "\n";
    echo "  Total: {$item->total}\n";
    echo "  ---\n";
}

echo "\n✓ Semua property tersedia dan siap digunakan di view!\n";
