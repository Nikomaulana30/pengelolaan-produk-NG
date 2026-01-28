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
        Schema::create('warehouse_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_approval')->unique();
            $table->dateTime('tanggal_pengajuan');
            $table->string('nomor_referensi');
            $table->string('pengaju');
            $table->text('deskripsi_pengajuan');

            // Warehouse Supervisor Approval
            $table->enum('ws_status_approval', ['pending', 'approved', 'rejected', 'need_revision'])->default('pending');
            $table->dateTime('ws_tanggal_approval')->nullable();
            $table->string('ws_nama_approver')->nullable();
            $table->enum('ws_kondisi_barang', ['aman', 'perlu_penanganan', 'tidak_layak'])->nullable();
            $table->text('ws_catatan')->nullable();

            // Production Manager Approval
            $table->enum('pm_status_approval', ['pending', 'approved', 'rejected', 'need_revision'])->default('pending');
            $table->dateTime('pm_tanggal_approval')->nullable();
            $table->string('pm_nama_approver')->nullable();
            $table->enum('pm_keputusan', ['rework', 'repair', 'scrap', 'use_as_is'])->nullable();
            $table->text('pm_catatan')->nullable();

            // Overall Status
            $table->enum('status_keseluruhan', ['pending', 'approved', 'rejected', 'need_revision'])->default('pending');

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            $table->index('nomor_approval');
            $table->index('status_keseluruhan');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_approvals');
    }
};
