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
        Schema::create('master_priority_levels', function (Blueprint $table) {
            $table->id();
            $table->string('kode_priority')->unique();
            $table->string('nama_priority');
            $table->text('deskripsi');
            $table->integer('sla_hours'); // SLA response time
            $table->string('color_code'); // untuk UI
            $table->integer('sort_order');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_priority_levels');
    }
};
