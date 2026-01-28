<?php

namespace App\Models;

use App\Traits\HasApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceApproval extends Model
{
    use SoftDeletes, HasApproval;

    protected $table = 'finance_approvals';

    protected $fillable = [
        'nomor_approval',
        'nomor_referensi',
        'pengaju',
        'deskripsi_pengajuan',
        'jenis_dampak',
        'estimasi_biaya',
        'asal_permohonan',
        'referensi_permohonan',
        'status_approval',
        'tanggal_approval',
        'nama_approver',
        'budget_approval',
        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_approval' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke RCA Analysis (melalui nomor_referensi -> nomor_rca)
     */
    public function rcaAnalysis()
    {
        return $this->belongsTo(RcaAnalysis::class, 'nomor_referensi', 'nomor_rca');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status_approval', 'rejected');
    }

    // ===== APPROVAL TRAIT IMPLEMENTATION =====
    
    public function getApprovalType(): string
    {
        return 'finance_approval';
    }

    public function onApproved(int $level): void
    {
        if ($this->isFullyApproved()) {
            $this->update([
                'status_approval' => 'approved',
                'tanggal_approval' => now(),
                'budget_approval' => true
            ]);
        }
    }

    public function onRejected(int $level, ?string $reason): void
    {
        $this->update([
            'status_approval' => 'rejected',
            'budget_approval' => false,
            'catatan' => $reason
        ]);
    }
}
