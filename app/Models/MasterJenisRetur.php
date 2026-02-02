<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterJenisRetur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_jenis_retur',
        'nama_jenis_retur',
        'deskripsi',
        'require_replacement',
        'require_refund',
        'require_rework',
        'instruction_template',
        'is_active'
    ];

    protected $casts = [
        'require_replacement' => 'boolean',
        'require_refund' => 'boolean',
        'require_rework' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequiresRework($query)
    {
        return $query->where('require_rework', true);
    }

    // Relationships
    public function dokumenReturs()
    {
        return $this->hasMany(DokumenRetur::class, 'jenis_retur_id');
    }

    // Helper methods
    public function getProcessTypeAttribute()
    {
        $types = [];
        if ($this->require_replacement) $types[] = 'Replacement';
        if ($this->require_refund) $types[] = 'Refund';
        if ($this->require_rework) $types[] = 'Rework';
        return empty($types) ? 'Standard Return' : implode(' + ', $types);
    }

    public function getInstructionPreviewAttribute()
    {
        return $this->instruction_template ? 
            (strlen($this->instruction_template) > 100 ? 
                substr($this->instruction_template, 0, 100) . '...' : 
                $this->instruction_template) : 
            'No instruction template';
    }

    public function getComplexityLevelAttribute()
    {
        $complexity = 0;
        if ($this->require_replacement) $complexity++;
        if ($this->require_refund) $complexity++;
        if ($this->require_rework) $complexity++;
        
        return $complexity >= 2 ? 'Complex' : ($complexity == 1 ? 'Standard' : 'Simple');
    }
}
