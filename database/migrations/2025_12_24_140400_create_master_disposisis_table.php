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
        Schema::create('master_disposisis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_disposisi')->unique()->comment('Disposition code');
            $table->string('nama_disposisi')->comment('Disposition name');
            $table->enum('jenis_tindakan', ['return_to_vendor', 'scrap_disposal', 'rework', 'downgrade', 'repurpose'])->comment('Action type');
            $table->text('deskripsi')->nullable();
            $table->text('proses_tindakan')->nullable()->comment('Process workflow');
            $table->text('syarat_ketentuan')->nullable()->comment('Terms and conditions');
            $table->boolean('memerlukan_approval')->default(true);
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
        Schema::dropIfExists('master_disposisis');
    }
};
