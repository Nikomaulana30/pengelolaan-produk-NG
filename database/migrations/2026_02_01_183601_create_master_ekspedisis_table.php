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
        Schema::create('master_ekspedisis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ekspedisi')->unique();
            $table->string('nama_ekspedisi');
            $table->string('contact_person')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->json('service_types')->nullable(); // regular, express, same day
            $table->decimal('base_rate_per_kg', 10, 2)->default(0);
            $table->boolean('has_tracking')->default(false);
            $table->string('tracking_url_pattern')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_ekspedisis');
    }
};
