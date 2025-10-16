<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;


    protected $table = 'departments';

    // Define fillable fields for mass assignment
    protected $fillable = [
        'name', 
    ];
    
    protected $casts = [

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
     
    ];
public function letters()
{
    return $this->hasManyThrough(Letter::class, User::class, 'department_id', 'sender_id');
}

// In Department.php

// In Department model
public function employees()
{
    return $this->hasMany(User::class, 'department_id')
        ->whereHas('role', function ($q) {
            $q->where('name', 'employee');
        });
}

public function manager()
{
    return $this->belongsTo(User::class, 'manager_id');
}

    // Define relationships if needed
    public function users()
    {
        return $this->hasMany(User::class);
    }
public function documents()
{
    return $this->hasManyThrough(Document::class, User::class, 'department_id', 'user_id');
    // Adjust 'user_id' if Document relates to a different user field
}



}