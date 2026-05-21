<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRundownItem extends Model
{
    protected $primaryKey = 'id_rundown';

    protected $fillable = [
        'id_event',
        'day_number',
        'session_group',
        'waktu_mulai',
        'waktu_selesai',
        'kegiatan',
        'assigned_to',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function assignedCommittee()
    {
        return $this->belongsTo(EventCommittee::class, 'assigned_to', 'id_comm');
    }
}
