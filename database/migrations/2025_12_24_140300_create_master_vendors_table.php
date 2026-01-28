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
        Schema::create('master_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('kode_vendor')->unique()->comment('Vendor code');
            $table->string('nama_vendor')->comment('Vendor name');
            $table->string('alamat_vendor')->comment('Vendor address');
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('person_in_charge')->nullable()->comment('Contact person');
            $table->enum('kebijakan_retur', ['retur_fisik', 'debit_note', 'keduanya'])->default('retur_fisik')->comment('Return policy');
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
        Schema::dropIfExists('master_vendors');
    }
};
