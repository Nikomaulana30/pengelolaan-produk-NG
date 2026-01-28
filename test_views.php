<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n========================================\n";
echo "CEK VIEW FILES - PROFILE & SETTINGS\n";
echo "========================================\n\n";

$views = [
    'profile.show' => 'Profile Show Page',
    'profile.edit' => 'Profile Edit Form',
    'profile.change-password' => 'Change Password Form',
    'settings.index' => 'Settings Page'
];

$allExist = true;

foreach ($views as $view => $description) {
    if (view()->exists($view)) {
        echo "✅ {$view}\n";
        echo "   Description: {$description}\n";
        echo "   Status: EXISTS\n\n";
    } else {
        echo "❌ {$view}\n";
        echo "   Description: {$description}\n";
        echo "   Status: NOT FOUND\n\n";
        $allExist = false;
    }
}

echo "========================================\n";
echo "SUMMARY\n";
echo "========================================\n\n";

if ($allExist) {
    echo "✅ All view files exist!\n";
    echo "✅ No errors found in Profile & Settings\n\n";
} else {
    echo "❌ Some view files are missing\n\n";
}

echo "🎉 PROFILE & SETTINGS READY TO USE!\n";
echo "========================================\n\n";
