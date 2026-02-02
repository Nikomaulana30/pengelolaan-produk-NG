<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualityReinspection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_inspeksi',
        'warehouse_verification_id',
        'tanggal_inspeksi',
        'jenis_defect',
        'deskripsi_defect',
        'severity_level',
        'quantity_defect',
        'root_cause_analysis',
        'corrective_action',
        'preventive_action',
        'disposisi',
        'foto_defect',
        'dokumen_rca',
        'status',
        'quality_manager_id',
        'inspected_at',
        'estimasi_biaya_rework'
    ];

    protected $casts = [
        'foto_defect' => 'array',
        'dokumen_rca' => 'array',
        'tanggal_inspeksi' => 'datetime',
        'inspected_at' => 'datetime',
        'estimasi_biaya_rework' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_inspeksi)) {
                $model->nomor_inspeksi = self::generateNomorInspeksi();
            }
        });
    }

    public static function generateNomorInspeksi()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'QR-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    public function warehouseVerification()
    {
        return $this->belongsTo(WarehouseVerification::class);
    }

    public function qualityManager()
    {
        return $this->belongsTo(User::class, 'quality_manager_id');
    }

    public function productionRework()
    {
        return $this->hasOne(ProductionRework::class);
    }
    
    /**
     * Master Defect relationship
     */
    public function masterDefect()
    {
        return $this->belongsTo(MasterDefect::class, 'jenis_defect');
    }
    
    /**
     * Master Disposisi relationship
     */
    public function masterDisposisi()
    {
        return $this->belongsTo(MasterDisposisi::class, 'disposisi');
    }
    
    /**
     * Get original customer complaint
     */
    public function getOriginalComplaint()
    {
        return $this->warehouseVerification?->dokumenRetur?->customerComplaint;
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity_level', $severity);
    }

    public function scopeByDisposisi($query, $disposisi)
    {
        return $query->where('disposisi', $disposisi);
    }

    // Accessors
    public function getSeverityLevelLabelAttribute()
    {
        $labels = [
            'critical' => 'Kritikal',
            'major' => 'Mayor',
            'minor' => 'Minor'
        ];
        return $labels[$this->severity_level] ?? 'Mayor';
    }

    public function getDisposisiLabelAttribute()
    {
        $labels = [
            'rework' => 'Rework',
            'scrap' => 'Scrap',
            'return_to_vendor' => 'Retur ke Vendor',
            'return_to_customer' => 'Retur ke Customer'
        ];
        return $labels[$this->disposisi] ?? 'Rework';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'inspected' => 'Telah Diinspeksi',
            'sent_to_production' => 'Dikirim ke Produksi'
        ];
        return $labels[$this->status] ?? 'Draft';
    }

    public function getSeverityColorAttribute()
    {
        $colors = [
            'critical' => 'red',
            'major' => 'orange',
            'minor' => 'yellow'
        ];
        return $colors[$this->severity_level] ?? 'orange';
    }
}
