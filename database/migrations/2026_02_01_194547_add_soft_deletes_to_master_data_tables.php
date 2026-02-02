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
        // Add soft deletes to master data tables that use SoftDeletes trait
        $tables = [
            'master_customers',
            'master_priority_levels',
            'master_jenis_complaints',
            'master_kondisi_barangs',
            'master_jenis_returs',
            'master_defect_types',
            'master_rca_categories',
            'master_disposisis'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
        
        // master_vendors already has deleted_at from existing structure
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'master_customers',
            'master_priority_levels',
            'master_jenis_complaints',
            'master_kondisi_barangs',
            'master_jenis_returs',
            'master_defect_types',
            'master_rca_categories',
            'master_disposisis'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
