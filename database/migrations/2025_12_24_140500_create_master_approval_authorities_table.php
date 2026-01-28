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
        Schema::create('master_approval_authorities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('departemen', ['warehouse', 'quality', 'ppic'])->comment('Department');
            $table->enum('role_level', ['supervisor', 'manager', 'director'])->comment('Authority level');
            $table->decimal('approval_limit', 15, 2)->default(0)->comment('Approval amount limit');
            $table->enum('jenis_approval', ['penerimaan_barang', 'penyimpanan_ng', 'scrap_disposal', 'retur_vendor', 'rework', 'rca_analysis'])->comment('Approval type');
            $table->boolean('can_approve_self')->default(false)->comment('Can approve own submission?');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_approval_authorities');
    }
};
