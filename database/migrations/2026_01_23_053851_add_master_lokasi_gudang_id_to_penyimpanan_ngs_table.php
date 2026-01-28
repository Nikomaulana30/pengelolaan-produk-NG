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
            $table->unsignedBigInteger('master_lokasi_gudang_id')->nullable()->after('id');
            
            $table->foreign('master_lokasi_gudang_id')
                ->references('id')
                ->on('master_lokasi_gudangs')
                ->onDelete('set null');
            
            $table->index('master_lokasi_gudang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyimpanan_ngs', function (Blueprint $table) {
            $table->dropForeign(['master_lokasi_gudang_id']);
            $table->dropIndex(['master_lokasi_gudang_id']);
            $table->dropColumn('master_lokasi_gudang_id');
        });
    }
};
