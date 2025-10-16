<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'status',
        'due_date',
        'created_by',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_IN_PROGRESS = 'in_progress';

    protected $dates = ['due_date', 'completed_at'];

    /**
     * The user the task is assigned to.
     */


public function assignedTo()
{
    return $this->belongsTo(User::class, 'assigned_to');
}

public function assignedBy()
{
    return $this->belongsTo(User::class, 'created_by');
}

    public function isAssignedToCurrentUser(): bool
    {
        return $this->assigned_to === Auth::id();
    }


public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
