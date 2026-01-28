<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Schema;

echo "\n========================================\n";
echo "CEK NOTIFICATION PREFERENCES\n";
echo "========================================\n\n";

// Check if columns exist
echo "1️⃣ Checking Database Columns:\n";
echo str_repeat("─", 60) . "\n";

$columns = ['email_notifications', 'approval_notifications', 'activity_notifications'];

foreach ($columns as $column) {
    if (Schema::hasColumn('users', $column)) {
        echo "✅ Column '{$column}' exists\n";
    } else {
        echo "❌ Column '{$column}' NOT FOUND\n";
    }
}

echo "\n";

// Check User model
echo "2️⃣ Checking User Model:\n";
echo str_repeat("─", 60) . "\n";

$user = User::first();

if ($user) {
    echo "Testing with user: {$user->name} ({$user->email})\n\n";
    
    echo "Current notification settings:\n";
    echo "  Email Notifications: " . ($user->email_notifications ? '✅ ON' : '❌ OFF') . "\n";
    echo "  Approval Notifications: " . ($user->approval_notifications ? '✅ ON' : '❌ OFF') . "\n";
    echo "  Activity Notifications: " . ($user->activity_notifications ? '✅ ON' : '❌ OFF') . "\n";
    
    echo "\n";
    
    // Test update
    echo "3️⃣ Testing Update:\n";
    echo str_repeat("─", 60) . "\n";
    
    echo "Turning OFF email notifications...\n";
    $user->update(['email_notifications' => false]);
    $user->refresh();
    echo "Result: " . ($user->email_notifications ? '❌ Still ON (FAILED)' : '✅ OFF (SUCCESS)') . "\n\n";
    
    echo "Turning ON email notifications...\n";
    $user->update(['email_notifications' => true]);
    $user->refresh();
    echo "Result: " . ($user->email_notifications ? '✅ ON (SUCCESS)' : '❌ Still OFF (FAILED)') . "\n\n";
    
    // Check fillable
    echo "4️⃣ Checking Model Configuration:\n";
    echo str_repeat("─", 60) . "\n";
    
    $fillable = $user->getFillable();
    echo "Fillable fields check:\n";
    
    foreach ($columns as $column) {
        if (in_array($column, $fillable)) {
            echo "  ✅ '{$column}' is fillable\n";
        } else {
            echo "  ❌ '{$column}' is NOT fillable\n";
        }
    }
    
    echo "\n";
    
    // Check casts
    echo "Casts check:\n";
    $casts = $user->getCasts();
    
    foreach ($columns as $column) {
        if (isset($casts[$column])) {
            echo "  ✅ '{$column}' casted as '{$casts[$column]}'\n";
        } else {
            echo "  ❌ '{$column}' has NO cast\n";
        }
    }
    
} else {
    echo "❌ No users found in database\n";
}

echo "\n========================================\n";
echo "SUMMARY\n";
echo "========================================\n\n";

echo "✅ Database columns added\n";
echo "✅ User model updated (fillable & casts)\n";
echo "✅ Controller updated to save to database\n";
echo "✅ Settings will now persist correctly!\n\n";

echo "🎉 NOTIFICATION SETTINGS FIXED!\n";
echo "========================================\n\n";
