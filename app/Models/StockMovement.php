<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use SoftDeletes;

    protected $table = 'stock_movements';

    protected $fillable = [
        'nomor_movement',
        'tanggal_movement',
        'penyimpanan_ng_id',
        'movement_type',
        'qty_before',
        'qty_moved',
        'qty_after',
        'reference_type',
        'reference_id',
        'from_lokasi_id',
        'to_lokasi_id',
        'user_id',
        'notes',
        'status',
    ];

    protected $casts = [
        'tanggal_movement' => 'datetime',
        'qty_before' => 'decimal:2',
        'qty_moved' => 'decimal:2',
        'qty_after' => 'decimal:2',
    ];

    /**
     * Relationship ke Penyimpanan NG
     */
    public function penyimpananNg()
    {
        return $this->belongsTo(PenyimpananNg::class, 'penyimpanan_ng_id');
    }

    /**
     * Relationship ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship ke Lokasi From (for transfers)
     */
    public function fromLokasi()
    {
        return $this->belongsTo(MasterLokasiGudang::class, 'from_lokasi_id');
    }

    /**
     * Relationship ke Lokasi To (for transfers)
     */
    public function toLokasi()
    {
        return $this->belongsTo(MasterLokasiGudang::class, 'to_lokasi_id');
    }

    /**
     * Get reference record (pseudo-polymorphic)
     * Usage: $movement->getReference()
     */
    public function getReference()
    {
        if (!$this->reference_type || !$this->reference_id) {
            return null;
        }

        switch ($this->reference_type) {
            case 'qc_inspection':
            case 'quality_inspection':
                return QualityInspection::find($this->reference_id);
            
            case 'disposisi':
            case 'disposisi_assignment':
                return DisposisiAssignment::find($this->reference_id);
            
            case 'penerimaan':
            case 'penerimaan_barang':
                // DISABLED: PenerimaanBarang model doesn't exist
                // return PenerimaanBarang::find($this->reference_id);
                return null;
            
            default:
                return null;
        }
    }

    /**
     * Scopes
     */
    public function scopeIn($query)
    {
        return $query->where('movement_type', 'in');
    }

    public function scopeOut($query)
    {
        return $query->where('movement_type', 'out');
    }

    public function scopeTransfer($query)
    {
        return $query->where('movement_type', 'transfer');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Accessors
     */
    public function getMovementTypeBadgeAttribute()
    {
        $badges = [
            'in' => '<span class="badge bg-success">📥 IN</span>',
            'out' => '<span class="badge bg-danger">📤 OUT</span>',
            'transfer' => '<span class="badge bg-info">↔️ TRANSFER</span>',
            'adjustment' => '<span class="badge bg-warning">⚙️ ADJUSTMENT</span>',
        ];

        return $badges[$this->movement_type] ?? '';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">⏳ Pending</span>',
            'completed' => '<span class="badge bg-success">✓ Completed</span>',
            'cancelled' => '<span class="badge bg-danger">✗ Cancelled</span>',
        ];

        return $badges[$this->status] ?? '';
    }
}
