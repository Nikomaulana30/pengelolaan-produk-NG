<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update existing data to new roles before changing ENUM
        DB::table('users')->where('role', 'ppic')->update(['role' => 'staff_exim']);
        DB::table('users')->where('role', 'warehouse')->update(['role' => 'supervisor_warehouse']);
        DB::table('users')->where('role', 'quality')->update(['role' => 'manager_quality']);
        
        // Update enum role di users table dengan struktur jabatan yang baru
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff_exim', 'supervisor_warehouse', 'manager_quality', 'manager_production') NOT NULL DEFAULT 'staff_exim'");
    }

    public function down()
    {
        // Rollback ke enum semula
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'ppic', 'warehouse', 'quality') NOT NULL DEFAULT 'warehouse'");
        
        // Rollback data to old roles
        DB::table('users')->where('role', 'staff_exim')->update(['role' => 'ppic']);
        DB::table('users')->where('role', 'supervisor_warehouse')->update(['role' => 'warehouse']);
        DB::table('users')->where('role', 'manager_quality')->update(['role' => 'quality']);
    }
};
