<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'key', 'value'];

    // Define the relationship with the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
