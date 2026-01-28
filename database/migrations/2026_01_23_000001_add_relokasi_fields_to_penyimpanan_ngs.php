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
        Schema::table('penyimpanan_ngs', function (Blueprint $table) {
            // Lokasi Tujuan untuk relokasi/perpindahan
            $table->enum('zone_tujuan', ['zona_a', 'zona_b', 'zona_c', 'zona_d', 'zona_e'])->nullable()->after('lokasi_lengkap');
            $table->string('rack_tujuan')->nullable()->after('zone_tujuan');
            $table->string('bin_tujuan')->nullable()->after('rack_tujuan');
            $table->string('lokasi_lengkap_tujuan')->nullable()->after('bin_tujuan');
            
            // Tracking perpindahan
            $table->dateTime('tanggal_relokasi')->nullable()->after('lokasi_lengkap_tujuan');
            $table->string('alasan_relokasi')->nullable()->after('tanggal_relokasi');
            
            // Disposisi terpilih
            $table->unsignedBigInteger('master_disposisi_id')->nullable()->after('alasan_relokasi');
            $table->foreign('master_disposisi_id')->references('id')->on('master_disposisis')->onDelete('set null');
            
            // Indexes
            $table->index('zone_tujuan');
            $table->index('master_disposisi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyimpanan_ngs', function (Blueprint $table) {
            $table->dropForeign(['master_disposisi_id']);
            $table->dropIndex(['zone_tujuan']);
            $table->dropIndex(['master_disposisi_id']);
            
            $table->dropColumn([
                'zone_tujuan',
                'rack_tujuan',
                'bin_tujuan',
                'lokasi_lengkap_tujuan',
                'tanggal_relokasi',
                'alasan_relokasi',
                'master_disposisi_id',
            ]);
        });
    }
};
