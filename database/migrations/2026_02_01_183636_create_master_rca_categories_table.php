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
        Schema::create('master_rca_categories', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rca')->unique();
            $table->string('nama_kategori');
            $table->text('deskripsi');
            $table->enum('area', ['man', 'machine', 'method', 'material', 'environment', 'measurement']);
            $table->text('investigation_guide')->nullable();
            $table->text('standard_corrective_action')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_rca_categories');
    }
};
