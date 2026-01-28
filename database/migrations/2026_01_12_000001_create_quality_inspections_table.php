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
        Schema::create('quality_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_laporan')->unique();
            $table->dateTime('tanggal_inspeksi');
            $table->string('product');
            $table->string('part_no');
            $table->string('material');
            $table->string('drawing_no');
            $table->string('drawing_rev')->nullable();
            $table->string('customer');
            $table->string('batch_no');
            $table->string('made_by');
            $table->string('approved_by')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();

            $table->index('nomor_laporan');
            $table->index('user_id');
            $table->index('tanggal_inspeksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_inspections');
    }
};
