<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomRole extends Model
{
    protected $table = 'custom_roles';

    protected $fillable = [
        'name',
        'label',
        'description',
        'department_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'custom_role_user');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
