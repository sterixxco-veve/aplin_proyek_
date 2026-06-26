<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
class Organization extends Model
{
    protected $primaryKey = 'id_org';
    
    protected $fillable = [
        'nama_org',
        'logo_path'
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'id_org');
    }

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'organization_members',
            'organization_id',   // FK di pivot ke organization
            'user_id',           // FK di pivot ke user
            'id_org',            // PK di organizations
            'id_user'            // PK di users
        )->withPivot('id_divisi', 'position')->withTimestamps();
    }

    public function hasRole($userId, $role)
    {
        $user = User::find($userId);
        if ($user && $user->email === 'admin@mail.com') {
            return true;
        }

        if ($role === 'admin_org' || $role === 'admin') {
            return $this->members()
                ->where('users.id_user', $userId)
                ->wherePivot('id_divisi', 1)
                ->exists();
        }
        return false;
    }
}