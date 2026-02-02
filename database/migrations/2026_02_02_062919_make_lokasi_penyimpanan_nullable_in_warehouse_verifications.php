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
        if (Schema::hasTable('warehouse_verifications')) {
            Schema::table('warehouse_verifications', function (Blueprint $table) {
                $table->string('lokasi_penyimpanan')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('warehouse_verifications')) {
            Schema::table('warehouse_verifications', function (Blueprint $table) {
                $table->string('lokasi_penyimpanan')->nullable(false)->change();
            });
        }
    }
};
