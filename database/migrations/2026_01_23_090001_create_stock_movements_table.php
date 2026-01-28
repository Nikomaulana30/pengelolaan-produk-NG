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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            
            // Nomor & Tanggal
            $table->string('nomor_movement')->unique();
            $table->dateTime('tanggal_movement');
            
            // Link to Penyimpanan NG (Core Relationship)
            $table->foreignId('penyimpanan_ng_id')
                  ->constrained('penyimpanan_ngs')
                  ->onDelete('cascade');
            
            // Movement Details
            $table->enum('movement_type', [
                'in',           // Masuk ke storage (from QC, from production, etc)
                'out',          // Keluar dari storage (to disposisi, to customer, etc)
                'transfer',     // Transfer antar lokasi
                'adjustment'    // Adjustment (koreksi qty)
            ])->comment('Type of stock movement');
            
            // Quantity Tracking
            $table->decimal('qty_before', 10, 2)->comment('Qty sebelum movement');
            $table->decimal('qty_moved', 10, 2)->comment('Qty yang di-move');
            $table->decimal('qty_after', 10, 2)->comment('Qty setelah movement');
            
            // Reference (Polymorphic-like but simpler)
            $table->string('reference_type')->nullable()
                  ->comment('Type: qc_inspection, disposisi, manual, penerimaan, etc');
            $table->unsignedBigInteger('reference_id')->nullable()
                  ->comment('ID of reference record');
            
            // Location (if transfer)
            $table->foreignId('from_lokasi_id')->nullable()
                  ->constrained('master_lokasi_gudangs')
                  ->onDelete('set null');
            $table->foreignId('to_lokasi_id')->nullable()
                  ->constrained('master_lokasi_gudangs')
                  ->onDelete('set null');
            
            // User & Notes
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->text('notes')->nullable()
                  ->comment('Catatan movement');
            
            // Status
            $table->enum('status', ['pending', 'completed', 'cancelled'])
                  ->default('completed');
            
            // Audit
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('nomor_movement');
            $table->index('tanggal_movement');
            $table->index('penyimpanan_ng_id');
            $table->index('movement_type');
            $table->index('reference_type');
            $table->index(['reference_type', 'reference_id']);
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
