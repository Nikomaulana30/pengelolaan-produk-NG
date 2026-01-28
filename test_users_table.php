<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Columns di users table:\n";
$columns = Schema::getColumnListing('users');
foreach($columns as $col) {
    echo "  - $col\n";
}

echo "\nChecking for deleted_at: " . (in_array('deleted_at', $columns) ? "✓ ADA" : "✗ TIDAK ADA") . "\n";
