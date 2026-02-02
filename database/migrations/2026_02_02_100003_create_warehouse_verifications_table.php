<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouse_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_verifikasi')->unique();
            $table->foreignId('dokumen_retur_id')->constrained()->onDelete('cascade');
            $table->timestamp('tanggal_terima');
            $table->integer('quantity_diterima');
            $table->integer('quantity_ng_confirmed');
            $table->integer('quantity_ok');
            $table->text('kondisi_fisik_barang');
            $table->text('catatan_penerimaan');
            $table->json('foto_barang_ng')->nullable();
            $table->string('lokasi_penyimpanan');
            $table->enum('status_verifikasi', ['received', 'verified', 'forwarded_to_quality'])->default('received');
            $table->enum('status', ['draft', 'verified', 'sent_to_quality'])->default('draft');
            $table->unsignedBigInteger('warehouse_staff_id');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('warehouse_staff_id')->references('id')->on('users');
            $table->index(['status', 'tanggal_terima']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_verifications');
    }
};