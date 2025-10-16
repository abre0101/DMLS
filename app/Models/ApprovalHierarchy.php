<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHierarchy extends Model
{
    use HasFactory;

    protected $fillable = ['document_type', 'role_id', 'level', 'next_level_id'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function nextApproverRole()
    {
        return $this->belongsTo(Role::class, 'next_level_id');
    }
}