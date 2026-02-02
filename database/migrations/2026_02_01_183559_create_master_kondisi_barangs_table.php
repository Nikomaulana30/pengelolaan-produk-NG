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
        Schema::create('master_kondisi_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kondisi')->unique();
            $table->string('nama_kondisi');
            $table->text('deskripsi');
            $table->string('color_code');
            $table->boolean('is_ng')->default(false); // apakah kondisi NG
            $table->boolean('can_rework')->default(false); // bisa dirework
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kondisi_barangs');
    }
};
