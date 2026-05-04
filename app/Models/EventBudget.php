<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBudget extends Model
{
    protected $primaryKey = 'id_budget';

    protected $fillable = [
        'id_event',
        'id_category',
        'keterangan',
        'qty',
        'nominal_rencana'
    ];

    protected static function boot()
    {
        parent::boot();

        // ✅ AUTO SUBTOTAL
        static::saving(function ($model) {
            $model->sub_total = $model->qty * $model->nominal_rencana;
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}