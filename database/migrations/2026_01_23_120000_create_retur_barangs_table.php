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
            
            // Document Info
            $table->string('nomor_retur')->unique();
            $table->date('tanggal_retur');
            
            // Relationships
            $table->foreignId('penerimaan_barang_id')->nullable()
                  ->constrained('penerimaan_barangs')->onDelete('set null');
            $table->foreignId('penyimpanan_ng_id')->nullable()
                  ->constrained('penyimpanan_ngs')->onDelete('set null');
            
            // Retur Details
            $table->string('nama_barang');
            $table->string('sku')->nullable();
            $table->string('supplier_name');
            $table->text('alasan_retur');
            $table->decimal('qty_retur', 10, 2);
            $table->string('satuan')->default('pcs');
            
            // Shipping Info
            $table->string('no_surat_jalan_retur')->nullable();
            $table->string('ekspedisi')->nullable();
            $table->decimal('biaya_retur', 15, 2)->default(0);
            $table->string('biaya_currency')->default('IDR');
            
            // Status & Approval
            $table->enum('status', [
                'draft',
                'pending_approval',
                'approved',
                'shipped',
                'completed',
                'rejected',
                'cancelled'
            ])->default('draft');
            
            // Users
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Timestamps
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('nomor_retur');
            $table->index('tanggal_retur');
            $table->index('status');
            $table->index('penerimaan_barang_id');
            $table->index('penyimpanan_ng_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur_barangs');
    }
};
