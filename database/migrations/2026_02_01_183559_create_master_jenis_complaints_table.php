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
        Schema::create('master_jenis_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis')->unique();
            $table->string('nama_jenis');
            $table->text('deskripsi');
            $table->string('icon_class')->nullable();
            $table->boolean('require_photo')->default(false);
            $table->boolean('require_document')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_jenis_complaints');
    }
};
