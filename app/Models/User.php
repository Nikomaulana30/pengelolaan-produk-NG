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

    /**
     * Check if user is Quality
     */
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
        return match($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_PPIC => 'PPIC',
            self::ROLE_WAREHOUSE => 'Warehouse',
            self::ROLE_QUALITY => 'Quality',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get role badge color
     */
    public function getRoleBadgeColor(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'danger',
            self::ROLE_PPIC => 'primary',
            self::ROLE_WAREHOUSE => 'success',
            self::ROLE_QUALITY => 'warning',
            default => 'secondary',
        };
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
     * Relationship: User has many notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCount()
    {
        return $this->notifications()->where('status', 'unread')->count();
    }
}
