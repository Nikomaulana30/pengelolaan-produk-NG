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
            // Add missing fields for defect tracking and RCA analysis
            $table->string('hasil')->nullable()->after('batch_no')->comment('OK/NG');
            $table->string('kode_defect')->nullable()->after('hasil');
            $table->string('kode_barang')->nullable()->after('kode_defect');

            // Add foreign key constraints
            $table->foreign('kode_defect')->references('kode_defect')->on('master_defects')->onDelete('set null');
            $table->foreign('kode_barang')->references('kode_barang')->on('master_produks')->onDelete('set null');

            // Add indexes
            $table->index('hasil');
            $table->index('kode_defect');
            $table->index('kode_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quality_inspections', function (Blueprint $table) {
            $table->dropForeign(['kode_defect']);
            $table->dropForeign(['kode_barang']);
            $table->dropIndex(['hasil']);
            $table->dropIndex(['kode_defect']);
            $table->dropIndex(['kode_barang']);
            $table->dropColumn(['hasil', 'kode_defect', 'kode_barang']);
        });
    }
};
