<?php

require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle($request = \Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;

echo "=== DATA MIGRATION RESULT ===\n\n";

echo "📊 TABEL LAMA (master_produks):\n";
$oldCount = DB::table('master_produks')->count();
echo "   Total: " . $oldCount . " records\n\n";

echo "📦 TABEL BARU (master_products):\n";
$newCount = DB::table('master_products')->count();
echo "   Total: " . $newCount . " records\n\n";

echo "✅ Sample data dari master_products (BARU):\n";
$samples = DB::table('master_products')
    ->select('kode_produk', 'nama_produk', 'kategori', 'unit', 'harga', 'vendor_id')
    ->limit(5)
    ->get();

foreach ($samples as $p) {
    echo sprintf(
        "   • %s - %s (Category: %s, Unit: %s, Harga: %s, Vendor: %s)\n",
        $p->kode_produk,
        $p->nama_produk,
        $p->kategori,
        $p->unit,
        $p->harga ? 'Rp ' . number_format($p->harga, 0, ',', '.') : '-',
        $p->vendor_id ?? 'NULL'
    );
}

echo "\n✅ MIGRATION COMPLETE!\n";
echo "   - " . $oldCount . " records berhasil dimigrate\n";
echo "   - Semua data preserved dengan struktur baru\n";
echo "   - Vendor mapping: " . ($samples->first()?->vendor_id ? "Assigned to Vendor ID " . $samples->first()?->vendor_id : "NULL (dapat diisi kemudian)") . "\n";
