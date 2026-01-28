<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migrate data dari master_produks (lama) ke master_products (baru)
     * Mapping:
     *   kode_barang → kode_produk
     *   nama_barang → nama_produk
     *   deskripsi_barang → spesifikasi
     *   kategori → kategori
     *   satuan → unit
     *   (new) vendor_id ← Assign ke vendor pertama (ID 1) atau NULL
     */
    public function up(): void
    {
        // Check apakah tabel lama master_produks ada
        if (!Schema::hasTable('master_produks')) {
            return; // Skip jika tabel lama tidak ada
        }

        // Get all data dari master_produks lama
        $oldProducts = DB::table('master_produks')
            ->select('id', 'kode_barang', 'nama_barang', 'deskripsi', 'kategori_barang', 'satuan', 'harga_satuan', 'is_active', 'created_at', 'updated_at')
            ->get();

        // Migrate setiap record ke master_products baru
        foreach ($oldProducts as $oldProduct) {
            // Check apakah sudah ada di tabel baru
            $exists = DB::table('master_products')
                ->where('kode_produk', $oldProduct->kode_barang)
                ->exists();

            if (!$exists) {
                // Get default vendor atau NULL
                $defaultVendor = DB::table('master_vendors')
                    ->where('is_active', true)
                    ->orderBy('id')
                    ->first();

                $vendorId = $defaultVendor ? $defaultVendor->id : null;

                // Insert ke master_products baru
                DB::table('master_products')->insert([
                    'kode_produk' => $oldProduct->kode_barang,
                    'nama_produk' => $oldProduct->nama_barang,
                    'kategori' => $oldProduct->kategori_barang,
                    'unit' => $oldProduct->satuan,
                    'harga' => $oldProduct->harga_satuan,
                    'vendor_id' => $vendorId, // Assign ke vendor pertama atau NULL
                    'spesifikasi' => $oldProduct->deskripsi,
                    'drawing_file' => null,
                    'is_active' => $oldProduct->is_active ?? true,
                    'created_at' => $oldProduct->created_at ?? now(),
                    'updated_at' => $oldProduct->updated_at ?? now(),
                ]);
            }
        }

        echo "\n✅ Data migration complete! " . count($oldProducts) . " records migrated.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete records yang dimigrate dari master_produks
        if (Schema::hasTable('master_produks')) {
            $oldKodeProduk = DB::table('master_produks')
                ->pluck('kode_barang')
                ->toArray();

            if (!empty($oldKodeProduk)) {
                DB::table('master_products')
                    ->whereIn('kode_produk', $oldKodeProduk)
                    ->delete();
            }
        }
    }
};

