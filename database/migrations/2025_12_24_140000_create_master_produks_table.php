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
        Schema::create('master_produks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique()->comment('SKU - Unique identifier');
            $table->string('nama_barang')->comment('Product name');
            $table->string('satuan')->default('Pcs')->comment('Unit of Measure: Pcs, Kg, Box, etc');
            $table->enum('kategori_barang', ['raw_material', 'wip', 'finished_goods'])->comment('Product category');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->integer('qty_minimum')->default(0)->comment('Minimum stock level');
            $table->integer('qty_maksimum')->default(0)->comment('Maximum stock level');
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
        Schema::dropIfExists('master_produks');
    }
};
