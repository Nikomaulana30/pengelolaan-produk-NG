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
        Schema::create('finance_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_approval')->unique();
            $table->string('nomor_referensi');
            $table->string('pengaju');
            $table->text('deskripsi_pengajuan');
            $table->enum('jenis_dampak', ['claim', 'retur', 'scrap', 'rework_cost', 'tidak_ada']);
            $table->decimal('estimasi_biaya', 15, 2);
            $table->enum('asal_permohonan', ['qc', 'warehouse', 'manager', 'internal_ppic']);
            $table->string('referensi_permohonan')->nullable();
            $table->enum('status_approval', ['pending', 'approved', 'rejected', 'need_revision', 'not_applicable'])->default('pending');
            $table->date('tanggal_approval');
            $table->string('nama_approver');
            $table->enum('budget_approval', ['dalam_budget', 'melebihi_budget', 'perlu_persetujuan_lebih_tinggi']);
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            $table->index('nomor_approval');
            $table->index('status_approval');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_approvals');
    }
};
