<?php

namespace App\Models;

use App\Traits\HasApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScrapDisposal extends Model
{
    use SoftDeletes, HasApproval;

    protected $table = 'scrap_disposals';

    protected $fillable = [
        'nomor_scrap',
        'tanggal_scrap',
        'nama_petugas',
        'nomor_referensi',
        'nama_barang',
        'quantity',
        'alasan_scrap',
        'deskripsi_kondisi',
        'hasil_test_qc',
        'tanggal_test_qc',
        'qc_inspector',
        'catatan_qc',
        'metode_pembuangan',
        'tanggal_rencana_scrap',
        'pihak_pelaksana',
        'estimasi_biaya_pembuangan',
        'dokumen_bukti',
        'status_approval',
        'tanggal_approval',
        'nama_manager',
        'catatan_manager',
        'user_id',
    ];

    protected $casts = [
        'tanggal_scrap' => 'datetime',
        'tanggal_test_qc' => 'date',
        'tanggal_rencana_scrap' => 'date',
        'tanggal_approval' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scrap disposal dapat direferensikan dari master produk
     * (untuk tracking barang yang di-scrap)
     */
    public function masterProduk()
    {
        return $this->belongsTo(MasterProduk::class, 'nama_barang', 'nama_produk');
    }

    /**
     * Scrap disposal berasal dari Penyimpanan NG
     * (via nomor_referensi -> nomor_storage)
     */
    public function penyimpananNg()
    {
        return $this->belongsTo(PenyimpananNg::class, 'nomor_referensi', 'nomor_storage');
    }

    /**
     * Scrap disposal dapat berasal dari hasil DisposisiAssignment
     */
    public function disposisiAssignment()
    {
        return $this->belongsTo(DisposisiAssignment::class, 'nomor_referensi', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status_approval', 'rejected');
    }

    // Activity logs untuk tracking
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'traceable');
    }

    // ===== APPROVAL TRAIT IMPLEMENTATION =====
    
    public function getApprovalType(): string
    {
        return 'scrap_disposal';
    }

    public function onApproved(int $level): void
    {
        if ($this->isFullyApproved()) {
            $this->update(['status_approval' => 'approved', 'tanggal_approval' => now()]);
        }
    }

    public function onRejected(int $level, ?string $reason): void
    {
        $this->update(['status_approval' => 'rejected', 'catatan_manager' => $reason]);
    }
}
