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
        Schema::create('master_products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk', 50)->unique()->comment('Unique product code');
            $table->string('nama_produk', 255)->comment('Product name');
            $table->string('kategori', 50)->nullable()->comment('Product category (raw_material, wip, finished_goods)');
            $table->string('unit', 20)->nullable()->comment('Unit of measurement (pcs, kg, liter, etc)');
            $table->decimal('harga', 12, 2)->nullable()->comment('Product unit price');
            $table->unsignedBigInteger('vendor_id')->nullable()->comment('Link to master_vendors');
            $table->text('spesifikasi')->nullable()->comment('Product specification/description');
            $table->string('drawing_file')->nullable()->comment('Path to product drawing file');
            $table->boolean('is_active')->default(true)->comment('Active status');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraint
            $table->foreign('vendor_id')
                ->references('id')
                ->on('master_vendors')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // Indexes for performance
            $table->index('kode_produk');
            $table->index('vendor_id');
            $table->index('kategori');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_products');
    }
};
