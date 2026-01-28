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
        // Step 1: Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Step 2: Update inventory_stocks to point to master_products instead of master_produks
        // The product_id values should already match since we migrated data with same IDs
        
        // Drop the old foreign key
        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->dropForeign('inventory_stocks_product_id_foreign');
        });

        // Add new foreign key pointing to master_products
        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on('master_products')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        // Step 3: Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        echo "✅ inventory_stocks foreign key migrated from master_produks to master_products\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: point FK back to master_produks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('inventory_stocks', function (Blueprint $table) {
            $table->foreign('product_id')
                ->references('id')
                ->on('master_produks')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
