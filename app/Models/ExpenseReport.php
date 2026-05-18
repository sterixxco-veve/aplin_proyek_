<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseReport extends Model
{
    protected $primaryKey = 'id_expense';

    protected $fillable = [
        'id_event',
        'id_user',
        'id_expense_category',
        'nama_pengeluaran',
        'nominal',
        'qty',
        'nomor_rekening',
        'bukti_nota_path',
        'is_reimbursed'
    ];

    protected static function boot()
    {
        parent::boot();

        // ✅ AUTO SUBTOTAL
        static::saving(function ($model) {
            $model->sub_total = $model->qty * $model->nominal;
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'id_expense_category', 'id_expense_category');
    }

    public function isLockedForModification(): bool
    {
        return in_array($this->approval_status, ['accepted', 'rejected', 'declined'], true);
    }
}