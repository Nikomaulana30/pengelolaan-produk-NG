<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterApprovalAuthority;

echo "Testing MasterApprovalAuthority dengan approval_limit:\n";
echo "=====================================================\n\n";

// Check existing data
$authorities = MasterApprovalAuthority::all();

echo "Current data di master_approval_authorities:\n";
foreach($authorities as $auth) {
    $userName = isset($auth->user->name) ? $auth->user->name : 'N/A';
    $limit = isset($auth->approval_limit) ? $auth->approval_limit : 'NULL';
    $isActive = $auth->is_active ? 'Ya' : 'Tidak';
    
    echo "  ID: {$auth->id}\n";
    echo "  User: $userName\n";
    echo "  Approval Limit: $limit\n";
    echo "  Departemen: {$auth->departemen}\n";
    echo "  Jenis Approval: {$auth->jenis_approval}\n";
    echo "  Is Active: $isActive\n";
    echo "  ---\n";
}

echo "\n✓ Test selesai!\n";
echo "Field yang digunakan di database: approval_limit\n";
echo "Field di view sudah diperbaiki untuk menggunakan: approval_limit\n";
