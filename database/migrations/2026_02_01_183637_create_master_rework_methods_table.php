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
        Schema::create('master_rework_methods', function (Blueprint $table) {
            $table->id();
            $table->string('kode_method')->unique();
            $table->string('nama_method');
            $table->text('deskripsi');
            $table->enum('kategori', ['melting', 'welding', 'machining', 'surface_treatment', 'assembly', 'other']);
            $table->text('equipment_required')->nullable();
            $table->text('skill_required')->nullable();
            $table->decimal('setup_time_hours', 5, 2)->default(0);
            $table->decimal('process_time_per_unit', 5, 2)->default(0);
            $table->decimal('estimated_cost_per_unit', 10, 2)->default(0);
            $table->integer('success_rate_percent')->default(90);
            $table->text('work_instruction')->nullable();
            $table->text('safety_requirement')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_rework_methods');
    }
};
