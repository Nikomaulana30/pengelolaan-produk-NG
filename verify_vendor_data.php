<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterVendor;

echo "\n╔══════════════════════════════════════════════════════════════════╗\n";
echo "║            VERIFIKASI DATA MASTER VENDOR                         ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

$vendors = MasterVendor::orderBy('kode_vendor')->get();

echo "Total Vendor: " . $vendors->count() . "\n\n";
echo "┌──────────┬────────────────────────────────────┬──────────────┬──────────┐\n";
echo "│   Kode   │           Nama Vendor              │     Kota     │  Status  │\n";
echo "├──────────┼────────────────────────────────────┼──────────────┼──────────┤\n";

foreach ($vendors as $vendor) {
    $kode = str_pad($vendor->kode_vendor, 8);
    $nama = str_pad(substr($vendor->nama_vendor, 0, 34), 34);
    $kota = str_pad($vendor->kota, 12);
    $status = $vendor->is_active ? '✅ Aktif ' : '❌ Nonaktif';
    
    echo "│ {$kode} │ {$nama} │ {$kota} │ {$status} │\n";
}

echo "└──────────┴────────────────────────────────────┴──────────────┴──────────┘\n\n";

// Statistik
$aktif = $vendors->where('is_active', true)->count();
$nonaktif = $vendors->where('is_active', false)->count();

echo "📊 STATISTIK:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Vendor Aktif      : {$aktif} (" . round($aktif/$vendors->count()*100, 1) . "%)\n";
echo "❌ Vendor Non-Aktif  : {$nonaktif} (" . round($nonaktif/$vendors->count()*100, 1) . "%)\n\n";

// Group by kota
echo "📍 DISTRIBUSI PER KOTA:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$cities = $vendors->groupBy('kota');
foreach ($cities as $kota => $vlist) {
    echo "   {$kota}: " . $vlist->count() . " vendor\n";
}

echo "\n✅ Data Master Vendor berhasil diisi!\n";
echo "Sekarang halaman Master Vendor tidak kosong lagi.\n\n";
