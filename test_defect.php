<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get first defect
$defect = \App\Models\MasterDefect::first();

if ($defect) {
    echo "ID: " . $defect->id . PHP_EOL;
    echo "Nama: " . $defect->nama_defect . PHP_EOL;
    echo "is_rework_possible raw value: " . var_export($defect->getAttributes()['is_rework_possible'], true) . PHP_EOL;
    echo "is_rework_possible casted: " . ($defect->is_rework_possible ? 'true (checked)' : 'false (not checked)') . PHP_EOL;
    echo "!is_rework_possible: " . (!$defect->is_rework_possible ? 'true (checked)' : 'false (not checked)') . PHP_EOL;
    echo PHP_EOL;
    echo "Try toggling...";
    echo PHP_EOL;
    
    // Try setting to 0
    $defect->update(['is_rework_possible' => false]);
    $defect->refresh();
    
    echo "After setting to false:";
    echo PHP_EOL;
    echo "is_rework_possible raw value: " . var_export($defect->getAttributes()['is_rework_possible'], true) . PHP_EOL;
    echo "is_rework_possible casted: " . ($defect->is_rework_possible ? 'true (checked)' : 'false (not checked)') . PHP_EOL;
    echo "!is_rework_possible: " . (!$defect->is_rework_possible ? 'true (checked)' : 'false (not checked)') . PHP_EOL;
} else {
    echo "No defect found";
}

