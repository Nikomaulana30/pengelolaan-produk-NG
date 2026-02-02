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
        if (Schema::hasTable('quality_reinspections')) {
            // Add 'rework_completed' to status enum
            DB::statement("ALTER TABLE `quality_reinspections` MODIFY COLUMN `status` ENUM('draft', 'inspected', 'sent_to_production', 'rework_completed') NOT NULL DEFAULT 'draft'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if table exists before modifying
        if (Schema::hasTable('quality_reinspections')) {
            // Rollback to previous enum values
            DB::statement("ALTER TABLE `quality_reinspections` MODIFY COLUMN `status` ENUM('draft', 'inspected', 'sent_to_production') NOT NULL DEFAULT 'draft'");
        }
    }
};
