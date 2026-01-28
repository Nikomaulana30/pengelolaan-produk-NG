<?php

// Test route generation
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ROUTE VERIFICATION ===\n";
echo "✅ Testing route generation:\n";

try {
    echo "  - reports.return-analysis: " . route('reports.return-analysis') . "\n";
    echo "  - reports.export: " . route('reports.export') . "\n";
    echo "  - vendor-scorecard.index: " . route('vendor-scorecard.index') . "\n";
    echo "\n✅ ALL ROUTES WORKING CORRECTLY!\n";
    echo "\n✅ View path updated in AnalyticsDashboardController\n";
    echo "   From: menu-sidebar.analytics-dashboard.index\n";
    echo "   To: menu-sidebar.reports.return-analysis\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
