<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Available roles
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF_EXIM = 'staff_exim';
    const ROLE_WAREHOUSE_STAFF = 'warehouse_staff';
    const ROLE_QUALITY_MANAGER = 'quality_manager';
    const ROLE_PRODUCTION_MANAGER = 'production_manager';
    const ROLE_PPIC = 'ppic';
    const ROLE_WAREHOUSE = 'warehouse';
    const ROLE_QUALITY = 'quality';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'bio',
        'avatar',
        'theme',
        'department',
        'last_login_at',
        'email_notifications',
        'approval_notifications',
        'activity_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_notifications' => 'boolean',
            'approval_notifications' => 'boolean',
            'activity_notifications' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is PPIC
     */
    public function isPpic(): bool
    {
        return $this->role === self::ROLE_PPIC;
    }

    /**
     * Check if user is Warehouse
     */
    public function isWarehouse(): bool
    {
        return $this->role === self::ROLE_WAREHOUSE;
    }

    // RELATIONSHIPS FOR WORKFLOW PROCESS
    
    /**
     * Customer Complaints handled as Staff Export/Import
     */
    public function customerComplaints()
    {
        return $this->hasMany(CustomerComplaint::class, 'staff_exim_id');
    }

    /**
     * Dokumen Retur created by Staff Export/Import
     */
    public function dokumenReturCreated()
    {
        return $this->hasMany(DokumenRetur::class, 'staff_exim_id');
    }

    /**
     * Warehouse Verifications handled as Warehouse Staff
     */
    public function warehouseVerifications()
    {
        return $this->hasMany(WarehouseVerification::class, 'warehouse_staff_id');
    }

    /**
     * Quality Reinspections handled as Quality Manager
     */
    public function qualityReinspections()
    {
        return $this->hasMany(QualityReinspection::class, 'quality_manager_id');
    }

    /**
     * Production Reworks handled as Production Manager
     */
    public function productionReworks()
    {
        return $this->hasMany(ProductionRework::class, 'production_manager_id');
    }

    /**
     * Final Quality Checks approved by Staff Export/Import
     */
    public function finalQualityChecks()
    {
        return $this->hasMany(FinalQualityCheck::class, 'staff_exim_id');
    }

    /**
     * Return Shipments handled by Warehouse Staff
     */
    public function returnShipments()
    {
        return $this->hasMany(ReturnShipment::class, 'warehouse_staff_id');
    }

    /**
     * Notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // ADDITIONAL RELATIONSHIPS FOR MASTER DATA
    
    /**
     * Master Customers created by this user
     */
    public function masterCustomersCreated()
    {
        return $this->hasMany(MasterCustomer::class, 'created_by');
    }

    /**
     * Master Products created by this user
     */
    public function masterProductsCreated()
    {
        return $this->hasMany(MasterProduk::class, 'created_by');
    }

    /**
     * Get user's current workload count
     */
    public function getCurrentWorkload()
    {
        $workload = 0;
        
        switch ($this->role) {
            case self::ROLE_STAFF_EXIM:
                $workload = $this->customerComplaints()->where('status', 'processing')->count() +
                           $this->finalQualityChecks()->where('status', 'pending')->count();
                break;
            case self::ROLE_WAREHOUSE_STAFF:
                $workload = $this->warehouseVerifications()->where('status', 'pending')->count() +
                           $this->returnShipments()->where('status', 'preparing')->count();
                break;
            case self::ROLE_QUALITY_MANAGER:
                $workload = $this->qualityReinspections()->where('status', 'pending')->count();
                break;
            case self::ROLE_PRODUCTION_MANAGER:
                $workload = $this->productionReworks()->where('status', 'in_progress')->count();
                break;
        }
        
        return $workload;
    }

    public function isQuality(): bool
    {
        return $this->role === self::ROLE_QUALITY;
    }

    /**
     * Check if user has specific role(s)
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }
        return in_array($this->role, $roles);
    }

    /**
     * Check if user can access a menu/feature
     */
    public function canAccess(string $menu): bool
    {
        // Admin can access everything
        if ($this->isAdmin()) {
            return true;
        }

        $accessMap = [
            // Dashboard - All authenticated users
            'dashboard' => ['admin', 'ppic', 'warehouse', 'quality'],
            
            // Data Master - Admin only by default, but allow reading for others
            'master-data' => ['admin'],
            
            // PPIC
            'ppic' => ['admin', 'ppic'],
            'rca-analysis' => ['admin', 'ppic'],
            'ppic-approval' => ['admin', 'ppic'],
            
            // Warehouse
            'warehouse' => ['admin', 'warehouse'],
            'penerimaan-barang' => ['admin', 'warehouse'],
            'retur-barang' => ['admin', 'warehouse'],
            'penyimpanan-ng' => ['admin', 'warehouse'],
            'scrap-disposal' => ['admin', 'warehouse'],
            'warehouse-approval' => ['admin', 'warehouse'],
            
            // Quality
            'quality' => ['admin', 'quality'],
            'inspeksi-qc' => ['admin', 'quality'],
            'quality-approval' => ['admin', 'quality'],
            
            // Return NG Workflow
            'return-ng' => ['admin', 'staff_exim', 'warehouse_staff', 'quality_manager', 'production_manager'],
            'customer-complaint' => ['admin', 'staff_exim'],
            'dokumen-retur' => ['admin', 'warehouse_staff'],
            'warehouse-verification' => ['admin', 'warehouse_staff'], 
            'quality-reinspection' => ['admin', 'quality_manager'],
            'production-rework' => ['admin', 'production_manager'],
            'final-quality-check' => ['admin', 'staff_exim'],
            'return-shipment' => ['admin', 'warehouse_staff'],
            'return-reports' => ['admin', 'staff_exim'],
            
            // Reports - accessible by related roles
            'reports' => ['admin', 'ppic', 'warehouse', 'quality'],
            'laporan-recap' => ['admin', 'ppic', 'warehouse', 'quality'],
            'vendor-scorecard' => ['admin', 'ppic', 'warehouse', 'quality'],
            
            // User Management - Admin only
            'user-management' => ['admin'],
        ];

        return isset($accessMap[$menu]) && in_array($this->role, $accessMap[$menu]);
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        $names = [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_STAFF_EXIM => 'Staff Export/Import',
            self::ROLE_WAREHOUSE_STAFF => 'Staff Warehouse',
            self::ROLE_QUALITY_MANAGER => 'Quality Manager',
            self::ROLE_PRODUCTION_MANAGER => 'Production Manager',
        ];
        return $names[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role));
    }

    /**
     * Get role badge color
     */
    public function getRoleBadgeColor(): string
    {
        $colors = [
            self::ROLE_ADMIN => 'danger',
            self::ROLE_STAFF_EXIM => 'primary',
            self::ROLE_WAREHOUSE_STAFF => 'warning',
            self::ROLE_QUALITY_MANAGER => 'info',
            self::ROLE_PRODUCTION_MANAGER => 'success',
            self::ROLE_PPIC => 'primary',
            self::ROLE_WAREHOUSE => 'success',
            self::ROLE_QUALITY => 'warning',
        ];
        return $colors[$this->role] ?? 'secondary';
    }

    /**
     * Scope: Get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->where('status', 'unread')->count();
    }
}
