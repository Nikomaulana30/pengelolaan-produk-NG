<?php

namespace App\Traits;

use App\Models\Approval;

trait HasApproval
{
    /**
     * Polymorphic relationship to approvals
     */
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    /**
     * Get pending approvals
     */
    public function pendingApprovals()
    {
        return $this->approvals()->where('status', 'pending');
    }

    /**
     * Get approved approvals
     */
    public function approvedApprovals()
    {
        return $this->approvals()->where('status', 'approved');
    }

    /**
     * Check if has pending approval
     */
    public function hasPendingApproval($level = null)
    {
        $query = $this->pendingApprovals();
        
        if ($level !== null) {
            $query->where('level', $level);
        }

        return $query->exists();
    }

    /**
     * Check if approved at level
     */
    public function isApprovedAt($level)
    {
        return $this->approvals()
            ->where('level', $level)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Check if fully approved (all levels)
     */
    public function isFullyApproved()
    {
        $pendingCount = $this->pendingApprovals()->count();
        $rejectedCount = $this->approvals()->where('status', 'rejected')->count();

        return $pendingCount === 0 && $rejectedCount === 0 && $this->approvals()->count() > 0;
    }

    /**
     * Submit for approval
     */
    public function submitForApproval($level = 1, $notes = null)
    {
        // Check if already has pending approval
        if ($this->hasPendingApproval($level)) {
            throw new \Exception("Already has pending approval at level {$level}");
        }

        // Create approval requests
        $approvals = Approval::createForModel($this, $level, $notes);

        // Update model status if has status field
        if ($this->hasAttribute('status')) {
            $this->update(['status' => 'pending_approval']);
        }

        return $approvals;
    }

    /**
     * Cancel all pending approvals
     */
    public function cancelApprovals()
    {
        $this->pendingApprovals()->update([
            'status' => 'cancelled',
        ]);

        // Update model status if has status field
        if ($this->hasAttribute('status')) {
            $this->update(['status' => 'draft']);
        }
    }

    /**
     * Override this method in model to define approval type
     * Example: return 'penyimpanan_ng';
     */
    public function getApprovalType()
    {
        throw new \Exception("Method getApprovalType() must be implemented in " . get_class($this));
    }

    /**
     * Hook called when approval is approved
     * Override in model if needed
     */
    public function onApproved($level)
    {
        // Check if all required levels are approved
        if ($this->isFullyApproved() && $this->hasAttribute('status')) {
            $this->update(['status' => 'approved']);
        }
    }

    /**
     * Hook called when approval is rejected
     * Override in model if needed
     */
    public function onRejected($level, $reason)
    {
        if ($this->hasAttribute('status')) {
            $this->update(['status' => 'rejected']);
        }
    }

    /**
     * Check if attribute exists
     */
    public function hasAttribute($attribute)
    {
        return array_key_exists($attribute, $this->attributes) || 
               in_array($attribute, $this->fillable);
    }
}
