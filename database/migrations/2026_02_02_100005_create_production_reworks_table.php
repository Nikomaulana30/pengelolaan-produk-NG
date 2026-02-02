<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_reworks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rework')->unique();
            $table->foreignId('quality_reinspection_id')->constrained()->onDelete('cascade');
            $table->timestamp('tanggal_mulai_rework');
            $table->timestamp('tanggal_selesai_rework')->nullable();
            $table->enum('metode_rework', ['melting', 'welding', 'machining', 'surface_treatment', 'assembly', 'other'])->default('melting');
            $table->text('deskripsi_rework');
            $table->text('instruksi_rework');
            $table->integer('quantity_rework');
            $table->integer('quantity_hasil_ok')->nullable();
            $table->integer('quantity_hasil_ng')->nullable();
            $table->text('catatan_proses')->nullable();
            $table->json('dokumen_proses')->nullable();
            $table->decimal('estimasi_biaya', 12, 2);
            $table->decimal('actual_biaya', 12, 2)->nullable();
            $table->integer('estimasi_waktu_hari');
            $table->integer('actual_waktu_hari')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed', 'sent_to_warehouse'])->default('draft');
            $table->unsignedBigInteger('production_manager_id');
            $table->string('pic_rework')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('production_manager_id')->references('id')->on('users');
            $table->index(['status', 'tanggal_mulai_rework']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_reworks');
    }
};