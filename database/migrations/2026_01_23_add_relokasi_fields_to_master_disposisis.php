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
        Schema::table('master_disposisis', function (Blueprint $table) {
            // Add relocation target location fields
            $table->enum('zone_tujuan', [
                'zona_a', 'zona_b', 'zona_c', 'zona_d', 'zona_e',
                'zona_return', 'zona_scrap', 'zona_rework'
            ])->nullable()->after('syarat_ketentuan')->comment('Target zone for relocation');
            
            $table->string('rack_tujuan', 100)->nullable()->after('zone_tujuan')->comment('Target rack for relocation');
            $table->string('bin_tujuan', 100)->nullable()->after('rack_tujuan')->comment('Target bin for relocation');
            $table->string('lokasi_lengkap_tujuan', 255)->nullable()->after('bin_tujuan')->comment('Complete target location format: ZA-A1-001');
            
            // Add indexes for performance
            $table->index('zone_tujuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_disposisis', function (Blueprint $table) {
            $table->dropIndex(['zone_tujuan']);
            $table->dropColumn(['zone_tujuan', 'rack_tujuan', 'bin_tujuan', 'lokasi_lengkap_tujuan']);
        });
    }
};
