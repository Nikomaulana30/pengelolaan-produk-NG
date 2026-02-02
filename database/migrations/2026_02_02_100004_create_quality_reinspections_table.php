<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quality_reinspections', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_inspeksi')->unique();
            $table->foreignId('warehouse_verification_id')->constrained()->onDelete('cascade');
            $table->timestamp('tanggal_inspeksi');
            $table->string('jenis_defect');
            $table->text('deskripsi_defect');
            $table->enum('severity_level', ['critical', 'major', 'minor'])->default('major');
            $table->integer('quantity_defect');
            $table->text('root_cause_analysis');
            $table->text('corrective_action');
            $table->text('preventive_action');
            $table->enum('disposisi', ['rework', 'scrap', 'return_to_vendor', 'return_to_customer'])->default('rework');
            $table->json('foto_defect')->nullable();
            $table->json('dokumen_rca')->nullable();
            $table->enum('status', ['draft', 'inspected', 'sent_to_production', 'rework_completed'])->default('draft');
            $table->unsignedBigInteger('quality_manager_id');
            $table->timestamp('inspected_at')->nullable();
            $table->decimal('estimasi_biaya_rework', 12, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('quality_manager_id')->references('id')->on('users');
            $table->index(['status', 'severity_level'], 'qr_status_severity_idx');
            $table->index(['tanggal_inspeksi'], 'qr_tanggal_inspeksi_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quality_reinspections');
    }
};