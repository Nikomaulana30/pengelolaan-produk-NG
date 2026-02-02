<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterKondisiBarang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_kondisi',
        'nama_kondisi',
        'deskripsi',
        'color_code',
        'is_ng',
        'can_rework',
        'is_active'
    ];

    protected $casts = [
        'is_ng' => 'boolean',
        'can_rework' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNgConditions($query)
    {
        return $query->where('is_ng', true);
    }

    public function scopeReworkable($query)
    {
        return $query->where('can_rework', true);
    }

    // Relationships
    public function warehouseVerifications()
    {
        return $this->hasMany(WarehouseVerification::class, 'kondisi_barang_id');
    }

    // Helper methods
    public function getStatusBadgeAttribute()
    {
        if ($this->is_ng) {
            return $this->can_rework ? 'badge-warning' : 'badge-danger';
        }
        return 'badge-success';
    }

    public function getActionableAttribute()
    {
        return $this->is_ng && $this->can_rework;
    }

    public function getStatusTextAttribute()
    {
        if ($this->is_ng) {
            return $this->can_rework ? 'NG - Reworkable' : 'NG - Scrap';
        }
        return 'Good Condition';
    }
}
