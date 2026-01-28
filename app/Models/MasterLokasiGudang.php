<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterLokasiGudang extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'zone',
        'rack',
        'bin',
        'lokasi_lengkap',
        'kapasitas_max',
        'current_usage',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kapasitas_max' => 'integer',
        'current_usage' => 'integer',
    ];

    /**
     * Relasi ke Penyimpanan NG
     */
    public function penyimpananNgs()
    {
        return $this->hasMany(PenyimpananNg::class, 'master_lokasi_gudang_id');
    }

    /**
     * Relasi ke Penerimaan Barang
     */
    public function penerimaanBarangs()
    {
        return $this->hasMany(PenerimaanBarang::class, 'master_lokasi_gudang_id');
    }
}
