<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== CREATING SAMPLE USERS FOR EACH ROLE ===\n\n";

try {
    $users = [
        [
            'name' => 'Administrator',
            'email' => 'admin@metinca.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => 1,
        ],
        [
            'name' => 'Staff EXIM 1',
            'email' => 'exim@metinca.com',
            'password' => bcrypt('password'),
            'role' => 'staff_exim',
            'is_active' => 1,
        ],
        [
            'name' => 'Supervisor Warehouse',
            'email' => 'warehouse@metinca.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_warehouse',
            'is_active' => 1,
        ],
        [
            'name' => 'Manager Quality',
            'email' => 'quality@metinca.com',
            'password' => bcrypt('password'),
            'role' => 'manager_quality',
            'is_active' => 1,
        ],
        [
            'name' => 'Manager Production',
            'email' => 'production@metinca.com',
            'password' => bcrypt('password'),
            'role' => 'manager_production',
            'is_active' => 1,
        ],
    ];

    // Check if admin exists
    $adminExists = DB::table('users')->where('email', 'admin@metinca.com')->exists();
    
    // Insert new users only if they don't exist
    foreach ($users as $user) {
        $exists = DB::table('users')->where('email', $user['email'])->exists();
        if (!$exists) {
            $user['created_at'] = now();
            $user['updated_at'] = now();
            DB::table('users')->insert($user);
            echo "✅ Created: {$user['name']} ({$user['email']}) - Role: {$user['role']}\n";
        } else {
            echo "⏭️  Skipped: {$user['email']} (already exists)\n";
        }
    }

    echo "\n=== ALL USERS CREATED SUCCESSFULLY ===\n";
    echo "Default password for all users: password\n\n";
    echo "Login credentials:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔴 Admin: admin@metinca.com\n";
    echo "🔵 Staff EXIM: exim@metinca.com\n";
    echo "🟢 Warehouse: warehouse@metinca.com\n";
    echo "🟡 Quality: quality@metinca.com\n";
    echo "🟠 Production: production@metinca.com\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
