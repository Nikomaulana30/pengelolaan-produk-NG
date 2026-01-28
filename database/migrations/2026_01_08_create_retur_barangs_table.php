<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('produk_id');
            $table->string('no_retur')->unique()->comment('Nomor retur barang');
            $table->date('tanggal_retur')->comment('Tanggal barang dikembalikan');
            $table->enum('alasan_retur', [
                'defect',
                'qty_tidak_sesuai',
                'kualitas_buruk',
                'expired',
                'rusak_pengiriman',
                'lainnya'
            ])->comment('Alasan pengembalian barang');
            $table->integer('jumlah_retur')->comment('Jumlah barang yang dikembalikan');
            $table->text('deskripsi_keluhan')->nullable()->comment('Deskripsi detail keluhan');
            $table->enum('status_approval', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending')->comment('Status approval retur');
            $table->text('catatan_approval')->nullable()->comment('Catatan dari approver');
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('vendor_id')->references('id')->on('master_vendors')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('master_produks')->onDelete('cascade');

            // Indexes
            $table->index('vendor_id');
            $table->index('produk_id');
            $table->index('status_approval');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur_barangs');
    }
};
