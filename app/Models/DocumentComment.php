<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentComment extends Model
{
    protected $fillable = [
        'document_id',  // ✅ Add this line
        'user_id',
        'comment',
        // Add any other fields that are mass assignable
    ];

    // Optional: relationships or other logic
}
