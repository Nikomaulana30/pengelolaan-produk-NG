<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterRcaCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_rca',
        'nama_kategori',
        'deskripsi',
        'area',
        'investigation_guide',
        'standard_corrective_action',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Constants for RCA Areas (5M+1E)
    const AREA_MAN = 'man';
    const AREA_MACHINE = 'machine';
    const AREA_METHOD = 'method';
    const AREA_MATERIAL = 'material';
    const AREA_ENVIRONMENT = 'environment';
    const AREA_MEASUREMENT = 'measurement';

    public static function getAreas()
    {
        return [
            self::AREA_MAN => 'Man (People)',
            self::AREA_MACHINE => 'Machine (Equipment)',
            self::AREA_METHOD => 'Method (Process)',
            self::AREA_MATERIAL => 'Material (Raw Material)',
            self::AREA_ENVIRONMENT => 'Environment (Condition)',
            self::AREA_MEASUREMENT => 'Measurement (Inspection)'
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByArea($query, $area)
    {
        return $query->where('area', $area);
    }

    // Relationships
    public function qualityReinspections()
    {
        return $this->hasMany(QualityReinspection::class, 'rca_category_id');
    }

    // Helper methods
    public function getAreaDisplayAttribute()
    {
        return self::getAreas()[$this->area] ?? $this->area;
    }

    public function getAreaBadgeAttribute()
    {
        $badgeMap = [
            self::AREA_MAN => 'badge-primary',
            self::AREA_MACHINE => 'badge-danger',
            self::AREA_METHOD => 'badge-warning',
            self::AREA_MATERIAL => 'badge-success',
            self::AREA_ENVIRONMENT => 'badge-info',
            self::AREA_MEASUREMENT => 'badge-secondary'
        ];
        return $badgeMap[$this->area] ?? 'badge-secondary';
    }

    public function getInvestigationStepsAttribute()
    {
        if (!$this->investigation_guide) return [];
        return explode('\n', $this->investigation_guide);
    }
}
