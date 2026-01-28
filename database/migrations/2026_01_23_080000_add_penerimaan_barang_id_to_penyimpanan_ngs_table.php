<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penyimpanan_ngs', function (Blueprint $table) {
            // Add foreign key to penerimaan_barangs table
            $table->foreignId('penerimaan_barang_id')
                  ->nullable()
                  ->after('nomor_referensi')
                  ->constrained('penerimaan_barangs')
                  ->onDelete('set null');
            
            // Add index for better query performance
            $table->index('penerimaan_barang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyimpanan_ngs', function (Blueprint $table) {
            $table->dropForeign(['penerimaan_barang_id']);
            $table->dropIndex(['penerimaan_barang_id']);
            $table->dropColumn('penerimaan_barang_id');
        });
    }
};
