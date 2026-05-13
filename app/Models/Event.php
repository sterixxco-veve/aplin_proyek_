<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $primaryKey = 'id_event';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_event',
        'id_org',
        'id_event_category',
        'nama_event',
        'tgl_mulai',
        'status'
    ];

    protected $appends = ['progress', 'financial_summary'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_event) {
                $model->id_event = (string) Str::uuid();
            }
        });

        // ✅ AUTO STATUS UPDATE
        static::saving(function ($event) {
            if ($event->tgl_mulai <= now() && $event->status === 'planning') {
                $event->status = 'ongoing';
            }
        });
    }

    // RELATIONS
    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class, 'id_org', 'id_org');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\EventCategory::class, 'id_event_category', 'id_event_category');
    }

    public function committees()
    {
        return $this->hasMany(\App\Models\EventCommittee::class, 'id_event', 'id_event');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'id_event');
    }

    public function budgets()
    {
        return $this->hasMany(EventBudget::class, 'id_event');
    }

    public function expenses()
    {
        return $this->hasMany(ExpenseReport::class, 'id_event');
    }

    
    // ✅ PROGRESS
    public function getProgressAttribute()
    {
        $total = $this->tasks()->count();
        $done = $this->tasks()->where('status', 'done')->count();

        return $total ? round(($done / $total) * 100) : 0;
    }

    public function getKategoriAttribute()
    {
        return $this->category?->slug;
    }

    // ✅ FINANCIAL SUMMARY
    public function getTotalBudgetAttribute()
    {
        return (float) $this->budgets()->sum('sub_total');
    }

    public function getTotalExpenseAttribute()
    {
        return (float) $this->expenses()->sum('sub_total');
    }

    public function getRemainingBudgetAttribute()
    {
        return $this->total_budget - $this->total_expense;
    }

    public function getFinancialSummaryAttribute()
    {
        return [
            'total_budget' => round($this->total_budget, 2),
            'total_expense' => round($this->total_expense, 2),
            'remaining' => round($this->remaining_budget, 2),
        ];
    }
}