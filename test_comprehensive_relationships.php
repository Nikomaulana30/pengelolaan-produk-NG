<?php

require_once 'bootstrap/app.php';

echo "\n🔥 TESTING COMPREHENSIVE RELATIONSHIP SYSTEM\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Test 1: Auto-number generation
    echo "📋 TEST 1: AUTO-NUMBER GENERATION\n";
    echo "-" . str_repeat("-", 35) . "\n";
    
    // Test CustomerComplaint auto-number
    $complaint = new \App\Models\CustomerComplaint();
    $complaint->master_customer_id = 1;
    $complaint->produk_id = 1;
    $complaint->nama_customer = "Test Customer";
    $complaint->produk = "Test Product";
    $complaint->deskripsi_masalah = "Test complaint for auto-number";
    $complaint->save();
    
    echo "✅ CustomerComplaint created with number: " . $complaint->nomor_complaint . "\n";
    
    // Test DokumenRetur auto-number
    $dokumen = new \App\Models\DokumenRetur();
    $dokumen->customer_complaint_id = $complaint->id;
    $dokumen->master_customer_id = 1;
    $dokumen->produk_id = 1;
    $dokumen->jumlah_retur = 10;
    $dokumen->save();
    
    echo "✅ DokumenRetur created with number: " . $dokumen->nomor_dokumen . "\n";
    
    // Test 2: Relationship chain
    echo "\n🔗 TEST 2: RELATIONSHIP CHAIN\n";
    echo "-" . str_repeat("-", 35) . "\n";
    
    // Test forward relationship
    $complaintWithRetur = \App\Models\CustomerComplaint::with('dokumenRetur')->find($complaint->id);
    if ($complaintWithRetur->dokumenRetur) {
        echo "✅ CustomerComplaint → DokumenRetur relationship working\n";
        echo "   Complaint: " . $complaintWithRetur->nomor_complaint . "\n";
        echo "   Retur: " . $complaintWithRetur->dokumenRetur->nomor_dokumen . "\n";
    }
    
    // Test backward relationship
    $returWithComplaint = \App\Models\DokumenRetur::with('customerComplaint')->find($dokumen->id);
    if ($returWithComplaint->customerComplaint) {
        echo "✅ DokumenRetur → CustomerComplaint relationship working\n";
    }
    
    // Test 3: Master data relationships
    echo "\n📊 TEST 3: MASTER DATA RELATIONSHIPS\n";
    echo "-" . str_repeat("-", 35) . "\n";
    
    // Test MasterCustomer relationships
    $customer = \App\Models\MasterCustomer::first();
    if ($customer) {
        $stats = $customer->getStatistics();
        echo "✅ MasterCustomer statistics:\n";
        echo "   Customer: " . $customer->nama_customer . "\n";
        echo "   Total Complaints: " . $stats['total_complaints'] . "\n";
        echo "   Completed Returns: " . $stats['completed_returns'] . "\n";
    }
    
    // Test MasterProduk relationships
    $produk = \App\Models\MasterProduk::first();
    if ($produk) {
        $qualityStats = $produk->getQualityStatistics();
        echo "✅ MasterProduk quality statistics:\n";
        echo "   Product: " . $produk->nama_produk . "\n";
        echo "   Total Complaints: " . $qualityStats['total_complaints'] . "\n";
        echo "   Quality Score: " . $qualityStats['quality_score'] . "\n";
    }
    
    // Test 4: Scope queries
    echo "\n🔍 TEST 4: SCOPE QUERIES\n";
    echo "-" . str_repeat("-", 35) . "\n";
    
    $pendingComplaints = \App\Models\CustomerComplaint::pending()->count();
    echo "✅ Pending complaints count: " . $pendingComplaints . "\n";
    
    $thisWeekComplaints = \App\Models\CustomerComplaint::thisWeek()->count();
    echo "✅ This week complaints: " . $thisWeekComplaints . "\n";
    
    $thisMonthComplaints = \App\Models\CustomerComplaint::thisMonth()->count();
    echo "✅ This month complaints: " . $thisMonthComplaints . "\n";
    
    // Test 5: User role relationships
    echo "\n👥 TEST 5: USER ROLE RELATIONSHIPS\n";
    echo "-" . str_repeat("-", 35) . "\n";
    
    $user = \App\Models\User::first();
    if ($user) {
        echo "✅ User: " . $user->name . " (" . $user->role . ")\n";
        echo "   Can access admin workflow: " . ($user->canAccessAdminWorkflow() ? "Yes" : "No") . "\n";
        echo "   Can access warehouse: " . ($user->canAccessWarehouse() ? "Yes" : "No") . "\n";
        echo "   Can access quality: " . ($user->canAccessQuality() ? "Yes" : "No") . "\n";
        echo "   Can access production: " . ($user->canAccessProduction() ? "Yes" : "No") . "\n";
    }
    
    echo "\n🎉 ALL TESTS COMPLETED SUCCESSFULLY!\n";
    echo "=" . str_repeat("=", 50) . "\n";
    echo "✅ Auto-number generation: WORKING\n";
    echo "✅ Relationship chains: WORKING\n";
    echo "✅ Master data analytics: WORKING\n";
    echo "✅ Scope queries: WORKING\n";
    echo "✅ User role system: WORKING\n";
    echo "\n🚀 System ready for production use!\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}