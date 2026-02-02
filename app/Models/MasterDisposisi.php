<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterDisposisi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_disposisis';

    protected $fillable = [
        'kode_disposisi',
        'nama_disposisi',
        'jenis_tindakan',
        'deskripsi',
        'proses_tindakan',
        'syarat_ketentuan',
        'memerlukan_approval',
        'is_active',
        'penyimpanan_ng_id',
        'master_lokasi_gudang_tujuan_id',
        // Relocation fields
        'zone_tujuan',
        'rack_tujuan',
        'bin_tujuan',
        'lokasi_lengkap_tujuan',
    ];

    protected $casts = [
        'memerlukan_approval' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    
    // Direct relationship to single PenyimpananNg
    public function penyimpananNg()
    {
        return $this->belongsTo(PenyimpananNg::class, 'penyimpanan_ng_id');
    }
    
    // Relationship to Master Lokasi Gudang (tujuan relokasi)
    public function lokasiGudangTujuan()
    {
        return $this->belongsTo(MasterLokasiGudang::class, 'master_lokasi_gudang_tujuan_id');
    }
    
    // Workflow relationships - DISABLED: Tables don't have disposisi_id foreign key
    // quality_reinspections only has 'disposisi' enum column (not FK)
    // final_quality_checks doesn't reference master_disposisis at all
    
    // Get all PenyimpananNg records that reference this disposisi
    public function penyimpananNgs()
    {
        return $this->hasMany(PenyimpananNg::class, 'master_disposisi_id');
    }
    
    /**
     * Get disposisi usage statistics
     */
    public function getUsageStatistics()
    {
        return [
            'total_penyimpanan_ng' => $this->penyimpananNgs()->count(),
            'approval_required' => $this->memerlukan_approval,
            'is_active' => $this->is_active,
        ];
    }
    
    /**
     * Check if this disposition requires specific approval workflow
     */
    public function requiresApprovalWorkflow()
    {
        return $this->memerlukan_approval && $this->is_active;
    }
    
    // Alias for consistency
    public function lokasiTujuan()
    {
        return $this->lokasiGudangTujuan();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequiresApproval($query)
    {
        return $query->where('memerlukan_approval', true);
    }

    /**
     * Generate kode disposisi otomatis dari nama disposisi
     * Format: XXX-001 (3 huruf dari nama + counter)
     */
    public static function generateKodeDisposisi($namaDisposisi)
    {
        // Ambil kata-kata dari nama disposisi
        $words = explode(' ', $namaDisposisi);
        
        // Ambil huruf pertama dari setiap kata atau 3 huruf pertama dari kata pertama
        if (count($words) >= 3) {
            // Jika ada 3+ kata, ambil huruf pertama masing-masing
            $prefix = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1));
        } else {
            // Jika kurang dari 3 kata, ambil 3 huruf pertama dari kata pertama
            $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $namaDisposisi), 0, 3));
        }
        
        // Pastikan prefix minimal 3 karakter
        $prefix = str_pad($prefix, 3, 'X');
        
        // Cari kode terakhir dengan prefix yang sama
        $lastCode = self::withTrashed()
            ->where('kode_disposisi', 'LIKE', $prefix . '-%')
            ->orderByRaw('CAST(SUBSTRING(kode_disposisi, 5) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastCode) {
            // Extract nomor dari kode terakhir (RWK-001 → 001)
            preg_match('/(\d+)$/', $lastCode->kode_disposisi, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format: RWK-001
        return $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getActionBadgeAttribute()
    {
        $badges = [
            'return_to_vendor' => '<span class="badge bg-primary">↩️ Return to Vendor</span>',
            'scrap_disposal' => '<span class="badge bg-danger">🗑️ Scrap/Disposal</span>',
            'rework' => '<span class="badge bg-warning">🔧 Rework</span>',
            'downgrade' => '<span class="badge bg-info">⬇️ Downgrade</span>',
            'repurpose' => '<span class="badge bg-secondary">♻️ Repurpose</span>',
        ];
        return $badges[$this->jenis_tindakan] ?? '';
    }
}
