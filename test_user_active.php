<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Testing User::active() scope:\n";
echo "==============================\n\n";

try {
    $users = User::active()->get();
    echo "✓ User::active() scope berhasil dipanggil!\n";
    echo "Total users: " . count($users) . "\n";
    
    foreach($users as $user) {
        echo "  - {$user->id}: {$user->name} ({$user->email})\n";
    }
    
    echo "\n✓ Tidak ada error!\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
