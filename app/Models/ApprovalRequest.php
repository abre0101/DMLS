<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ApprovalRequest
 *
 * Represents a single approval request assigned to an approver.
 *
 * @property int $id
 * @property int $document_id
 * @property int $approver_id
 * @property int $user_id
 * @property int $level
 * @property string $status
 * @property Carbon|null $approved_at
 * @property string|null $signature_data
 * @property int|null $escalated_to_id
 */
class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'approver_id',
        'user_id',
        'level',
        'status',
        'approved_at',
      
        'escalated_to_id',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_ESCALATED = 'escalated';

    /**
     * Relations
     */
// In App\Models\ApprovalRequest.php
public function user()
{
    return $this->belongsTo(User::class);
}

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

 

    public function escalatedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'escalated_to_id');
    }
public function approver()
{
    return $this->belongsTo(User::class, 'approver_id');
}

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('approver_id', $userId);
    }

    /**
     * Actions
     */

    public function approve(?string $signatureData = null): void
    {
        if (!Auth::user()->can('approve', $this)) {
            throw new \Exception('Unauthorized approval attempt.');
        }

        $this->update([
            'status' => self::STATUS_APPROVED,
            'approved_at' => Carbon::now(),
            'signature_data' => $signatureData ?? $this->signature_data,
        ]);
    }

    public function reject(): void
    {
        if (!Auth::user()->can('reject', $this)) {
            throw new \Exception('Unauthorized rejection attempt.');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
        ]);
    }

    public function escalate(User $user): void
    {
        if (!Auth::user()->can('escalate', $this)) {
            throw new \Exception('Unauthorized escalation attempt.');
        }

        $this->update([
            'status' => self::STATUS_ESCALATED,
            'escalated_to_id' => $user->id,
        ]);
    }

    /**
     * Helpers
     */

    public function canApprove(): bool
    {
        return Auth::id() === $this->approver_id;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
