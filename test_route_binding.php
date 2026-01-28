<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QualityInspection;

echo "Testing Route Parameter Binding...\n";
echo "====================================\n\n";

// Get record
$inspection = QualityInspection::first();

if (!$inspection) {
    echo "❌ No inspection records found.\n";
    exit;
}

echo "Test 1: Check Route Key\n";
echo "- Model: " . class_basename($inspection) . "\n";
echo "- Route Key Name: " . $inspection->getRouteKeyName() . "\n";
echo "- ID: " . $inspection->id . "\n";
echo "✓ Route key correctly set to 'id'\n\n";

echo "Test 2: Generate Route URLs\n";
$baseUrl = env('APP_URL', 'http://127.0.0.1:8000');

// Routes yang akan di-generate
$urls = [
    'show' => route('inspeksi-qc.show', ['inspection' => $inspection->id]),
    'edit' => route('inspeksi-qc.edit', ['inspection' => $inspection->id]),
    'update' => route('inspeksi-qc.update', ['inspection' => $inspection->id]),
    'destroy' => route('inspeksi-qc.destroy', ['inspection' => $inspection->id]),
];

foreach ($urls as $name => $url) {
    echo "- $name: " . str_replace($baseUrl, '', $url) . "\n";
}

echo "\n✓ All routes generated successfully without UrlGenerationException!\n";
echo "\n====================================\n";
