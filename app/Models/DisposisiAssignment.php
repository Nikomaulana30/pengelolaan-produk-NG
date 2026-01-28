<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposisiAssignment extends Model
{
    protected $table = 'disposisi_assignments';
    
    protected $fillable = [
        'penyimpanan_ng_id',
        'master_disposisi_id',
        'master_lokasi_gudang_tujuan_id',
        'status',
        'catatan',
        'hasil_eksekusi',
        'assigned_by',
        'executed_by',
        'assigned_at',
        'executed_at',
        'zone_tujuan',
        'rack_tujuan',
        'bin_tujuan',
        'lokasi_lengkap_tujuan',
        'tanggal_relokasi',
        'alasan_relokasi',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'executed_at' => 'datetime',
        'tanggal_relokasi' => 'datetime',
    ];

    // Relationships
    public function penyimpananNg()
    {
        return $this->belongsTo(PenyimpananNg::class, 'penyimpanan_ng_id');
    }

    public function disposisi()
    {
        return $this->belongsTo(MasterDisposisi::class, 'master_disposisi_id');
    }
    
    // Alias for consistency
    public function masterDisposisi()
    {
        return $this->disposisi();
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function executedBy()
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function lokasiGudangTujuan()
    {
        return $this->belongsTo(MasterLokasiGudang::class, 'master_lokasi_gudang_tujuan_id');
    }
    
    // Alias for consistency  
    public function lokasiGudang()
    {
        return $this->lokasiGudangTujuan();
    }

    /**
     * Disposisi assignment dapat menghasilkan scrap disposal
     */
    public function scrapDisposals()
    {
        return $this->hasMany(ScrapDisposal::class, 'nomor_referensi', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Methods
    public function markInProgress($userId = null)
    {
        $this->update([
            'status' => 'in_progress',
            'executed_by' => $userId ?? auth()->id(),
        ]);
    }

    public function markCompleted($hasil, $userId = null)
    {
        $this->update([
            'status' => 'completed',
            'hasil_eksekusi' => $hasil,
            'executed_by' => $userId ?? auth()->id(),
            'executed_at' => now(),
        ]);
    }

    public function markCancelled($alasan = null, $userId = null)
    {
        $this->update([
            'status' => 'cancelled',
            'hasil_eksekusi' => $alasan,
            'executed_by' => $userId ?? auth()->id(),
        ]);
    }
}
