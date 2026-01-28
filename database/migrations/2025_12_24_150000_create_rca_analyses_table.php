<?php

namespace Database\Migrations;

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
        Schema::create('rca_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rca')->unique();
            $table->dateTime('tanggal_analisa');
            $table->enum('metode_rca', ['5_why', 'fishbone', 'kombinasi']);
            $table->string('kode_defect')->nullable();
            $table->foreign('kode_defect')->references('kode_defect')->on('master_defects')->onDelete('cascade');
            $table->string('kode_barang')->nullable();
            $table->foreign('kode_barang')->references('kode_barang')->on('master_produks')->onDelete('cascade');
            $table->enum('criticality_level', ['minor', 'major', 'critical'])->nullable();
            $table->enum('sumber_masalah', ['supplier', 'proses_produksi', 'handling_gudang', 'lainnya'])->nullable();
            $table->enum('penyebab_utama', ['human_error', 'metode_kerja', 'material', 'mesin', 'lingkungan', 'pengukuran']);
            $table->text('deskripsi_masalah');
            $table->longText('analisa_detail');
            $table->text('corrective_action');
            $table->text('preventive_action');
            $table->enum('pic_analisa', ['qc', 'engineering', 'warehouse', 'production', 'maintenance']);
            $table->string('nama_analis');
            $table->date('due_date');
            $table->enum('status_rca', ['open', 'in_progress', 'closed'])->default('open');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('kode_defect');
            $table->index('kode_barang');
            $table->index('status_rca');
            $table->index('tanggal_analisa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rca_analyses');
    }
};
