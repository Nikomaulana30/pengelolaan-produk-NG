<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('master_produks')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('master_lokasis')->onDelete('cascade');
            $table->integer('quantity')->default(0)->comment('Jumlah barang di lokasi ini');
            $table->integer('reserved_quantity')->default(0)->comment('Jumlah yang sudah dipesan');
            $table->integer('available_quantity')->default(0)->comment('Jumlah tersedia (quantity - reserved)');
            $table->string('status')->default('good')->comment('good, quarantine, damaged, scrap'); // good, quarantine, damaged
            $table->text('notes')->nullable()->comment('Catatan kondisi stok');
            $table->timestamps();
            $table->softDeletes();

            // Unique constraint: satu produk hanya ada sekali per lokasi
            $table->unique(['product_id', 'location_id']);
            
            // Index untuk pencarian cepat
            $table->index('status');
            $table->index('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};
