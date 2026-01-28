<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterProduk extends Model
{
    use SoftDeletes;

    protected $table = 'master_products';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori',
        'unit',
        'harga',
        'vendor_id',
        'spesifikasi',
        'drawing_file',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // ===== RELATIONSHIPS =====

    /**
     * Produk belongs to Vendor
     */
    public function vendor()
    {
        return $this->belongsTo(MasterVendor::class, 'vendor_id');
    }

    /**
     * Produk has many inspection records
     */
    public function inspeksi()
    {
        return $this->hasMany(QualityInspection::class, 'kode_barang', 'kode_produk');
    }

    /**
     * Produk has many return records
     */
    public function returBarangs()
    {
        return $this->hasMany(ReturBarang::class, 'produk_id');
    }

    /**
     * Produk has many RCA analyses
     */
    public function rcaAnalyses()
    {
        return $this->hasMany(RcaAnalysis::class, 'kode_barang', 'kode_produk');
    }

    /**
     * Produk has many scrap disposal records
     */
    public function scrapDisposals()
    {
        return $this->hasMany(ScrapDisposal::class, 'nama_barang', 'nama_produk');
    }

    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class, 'product_id');
    }

    public function locations()
    {
        return $this->hasManyThrough(
            MasterLokasi::class,
            InventoryStock::class,
            'product_id',
            'id',
            'id',
            'location_id'
        );
    }

    // ===== METHODS =====

    /**
     * Get total stok produk di semua lokasi
     */
    public function getTotalStock()
    {
        return $this->inventoryStocks()->sum('quantity');
    }

    /**
     * Get stok tersedia (tidak di-reserve)
     */
    public function getAvailableStock()
    {
        return $this->inventoryStocks()->sum('available_quantity');
    }

    /**
     * Get stok quarantine
     */
    public function getQuarantineStock()
    {
        return $this->inventoryStocks()
            ->where('status', 'quarantine')
            ->sum('quantity');
    }
}
