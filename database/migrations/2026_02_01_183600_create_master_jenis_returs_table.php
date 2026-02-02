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
        Schema::create('master_jenis_returs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis_retur')->unique();
            $table->string('nama_jenis_retur');
            $table->text('deskripsi');
            $table->boolean('require_replacement')->default(false);
            $table->boolean('require_refund')->default(false);
            $table->boolean('require_rework')->default(false);
            $table->text('instruction_template')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_jenis_returs');
    }
};
