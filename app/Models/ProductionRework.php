<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionRework extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_rework',
        'quality_reinspection_id',
        'tanggal_mulai_rework',
        'tanggal_selesai_rework',
        'metode_rework',
        'deskripsi_rework',
        'instruksi_rework',
        'quantity_rework',
        'quantity_hasil_ok',
        'quantity_hasil_ng',
        'catatan_proses',
        'dokumen_proses',
        'estimasi_biaya',
        'actual_biaya',
        'estimasi_waktu_hari',
        'actual_waktu_hari',
        'status',
        'production_manager_id',
        'pic_rework'
    ];

    protected $casts = [
        'dokumen_proses' => 'array',
        'tanggal_mulai_rework' => 'datetime',
        'tanggal_selesai_rework' => 'datetime',
        'estimasi_biaya' => 'decimal:2',
        'actual_biaya' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_rework)) {
                $model->nomor_rework = self::generateNomorRework();
            }
        });
    }

    public static function generateNomorRework()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'PR-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    public function qualityReinspection()
    {
        return $this->belongsTo(QualityReinspection::class);
    }

    public function productionManager()
    {
        return $this->belongsTo(User::class, 'production_manager_id');
    }

    public function finalQualityCheck()
    {
        return $this->hasOne(FinalQualityCheck::class);
    }
    
    /**
     * Get original customer complaint through the chain
     */
    public function getOriginalComplaint()
    {
        return $this->qualityReinspection?->warehouseVerification?->dokumenRetur?->customerComplaint;
    }
    
    /**
     * PIC Rework User relationship
     */
    public function picReworkUser()
    {
        return $this->belongsTo(User::class, 'pic_rework');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getMetodeReworkLabelAttribute()
    {
        $labels = [
            'melting' => 'Melting',
            'welding' => 'Welding',
            'machining' => 'Machining',
            'surface_treatment' => 'Surface Treatment',
            'assembly' => 'Assembly',
            'other' => 'Lainnya'
        ];
        return $labels[$this->metode_rework] ?? 'Melting';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            'sent_to_quality_check' => 'Dikirim untuk Quality Check'
        ];
        return $labels[$this->status] ?? 'Draft';
    }

    public function getDurasiReworkAttribute()
    {
        if (!$this->tanggal_mulai_rework) return null;
        
        $endDate = $this->tanggal_selesai_rework ?? now();
        return $this->tanggal_mulai_rework->diffInDays($endDate);
    }

    public function getSuccessRateAttribute()
    {
        if ($this->quantity_rework == 0) return 0;
        return round(($this->quantity_hasil_ok ?? 0) / $this->quantity_rework * 100, 2);
    }

    public function getBiayaVarianceAttribute()
    {
        if (!$this->estimasi_biaya || !$this->actual_biaya) return null;
        return $this->actual_biaya - $this->estimasi_biaya;
    }

    public function getWaktuVarianceAttribute()
    {
        if (!$this->estimasi_waktu_hari || !$this->actual_waktu_hari) return null;
        return $this->actual_waktu_hari - $this->estimasi_waktu_hari;
    }
}