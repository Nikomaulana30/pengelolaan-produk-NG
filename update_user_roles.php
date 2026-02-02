<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "Updating users role enum to structured positions...\n";

try {
    // Update existing user roles first
    DB::table('users')->where('role', 'ppic')->update(['role' => 'admin']); // Temp value
    DB::table('users')->where('role', 'warehouse')->update(['role' => 'admin']); // Temp value  
    DB::table('users')->where('role', 'quality')->update(['role' => 'admin']); // Temp value
    
    // Update ENUM
    DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff_exim', 'supervisor_warehouse', 'manager_quality', 'manager_production') NOT NULL DEFAULT 'staff_exim'");
    
    echo "✅ Role enum updated successfully!\n";
    echo "Available roles:\n";
    echo "  - admin\n";
    echo "  - staff_exim\n";
    echo "  - supervisor_warehouse\n";
    echo "  - manager_quality\n";
    echo "  - manager_production\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
