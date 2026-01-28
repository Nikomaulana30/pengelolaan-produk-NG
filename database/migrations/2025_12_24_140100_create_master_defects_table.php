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
        Schema::create('master_defects', function (Blueprint $table) {
            $table->id();
            $table->string('kode_defect')->unique()->comment('Defect code');
            $table->string('nama_defect')->comment('Defect type/name');
            $table->text('deskripsi')->nullable();
            $table->enum('criticality_level', ['minor', 'major', 'critical'])->comment('Defect severity level');
            $table->enum('sumber_masalah', ['supplier', 'proses_produksi', 'handling_gudang', 'lainnya'])->comment('Defect source');
            $table->text('solusi_standar')->nullable()->comment('Standard solution');
            $table->boolean('is_rework_possible')->default(true)->comment('Can be reworked?');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_defects');
    }
};
