<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_partner';

    protected $fillable = [
        'id_event',
        'nama_partner',
        'jenis_partner',
        'assigned_pic',
        'status',
        'contact_person_name',
        'contact_person_position',
        'contact_person_email',
        'contact_person_phone',
        'website',
        'instagram',
        'alamat',
        'logo_path',
        'sponsor_level',
        'contribution_type',
        'contribution_value',
        'contribution_description',
        'benefit',
        'proposal_sent_at',
        'deal_at',
        'rejected_at',
        'rejection_reason',
        'proposal_file_url',
        'mou_file_url',
        'agreement_file_url',
        'notes',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'assigned_pic', 'id_user');
    }
}