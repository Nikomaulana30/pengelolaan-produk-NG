<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_complaint')->unique();
            $table->string('nama_customer');
            $table->string('email_customer');
            $table->string('telepon_customer');
            $table->text('alamat_customer');
            $table->string('produk');
            $table->integer('quantity_ng');
            $table->text('deskripsi_complaint');
            $table->json('foto_complaint')->nullable();
            $table->json('dokumen_pendukung')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['draft', 'submitted', 'processing', 'completed', 'cancelled'])->default('draft');
            $table->timestamp('tanggal_complaint');
            $table->unsignedBigInteger('staff_exim_id')->nullable();
            $table->text('catatan_staff')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('staff_exim_id')->references('id')->on('users');
            $table->index(['status'], 'cc_status_idx');
            $table->index(['priority'], 'cc_priority_idx');
            $table->index(['tanggal_complaint'], 'cc_tanggal_complaint_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_complaints');
    }
};