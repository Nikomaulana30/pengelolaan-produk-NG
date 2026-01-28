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
        Schema::create('disposisi_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyimpanan_ng_id');
            $table->unsignedBigInteger('master_disposisi_id');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('catatan')->nullable();
            $table->text('hasil_eksekusi')->nullable();
            $table->unsignedBigInteger('assigned_by')->nullable(); // User ID
            $table->unsignedBigInteger('executed_by')->nullable(); // User ID
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();

            $table->foreign('penyimpanan_ng_id')->references('id')->on('penyimpanan_ngs')->onDelete('cascade');
            $table->foreign('master_disposisi_id')->references('id')->on('master_disposisis')->onDelete('restrict');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('executed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisi_assignments');
    }
};
