<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
class Division extends Model
{
    protected $primaryKey = 'id_divisi';

    protected $fillable = [
        'nama_divisi',
        'is_default'
    ];

    public function committees()
    {
        return $this->hasMany(EventCommittee::class, 'id_divisi');
    }
}