<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'approver_id',
        'step_order',
        'status',
        'comment',
        'signature',
        'approved_at',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
