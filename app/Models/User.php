<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_user',
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!$user->id_user) {
                $user->id_user = (string) Str::uuid();
            }
            // Set default role jika kosong
            if (!$user->role) {
                $user->role = 'member';
            }
        });
    }

    /**
     * ✅ AUTO HASH PASSWORD (Modern Style)
     * Menggunakan Laravel Attribute untuk mutator yang lebih bersih.
     * Ini akan otomatis menghash password saat kita melakukan User::create() atau $user->password = '...'
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::needsRehash($value) ? Hash::make($value) : $value,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RBAC HELPERS
    |--------------------------------------------------------------------------
    */

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function hasRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function committees()
    {
        return $this->hasMany(EventCommittee::class, 'id_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    public function organizations()
    {
        return $this->belongsToMany(
            Organization::class,
            'organization_members',
            'user_id',
            'organization_id',
            'id_user',
            'id_org'
        )->withPivot('role')->withTimestamps();
    }
    
    public function eventCommittees()
    {
        return $this->hasMany(EventCommittee::class, 'id_user', 'id_user');
    }
}