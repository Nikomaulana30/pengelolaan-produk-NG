<?php

namespace App\Models;

use App\Traits\HasApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RcaAnalysis extends Model
{
    use SoftDeletes, HasApproval;

    protected $table = 'rca_analyses';

    protected $fillable = [
        'nomor_rca',
        'tanggal_analisa',
        'metode_rca',
        'kode_defect',
        'kode_barang',
        'retur_barang_id',
        'criticality_level',
        'sumber_masalah',
        'penyebab_utama',
        'deskripsi_masalah',
        'analisa_detail',
        'corrective_action',
        'preventive_action',
        'pic_analisa',
        'nama_analis',
        'due_date',
        'status_rca',
        'catatan',
    ];

    protected $casts = [
        'tanggal_analisa' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Relasi ke Master Defect
     */
    public function masterDefect()
    {
        return $this->belongsTo(MasterDefect::class, 'kode_defect', 'kode_defect');
    }

    /**
     * Relasi ke Master Produk
     */
    public function masterProduk()
    {
        // Relationship using kode_barang (string) to kode_produk mapping
        return $this->belongsTo(MasterProduk::class, 'kode_barang', 'kode_produk');
    }

    /**
     * Relasi ke Retur Barang (Optional)
     */
    public function returBarang()
    {
        return $this->belongsTo(ReturBarang::class, 'retur_barang_id');
    }

    /**
     * Relasi ke Finance Approval (One RCA can have multiple finance approvals)
     */
    public function financeApprovals()
    {
        return $this->hasMany(FinanceApproval::class, 'nomor_referensi', 'nomor_rca');
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_rca', $status);
    }

    /**
     * Scope: Filter active/open RCA
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status_rca', ['open', 'in_progress']);
    }

    /**
     * Scope: Filter by defect
     */
    public function scopeByDefect($query, $kode_defect)
    {
        return $query->where('kode_defect', $kode_defect);
    }

    /**
     * Scope: Filter by produk
     */
    public function scopeByProduk($query, $kode_barang)
    {
        return $query->where('kode_barang', $kode_barang);
    }

    /**
     * Scope: Overdue RCA
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
                     ->whereIn('status_rca', ['open', 'in_progress']);
    }

    /**
     * Accessor: Status badge
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status_rca) {
            'open' => '<span class="badge bg-danger">🔴 Open</span>',
            'in_progress' => '<span class="badge bg-warning">🟡 In Progress</span>',
            'closed' => '<span class="badge bg-success">🟢 Closed</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    /**
     * Accessor: Metode badge
     */
    public function getMetodeBadgeAttribute()
    {
        return match($this->metode_rca) {
            '5_why' => '<span class="badge bg-info">5 Why Analysis</span>',
            'fishbone' => '<span class="badge bg-primary">Fishbone Diagram</span>',
            'kombinasi' => '<span class="badge bg-secondary">Kombinasi</span>',
            default => '<span class="badge bg-light text-dark">' . $this->metode_rca . '</span>',
        };
    }

    /**
     * Accessor: PIC badge
     */
    public function getPicBadgeAttribute()
    {
        return match($this->pic_analisa) {
            'qc' => '<span class="badge bg-info">QC</span>',
            'engineering' => '<span class="badge bg-primary">Engineering</span>',
            'warehouse' => '<span class="badge bg-success">Warehouse</span>',
            'production' => '<span class="badge bg-warning">Production</span>',
            'maintenance' => '<span class="badge bg-secondary">Maintenance</span>',
            default => '<span class="badge bg-light text-dark">' . $this->pic_analisa . '</span>',
        };
    }

    /**
     * Check if RCA is overdue
     */
    public function isOverdue()
    {
        return $this->due_date < now()->toDateString() && in_array($this->status_rca, ['open', 'in_progress']);
    }

    /**
     * Generate nomor RCA otomatis
     */
    public static function generateNomorRca()
    {
        $date = now()->format('Ymd');
        $latestRca = self::where('nomor_rca', 'like', "RCA-{$date}-%")
            ->latest('id')
            ->first();
        
        if ($latestRca) {
            $lastNumber = (int) substr($latestRca->nomor_rca, -4);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }
        
        return "RCA-{$date}-{$nextNumber}";
    }

    // ===== APPROVAL TRAIT IMPLEMENTATION =====
    
    public function getApprovalType(): string
    {
        return 'rca_analysis';
    }

    public function onApproved(int $level): void
    {
        // When fully approved, mark as in_progress and ready for action
        if ($this->isFullyApproved()) {
            $this->update(['status_rca' => 'in_progress']);
        }
    }

    public function onRejected(int $level, ?string $reason): void
    {
        // When rejected, add note to catatan field
        $this->update([
            'catatan' => ($this->catatan ? $this->catatan . "\n\n" : '') . 
                        "Rejected at level {$level}: {$reason}"
        ]);
    }
}
