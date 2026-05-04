<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $table = 'expense_categories';

    protected $primaryKey = 'id_expense_category';

    protected $fillable = [
        'nama_kategori',
    ];

    // =========================
    // RELATIONSHIP
    // =========================

    public function expenses()
    {
        return $this->hasMany(ExpenseReport::class, 'id_expense_category', 'id_expense_category');
    }
}