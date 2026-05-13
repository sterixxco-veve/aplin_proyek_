<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    protected $table = 'budget_categories';
    protected $primaryKey = 'id_category';

    protected $fillable = [
        'nama_kategori',
    ];

    public function eventBudgets()
    {
        return $this->hasMany(EventBudget::class, 'id_category', 'id_category');
    }
}
