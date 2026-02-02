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
        Schema::create('master_production_lines', function (Blueprint $table) {
            $table->id();
            $table->string('kode_line')->unique();
            $table->string('nama_line');
            $table->text('deskripsi');
            $table->enum('jenis_line', ['injection', 'assembly', 'welding', 'machining', 'painting', 'packaging']);
            $table->integer('kapasitas_per_hari');
            $table->decimal('efficiency_percent', 5, 2)->default(85);
            $table->json('capability_methods')->nullable(); // methods yang bisa dilakukan
            $table->string('location_area');
            $table->string('pic_supervisor')->nullable();
            $table->enum('status_line', ['active', 'maintenance', 'down']);
            $table->boolean('can_rework')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_production_lines');
    }
};
