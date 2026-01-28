<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDefect extends Model
{
    use SoftDeletes;

    protected $table = 'master_defects';

    protected $fillable = [
        'kode_defect',
        'nama_defect',
        'deskripsi',
        'criticality_level',
        'sumber_masalah',
        'solusi_standar',
        'is_rework_possible',
        'is_active',
    ];

    protected $casts = [
        'is_rework_possible' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCriticality($query, $level)
    {
        return $query->where('criticality_level', $level);
    }

    public function scopeReworkable($query)
    {
        return $query->where('is_rework_possible', true);
    }

    public function getCriticalityBadgeAttribute()
    {
        $badges = [
            'minor' => '<span class="badge bg-info">⚪ Minor</span>',
            'major' => '<span class="badge bg-warning">🟡 Major</span>',
            'critical' => '<span class="badge bg-danger">🔴 Critical</span>',
        ];
        return $badges[$this->criticality_level] ?? '';
    }

    // ===== RELATIONSHIPS =====

    /**
     * Defect has many quality inspection records
     */
    public function qualityInspections()
    {
        return $this->hasMany(QualityInspection::class, 'kode_defect', 'kode_defect');
    }

    /**
     * Defect has many RCA analyses
     */
    public function rcaAnalyses()
    {
        return $this->hasMany(RcaAnalysis::class, 'kode_defect', 'kode_defect');
    }
}
