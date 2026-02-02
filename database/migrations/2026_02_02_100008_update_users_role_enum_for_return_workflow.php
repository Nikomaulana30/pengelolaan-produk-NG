<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update enum role di users table untuk menambah role baru dengan struktur jabatan
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff_exim', 'supervisor_warehouse', 'manager_quality', 'manager_production') NOT NULL DEFAULT 'staff_exim'");
    }

    public function down()
    {
        // Rollback ke enum semula
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'ppic', 'warehouse', 'quality') NOT NULL DEFAULT 'warehouse'");
    }
};