<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workflow extends Model
{
    use HasFactory;

    // Define constants for workflow statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    // Specify the fillable attributes
    protected $fillable = [
        'name',
        'status',
        'description',
        'document_id', // ✅ Include this to allow mass assignment
    ];

    /**
     * Scope a query to only include pending workflows.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Get the workflow's status in a human-readable format.
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Relationship: Workflow belongs to a Document.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Relationship: Workflow has many WorkflowSteps ordered by step_order.
     */
    public function steps()
    {
        return $this->hasMany(WorkflowStep::class)->orderBy('step_order');
    }

    /**
     * Get pending step for a given role.
     */
    public function pendingStepForRole(string $role)
    {
        return $this->steps()
            ->where('role', $role)
            ->where('status', self::STATUS_PENDING)
            ->orderBy('step_order')
            ->first();
    }
}
