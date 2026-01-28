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
        Schema::table('quality_inspections', function (Blueprint $table) {
            // Add foreign key to penyimpanan_ngs table
            // When QC finds defect → auto create Penyimpanan NG → link here
            $table->foreignId('penyimpanan_ng_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('penyimpanan_ngs')
                  ->onDelete('set null');
            
            // Flag untuk auto-create storage saat defect ditemukan
            $table->boolean('auto_create_storage')
                  ->default(false)
                  ->after('penyimpanan_ng_id')
                  ->comment('Auto create Penyimpanan NG if defect found');
            
            // Add index for better query performance
            $table->index('penyimpanan_ng_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quality_inspections', function (Blueprint $table) {
            $table->dropForeign(['penyimpanan_ng_id']);
            $table->dropIndex(['penyimpanan_ng_id']);
            $table->dropColumn(['penyimpanan_ng_id', 'auto_create_storage']);
        });
    }
};
