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
        // Add to master_disposisis
        Schema::table('master_disposisis', function (Blueprint $table) {
            $table->unsignedBigInteger('master_lokasi_gudang_tujuan_id')->nullable()->after('id');
            
            $table->foreign('master_lokasi_gudang_tujuan_id')
                ->references('id')
                ->on('master_lokasi_gudangs')
                ->onDelete('set null');
            
            $table->index('master_lokasi_gudang_tujuan_id');
        });

        // Add to disposisi_assignments
        Schema::table('disposisi_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('master_lokasi_gudang_tujuan_id')->nullable()->after('master_disposisi_id');
            
            $table->foreign('master_lokasi_gudang_tujuan_id')
                ->references('id')
                ->on('master_lokasi_gudangs')
                ->onDelete('set null');
            
            $table->index('master_lokasi_gudang_tujuan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_disposisis', function (Blueprint $table) {
            $table->dropForeign(['master_lokasi_gudang_tujuan_id']);
            $table->dropIndex(['master_lokasi_gudang_tujuan_id']);
            $table->dropColumn('master_lokasi_gudang_tujuan_id');
        });

        Schema::table('disposisi_assignments', function (Blueprint $table) {
            $table->dropForeign(['master_lokasi_gudang_tujuan_id']);
            $table->dropIndex(['master_lokasi_gudang_tujuan_id']);
            $table->dropColumn('master_lokasi_gudang_tujuan_id');
        });
    }
};
