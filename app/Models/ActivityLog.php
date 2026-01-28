<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'traceable_type',
        'traceable_id',
        'action',
        'user_id',
        'description',
        'old_value',
        'new_value',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the traceable model
     */
    public function traceable()
    {
        return $this->morphTo();
    }

    /**
     * Scope: Get logs for a specific model
     */
    public function scopeForModel($query, string $modelClass, int $modelId)
    {
        return $query
            ->where('traceable_type', $modelClass)
            ->where('traceable_id', $modelId)
            ->latest();
    }

    /**
     * Scope: Get logs by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Get logs by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get recent logs (last X days)
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
