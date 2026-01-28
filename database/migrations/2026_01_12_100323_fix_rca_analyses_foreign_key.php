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
        // Disable FK checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop old FK that points to deleted master_produks table
        Schema::table('rca_analyses', function (Blueprint $table) {
            try {
                $table->dropForeign('rca_analyses_kode_barang_foreign');
            } catch (\Exception $e) {
                // FK might not exist
                \Log::warning('FK rca_analyses_kode_barang_foreign not found: ' . $e->getMessage());
            }
        });

        // Add new FK pointing to master_products with correct field
        Schema::table('rca_analyses', function (Blueprint $table) {
            $table->foreign('kode_barang')
                ->references('kode_produk')
                ->on('master_products')
                ->onDelete('set null')
                ->change();
        });

        // Re-enable FK checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        echo "✅ RCA analyses foreign key fixed!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::table('rca_analyses', function (Blueprint $table) {
            try {
                $table->dropForeign(['kode_barang']);
            } catch (\Exception $e) {
                // FK might not exist
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
