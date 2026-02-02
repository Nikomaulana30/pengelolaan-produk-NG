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
        Schema::create('master_defect_types', function (Blueprint $table) {
            $table->id();
            $table->string('kode_defect')->unique();
            $table->string('nama_defect');
            $table->text('deskripsi');
            $table->enum('kategori', ['appearance', 'dimension', 'function', 'material', 'other']);
            $table->enum('severity', ['critical', 'major', 'minor']);
            $table->string('measurement_method')->nullable();
            $table->text('standard_action')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_defect_types');
    }
};
