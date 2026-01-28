<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthViewController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PenyimpananNgController;
use App\Http\Controllers\MasterProdukController;
use App\Http\Controllers\MasterDefectController;
use App\Http\Controllers\MasterLokasiController;
use App\Http\Controllers\MasterLokasiGudangController;
use App\Http\Controllers\MasterVendorController;
use App\Http\Controllers\MasterDisposisiController;
use App\Http\Controllers\MasterApprovalAuthorityController;
use App\Http\Controllers\RcaAnalysisController;
use App\Http\Controllers\VendorScorecardController;
use App\Http\Controllers\AnalyticsDashboardController;
use App\Http\Controllers\DisposisiAssignmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReturBarangController;
use App\Http\Controllers\FinanceApprovalController;
use App\Http\Controllers\ScrapDisposalController;
use App\Http\Controllers\WarehouseApprovalController;
use App\Http\Controllers\QualityInspectionController;
use App\Http\Controllers\QualityApprovalController;
use App\Http\Controllers\LaporanRecapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
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
        //penerimaan barang
        Route::resource('penerimaan-barang', PenerimaanBarangController::class);
        Route::post('penerimaan-barang/{penerimaanBarang}/submit', [PenerimaanBarangController::class, 'submit'])->name('penerimaan-barang.submit');
        Route::post('penerimaan-barang/{penerimaanBarang}/approve', [PenerimaanBarangController::class, 'approve'])->name('penerimaan-barang.approve');

        //penyimpanan ng
        Route::resource('penyimpanan-ng', PenyimpananNgController::class);
        Route::post('penyimpanan-ng/{penyimpananNg}/submit', [PenyimpananNgController::class, 'submit'])->name('penyimpanan-ng.submit');
        Route::post('penyimpanan-ng/{penyimpananNg}/approve', [PenyimpananNgController::class, 'approve'])->name('penyimpanan-ng.approve');

        // ============ RETUR BARANG ROUTES ============
        Route::resource('retur-barang', ReturBarangController::class);

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

        // ============ DISPOSISI ASSIGNMENT ROUTES ============
        Route::resource('disposisi-assignment', DisposisiAssignmentController::class);
        Route::put('disposisi-assignment/{disposisiAssignment}/in-progress', [DisposisiAssignmentController::class, 'markInProgress'])->name('disposisi-assignment.mark-in-progress');
        Route::put('disposisi-assignment/{disposisiAssignment}/completed', [DisposisiAssignmentController::class, 'markCompleted'])->name('disposisi-assignment.mark-completed');
        Route::put('disposisi-assignment/{disposisiAssignment}/cancelled', [DisposisiAssignmentController::class, 'markCancelled'])->name('disposisi-assignment.mark-cancelled');
    });

    // ============================================
    // QUALITY ROUTES (Admin + Quality)
    // ============================================
    Route::middleware(['role:admin,quality'])->group(function(){
        // ============ QUALITY INSPECTION ROUTES ============
        Route::resource('inspeksi-qc', QualityInspectionController::class, [
            'names' => [
                'index' => 'inspeksi-qc.index',
                'create' => 'inspeksi-qc.create',
                'store' => 'inspeksi-qc.store',
                'show' => 'inspeksi-qc.show',
                'edit' => 'inspeksi-qc.edit',
                'update' => 'inspeksi-qc.update',
                'destroy' => 'inspeksi-qc.destroy',
            ],
            'parameters' => [
                'inspeksi-qc' => 'inspection'
            ]
        ]);

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

        // ============ RCA ANALYSIS ROUTES ============
        Route::resource('rca-analysis', RcaAnalysisController::class, [
            'parameters' => ['rca-analysis' => 'rca_analysis']
        ]);
        Route::get('/rca-analysis/defect/{kode_defect}', [RcaAnalysisController::class, 'getDefectDetails'])->name('rca.defect-details');
        Route::get('/rca-analysis/produk/{kode_barang}', [RcaAnalysisController::class, 'getProductDetails'])->name('rca.product-details');
        Route::get('/rca-analysis/retur/{id}', [RcaAnalysisController::class, 'getReturDetails'])->name('rca.retur-details');
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

        // ============ DATA MASTER ROUTES ============
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

});
