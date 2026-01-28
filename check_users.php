<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== EXISTING USERS IN DATABASE ===\n\n";

$users = DB::table('users')->get();

if ($users->count() === 0) {
    echo "No users found in database.\n";
} else {
    echo "Total users: " . $users->count() . "\n\n";
    
    foreach ($users as $user) {
        echo "ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Role: {$user->role}\n";
        echo "Created: {$user->created_at}\n";
        echo "---\n\n";
    }
}

echo "=== END ===\n";
