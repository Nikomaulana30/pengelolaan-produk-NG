<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_vendors';

    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'alamat_vendor',
        'kota',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'person_in_charge',
        'kebijakan_retur',
        'deskripsi',
        'is_active',
        // Return NG workflow fields
        'email_vendor',
        'telepon_vendor',
        'contact_person',
        'kategori_vendor',
        'quality_rating',
        'delivery_rating',
        'service_rating',
        'status_vendor',
        'contract_start',
        'contract_end'
    ];

    protected $casts = [
        'quality_rating' => 'decimal:2',
        'delivery_rating' => 'decimal:2',
        'service_rating' => 'decimal:2',
        'contract_start' => 'date',
        'contract_end' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    /**
     * Vendor has many products
     */
    public function produks()
    {
        return $this->hasMany(MasterProduk::class, 'vendor_id');
    }

    // TODO: Uncomment when ReturBarang model is created
    // public function returBarangs()
    // {
    //     return $this->hasMany(ReturBarang::class, 'vendor_id');
    // }

    public function qualityReinspections()
    {
        return $this->hasMany(QualityReinspection::class, 'vendor_id');
    }

    // Constants for Return NG workflow
    const KATEGORI_MATERIAL = 'material';
    const KATEGORI_COMPONENT = 'component';
    const KATEGORI_SERVICE = 'service';
    const KATEGORI_LOGISTIC = 'logistic';

    const STATUS_ACTIVE = 'active';
    const STATUS_HOLD = 'hold';
    const STATUS_BLACKLIST = 'blacklist';

    public static function getKategoris()
    {
        return [
            self::KATEGORI_MATERIAL => 'Material Supplier',
            self::KATEGORI_COMPONENT => 'Component Supplier',
            self::KATEGORI_SERVICE => 'Service Provider',
            self::KATEGORI_LOGISTIC => 'Logistic Partner'
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_HOLD => 'On Hold',
            self::STATUS_BLACKLIST => 'Blacklisted'
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori_vendor', $kategori);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status_vendor', $status);
    }

    public function scopeGoodPerformance($query)
    {
        return $query->where('quality_rating', '>=', 4.0)
                    ->where('delivery_rating', '>=', 4.0)
                    ->where('service_rating', '>=', 4.0);
    }

    // Helper methods
    public function getKategoriDisplayAttribute()
    {
        return self::getKategoris()[$this->kategori_vendor] ?? $this->kategori_vendor;
    }

    public function getStatusDisplayAttribute()
    {
        return self::getStatuses()[$this->status_vendor] ?? $this->status_vendor;
    }

    public function getStatusBadgeAttribute()
    {
        $badgeMap = [
            self::STATUS_ACTIVE => 'badge-success',
            self::STATUS_HOLD => 'badge-warning',
            self::STATUS_BLACKLIST => 'badge-danger'
        ];
        return $badgeMap[$this->status_vendor] ?? 'badge-secondary';
    }

    public function getOverallRatingAttribute()
    {
        if (!$this->quality_rating || !$this->delivery_rating || !$this->service_rating) {
            return 0;
        }
        return round(($this->quality_rating + $this->delivery_rating + $this->service_rating) / 3, 2);
    }

    public function getContractStatusAttribute()
    {
        if (!$this->contract_end) return 'No expiry';
        
        $today = now();
        $daysToExpiry = $today->diffInDays($this->contract_end, false);
        
        if ($daysToExpiry < 0) return 'Expired';
        if ($daysToExpiry <= 30) return 'Expiring Soon';
        return 'Active';
    }

    public function isContractExpiringSoon()
    {
        return $this->contract_end && now()->diffInDays($this->contract_end, false) <= 30;
    }

    /**
     * Vendor has many quality inspections through products
     */
    public function qualityInspections()
    {
        return $this->hasManyThrough(
            QualityInspection::class,
            MasterProduk::class,
            'vendor_id',        // FK on master_products
            'kode_barang',      // FK on quality_inspections
            'id',               // Local key on master_vendors
            'kode_produk'       // Local key on master_products
        );
    }

    public function getKebijanPolicyBadgeAttribute()
    {
        $badges = [
            'retur_fisik' => '<span class="badge bg-primary">📦 Retur Fisik</span>',
            'debit_note' => '<span class="badge bg-info">📄 Debit Note</span>',
            'keduanya' => '<span class="badge bg-success">📦📄 Keduanya</span>',
        ];
        return $badges[$this->kebijakan_retur] ?? '';
    }
}
