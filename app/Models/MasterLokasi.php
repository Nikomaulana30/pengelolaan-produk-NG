<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterLokasi extends Model
{
    use SoftDeletes;

    protected $table = 'master_lokasis';

    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'zona_gudang',
        'rack',
        'bin',
        'tipe_lokasi',
        'status_lokasi',
        'kapasitas_maksimal',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kapasitas_maksimal' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status_lokasi', 'available');
    }

    public function scopeByZona($query, $zona)
    {
        return $query->where('zona_gudang', $zona);
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_lokasi', $tipe);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'available' => '<span class="badge bg-success">✓ Available</span>',
            'full' => '<span class="badge bg-danger">✗ Full</span>',
            'maintenance' => '<span class="badge bg-warning">⚙ Maintenance</span>',
            'blocked' => '<span class="badge bg-secondary">⛔ Blocked</span>',
        ];
        return $badges[$this->status_lokasi] ?? '';
    }

    // ===== RELATIONSHIPS =====

    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class, 'location_id');
    }

    public function products()
    {
        return $this->hasManyThrough(
            MasterProduk::class,
            InventoryStock::class,
            'location_id',
            'id',
            'id',
            'product_id'
        );
    }

    // ===== METHODS =====

    /**
     * Get total stok di lokasi ini
     */
    public function getTotalStock()
    {
        return $this->inventoryStocks()->sum('quantity');
    }

    /**
     * Get stok tersedia (baik)
     */
    public function getAvailableStock()
    {
        return $this->inventoryStocks()
            ->where('status', 'good')
            ->sum('available_quantity');
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

    /**
     * Check jika lokasi masih punya kapasitas
     */
    public function hasCapacity()
    {
        if (!$this->kapasitas_maksimal) {
            return true;
        }
        return $this->getTotalStock() < $this->kapasitas_maksimal;
    }
}
