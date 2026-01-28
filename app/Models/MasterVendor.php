<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterVendor extends Model
{
    use SoftDeletes;

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
    ];

    protected $casts = [
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

    public function returBarangs()
    {
        return $this->hasMany(ReturBarang::class, 'vendor_id');
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
