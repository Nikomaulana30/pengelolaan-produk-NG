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
        Schema::table('warehouse_verifications', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouse_verifications', 'master_lokasi_gudang_id')) {
                $table->foreignId('master_lokasi_gudang_id')->nullable()->after('catatan_penerimaan')->constrained('master_lokasi_gudangs')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('warehouse_verifications', 'master_lokasi_gudang_id')) {
                $table->dropForeign(['master_lokasi_gudang_id']);
                $table->dropColumn('master_lokasi_gudang_id');
            }
        });
    }
};
