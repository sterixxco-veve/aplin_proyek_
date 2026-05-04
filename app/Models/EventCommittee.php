<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
class EventCommittee extends Model
{
    protected $primaryKey = 'id_comm';

    protected $fillable = [
        'id_event',
        'id_user',
        'id_divisi',
        'jabatan'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_divisi');
    }
}