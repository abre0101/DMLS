<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Document;
use App\Models\Department;
use App\Models\Task;
use App\Models\Role;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'manager_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function hasRole(string $role): bool
    {
        return optional($this->role)->name === strtolower($role);
    }

    public function canAccess(Document $document): bool
    {
        return $this->hasRole('director') || $this->hasRole('manager');
    }

    public function customRoles()
    {
        return $this->belongsToMany(CustomRole::class, 'custom_role_user');
    }

    public function hasCustomRole(string $roleName): bool
    {
        return $this->customRoles()->where('name', $roleName)->exists();
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_user', 'user_id', 'document_id');
    }

    public function collaborations()
    {
        return $this->belongsToMany(Document::class, 'document_user', 'user_id', 'document_id');
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    public function collaboratedDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_user', 'user_id', 'document_id');
    }

    public function pendingDocuments()
    {
        return $this->uploadedDocuments()->whereNull('manager_approval');
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Document::class, 'bookmarks')->withTimestamps();
    }

    public function pendingApprovals()
    {
        return $this->hasMany(ApprovalRequest::class, 'user_id')->where('status', 'pending');
    }

    public function letters()
    {
        return $this->hasMany(Letter::class, 'sender_id');
    }

    public function receivedLetters()
    {
        return $this->hasMany(Letter::class, 'receiver_id');
    }
}
