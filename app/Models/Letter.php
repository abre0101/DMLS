<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'sender_id',
        'receiver_id',
        'content',
        'parent_id',
        'status',
        'direction',
        'is_read',
        'department',
        'seen_at',
    ];

    protected $casts = [
        'seen_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(LetterTemplate::class, 'template_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parent()
    {
        return $this->belongsTo(Letter::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Letter::class, 'parent_id');
    }
}
