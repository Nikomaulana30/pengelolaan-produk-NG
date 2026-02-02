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

    // Relationships
    public function qualityReinspections()
    {
        return $this->hasMany(QualityReinspection::class, 'jenis_defect');
    }
    
    // TODO: ProductionRework doesn't have defect_type_id column
    // Production reworks are linked through QualityReinspection
    // public function productionReworks()
    // {
    //     return $this->hasMany(ProductionRework::class, 'defect_type_id');
    // }
    
    /**
     * Get defect occurrence statistics
     */
    public function getOccurrenceStatistics()
    {
        return [
            'total_occurrences' => $this->qualityReinspections()->count(),
            'critical_occurrences' => $this->qualityReinspections()->where('severity_level', 'critical')->count(),
            'rework_success_rate' => $this->calculateReworkSuccessRate(),
            'most_affected_products' => $this->getMostAffectedProducts(),
        ];
    }
    
    /**
     * Calculate rework success rate for this defect type
     */
    protected function calculateReworkSuccessRate()
    {
        $totalReworks = $this->productionReworks()->count();
        if ($totalReworks == 0) return 0;
        
        $successfulReworks = $this->productionReworks()
            ->whereHas('finalQualityCheck', function($q) {
                $q->where('keputusan_final', 'approved_for_shipment');
            })->count();
            
        return round(($successfulReworks / $totalReworks) * 100, 2);
    }
    
    /**
     * Get most affected products by this defect
     */
    protected function getMostAffectedProducts()
    {
        return $this->qualityReinspections()
            ->join('warehouse_verifications', 'quality_reinspections.warehouse_verification_id', '=', 'warehouse_verifications.id')
            ->join('dokumen_returs', 'warehouse_verifications.dokumen_retur_id', '=', 'dokumen_returs.id')
            ->join('customer_complaints', 'dokumen_returs.customer_complaint_id', '=', 'customer_complaints.id')
            ->select('customer_complaints.produk')
            ->groupBy('customer_complaints.produk')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->pluck('customer_complaints.produk')
            ->toArray();
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
