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
        Schema::create('penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            
            // Nomor Dokumen dan Tanggal
            $table->string('nomor_dokumen')->unique()->comment('Format: NG-YYYYMMDD-XXXX');
            $table->dateTime('tanggal_input')->comment('Tanggal dan waktu input data');
            
            // Jenis dan Lokasi
            $table->enum('jenis_pengembalian', ['internal', 'customer_return', 'supplier'])
                ->comment('Sumber pengembalian barang: Internal/Customer/Supplier');
            $table->enum('lokasi_temuan', ['produksi', 'gudang', 'customer', 'supplier'])
                ->comment('Lokasi dimana barang NG ditemukan');
            
            // Data Barang
            $table->string('nama_barang')->comment('Nama barang yang diterima');
            $table->string('sku')->nullable()->comment('SKU / Kode Barang');
            $table->string('batch_number')->nullable()->comment('Batch number produksi');
            
            // Kuantitas
            $table->integer('qty_baik')->default(0)->comment('Jumlah barang yang masih layak pakai');
            $table->integer('qty_rusak')->default(0)->comment('Jumlah barang yang tidak memenuhi standar (NG)');
            
            // Petugas dan Keterangan
            $table->string('penginput')->comment('Nama petugas warehouse yang menginput');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan tentang barang NG');
            
            // User Reference
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')
                ->comment('User yang membuat record ini');
            
            // Status dan Audit
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft')
                ->comment('Status penerimaan barang');
            $table->timestamp('submitted_at')->nullable()->comment('Waktu submit ke sistem');
            $table->timestamp('approved_at')->nullable()->comment('Waktu approval');
            $table->string('approved_by')->nullable()->comment('User yang approve');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk performa query
            $table->index('nomor_dokumen');
            $table->index('tanggal_input');
            $table->index('jenis_pengembalian');
            $table->index('lokasi_temuan');
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barangs');
    }
};
