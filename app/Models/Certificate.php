<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
class Certificate extends Model
{
    protected $primaryKey = 'id_cert';

    protected $fillable = [
        'id_event',
        'nama_penerima',
        'email_penerima',
        'nrp_penerima',
        'qr_token',
        'file_url'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}