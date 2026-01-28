<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualityInspection extends Model
{
    use SoftDeletes;

    protected $table = 'quality_inspections';

    protected $fillable = [
        'nomor_laporan',
        'tanggal_inspeksi',
        'product',
        'part_no',
        'material',
        'drawing_no',
        'drawing_rev',
        'customer',
        'batch_no',
        'hasil',
        'kode_defect',
        'kode_barang',
        'made_by',
        'approved_by',
        'catatan',
        'status_approval',
        'catatan_approval',
        'nama_approver',
        'tanggal_approval',
        'user_id',
        'penyimpanan_ng_id',
        'auto_create_storage',
    ];

    protected $casts = [
        'tanggal_inspeksi' => 'datetime',
        'tanggal_approval' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'auto_create_storage' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function masterDefect()
    {
        return $this->belongsTo(MasterDefect::class, 'kode_defect', 'kode_defect');
    }

    public function masterProduk()
    {
        // Relationship using kode_barang (string) to kode_produk mapping
        return $this->belongsTo(MasterProduk::class, 'kode_barang', 'kode_produk');
    }

    /**
     * Relationship ke Penyimpanan NG
     * If QC finds defect → create Penyimpanan NG → link here
     */
    public function penyimpananNg()
    {
        return $this->belongsTo(PenyimpananNg::class, 'penyimpanan_ng_id');
    }
}
