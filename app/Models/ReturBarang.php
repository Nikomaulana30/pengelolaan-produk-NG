<?php

namespace App\Models;

use App\Traits\HasApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturBarang extends Model
{
    use SoftDeletes, HasApproval;

    protected $table = 'retur_barangs';

    protected $fillable = [
        'vendor_id',
        'produk_id',
        'no_retur',
        'tanggal_retur',
        'alasan_retur',
        'jumlah_retur',
        'deskripsi_keluhan',
        'status_approval',
        'catatan_approval',
    ];

    protected $casts = [
        'tanggal_retur' => 'date',
        'jumlah_retur' => 'integer',
    ];

    // Boot method untuk auto-generate no_retur
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->no_retur) {
                $model->no_retur = self::generateNoRetur();
            }
        });
    }

    // Generate nomor retur dengan format: RET-YYYYMM-XXXXX
    public static function generateNoRetur()
    {
        $yearMonth = now()->format('Ym');
        $lastRetur = self::where('no_retur', 'like', 'RET-' . $yearMonth . '%')
            ->orderBy('no_retur', 'desc')
            ->first();

        if ($lastRetur) {
            $lastNumber = (int) substr($lastRetur->no_retur, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return 'RET-' . $yearMonth . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(MasterVendor::class, 'vendor_id');
    }

    public function produk()
    {
        return $this->belongsTo(MasterProduk::class, 'produk_id');
    }

    public function rcaAnalyses()
    {
        return $this->hasMany(RcaAnalysis::class, 'retur_barang_id');
    }

    // Activity logs untuk tracking
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'traceable');
    }

    // ===== APPROVAL TRAIT IMPLEMENTATION =====
    
    /**
     * Define approval type for this model
     */
    public function getApprovalType(): string
    {
        return 'retur_barang';
    }

    /**
     * Hook: Execute after approval
     */
    public function onApproved(int $level): void
    {
        // Update status when fully approved
        if ($this->isFullyApproved()) {
            $this->update(['status_approval' => 'approved']);
        }
    }

    /**
     * Hook: Execute after rejection
     */
    public function onRejected(int $level, ?string $reason): void
    {
        $this->update(['status_approval' => 'rejected']);
    }
}
