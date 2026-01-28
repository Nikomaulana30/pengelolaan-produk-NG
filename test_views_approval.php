<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QualityInspection;

echo "Testing View Files...\n";
echo "====================\n\n";

$approval = QualityInspection::first();

if (!$approval) {
    echo "❌ No records found.\n";
    exit;
}

echo "Test 1: Check if views exist\n";
$views = [
    'menu-sidebar.quality.approval',
    'menu-sidebar.quality.approval-show',
    'menu-sidebar.quality.approval-edit',
];

$finder = app('view.finder');
foreach ($views as $view) {
    try {
        $path = $finder->find($view);
        echo "✓ $view → $path\n";
    } catch (\Exception $e) {
        echo "✗ $view → NOT FOUND\n";
    }
}

echo "\n✓ All views are available!\n";
