<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
class InvitationLetter extends Model
{
    protected $primaryKey = 'id_surat';

    protected $fillable = [
        'id_event',
        'tipe_surat',
        'nama_penerima',
        'talk_title',
        'hari_acara',
        'waktu_acara',
        'tempat_acara',
        'file_url',
        'dibuat_oleh'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}