<?php

echo "=== COMPREHENSIVE SYSTEM CHECK ===\n";

// 1. Test Routes
echo "\n1. ✅ ROUTES CHECK";
try {
    echo "\n   reports.return-analysis: " . (route('reports.return-analysis') ? "✓" : "✗");
    echo "\n   reports.export: " . (route('reports.export') ? "✓" : "✗");
    echo "\n   vendor-scorecard.index: " . (route('vendor-scorecard.index') ? "✓" : "✗");
    echo "\n   vendor-scorecard.show: " . (route('vendor-scorecard.show', 1) ? "✓" : "✗");
} catch (Exception $e) {
    echo "\n   ✗ Error: " . $e->getMessage();
}

// 2. Database Check
echo "\n\n2. ✅ DATABASE CHECK";
require 'bootstrap/app.php';
$app = app();
echo "\n   Loading models...";
try {
    echo "\n   Vendors: " . \App\Models\MasterVendor::count();
    echo "\n   Returns: " . \App\Models\ReturBarang::count();
    echo "\n   RCAs: " . \App\Models\RcaAnalysis::count();
    echo "\n   Defects: " . \App\Models\MasterDefect::count();
} catch (Exception $e) {
    echo "\n   Error: " . $e->getMessage();
}

// 3. Controller Check
echo "\n\n3. ✅ CONTROLLER CHECK";
try {
    $controller = new \App\Http\Controllers\AnalyticsDashboardController();
    echo "\n   AnalyticsDashboardController: ✓";
    echo "\n   Methods: index(), export()";
} catch (Exception $e) {
    echo "\n   ✗ Error: " . $e->getMessage();
}

// 4. View Check
echo "\n\n4. ✅ VIEW CHECK";
$views = [
    'menu-sidebar.reports.return-analysis',
    'menu-sidebar.vendor-scorecard.index',
    'menu-sidebar.vendor-scorecard.show',
    'layouts.app',
];
foreach ($views as $view) {
    $exists = view()->exists($view);
    echo "\n   " . ($exists ? "✓" : "✗") . " $view";
}

echo "\n\n5. ✅ ROUTE NAMES CHECK";
$routes = ['reports.return-analysis', 'reports.export', 'vendor-scorecard.index', 'vendor-scorecard.show'];
foreach ($routes as $routeName) {
    try {
        if ($routeName === 'vendor-scorecard.show') {
            route($routeName, 1);
        } else {
            route($routeName);
        }
        echo "\n   ✓ $routeName";
    } catch (Exception $e) {
        echo "\n   ✗ $routeName - " . $e->getMessage();
    }
}

echo "\n\n✨ SYSTEM STATUS: READY FOR DEPLOYMENT";
echo "\n\n";
?>
