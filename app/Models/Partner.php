<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
class Partner extends Model
{
    protected $primaryKey = 'id_partner';

    protected $fillable = [
        'id_event',
        'nama_partner',
        'jenis_partner',
        'assigned_pic',
        'status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'assigned_pic');
    }
}