<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $fillable = ['department_id', 'name', 'content'];
 protected $table = 'letter_templates';  // Specify your actual table name


    // Relation to department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relation to letters using this template
    public function letters()
    {
        return $this->hasMany(Letter::class, 'template_id');
    }

    // Count of associated letters
    public function lettersCount()
    {
        return $this->letters()->count();
    }

    // Latest associated letter
    public function latestLetter()
    {
        return $this->hasOne(Letter::class, 'template_id')->latestOfMany();
    }
}
