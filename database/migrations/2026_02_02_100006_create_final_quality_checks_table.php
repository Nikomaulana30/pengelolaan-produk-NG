<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('final_quality_checks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_final_check')->unique();
            $table->foreignId('production_rework_id')->constrained()->onDelete('cascade');
            $table->timestamp('tanggal_check');
            $table->integer('quantity_checked');
            $table->integer('quantity_passed');
            $table->integer('quantity_failed');
            $table->text('hasil_pemeriksaan');
            $table->text('catatan_quality');
            $table->enum('keputusan_final', ['approved_for_shipment', 'need_rework', 'rejected'])->default('need_rework');
            $table->json('foto_hasil_check')->nullable();
            $table->json('dokumen_quality')->nullable();
            $table->enum('status', ['draft', 'checked', 'approved_for_shipment'])->default('draft');
            $table->unsignedBigInteger('staff_exim_id');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('staff_exim_id')->references('id')->on('users');
            $table->index(['status'], 'fqc_status_idx');
            $table->index(['keputusan_final'], 'fqc_keputusan_final_idx');
            $table->index(['tanggal_check'], 'fqc_tanggal_check_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('final_quality_checks');
    }
};