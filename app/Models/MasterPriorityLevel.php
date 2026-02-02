<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPriorityLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_priority',
        'nama_priority',
        'deskripsi',
        'sla_hours',
        'color_code',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sla_hours' => 'integer',
        'sort_order' => 'integer'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrderedByPriority($query)
    {
        return $query->orderBy('sort_order');
    }

    // Relationships
    public function customerComplaints()
    {
        return $this->hasMany(CustomerComplaint::class, 'priority_level_id');
    }

    // Helper methods
    public function getSlaStatusAttribute()
    {
        return $this->sla_hours <= 4 ? 'urgent' : ($this->sla_hours <= 24 ? 'normal' : 'low');
    }

    public function getFormattedSlaAttribute()
    {
        return $this->sla_hours . ' hours';
    }
}
