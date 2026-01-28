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
        Schema::table('disposisi_assignments', function (Blueprint $table) {
            $table->string('zone_tujuan')->nullable()->after('catatan');
            $table->string('rack_tujuan')->nullable()->after('zone_tujuan');
            $table->string('bin_tujuan')->nullable()->after('rack_tujuan');
            $table->string('lokasi_lengkap_tujuan')->nullable()->after('bin_tujuan');
            $table->datetime('tanggal_relokasi')->nullable()->after('lokasi_lengkap_tujuan');
            $table->text('alasan_relokasi')->nullable()->after('tanggal_relokasi');
            
            $table->index('zone_tujuan');
            $table->index('lokasi_lengkap_tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisi_assignments', function (Blueprint $table) {
            $table->dropIndex(['zone_tujuan']);
            $table->dropIndex(['lokasi_lengkap_tujuan']);
            $table->dropColumn([
                'zone_tujuan',
                'rack_tujuan',
                'bin_tujuan',
                'lokasi_lengkap_tujuan',
                'tanggal_relokasi',
                'alasan_relokasi'
            ]);
        });
    }
};
