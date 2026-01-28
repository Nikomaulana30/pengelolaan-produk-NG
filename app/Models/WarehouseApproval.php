<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseApproval extends Model
{
    use SoftDeletes;

    protected $table = 'warehouse_approvals';

    protected $fillable = [
        'nomor_approval',
        'tanggal_pengajuan',
        'nomor_referensi',
        'pengaju',
        'deskripsi_pengajuan',
        'ws_status_approval',
        'ws_tanggal_approval',
        'ws_nama_approver',
        'ws_kondisi_barang',
        'ws_catatan',
        'pm_status_approval',
        'pm_tanggal_approval',
        'pm_nama_approver',
        'pm_keputusan',
        'pm_catatan',
        'status_keseluruhan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'ws_tanggal_approval' => 'datetime',
        'pm_tanggal_approval' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes for Warehouse Supervisor
    public function scopeWSPending($query)
    {
        return $query->where('ws_status_approval', 'pending');
    }

    public function scopeWSApproved($query)
    {
        return $query->where('ws_status_approval', 'approved');
    }

    public function scopeWSRejected($query)
    {
        return $query->where('ws_status_approval', 'rejected');
    }

    // Scopes for Production Manager
    public function scopePMPending($query)
    {
        return $query->where('pm_status_approval', 'pending');
    }

    public function scopePMApproved($query)
    {
        return $query->where('pm_status_approval', 'approved');
    }

    public function scopePMRejected($query)
    {
        return $query->where('pm_status_approval', 'rejected');
    }

    // Scopes for Overall Status
    public function scopePending($query)
    {
        return $query->where('status_keseluruhan', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status_keseluruhan', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status_keseluruhan', 'rejected');
    }

    // Method untuk update status keseluruhan
    public function updateOverallStatus()
    {
        // Jika keduanya approved, maka keseluruhan approved
        if ($this->ws_status_approval === 'approved' && $this->pm_status_approval === 'approved') {
            $this->status_keseluruhan = 'approved';
        }
        // Jika ada yang rejected, maka keseluruhan rejected
        elseif ($this->ws_status_approval === 'rejected' || $this->pm_status_approval === 'rejected') {
            $this->status_keseluruhan = 'rejected';
        }
        // Jika ada yang need_revision, maka keseluruhan need_revision
        elseif ($this->ws_status_approval === 'need_revision' || $this->pm_status_approval === 'need_revision') {
            $this->status_keseluruhan = 'need_revision';
        }
        // Jika masih ada yang pending, maka keseluruhan pending
        else {
            $this->status_keseluruhan = 'pending';
        }
        return $this;
    }
}
