<?php

namespace App\Models; // ← THIS is missing in your code

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $fillable = [
        'document_id',
        'title',
        'description',
        'author',
        'department',
        'file_path',
        'file_type',
        'category_id',
        'version',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
