<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDefectType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_defect',
        'nama_defect',
        'deskripsi',
        'kategori',
        'severity',
        'measurement_method',
        'standard_action',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    // Relationships
    public function qualityReinspections()
    {
        return $this->hasMany(QualityReinspection::class, 'defect_type_id');
    }

    // Helper methods
    public function getSeverityBadgeAttribute()
    {
        switch ($this->severity) {
            case 'critical':
                return 'badge-danger';
            case 'major':
                return 'badge-warning';
            case 'minor':
                return 'badge-info';
            default:
                return 'badge-secondary';
        }
    }

    public function getKategoriBadgeAttribute()
    {
        switch ($this->kategori) {
            case 'appearance':
                return 'badge-primary';
            case 'dimension':
                return 'badge-success';
            case 'function':
                return 'badge-warning';
            case 'material':
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }
}
