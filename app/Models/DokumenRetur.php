<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DokumenRetur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_dokumen_retur',
        'customer_complaint_id',
        'nomor_referensi',
        'instruksi_retur',
        'dokumen_retur',
        'jenis_retur',
        'status',
        'tanggal_dokumen',
        'tanggal_kirim',
        'staff_exim_id',
        'catatan_tambahan'
    ];

    protected $casts = [
        'dokumen_retur' => 'array',
        'tanggal_dokumen' => 'datetime',
        'tanggal_kirim' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_dokumen_retur)) {
                $model->nomor_dokumen_retur = self::generateNomorDokumen();
            }
        });
    }

    public static function generateNomorDokumen()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'DR-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    public function customerComplaint()
    {
        return $this->belongsTo(CustomerComplaint::class);
    }

    public function staffExim()
    {
        return $this->belongsTo(User::class, 'staff_exim_id');
    }

    public function warehouseVerification()
    {
        return $this->hasOne(WarehouseVerification::class);
    }
    
    /**
     * Get the next workflow stage after dokumen retur
     */
    public function getNextStageData()
    {
        return $this->warehouseVerification;
    }
    
    /**
     * Master Product relationship
     */
    public function masterProduk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getJenisReturLabelAttribute()
    {
        $labels = [
            'full_return' => 'Retur Penuh',
            'partial_return' => 'Retur Sebagian',
            'replacement' => 'Penggantian',
            'credit_note' => 'Credit Note'
        ];
        return $labels[$this->jenis_retur] ?? 'Retur Penuh';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'sent_to_warehouse' => 'Dikirim ke Warehouse',
            'received_by_warehouse' => 'Diterima Warehouse'
        ];
        return $labels[$this->status] ?? 'Draft';
    }
}