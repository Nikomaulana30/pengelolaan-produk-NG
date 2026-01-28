<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable FK checks to allow dropping table with multiple FK references
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop all foreign keys referencing master_produks from other tables
        // Tables affected: inventory_stocks, retur_barangs, rca_analyses, quality_inspections
        
        try {
            Schema::table('inventory_stocks', function (Blueprint $table) {
                $table->dropForeignKey('inventory_stocks_product_id_foreign');
            });
        } catch (\Exception $e) {
            // Already dropped or different name
        }

        try {
            Schema::table('retur_barangs', function (Blueprint $table) {
                $table->dropForeignKey('retur_barangs_produk_id_foreign');
            });
        } catch (\Exception $e) {
            // Already dropped or different name
        }

        try {
            Schema::table('rca_analyses', function (Blueprint $table) {
                $table->dropForeignKey('rca_analyses_kode_barang_foreign');
            });
        } catch (\Exception $e) {
            // Already dropped or different name
        }

        try {
            Schema::table('quality_inspections', function (Blueprint $table) {
                $table->dropForeignKey('quality_inspections_kode_barang_foreign');
            });
        } catch (\Exception $e) {
            // Already dropped or different name
        }

        // Now safe to drop the old table
        Schema::dropIfExists('master_produks');

        // Re-enable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        echo "✅ Old master_produks table dropped successfully.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Old table structure is no longer needed - data migrated to master_products
        // If reverting is necessary, run: php artisan migrate:rollback
    }
};
