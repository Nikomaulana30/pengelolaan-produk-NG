<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinalQualityCheck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_final_check',
        'production_rework_id',
        'tanggal_check',
        'quantity_checked',
        'quantity_passed',
        'quantity_failed',
        'hasil_pemeriksaan',
        'catatan_quality',
        'keputusan_final',
        'foto_hasil_check',
        'dokumen_quality',
        'status',
        'staff_exim_id',
        'approved_at'
    ];

    protected $casts = [
        'foto_hasil_check' => 'array',
        'dokumen_quality' => 'array',
        'tanggal_check' => 'datetime',
        'approved_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_final_check)) {
                $model->nomor_final_check = self::generateNomorFinalCheck();
            }
        });
    }

    public static function generateNomorFinalCheck()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'FQ-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    public function productionRework()
    {
        return $this->belongsTo(ProductionRework::class);
    }

    public function staffExim()
    {
        return $this->belongsTo(User::class, 'staff_exim_id');
    }

    public function returnShipment()
    {
        return $this->hasOne(ReturnShipment::class);
    }
    
    /**
     * Get original customer complaint through the chain
     */
    public function getOriginalComplaint()
    {
        return $this->productionRework?->qualityReinspection?->warehouseVerification?->dokumenRetur?->customerComplaint;
    }
    
    /**
     * Check if item can proceed to shipment
     */
    public function canProceedToShipment()
    {
        return $this->keputusan_final === 'approved_for_shipment' && 
               $this->status === 'approved';
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeApproved($query)
    {
        return $query->where('keputusan_final', 'approved_for_shipment');
    }

    public function scopeRejected($query)
    {
        return $query->where('keputusan_final', 'rejected');
    }

    // Accessors
    public function getKeputusanFinalLabelAttribute()
    {
        $labels = [
            'approved_for_shipment' => 'Disetujui untuk Pengiriman',
            'need_rework' => 'Perlu Rework',
            'rejected' => 'Ditolak'
        ];
        return $labels[$this->keputusan_final] ?? 'Perlu Rework';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'checked' => 'Telah Diperiksa',
            'approved_for_shipment' => 'Disetujui untuk Pengiriman'
        ];
        return $labels[$this->status] ?? 'Draft';
    }

    public function getPassRateAttribute()
    {
        if ($this->quantity_checked == 0) return 0;
        return round(($this->quantity_passed / $this->quantity_checked) * 100, 2);
    }

    public function getKeputusanColorAttribute()
    {
        $colors = [
            'approved_for_shipment' => 'green',
            'need_rework' => 'orange',
            'rejected' => 'red'
        ];
        return $colors[$this->keputusan_final] ?? 'orange';
    }
}