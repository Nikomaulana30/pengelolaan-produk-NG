<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaanBarang extends Model
{
    use SoftDeletes;
    
    protected $table = 'penerimaan_barangs';
    
    protected $fillable = [
        'nomor_dokumen',
        'tanggal_input',
        'jenis_pengembalian',
        'lokasi_temuan',
        'nama_barang',
        'sku',
        'batch_number',
        'qty_baik',
        'qty_rusak',
        'penginput',
        'keterangan',
        'user_id',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
        // Master Lokasi Gudang Integration
        'master_lokasi_gudang_id',
        'zone',
        'rack',
        'bin',
        'lokasi_lengkap',
        'status_penerimaan',
        'hasil_inspeksi',
        'ada_defect',
    ];
    
    protected $casts = [
        'tanggal_input' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'deleted_at' => 'datetime',
        'ada_defect' => 'boolean',
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relasi ke Master Lokasi Gudang
     */
    public function lokasiGudang()
    {
        return $this->belongsTo(MasterLokasiGudang::class, 'master_lokasi_gudang_id');
    }
    
    /**
     * Relasi ke Penyimpanan NG (jika ada defect)
     */
    public function penyimpananNgs()
    {
        return $this->hasMany(PenyimpananNg::class, 'penerimaan_barang_id');
    }
    
    // Scopes
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
    
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    
    // Accessors & Mutators
    public function getTotalQtyAttribute()
    {
        return $this->qty_baik + $this->qty_rusak;
    }
}

