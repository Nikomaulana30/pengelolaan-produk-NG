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
        // Check if table exists before modifying
        if (Schema::hasTable('production_reworks')) {
            // Update status enum to replace 'sent_to_quality_check' with 'sent_to_warehouse'
            DB::statement("ALTER TABLE `production_reworks` MODIFY COLUMN `status` ENUM('draft', 'in_progress', 'completed', 'sent_to_warehouse') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if table exists before modifying
        if (Schema::hasTable('production_reworks')) {
            // Rollback to previous enum values
            DB::statement("ALTER TABLE `production_reworks` MODIFY COLUMN `status` ENUM('draft', 'in_progress', 'completed', 'sent_to_quality_check') NOT NULL DEFAULT 'draft'");
        }
    }
};
