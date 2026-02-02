<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenyimpananNg extends Model
{
    use SoftDeletes;

    protected $table = 'penyimpanan_ngs';

    protected $fillable = [
        'nomor_storage',
        'tanggal_penyimpanan',
        'nomor_referensi',
        'penerimaan_barang_id',
        'nama_barang',
        'master_lokasi_gudang_id',
        'zone',
        'rack',
        'bin',
        'lokasi_lengkap',
        'zone_tujuan',
        'rack_tujuan',
        'bin_tujuan',
        'lokasi_lengkap_tujuan',
        'tanggal_relokasi',
        'alasan_relokasi',
        'qty_awal',
        'qty_setelah_perbaikan',
        'selisih_qty',
        'status_barang',
        'user_id',
        'catatan',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
        'master_disposisi_id',
    ];

    protected $casts = [
        'tanggal_penyimpanan' => 'datetime',
        'tanggal_relokasi' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan MasterDisposisi (Direct FK)
    public function disposisi()
    {
        return $this->belongsTo(MasterDisposisi::class, 'master_disposisi_id');
    }

    /**
     * Relasi ke Master Lokasi Gudang
     */
    public function lokasiGudang()
    {
        return $this->belongsTo(MasterLokasiGudang::class, 'master_lokasi_gudang_id');
    }

    /**
     * Relasi ke Penerimaan Barang (if linked)
     * DISABLED: PenerimaanBarang model doesn't exist
     */
    // public function penerimaanBarang()
    // {
    //     return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_barang_id');
    // }

    /**
     * Relasi ke Quality Inspection (reverse)
     * QC yang menghasilkan penyimpanan NG ini
     */
    public function qualityInspection()
    {
        return $this->hasOne(QualityInspection::class, 'penyimpanan_ng_id');
    }

    /**
     * Relasi ke Stock Movements
     * Track semua pergerakan barang NG ini
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'penyimpanan_ng_id');
    }

    /**
     * Relasi ke Scrap Disposal (One-to-Many)
     * NG storage dapat menjadi scrap disposal
     */
    public function scrapDisposals()
    {
        return $this->hasMany(ScrapDisposal::class, 'nomor_referensi', 'nomor_storage');
    }

    // Relationship dengan DisposisiAssignment (One-to-Many)
    public function disposisiAssignments()
    {
        return $this->hasMany(DisposisiAssignment::class, 'penyimpanan_ng_id');
    }

    // Relationship dengan MasterDisposisi (Through DisposisiAssignment)
    public function disposisis()
    {
        return $this->hasManyThrough(
            MasterDisposisi::class,
            DisposisiAssignment::class,
            'penyimpanan_ng_id',
            'id',
            'id',
            'master_disposisi_id'
        );
    }

    // Activity logs untuk tracking
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'traceable');
    }

    // Query Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('status_barang', '!=', 'dipindahkan');
    }

    // Status Badge Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'submitted' => '<span class="badge bg-info">Submitted</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
        ];

        return $badges[$this->status] ?? '';
    }

    public function getBarangStatusBadgeAttribute()
    {
        $badges = [
            'disimpan' => '<span class="badge bg-primary">📦 Disimpan</span>',
            'dalam_perbaikan' => '<span class="badge bg-warning">🔧 Dalam Perbaikan</span>',
            'menunggu_approval' => '<span class="badge bg-info">⏳ Menunggu Approval</span>',
            'siap_dipindahkan' => '<span class="badge bg-success">✓ Siap Dipindahkan</span>',
            'dipindahkan' => '<span class="badge bg-secondary">↗ Sudah Dipindahkan</span>',
        ];

        return $badges[$this->status_barang] ?? '';
    }
}
