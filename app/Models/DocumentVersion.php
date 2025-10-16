<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id',
        'title',
        'description',
        'author',
        'department',
        'file_path',
        'file_type',
        'tags',
        'watermark',
        'version',
    ];

    protected $casts = [
        'tags' => 'array',
    ];
}
