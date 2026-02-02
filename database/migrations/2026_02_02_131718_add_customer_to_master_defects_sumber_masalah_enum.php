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
        // Alter the enum column to add 'customer' option
        DB::statement("ALTER TABLE master_defects MODIFY COLUMN sumber_masalah ENUM('supplier', 'customer', 'proses_produksi', 'handling_gudang', 'lainnya') COMMENT 'Defect source'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE master_defects MODIFY COLUMN sumber_masalah ENUM('supplier', 'proses_produksi', 'handling_gudang', 'lainnya') COMMENT 'Defect source'");
    }
};
