<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentationLink extends Model
{
    protected $table = 'documentation_links';

    protected $primaryKey = 'id_doc';

    protected $fillable = [
        'id_event',
        'file_path',
        'google_drive_link'
    ];

    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'id_event',
            'id_event'
        );
    }
}