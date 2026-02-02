<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterJenisComplaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_jenis',
        'nama_jenis',
        'deskripsi',
        'icon_class',
        'require_photo',
        'require_document',
        'is_active'
    ];

    protected $casts = [
        'require_photo' => 'boolean',
        'require_document' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRequireAttachments($query)
    {
        return $query->where('require_photo', true)
                    ->orWhere('require_document', true);
    }

    // Relationships
    public function customerComplaints()
    {
        return $this->hasMany(CustomerComplaint::class, 'jenis_complaint_id');
    }

    // Helper methods
    public function getRequirementSummaryAttribute()
    {
        $requirements = [];
        if ($this->require_photo) $requirements[] = 'Photo';
        if ($this->require_document) $requirements[] = 'Document';
        return empty($requirements) ? 'No attachments required' : implode(', ', $requirements) . ' required';
    }

    public function getIconDisplayAttribute()
    {
        return $this->icon_class ?: 'bi bi-exclamation-circle';
    }
}
