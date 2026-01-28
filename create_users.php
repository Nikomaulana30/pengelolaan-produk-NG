<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== CREATE MULTIPLE USERS ===\n\n";

$users = [
    [
        'name' => 'Administrator',
        'email' => 'admin@metinca.com',
        'password' => 'admin123',
        'role' => 'admin',
    ],
    [
        'name' => 'Warehouse Staff',
        'email' => 'warehouse@metinca.com',
        'password' => 'warehouse123',
        'role' => 'warehouse',
    ],
    [
        'name' => 'QC Inspector',
        'email' => 'qc@metinca.com',
        'password' => 'qc123',
        'role' => 'quality',
    ],
    [
        'name' => 'PPIC Staff',
        'email' => 'ppic@metinca.com',
        'password' => 'ppic123',
        'role' => 'ppic',
    ],
];

foreach ($users as $userData) {
    $user = User::updateOrCreate(
        ['email' => $userData['email']],
        [
            'name' => $userData['name'],
            'password' => Hash::make($userData['password']),
            'role' => $userData['role'],
            'email_verified_at' => now(),
        ]
    );
    
    echo "✅ Created: {$userData['email']} / {$userData['password']} (Role: {$userData['role']})\n";
}

echo "\n=== ALL USERS CREATED SUCCESSFULLY ===\n";
