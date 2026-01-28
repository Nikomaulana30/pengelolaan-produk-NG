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
        Schema::create('penyimpanan_ngs', function (Blueprint $table) {
            $table->id();
            
            // Nomor & Tanggal
            $table->string('nomor_storage')->unique();
            $table->dateTime('tanggal_penyimpanan');
            $table->string('nomor_referensi');
            $table->string('nama_barang');
            
            // Lokasi Penyimpanan
            $table->enum('zone', ['zona_a', 'zona_b', 'zona_c', 'zona_d', 'zona_e']);
            $table->string('rack');
            $table->string('bin');
            $table->string('lokasi_lengkap');
            
            // Quantity Tracking
            $table->integer('qty_awal');
            $table->integer('qty_setelah_perbaikan')->nullable();
            $table->integer('selisih_qty')->nullable();
            
            // Status & User
            $table->enum('status_barang', ['disimpan', 'dalam_perbaikan', 'menunggu_approval', 'siap_dipindahkan', 'dipindahkan']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('catatan')->nullable();
            
            // Approval Workflow
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('approved_by')->nullable();
            
            // Audit
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('nomor_storage');
            $table->index('tanggal_penyimpanan');
            $table->index('zone');
            $table->index('status_barang');
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyimpanan_ngs');
    }
};
