<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryStock extends Model
{
    use SoftDeletes;

    protected $table = 'inventory_stocks';

    protected $fillable = [
        'product_id',
        'location_id',
        'quantity',
        'reserved_quantity',
        'available_quantity',
        'status',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'available_quantity' => 'integer',
    ];

    // ===== RELATIONSHIPS =====

    public function product()
    {
        return $this->belongsTo(MasterProduk::class, 'product_id');
    }

    public function location()
    {
        return $this->belongsTo(MasterLokasi::class, 'location_id');
    }

    // ===== SCOPES =====

    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeGood($query)
    {
        return $query->where('status', 'good');
    }

    public function scopeQuarantine($query)
    {
        return $query->where('status', 'quarantine');
    }

    // ===== ACCESSORS & MUTATORS =====

    protected function availableQuantity()
    {
        return $this->attributes['quantity'] - $this->attributes['reserved_quantity'];
    }

    public function getAvailableQuantityAttribute()
    {
        return max(0, $this->quantity - $this->reserved_quantity);
    }

    // ===== METHODS =====

    /**
     * Tambah stok barang
     */
    public function addStock($quantity, $notes = null)
    {
        $this->increment('quantity', $quantity);
        if ($notes) {
            $this->update(['notes' => $notes]);
        }
        return $this;
    }

    /**
     * Kurangi stok barang
     */
    public function reduceStock($quantity, $notes = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('Stok tidak cukup. Tersedia: ' . $this->quantity);
        }
        
        $this->decrement('quantity', $quantity);
        if ($notes) {
            $this->update(['notes' => $notes]);
        }
        return $this;
    }

    /**
     * Reserve stok (tidak bisa diambil sampai di-cancel atau confirm)
     */
    public function reserveStock($quantity)
    {
        if ($this->available_quantity < $quantity) {
            throw new \Exception('Stok tersedia tidak cukup untuk di-reserve. Tersedia: ' . $this->available_quantity);
        }
        
        $this->increment('reserved_quantity', $quantity);
        return $this;
    }

    /**
     * Cancel reserve
     */
    public function cancelReserve($quantity)
    {
        $this->decrement('reserved_quantity', $quantity);
        return $this;
    }

    /**
     * Ubah status (good → quarantine → scrap)
     */
    public function updateStatus($newStatus, $notes = null)
    {
        $this->update([
            'status' => $newStatus,
            'notes' => $notes ?? $this->notes,
        ]);
        return $this;
    }

    /**
     * Transfer stok ke lokasi lain
     */
    public function transferTo(MasterLokasi $newLocation, $quantity)
    {
        if ($this->available_quantity < $quantity) {
            throw new \Exception('Stok tersedia tidak cukup untuk transfer');
        }

        // Kurangi dari lokasi lama
        $this->reduceStock($quantity, "Transfer ke {$newLocation->nama_lokasi}");

        // Tambah atau buat di lokasi baru
        $existingStock = InventoryStock::where('product_id', $this->product_id)
            ->where('location_id', $newLocation->id)
            ->first();

        if ($existingStock) {
            $existingStock->addStock($quantity, "Transfer dari {$this->location->nama_lokasi}");
        } else {
            InventoryStock::create([
                'product_id' => $this->product_id,
                'location_id' => $newLocation->id,
                'quantity' => $quantity,
                'status' => $this->status,
                'notes' => "Transfer dari {$this->location->nama_lokasi}",
            ]);
        }

        return $this;
    }

    /**
     * Get stok overview untuk dashboard
     */
    public static function getInventoryOverview()
    {
        return [
            'total_quantity' => self::sum('quantity'),
            'available_quantity' => self::sum('available_quantity'),
            'reserved_quantity' => self::sum('reserved_quantity'),
            'quarantine_quantity' => self::where('status', 'quarantine')->sum('quantity'),
            'good_quantity' => self::where('status', 'good')->sum('quantity'),
            'total_locations' => self::distinct('location_id')->count(),
            'total_products' => self::distinct('product_id')->count(),
        ];
    }
};
