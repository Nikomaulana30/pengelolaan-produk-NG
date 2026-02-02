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
        Schema::create('master_quality_standards', function (Blueprint $table) {
            $table->id();
            $table->string('kode_standard')->unique();
            $table->string('nama_standard');
            $table->text('deskripsi');
            $table->enum('kategori', ['dimensional', 'appearance', 'functional', 'material', 'packaging']);
            $table->text('measurement_procedure');
            $table->string('tolerance_range');
            $table->string('measurement_tool');
            $table->text('acceptance_criteria');
            $table->text('rejection_criteria');
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_quality_standards');
    }
};
