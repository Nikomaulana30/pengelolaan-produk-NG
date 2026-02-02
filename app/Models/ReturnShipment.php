<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnShipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_pengiriman',
        'final_quality_check_id',
        'tanggal_pengiriman',
        'quantity_shipped',
        'ekspedisi',
        'nomor_resi',
        'alamat_pengiriman',
        'catatan_pengiriman',
        'dokumen_pengiriman',
        'biaya_pengiriman',
        'status_pengiriman',
        'status',
        'warehouse_staff_id',
        'delivered_at',
        'catatan_delivery',
        'rating_customer'
    ];

    protected $casts = [
        'dokumen_pengiriman' => 'array',
        'tanggal_pengiriman' => 'datetime',
        'delivered_at' => 'datetime',
        'biaya_pengiriman' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_pengiriman)) {
                $model->nomor_pengiriman = self::generateNomorPengiriman();
            }
        });
    }

    public static function generateNomorPengiriman()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'RS-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    public function finalQualityCheck()
    {
        return $this->belongsTo(FinalQualityCheck::class);
    }

    public function warehouseStaff()
    {
        return $this->belongsTo(User::class, 'warehouse_staff_id');
    }

    /**
     * Get original customer complaint through the complete chain
     */
    public function getOriginalComplaint()
    {
        return $this->finalQualityCheck?->productionRework?->qualityReinspection?->warehouseVerification?->dokumenRetur?->customerComplaint;
    }
    
    /**
     * Get complete tracking information
     */
    public function getCompleteTrackingInfo()
    {
        $complaint = $this->getOriginalComplaint();
        
        return [
            'complaint_number' => $complaint?->nomor_complaint,
            'customer_name' => $complaint?->nama_customer,
            'product' => $complaint?->produk,
            'shipment_number' => $this->nomor_pengiriman,
            'tracking_number' => $this->nomor_resi,
            'status' => $this->status_pengiriman,
            'delivery_address' => $this->alamat_pengiriman,
            'shipped_at' => $this->tanggal_pengiriman,
            'delivered_at' => $this->delivered_at,
        ];
    }
    
    /**
     * Master Customer through the chain
     */
    public function getMasterCustomer()
    {
        return $this->getOriginalComplaint()?->masterCustomer;
    }

    public function scopeShipped($query)
    {
        return $query->where('status_pengiriman', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status_pengiriman', 'delivered');
    }

    public function scopePending($query)
    {
        return $query->where('status_pengiriman', 'preparing');
    }
    
    public function scopeByCustomer($query, $customerId)
    {
        return $query->whereHas('finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint', function($q) use ($customerId) {
            $q->where('master_customer_id', $customerId);
        });
    }
    
    public function scopeByProduct($query, $productId)
    {
        return $query->whereHas('finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint', function($q) use ($productId) {
            $q->where('produk_id', $productId);
        });
    }
    
    public function scopeWithCompleteChain($query)
    {
        return $query->with([
            'finalQualityCheck.productionRework.qualityReinspection.warehouseVerification.dokumenRetur.customerComplaint.masterCustomer',
            'masterCustomer'
        ]);
    }

    // Accessors
    public function getStatusPengirimanLabelAttribute()
    {
        $labels = [
            'preparing' => 'Mempersiapkan',
            'shipped' => 'Dikirim',
            'delivered' => 'Terkirim',
            'failed' => 'Gagal'
        ];
        return $labels[$this->status_pengiriman] ?? 'Mempersiapkan';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai'
        ];
        return $labels[$this->status] ?? 'Draft';
    }

    public function getDeliveryDurationAttribute()
    {
        if (!$this->tanggal_pengiriman || !$this->delivered_at) return null;
        return $this->tanggal_pengiriman->diffInDays($this->delivered_at);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'preparing' => 'blue',
            'shipped' => 'orange',
            'delivered' => 'green',
            'failed' => 'red'
        ];
        return $colors[$this->status_pengiriman] ?? 'blue';
    }

    public function getRatingStarsAttribute()
    {
        if (!$this->rating_customer) return '';
        return str_repeat('⭐', $this->rating_customer);
    }
}