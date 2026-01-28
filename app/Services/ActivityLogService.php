<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    /**
     * Log ketika model dibuat
     */
    public static function logCreated(Model $model, ?string $description = null)
    {
        $description = $description ?? 'Data ' . class_basename($model) . ' dibuat';
        
        ActivityLog::create([
            'traceable_type' => $model::class,
            'traceable_id' => $model->id,
            'action' => 'created',
            'user_id' => auth()->id(),
            'description' => $description,
            'new_value' => json_encode($model->toArray()),
        ]);
    }

    /**
     * Log ketika status berubah
     */
    public static function logStatusChange(
        Model $model,
        string $fieldName,
        $oldValue,
        $newValue,
        ?string $description = null
    ) {
        $description = $description ?? "Status {$fieldName} berubah dari '{$oldValue}' menjadi '{$newValue}'";
        
        ActivityLog::create([
            'traceable_type' => $model::class,
            'traceable_id' => $model->id,
            'action' => 'status_changed',
            'user_id' => auth()->id(),
            'description' => $description,
            'old_value' => (string) $oldValue,
            'new_value' => (string) $newValue,
            'metadata' => [
                'field' => $fieldName,
            ],
        ]);
    }

    /**
     * Log ketika data diapprove
     */
    public static function logApproved(
        Model $model,
        ?string $notes = null
    ) {
        $description = 'Data diapprove oleh ' . (auth()->user()->name ?? 'System');
        if ($notes) {
            $description .= ' - ' . $notes;
        }
        
        ActivityLog::create([
            'traceable_type' => $model::class,
            'traceable_id' => $model->id,
            'action' => 'approved',
            'user_id' => auth()->id(),
            'description' => $description,
            'metadata' => [
                'approved_by' => auth()->user()->name ?? 'System',
                'approved_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Log ketika data direject
     */
    public static function logRejected(
        Model $model,
        string $reason
    ) {
        $description = 'Data ditolak oleh ' . (auth()->user()->name ?? 'System');
        
        ActivityLog::create([
            'traceable_type' => $model::class,
            'traceable_id' => $model->id,
            'action' => 'rejected',
            'user_id' => auth()->id(),
            'description' => $description . ' - ' . $reason,
            'metadata' => [
                'reason' => $reason,
                'rejected_by' => auth()->user()->name ?? 'System',
                'rejected_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Log disposisi final (Retur, Scrap, Rework)
     */
    public static function logDisposisi(
        Model $model,
        string $dispositionType,
        ?string $notes = null
    ) {
        $description = "Disposisi final: {$dispositionType}";
        if ($notes) {
            $description .= ' - ' . $notes;
        }
        
        ActivityLog::create([
            'traceable_type' => $model::class,
            'traceable_id' => $model->id,
            'action' => 'disposisi_set',
            'user_id' => auth()->id(),
            'description' => $description,
            'metadata' => [
                'disposition' => $dispositionType,
                'set_by' => auth()->user()->name ?? 'System',
                'set_at' => now()->toDateTimeString(),
            ],
        ]);
    }

    /**
     * Get activity history untuk model
     */
    public static function getHistory(Model $model, int $limit = 10)
    {
        return ActivityLog::where('traceable_type', $model::class)
            ->where('traceable_id', $model->id)
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get summary activities
     */
    public static function getSummary($modelClass, $days = 7)
    {
        return ActivityLog::where('traceable_type', $modelClass)
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('action')
            ->selectRaw('action, count(*) as count')
            ->get();
    }
}
