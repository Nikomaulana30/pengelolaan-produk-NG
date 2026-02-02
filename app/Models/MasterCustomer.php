<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_customer',
        'nama_customer', 
        'email_customer',
        'telepon_customer',
        'alamat_customer',
        'kategori_customer',
        'payment_terms',
        'credit_limit',
        'contact_person',
        'phone_contact_person',
        'is_active'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function customerComplaints()
    {
        return $this->hasMany(CustomerComplaint::class, 'master_customer_id');
    }

    public function dokumentReturs()
    {
        return $this->hasMany(DokumenRetur::class, 'master_customer_id');
    }

    public function returnShipments()
    {
        return $this->hasMany(ReturnShipment::class, 'master_customer_id');
    }
    
    /**
     * Get all return activities for this customer
     */
    public function getAllReturnActivities()
    {
        return $this->customerComplaints()
                    ->with([
                        'dokumenRetur.warehouseVerification.qualityReinspection.productionRework.finalQualityCheck.returnShipment'
                    ])
                    ->orderBy('created_at', 'desc');
    }
    
    /**
     * Get customer statistics
     */
    public function getStatistics()
    {
        return [
            'total_complaints' => $this->customerComplaints()->count(),
            'completed_returns' => $this->returnShipments()->where('status_pengiriman', 'delivered')->count(),
            'pending_complaints' => $this->customerComplaints()->where('status', 'processing')->count(),
            'average_resolution_time' => $this->getAverageResolutionTime(),
        ];
    }
    
    /**
     * Calculate average resolution time
     */
    protected function getAverageResolutionTime()
    {
        $completedReturns = $this->customerComplaints()
            ->whereHas('dokumenRetur.warehouseVerification.qualityReinspection.productionRework.finalQualityCheck.returnShipment', function($q) {
                $q->where('status_pengiriman', 'delivered');
            })
            ->get();
            
        if ($completedReturns->isEmpty()) return 0;
        
        $totalDays = $completedReturns->sum(function($complaint) {
            $returnShipment = $complaint->dokumenRetur?->warehouseVerification?->qualityReinspection?->productionRework?->finalQualityCheck?->returnShipment;
            if (!$returnShipment?->delivered_at) return 0;
            return $complaint->created_at->diffInDays($returnShipment->delivered_at);
        });
        
        return round($totalDays / $completedReturns->count(), 2);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori_customer', $kategori);
    }

    // Helper Methods
    public function getFormattedCreditLimitAttribute()
    {
        return 'Rp ' . number_format($this->credit_limit, 0, ',', '.');
    }

    public function isVipCustomer()
    {
        return $this->kategori_customer === 'vip';
    }

    public function getTotalComplaints()
    {
        return $this->customerComplaints()->count();
    }

    public function getActiveComplaints()
    {
        return $this->customerComplaints()->where('status', '!=', 'resolved')->count();
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($customer) {
            if (empty($customer->kode_customer)) {
                $customer->kode_customer = static::generateKodeCustomer();
            }
        });
    }

    public static function generateKodeCustomer()
    {
        $prefix = 'CUST';
        $year = date('Y');
        $month = date('m');
        
        $lastCustomer = static::whereYear('created_at', $year)
                             ->whereMonth('created_at', $month)
                             ->orderBy('id', 'desc')
                             ->first();
        
        $sequence = $lastCustomer ? (int)substr($lastCustomer->kode_customer, -4) + 1 : 1;
        
        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
