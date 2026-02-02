<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthViewController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenyimpananNgController;
use App\Http\Controllers\MasterProdukController;
use App\Http\Controllers\MasterDefectController;
use App\Http\Controllers\MasterLokasiController;
use App\Http\Controllers\MasterLokasiGudangController;
use App\Http\Controllers\MasterVendorController;
use App\Http\Controllers\MasterDisposisiController;
use App\Http\Controllers\MasterApprovalAuthorityController;
use App\Http\Controllers\MasterData\MasterCustomerController;
use App\Http\Controllers\MasterData\MasterProdukController as MasterDataProdukController;
use App\Http\Controllers\MasterData\MasterDefectTypeController;
use App\Http\Controllers\MasterData\MasterDisposisiController as MasterDataDisposisiController;
use App\Http\Controllers\MasterData\MasterVendorController as MasterDataVendorController;
use App\Http\Controllers\VendorScorecardController;
use App\Http\Controllers\AnalyticsDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FinanceApprovalController;
use App\Http\Controllers\ScrapDisposalController;
use App\Http\Controllers\WarehouseApprovalController;
use App\Http\Controllers\QualityApprovalController;
use App\Http\Controllers\LaporanRecapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CustomerComplaintController;
use App\Http\Controllers\DokumenReturController;
use App\Http\Controllers\WarehouseVerificationController;
use App\Http\Controllers\QualityReinspectionController;
use App\Http\Controllers\ProductionReworkController;
use App\Http\Controllers\FinalQualityCheckController;
use App\Http\Controllers\ReturnShipmentController;
use App\Http\Controllers\ReturnReportsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/',function(){
    if(Auth::check()){
        return redirect()->route('dashboard');
    }
    return redirect()->route('home.main');
});

require __DIR__.'/auth.php';

// ============================================
// GUEST ROUTES (belum login)
// ============================================
Route::middleware('guest')->group(function () {
    
    // GET - Show Forms (Custom Views)
    Route::get('login', [AuthViewController::class, 'showLogin'])
        ->name('login');
    
    Route::get('register', [AuthViewController::class, 'showRegister'])
        ->name('register');
    
    Route::get('forgot-password', [AuthViewController::class, 'showForgotPassword'])
        ->name('password.request');
    
    Route::get('reset-password/{token}', [AuthViewController::class, 'showResetPassword'])
        ->name('password.reset');

    // POST - Handle Logic (Breeze Controllers)
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');
    
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
    
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

    Route::prefix('home')->group(function(){

        Route::get('/',function(){
            return view('home.main');
        })->name('home.main');

        Route::get('/products',function(){
            return view('home.products');
        })->name('home.products');

        Route::get('/divisions',function(){
            return view('home.divisions');
        })->name('home.divisions');

        Route::get('/facilities',function(){
            return view('home.facilities');
        })->name('home.facilities');

        Route::get('/gallery',function(){
            return view('home.galleries');
        })->name('home.gallery');

    });
});

// ============================================
// Auth ROUTES (sudah login)
// ============================================


