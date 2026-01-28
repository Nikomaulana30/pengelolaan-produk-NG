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
        Schema::create('scrap_disposals', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_scrap')->unique();
            $table->dateTime('tanggal_scrap');
            $table->string('nama_petugas');
            
            // Referensi dan informasi barang
            $table->string('nomor_referensi');
            $table->string('nama_barang');
            $table->integer('quantity')->default(1);
            
            // Alasan dan deskripsi
            $table->enum('alasan_scrap', ['tidak_bisa_diperbaiki', 'obsolete', 'expired', 'cacat_permanen', 'tidak_ekonomis']);
            $table->text('deskripsi_kondisi');
            
            // QC Test
            $table->string('hasil_test_qc');
            $table->date('tanggal_test_qc')->nullable();
            $table->string('qc_inspector')->nullable();
            $table->text('catatan_qc')->nullable();
            
            // Metode dan Pelaksanaan
            $table->enum('metode_pembuangan', ['pembakaran', 'penguburan', 'daur_ulang', 'penjualan_scrap', 'lainnya']);
            $table->date('tanggal_rencana_scrap')->nullable();
            $table->string('pihak_pelaksana')->nullable();
            $table->decimal('estimasi_biaya_pembuangan', 15, 2)->nullable();
            $table->string('dokumen_bukti')->nullable();
            
            // Approval
            $table->enum('status_approval', ['pending', 'approved', 'rejected', 'need_revision'])->default('pending');
            $table->dateTime('tanggal_approval')->nullable();
            $table->string('nama_manager')->nullable();
            $table->text('catatan_manager')->nullable();
            
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            $table->index('nomor_scrap');
            $table->index('status_approval');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrap_disposals');
    }
};
