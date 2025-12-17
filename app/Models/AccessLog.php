<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
    ];

    /**
     * Relationship: AccessLog belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
