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
        Schema::create('master_customers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_customer')->unique();
            $table->string('nama_customer');
            $table->string('email_customer');
            $table->string('telepon_customer');
            $table->text('alamat_customer');
            $table->enum('kategori_customer', ['vip', 'regular', 'new']);
            $table->enum('payment_terms', ['cod', '30_days', '45_days', '60_days']);
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->string('contact_person')->nullable();
            $table->string('phone_contact_person')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_customers');
    }
};
