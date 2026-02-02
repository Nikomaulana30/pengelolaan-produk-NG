<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== CHECKING USERS IN DATABASE ===\n\n";

try {
    $users = DB::table('users')->select('id', 'name', 'email', 'role', 'is_active')->get();
    
    if ($users->isEmpty()) {
        echo "❌ No users found in database!\n";
        echo "Creating default admin user...\n\n";
        
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@metinca.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Admin user created successfully!\n";
        echo "Email: admin@metinca.com\n";
        echo "Password: password\n\n";
    } else {
        echo "Found " . $users->count() . " user(s):\n\n";
        
        foreach ($users as $user) {
            $status = $user->is_active ? '✅ Active' : '❌ Inactive';
            echo "ID: {$user->id}\n";
            echo "Name: {$user->name}\n";
            echo "Email: {$user->email}\n";
            echo "Role: {$user->role}\n";
            echo "Status: {$status}\n";
            echo "-------------------\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
