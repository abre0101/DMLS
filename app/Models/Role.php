<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    // Define which fields are mass assignable
    protected $fillable = ['name'];

public function users()
{
    return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
}

    /**
     * Relationship: A role has many permissions (many-to-many).
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Automatically lowercase role name before saving.
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
}
