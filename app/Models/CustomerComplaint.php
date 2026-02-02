<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerComplaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_complaint',
        'nama_customer',
        'email_customer',
        'telepon_customer',
        'alamat_customer',
        'produk',
        'quantity_ng',
        'deskripsi_complaint',
        'foto_complaint',
        'dokumen_pendukung',
        'priority',
        'status',
        'tanggal_complaint',
        'staff_exim_id',
        'catatan_staff'
    ];

    protected $casts = [
        'foto_complaint' => 'array',
        'dokumen_pendukung' => 'array',
        'tanggal_complaint' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_complaint)) {
                $model->nomor_complaint = self::generateNomorComplaint();
            }
        });
    }

    public static function generateNomorComplaint()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'CC-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    public function staffExim()
    {
        return $this->belongsTo(User::class, 'staff_exim_id');
    }

    public function dokumenRetur()
    {
        return $this->hasOne(DokumenRetur::class);
    }
    
    /**
     * Get the complete workflow chain for this complaint
     */
    public function getWorkflowChain()
    {
        return [
            'complaint' => $this,
            'dokumen_retur' => $this->dokumenRetur,
            'warehouse_verification' => $this->dokumenRetur?->warehouseVerification,
            'quality_reinspection' => $this->dokumenRetur?->warehouseVerification?->qualityReinspection,
            'production_rework' => $this->dokumenRetur?->warehouseVerification?->qualityReinspection?->productionRework,
            'final_quality_check' => $this->dokumenRetur?->warehouseVerification?->qualityReinspection?->productionRework?->finalQualityCheck,
            'return_shipment' => $this->dokumenRetur?->warehouseVerification?->qualityReinspection?->productionRework?->finalQualityCheck?->returnShipment,
        ];
    }
    
    /**
     * Get the current workflow stage
     */
    public function getCurrentStage()
    {
        $chain = $this->getWorkflowChain();
        
        if (!$chain['dokumen_retur']) return 'complaint';
        if (!$chain['warehouse_verification']) return 'dokumen_retur';
        if (!$chain['quality_reinspection']) return 'warehouse_verification';
        if (!$chain['production_rework']) return 'quality_reinspection';
        if (!$chain['final_quality_check']) return 'production_rework';
        if (!$chain['return_shipment']) return 'final_quality_check';
        
        return 'completed';
    }

    /**
     * Master Customer relationship (if exists)
     */
    public function masterCustomer()
    {
        return $this->belongsTo(MasterCustomer::class, 'master_customer_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now());
    }

    // Accessors & Mutators
    public function getPriorityLabelAttribute()
    {
        $labels = [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'critical' => 'Kritikal'
        ];
        return $labels[$this->priority] ?? 'Sedang';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];
        return $labels[$this->status] ?? 'Draft';
    }
}