Route::middleware(['auth'])->group(function(){

    //dashboard - semua user bisa akses
    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');

    // ============================================
    // WAREHOUSE ROUTES (Admin + Warehouse)
    // ============================================
    Route::middleware(['role:admin,warehouse'])->group(function(){
        //penyimpanan ng
        Route::resource('penyimpanan-ng', PenyimpananNgController::class);
        Route::post('penyimpanan-ng/{penyimpananNg}/submit', [PenyimpananNgController::class, 'submit'])->name('penyimpanan-ng.submit');
        Route::post('penyimpanan-ng/{penyimpananNg}/approve', [PenyimpananNgController::class, 'approve'])->name('penyimpanan-ng.approve');

        //scrap disposal
        Route::prefix('scrap-disposal')->name('scrap-disposal.')->group(function(){
            Route::get('/', [ScrapDisposalController::class, 'index'])->name('index');
            Route::post('/', [ScrapDisposalController::class, 'store'])->name('store');
            Route::get('{scrap}', [ScrapDisposalController::class, 'show'])->name('show');
            Route::get('{scrap}/edit', [ScrapDisposalController::class, 'edit'])->name('edit');
            Route::put('{scrap}', [ScrapDisposalController::class, 'update'])->name('update');
            Route::delete('{scrap}', [ScrapDisposalController::class, 'destroy'])->name('destroy');
        });

        // ============ WAREHOUSE APPROVAL ROUTES ============
        Route::resource('warehouse/approval', WarehouseApprovalController::class, [
            'names' => [
                'index' => 'warehouse.approval.index',
                'create' => 'warehouse.approval.create',
                'store' => 'warehouse.approval.store',
                'show' => 'warehouse.approval.show',
                'edit' => 'warehouse.approval.edit',
                'update' => 'warehouse.approval.update',
                'destroy' => 'warehouse.approval.destroy',
            ]
        ]);
    });

    // ============================================
    // QUALITY ROUTES (Admin + Quality)
    // ============================================
    Route::middleware(['role:admin,quality'])->group(function(){
        // ============ QUALITY APPROVAL ROUTES ============
        Route::resource('quality/approval', QualityApprovalController::class, [
            'names' => [
                'index' => 'quality.approval.index',
                'create' => 'quality.approval.create',
                'store' => 'quality.approval.store',
                'show' => 'quality.approval.show',
                'edit' => 'quality.approval.edit',
                'update' => 'quality.approval.update',
                'destroy' => 'quality.approval.destroy',
            ]
        ]);
    });

    // ============================================
    // PPIC ROUTES (Admin + PPIC)
    // ============================================
    Route::middleware(['role:admin,ppic'])->group(function(){
        // ============ PPIC APPROVAL ROUTES ============
        Route::prefix('ppic/approval')->name('ppic.approval.')->group(function(){
            Route::get('/', [FinanceApprovalController::class, 'index'])->name('index');
            Route::post('/', [FinanceApprovalController::class, 'store'])->name('store');
            Route::get('{approval}', [FinanceApprovalController::class, 'show'])->name('show');
            Route::get('{approval}/edit', [FinanceApprovalController::class, 'edit'])->name('edit');
            Route::put('{approval}', [FinanceApprovalController::class, 'update'])->name('update');
            Route::delete('{approval}', [FinanceApprovalController::class, 'destroy'])->name('destroy');
        });
    });

    // ============================================
    // REPORTS ROUTES (All Authenticated Users)
    // ============================================
    //laporan recap
    Route::get('/laporan-recap', [LaporanRecapController::class, 'index'])->name('laporan-recap.index');
    Route::get('/laporan-recap/export/ppic', [LaporanRecapController::class, 'exportPpic'])->name('laporan-recap.export.ppic');
    Route::get('/laporan-recap/export/qa', [LaporanRecapController::class, 'exportQa'])->name('laporan-recap.export.qa');
    Route::get('/laporan-recap/export/warehouse', [LaporanRecapController::class, 'exportWarehouse'])->name('laporan-recap.export.warehouse');
    Route::get('/laporan-recap/export/comprehensive', [LaporanRecapController::class, 'exportComprehensive'])->name('laporan-recap.export.comprehensive');

    // ============ VENDOR SCORECARD ROUTES ============
    Route::resource('vendor-scorecard', VendorScorecardController::class, [
        'only' => ['index', 'show'],
        'parameters' => ['vendor-scorecard' => 'masterVendor']
    ]);

    // ============ REPORTS ROUTES ============
    Route::prefix('reports')->name('reports.')->group(function(){
        Route::get('/return-analysis', [AnalyticsDashboardController::class, 'index'])
            ->name('return-analysis');
        Route::get('/export', [AnalyticsDashboardController::class, 'export'])
            ->name('export');
    });

    // ============================================
    // RETURN NG WORKFLOW ROUTES
    // ============================================
    
    // Customer Complaint (Staff Export/Import)
    Route::middleware(['role:admin,staff_exim'])->group(function(){
        Route::resource('customer-complaint', CustomerComplaintController::class);
        Route::put('customer-complaint/{complaint}/update-status', [CustomerComplaintController::class, 'updateStatus'])
            ->name('customer-complaint.update-status');
    });

    // Dokumen Retur (Staff Export/Import)
    Route::middleware(['role:admin,staff_exim'])->group(function(){
        Route::resource('dokumen-retur', DokumenReturController::class);
        Route::put('dokumen-retur/{dokumen}/send-to-warehouse', [DokumenReturController::class, 'sendToWarehouse'])
            ->name('dokumen-retur.send-to-warehouse');
    });

    // Warehouse Verification (Warehouse Supervisor)
    Route::middleware(['role:admin,supervisor_warehouse'])->group(function(){
        Route::resource('warehouse-verification', WarehouseVerificationController::class);
        Route::put('warehouse-verification/{verification}/verify', [WarehouseVerificationController::class, 'verify'])
            ->name('warehouse-verification.verify');
        Route::put('warehouse-verification/{verification}/send-to-quality', [WarehouseVerificationController::class, 'sendToQuality'])
            ->name('warehouse-verification.send-to-quality');
    });

    // Quality Reinspection (Quality Manager)
    Route::middleware(['role:admin,manager_quality'])->group(function(){
        Route::resource('quality-reinspection', QualityReinspectionController::class);
        Route::put('quality-reinspection/{inspection}/approve', [QualityReinspectionController::class, 'approve'])
            ->name('quality-reinspection.approve');
        Route::put('quality-reinspection/{inspection}/send-to-production', [QualityReinspectionController::class, 'sendToProduction'])
            ->name('quality-reinspection.send-to-production');
    });

    // Production Rework (Production Manager)
    Route::middleware(['role:admin,manager_production'])->group(function(){
        Route::resource('production-rework', ProductionReworkController::class);
        Route::patch('production-rework/{id}/start', [ProductionReworkController::class, 'start'])
            ->name('production-rework.start');
        Route::patch('production-rework/{id}/complete', [ProductionReworkController::class, 'complete'])
            ->name('production-rework.complete');
        Route::patch('production-rework/{id}/send-to-warehouse', [ProductionReworkController::class, 'sendToWarehouse'])
            ->name('production-rework.send-to-warehouse');
    });

    // Final Quality Check (Staff Export/Import)
    Route::middleware(['role:admin,staff_exim'])->group(function(){
        Route::resource('final-quality-check', FinalQualityCheckController::class);
        Route::put('final-quality-check/{check}/approve', [FinalQualityCheckController::class, 'approve'])
            ->name('final-quality-check.approve');
        Route::put('final-quality-check/{check}/approve-for-shipment', [FinalQualityCheckController::class, 'approveForShipment'])
            ->name('final-quality-check.approve-for-shipment');
        Route::put('final-quality-check/{check}/reject', [FinalQualityCheckController::class, 'reject'])
            ->name('final-quality-check.reject');
    });

    // Return Shipment (Warehouse Supervisor)
    Route::middleware(['role:admin,supervisor_warehouse'])->group(function(){
        Route::resource('return-shipment', ReturnShipmentController::class);
        Route::put('return-shipment/{shipment}/ship', [ReturnShipmentController::class, 'ship'])
            ->name('return-shipment.ship');
        Route::put('return-shipment/{shipment}/delivered', [ReturnShipmentController::class, 'delivered'])
            ->name('return-shipment.delivered');
        Route::get('return-shipment/{shipment}/tracking', [ReturnShipmentController::class, 'trackingInfo'])
            ->name('return-shipment.tracking');
        Route::get('return-shipment/{shipment}/shipping-label', [ReturnShipmentController::class, 'printShippingLabel'])
            ->name('return-shipment.shipping-label');
    });

    // Return Reports (Admin & Staff Export/Import)
    Route::middleware(['role:admin,staff_exim'])->group(function(){
        Route::prefix('return-reports')->name('return-reports.')->group(function(){
            Route::get('/', [ReturnReportsController::class, 'index'])->name('index');
            Route::get('/dashboard', [ReturnReportsController::class, 'dashboardOverview'])->name('dashboard');
            Route::get('/complaint-analysis', [ReturnReportsController::class, 'complaintAnalysis'])->name('complaint-analysis');
            Route::get('/quality-analysis', [ReturnReportsController::class, 'qualityAnalysis'])->name('quality-analysis');
            Route::get('/cost-analysis', [ReturnReportsController::class, 'costAnalysis'])->name('cost-analysis');
            Route::get('/export', [ReturnReportsController::class, 'exportReport'])->name('export');
        });
    });

    //monitoring
    Route::get('/monitoring', function(){
        return view('menu-sidebar.monitoring');
    })->name('monitoring.index');

    // ============================================
    // PROFILE & SETTINGS (All Authenticated Users)
    // ============================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePasswordForm'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/preferences', [SettingsController::class, 'updatePreferences'])->name('update-preferences');
        Route::put('/notifications', [SettingsController::class, 'updateNotifications'])->name('update-notifications');
    });

    // ============================================
    // ADMIN ONLY ROUTES
    // ============================================
    Route::middleware(['role:admin'])->group(function(){
        // ============ USER MANAGEMENT ROUTES ============
        Route::resource('user', UserController::class);

        // ============ DATA MASTER ROUTES (OLD) ============
        Route::resource('master-produk', MasterProdukController::class);
        Route::resource('master-defect', MasterDefectController::class);
        Route::resource('master-lokasi', MasterLokasiController::class);
        Route::resource('master-lokasi-gudang', MasterLokasiGudangController::class);
        Route::resource('master-vendor', MasterVendorController::class);
        Route::resource('master-disposisi', MasterDisposisiController::class);
        Route::put('master-disposisi/{masterDisposisi}/toggle-status', [MasterDisposisiController::class, 'toggleStatus'])->name('master-disposisi.toggle-status');
        Route::resource('master-approval-authority', MasterApprovalAuthorityController::class);
        Route::resource('master-approval', MasterApprovalAuthorityController::class); // Alias for backward compatibility
    });

    // ============================================
    // ROLE-BASED MASTER DATA ACCESS
    // ============================================
    
    // Master Customer - Accessible by Admin & Staff Export/Import
    Route::middleware(['role:admin,staff_exim'])->group(function(){
        Route::resource('master-customer', MasterCustomerController::class)->names('master-customer');
        Route::put('master-customer/{masterCustomer}/toggle-status', [MasterCustomerController::class, 'toggleStatus'])
            ->name('master-customer.toggle-status');
        Route::get('master-customer/export', [MasterCustomerController::class, 'export'])
            ->name('master-customer.export');
    });

    // Master Produk (Enhanced) - Accessible by Admin, Quality Manager, Production Manager
    Route::middleware(['role:admin,manager_quality,manager_production'])->group(function(){
        Route::resource('master-data-produk', MasterDataProdukController::class)->names('master-data.produk');
        Route::put('master-data-produk/{produk}/toggle-status', [MasterDataProdukController::class, 'toggleStatus'])
            ->name('master-data.produk.toggle-status');
        Route::get('master-data-produk/{produk}/defects', [MasterDataProdukController::class, 'getCommonDefects'])
            ->name('master-data.produk.defects');
        Route::get('master-data-produk/{produk}/rework-methods', [MasterDataProdukController::class, 'getReworkMethods'])
            ->name('master-data.produk.rework-methods');
    });

    // Master Defect Types - Accessible by Admin & Quality Manager
    Route::middleware(['role:admin,manager_quality'])->group(function(){
        Route::resource('master-defect-type', MasterDefectTypeController::class)->names('master-defect-type');
        Route::put('master-defect-type/{defectType}/toggle-status', [MasterDefectTypeController::class, 'toggleStatus'])
            ->name('master-defect-type.toggle-status');
        Route::get('master-defect-type/by-category/{category}', [MasterDefectTypeController::class, 'getByCategory'])
            ->name('master-defect-type.by-category');
        Route::get('master-defect-type/by-severity/{severity}', [MasterDefectTypeController::class, 'getBySeverity'])
            ->name('master-defect-type.by-severity');
    });

    // Master Disposisi (Enhanced) - Accessible by Admin & Quality Manager  
    Route::middleware(['role:admin,manager_quality'])->group(function(){
        Route::resource('master-data-disposisi', MasterDataDisposisiController::class)->names('master-data.disposisi');
        Route::put('master-data-disposisi/{disposisi}/toggle-status', [MasterDataDisposisiController::class, 'toggleStatus'])
            ->name('master-data.disposisi.toggle-status');
        Route::get('master-data-disposisi/by-action/{action}', [MasterDataDisposisiController::class, 'getByAction'])
            ->name('master-data.disposisi.by-action');
        Route::get('master-data-disposisi/requiring-approval', [MasterDataDisposisiController::class, 'getRequiringApproval'])
            ->name('master-data.disposisi.requiring-approval');
    });

    // Master Vendor (Enhanced) - Accessible by Admin & Quality Manager
    Route::middleware(['role:admin,manager_quality,manager_production'])->group(function(){
        Route::resource('master-data-vendor', MasterDataVendorController::class)->names('master-data.vendor');
        Route::put('master-data-vendor/{vendor}/toggle-status', [MasterDataVendorController::class, 'toggleStatus'])
            ->name('master-data.vendor.toggle-status');
        Route::put('master-data-vendor/{vendor}/update-rating', [MasterDataVendorController::class, 'updateRating'])
            ->name('master-data.vendor.update-rating');
        Route::get('master-data-vendor/scorecard', [MasterDataVendorController::class, 'scorecard'])
            ->name('master-data.vendor.scorecard');
        Route::get('master-data-vendor/expiring-contracts', [MasterDataVendorController::class, 'expiringContracts'])
            ->name('master-data.vendor.expiring-contracts');
    });

});
