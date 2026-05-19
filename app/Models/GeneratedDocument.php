<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneratedDocument extends Model
{
    use SoftDeletes;

    protected $table = 'generated_documents';
    protected $primaryKey = 'id_document';

    protected $fillable = [
        'id_event',
        'document_type',
        'title',
        'file_url',
        'version_number',
        'status',
        'generated_by',
        'generated_at',
        'template_name',
        'template_version',
        'reference_type',
        'reference_id',
        'snapshot_data',
        'notes',
    ];

    protected $casts = [
        'snapshot_data' => 'array',
        'generated_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'generated_by', 'id_user');
    }
}
