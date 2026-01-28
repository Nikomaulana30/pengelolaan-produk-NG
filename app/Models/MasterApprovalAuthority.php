<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterApprovalAuthority extends Model
{
    use SoftDeletes;

    protected $table = 'master_approval_authorities';

    protected $fillable = [
        'user_id',
        'departemen',
        'role_level',
        'approval_limit',
        'jenis_approval',
        'can_approve_self',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'can_approve_self' => 'boolean',
        'is_active' => 'boolean',
        'approval_limit' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDepartemen($query, $departemen)
    {
        return $query->where('departemen', $departemen);
    }

    public function scopeByJenisApproval($query, $jenis)
    {
        return $query->where('jenis_approval', $jenis);
    }

    public function getDepartemenBadgeAttribute()
    {
        $badges = [
            'warehouse' => '<span class="badge bg-primary">📦 Warehouse</span>',
            'quality' => '<span class="badge bg-success">✓ Quality</span>',
            'ppic' => '<span class="badge bg-info">📊 PPIC</span>',
        ];
        return $badges[$this->departemen] ?? '';
    }

    public function getRoleLevelBadgeAttribute()
    {
        $badges = [
            'supervisor' => '<span class="badge bg-secondary">👤 Supervisor</span>',
            'manager' => '<span class="badge bg-warning">👥 Manager</span>',
            'director' => '<span class="badge bg-danger">👑 Director</span>',
        ];
        return $badges[$this->role_level] ?? '';
    }
}
