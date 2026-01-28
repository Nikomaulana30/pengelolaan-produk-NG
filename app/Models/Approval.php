<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approval extends Model
{
    use SoftDeletes;

    protected $table = 'approvals';

    protected $fillable = [
        'approvable_type',
        'approvable_id',
        'approval_authority_id',
        'approver_id',
        'level',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
        'notes',
        'approval_notes',
        'rejection_reason',
        'submitted_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Polymorphic relationship to approvable
     */
    public function approvable()
    {
        return $this->morphTo();
    }

    /**
     * Relationship ke Approver (User)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Relationship ke Submitter (User)
     */
    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Relationship ke Approval Authority
     */
    public function approvalAuthority()
    {
        return $this->belongsTo(MasterApprovalAuthority::class, 'approval_authority_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForApprover($query, $userId)
    {
        return $query->where('approver_id', $userId);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Helper methods
     */
    public function approve($notes = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);

        // Update approvable status if needed
        if (method_exists($this->approvable, 'onApproved')) {
            $this->approvable->onApproved($this->level);
        }

        return $this;
    }

    public function reject($reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Update approvable status if needed
        if (method_exists($this->approvable, 'onRejected')) {
            $this->approvable->onRejected($this->level, $reason);
        }

        return $this;
    }

    /**
     * Check if can be approved by user
     */
    public function canBeApprovedBy($userId)
    {
        return $this->approver_id == $userId && $this->status === 'pending';
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning"><i class="bi bi-clock"></i> Pending</span>',
            'approved' => '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>',
            'rejected' => '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejected</span>',
            'cancelled' => '<span class="badge bg-secondary"><i class="bi bi-dash-circle"></i> Cancelled</span>',
        ];

        return $badges[$this->status] ?? '';
    }

    public function getLevelBadgeAttribute()
    {
        $colors = [
            1 => 'success',
            2 => 'warning',
            3 => 'danger',
        ];

        $color = $colors[$this->level] ?? 'secondary';
        return "<span class='badge bg-{$color}'>Level {$this->level}</span>";
    }

    /**
     * Create approval request for a model
     */
    public static function createForModel($model, $level = 1, $notes = null)
    {
        // Get approval type from model
        $approvalType = $model->getApprovalType();
        
        // Get approvers for this type and level
        $authorities = MasterApprovalAuthority::getApprovers($approvalType, $level);

        if ($authorities->isEmpty()) {
            throw new \Exception("No approvers found for {$approvalType} at level {$level}");
        }

        $approvals = [];
        foreach ($authorities as $authority) {
            $approvals[] = self::create([
                'approvable_type' => get_class($model),
                'approvable_id' => $model->id,
                'approval_authority_id' => $authority->id,
                'approver_id' => $authority->user_id,
                'level' => $level,
                'status' => 'pending',
                'submitted_at' => now(),
                'notes' => $notes,
                'submitted_by' => auth()->id(),
            ]);
        }

        return $approvals;
    }
}
