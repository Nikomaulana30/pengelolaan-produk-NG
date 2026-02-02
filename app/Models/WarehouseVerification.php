<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseVerification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nomor_verifikasi',
        'dokumen_retur_id',
        'tanggal_terima',
        'quantity_diterima',
        'quantity_ng_confirmed',
        'quantity_ok',
        'kondisi_fisik_barang',
        'catatan_penerimaan',
        'foto_barang_ng',
        'lokasi_penyimpanan',
        'master_lokasi_gudang_id',
        'status_verifikasi',
        'status',
        'warehouse_staff_id',
        'verified_at'
    ];

    protected $casts = [
        'foto_barang_ng' => 'array',
        'tanggal_terima' => 'datetime',
        'verified_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_verifikasi)) {
                $model->nomor_verifikasi = self::generateNomorVerifikasi();
            }
        });
    }

    public static function generateNomorVerifikasi()
    {
        $today = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'WV-' . $today . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function dokumenRetur()
    {
        return $this->belongsTo(DokumenRetur::class);
    }

    public function warehouseStaff()
    {
        return $this->belongsTo(User::class, 'warehouse_staff_id');
    }

    public function qualityReinspection()
    {
        return $this->hasOne(QualityReinspection::class);
    }
    
    /**
     * Get the customer complaint through dokumen retur
     */
    public function customerComplaint()
    {
        return $this->hasOneThrough(
            CustomerComplaint::class,
            DokumenRetur::class,
            'id',
            'id',
            'dokumen_retur_id',
            'customer_complaint_id'
        );
    }
    
    /**
     * Master Lokasi Gudang relationship
     */
    public function lokasiGudang()
    {
        return $this->belongsTo(MasterLokasiGudang::class, 'master_lokasi_gudang_id');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    // Accessors
    public function getStatusVerifikasiLabelAttribute()
    {
        $labels = [
            'received' => 'Diterima',
            'verified' => 'Diverifikasi',
            'forwarded_to_quality' => 'Diteruskan ke Quality'
        ];
        return $labels[$this->status_verifikasi] ?? 'Diterima';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'draft' => 'Draft',
            'verified' => 'Diverifikasi',
            'sent_to_quality' => 'Dikirim ke Quality'
        ];
        return $labels[$this->status] ?? 'Draft';
    }

    public function getTotalQuantityAttribute()
    {
        return $this->quantity_ng_confirmed + $this->quantity_ok;
    }

    public function getDefectRateAttribute()
    {
        if ($this->quantity_diterima == 0) return 0;
        return round(($this->quantity_ng_confirmed / $this->quantity_diterima) * 100, 2);
    }
}
