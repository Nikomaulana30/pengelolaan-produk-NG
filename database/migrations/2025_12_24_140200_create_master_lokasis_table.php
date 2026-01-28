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
        Schema::create('master_lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lokasi')->unique()->comment('Location/Bin code');
            $table->string('nama_lokasi')->comment('Location name');
            $table->enum('zona_gudang', ['zona_a', 'zona_b', 'zona_c', 'zona_d', 'zona_e'])->comment('Warehouse zone');
            $table->string('rack')->comment('Rack identifier');
            $table->string('bin')->comment('Bin identifier');
            $table->enum('tipe_lokasi', ['regular', 'karantina', 'ng_storage', 'scrap'])->default('regular')->comment('Location type');
            $table->enum('status_lokasi', ['available', 'full', 'maintenance', 'blocked'])->default('available')->comment('Location status');
            $table->integer('kapasitas_maksimal')->nullable()->comment('Maximum capacity');
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
        Schema::dropIfExists('master_lokasis');
    }
};
