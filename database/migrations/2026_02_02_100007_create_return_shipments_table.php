<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('return_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengiriman')->unique();
            $table->foreignId('final_quality_check_id')->constrained()->onDelete('cascade');
            $table->timestamp('tanggal_pengiriman');
            $table->integer('quantity_shipped');
            $table->string('ekspedisi');
            $table->string('nomor_resi')->nullable();
            $table->text('alamat_pengiriman');
            $table->text('catatan_pengiriman')->nullable();
            $table->json('dokumen_pengiriman')->nullable();
            $table->decimal('biaya_pengiriman', 10, 2)->nullable();
            $table->enum('status_pengiriman', ['preparing', 'shipped', 'delivered', 'failed'])->default('preparing');
            $table->enum('status', ['draft', 'shipped', 'completed'])->default('draft');
            $table->unsignedBigInteger('warehouse_staff_id');
            $table->timestamp('delivered_at')->nullable();
            $table->text('catatan_delivery')->nullable();
            $table->integer('rating_customer')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('warehouse_staff_id')->references('id')->on('users');
            $table->index(['status'], 'rs_status_idx');
            $table->index(['status_pengiriman'], 'rs_status_pengiriman_idx');
            $table->index(['tanggal_pengiriman'], 'rs_tanggal_pengiriman_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_shipments');
    }
};