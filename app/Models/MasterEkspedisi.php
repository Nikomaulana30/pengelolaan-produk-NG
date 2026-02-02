<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterEkspedisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ekspedisi',
        'nama_ekspedisi',
        'contact_person',
        'telepon',
        'email',
        'alamat',
        'service_types',
        'base_rate_per_kg',
        'has_tracking',
        'tracking_url_pattern',
        'is_active'
    ];

    protected $casts = [
        'service_types' => 'array',
        'base_rate_per_kg' => 'decimal:2',
        'has_tracking' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function returnShipments()
    {
        return $this->hasMany(ReturnShipment::class, 'master_ekspedisi_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithTracking($query)
    {
        return $query->where('has_tracking', true);
    }

    // Helper Methods
    public function getFormattedRateAttribute()
    {
        return 'Rp ' . number_format($this->base_rate_per_kg, 0, ',', '.') . '/kg';
    }

    public function getServiceTypesList()
    {
        return $this->service_types ? implode(', ', $this->service_types) : '-';
    }

    public function canTrackShipment()
    {
        return $this->has_tracking && !empty($this->tracking_url_pattern);
    }

    public function generateTrackingUrl($trackingNumber)
    {
        if (!$this->canTrackShipment()) {
            return null;
        }
        
        return str_replace('{tracking_number}', $trackingNumber, $this->tracking_url_pattern);
    }

    public function getShipmentCount()
    {
        return $this->returnShipments()->count();
    }

    public function getDeliveredShipmentCount()
    {
        return $this->returnShipments()->where('status', 'delivered')->count();
    }

    public function getDeliverySuccessRate()
    {
        $total = $this->getShipmentCount();
        $delivered = $this->getDeliveredShipmentCount();
        
        return $total > 0 ? round(($delivered / $total) * 100, 2) : 0;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ekspedisi) {
            if (empty($ekspedisi->kode_ekspedisi)) {
                $ekspedisi->kode_ekspedisi = static::generateKodeEkspedisi();
            }
        });
    }

    public static function generateKodeEkspedisi()
    {
        $prefix = 'EXP';
        $year = date('Y');
        
        $lastEkspedisi = static::whereYear('created_at', $year)
                              ->orderBy('id', 'desc')
                              ->first();
        
        $sequence = $lastEkspedisi ? (int)substr($lastEkspedisi->kode_ekspedisi, -4) + 1 : 1;
        
        return $prefix . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
