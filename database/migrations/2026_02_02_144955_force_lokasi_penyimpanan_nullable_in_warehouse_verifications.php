<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to force the column to be nullable
        DB::statement('ALTER TABLE warehouse_verifications MODIFY COLUMN lokasi_penyimpanan VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE warehouse_verifications MODIFY COLUMN lokasi_penyimpanan VARCHAR(255) NOT NULL');
    }
};
