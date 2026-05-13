<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBudget extends Model
{
    protected $primaryKey = 'id_budget';

    protected $fillable = [
        'id_event',
        'id_user',
        'id_category',
        'keterangan',
        'qty',
        'nominal_rencana'
    ];

    protected $casts = [
        'qty' => 'integer',
        'nominal_rencana' => 'decimal:2',
        'sub_total' => 'decimal:2',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function category()
    {
        return $this->belongsTo(BudgetCategory::class, 'id_category', 'id_category');
    }
}