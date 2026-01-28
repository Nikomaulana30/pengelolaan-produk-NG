<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING MASTER LOKASI GUDANG RELATIONSHIPS ===" . PHP_EOL . PHP_EOL;

$lokasi = App\Models\MasterLokasiGudang::first();

if ($lokasi) {
    echo "Lokasi: {$lokasi->nama_lokasi} ({$lokasi->kode_lokasi})" . PHP_EOL;
    echo "Lokasi Lengkap: {$lokasi->lokasi_lengkap}" . PHP_EOL;
    echo PHP_EOL;
    
    // Test Penyimpanan NG relationship
    $penyimpananCount = $lokasi->penyimpananNgs()->count();
    echo "📦 Penyimpanan NG: {$penyimpananCount} items" . PHP_EOL;
    
    if ($penyimpananCount > 0) {
        $lokasi->penyimpananNgs->take(3)->each(function($p) {
            echo "  - {$p->nomor_storage}: {$p->nama_barang}" . PHP_EOL;
        });
    }
    
    echo PHP_EOL;
    
    // Test Penerimaan Barang relationship
    $penerimaanCount = $lokasi->penerimaanBarangs()->count();
    echo "📥 Penerimaan Barang: {$penerimaanCount} records" . PHP_EOL;
    
    if ($penerimaanCount > 0) {
        $lokasi->penerimaanBarangs->take(3)->each(function($p) {
            echo "  - {$p->nomor_dokumen}: {$p->nama_barang} (Qty Baik: {$p->qty_baik}, Rusak: {$p->qty_rusak})" . PHP_EOL;
        });
    } else {
        echo "  (No penerimaan records linked to this location)" . PHP_EOL;
    }
    
    echo PHP_EOL;
    echo "✅ Relationships are working!" . PHP_EOL;
    
} else {
    echo "❌ No Master Lokasi Gudang found in database" . PHP_EOL;
}

echo PHP_EOL;
echo "=== TEST COMPLETE ===" . PHP_EOL;
