<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dokumen_returs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dokumen_retur')->unique();
            $table->foreignId('customer_complaint_id')->constrained()->onDelete('cascade');
            $table->string('nomor_referensi')->nullable();
            $table->text('instruksi_retur');
            $table->json('dokumen_retur')->nullable(); // PDF, images
            $table->enum('jenis_retur', ['full_return', 'partial_return', 'replacement', 'credit_note'])->default('full_return');
            $table->enum('status', ['draft', 'sent_to_warehouse', 'received_by_warehouse'])->default('draft');
            $table->timestamp('tanggal_dokumen');
            $table->timestamp('tanggal_kirim')->nullable();
            $table->unsignedBigInteger('staff_exim_id');
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('staff_exim_id')->references('id')->on('users');
            $table->index(['status', 'tanggal_dokumen']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_returs');
    }
};