<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\ReturBarang;
use App\Models\MasterProduk;

echo "=== DEBUG: COLUMN NAMES & RELATIONSHIPS ===\n\n";

// Check master_products columns
$produk = MasterProduk::first();
if ($produk) {
    echo "MasterProduk columns that exist:\n";
    echo "- nama_produk: " . (isset($produk->nama_produk) ? 'YES' : 'NO') . "\n";
    echo "- nama_barang: " . (isset($produk->nama_barang) ? 'YES' : 'NO') . "\n";
    echo "Attributes: " . implode(", ", array_keys($produk->getAttributes())) . "\n\n";
}

echo "=== DEBUG: RETUR BARANG LIST WITH EAGER LOADING ===\n\n";

$returBarangList = ReturBarang::with(['vendor', 'produk'])
    ->whereIn('status_approval', ['approved', 'pending'])
    ->orderBy('tanggal_retur', 'desc')
    ->get();

echo "Total Retur: " . count($returBarangList) . "\n\n";

foreach ($returBarangList as $index => $retur) {
    echo "[$index] No Retur: {$retur->no_retur}\n";
    echo "    ID: {$retur->id}\n";
    echo "    vendor_id: {$retur->vendor_id}\n";
    echo "    produk_id: {$retur->produk_id}\n";
    echo "    Status: {$retur->status_approval}\n";
    
    // Check if vendor is loaded
    if ($retur->vendor) {
        echo "    ✓ Vendor LOADED: {$retur->vendor->nama_vendor}\n";
    } else {
        echo "    ✗ Vendor NULL or NOT LOADED\n";
    }
    
    // Check if produk is loaded
    if ($retur->produk) {
        echo "    ✓ Produk LOADED: " . ($retur->produk->nama_produk ?? $retur->produk->nama_barang ?? 'UNKNOWN') . "\n";
    } else {
        echo "    ✗ Produk NULL or NOT LOADED\n";
    }
    
    // Show what blade will render
    $vendor_text = $retur->vendor?->nama_vendor ?? 'Vendor Tidak Ditemukan';
    $produk_text = $retur->produk?->nama_produk ?? ($retur->produk?->nama_barang ?? 'Produk Tidak Ditemukan');
    echo "    Blade Output: {$retur->no_retur} - {$vendor_text} ({$produk_text})\n";
    echo "\n";
}
