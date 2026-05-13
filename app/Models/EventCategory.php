<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $table = 'event_categories';
    protected $primaryKey = 'id_event_category';

    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'id_event_category', 'id_event_category');
    }
}
