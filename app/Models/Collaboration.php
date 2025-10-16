<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collaboration extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'role', // e.g., viewer, editor, etc.
    ];

    /**
     * Get the user involved in the collaboration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the document being collaborated on.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
