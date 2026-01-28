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
        Schema::table('master_lokasi_gudangs', function (Blueprint $table) {
            $table->string('kode_lokasi')->unique()->after('id');
            $table->string('nama_lokasi')->after('kode_lokasi');
            $table->string('zone')->after('nama_lokasi');
            $table->string('rack')->after('zone');
            $table->string('bin')->after('rack');
            $table->string('lokasi_lengkap')->after('bin');
            $table->integer('kapasitas_max')->default(0)->after('lokasi_lengkap');
            $table->integer('current_usage')->default(0)->after('kapasitas_max');
            $table->text('deskripsi')->nullable()->after('current_usage');
            $table->boolean('is_active')->default(true)->after('deskripsi');
            $table->softDeletes()->after('updated_at');
            
            $table->index('zone');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_lokasi_gudangs', function (Blueprint $table) {
            $table->dropIndex(['zone']);
            $table->dropIndex(['is_active']);
            $table->dropColumn([
                'kode_lokasi',
                'nama_lokasi',
                'zone',
                'rack',
                'bin',
                'lokasi_lengkap',
                'kapasitas_max',
                'current_usage',
                'deskripsi',
                'is_active',
                'deleted_at'
            ]);
        });
    }
};
