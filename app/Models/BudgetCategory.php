<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    protected $table = 'budget_categories';
    protected $primaryKey = 'id_category';

    // Daftarkan field yang boleh diisi lewat mass assignment
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Relasi ke data anggaran Event (EventBudget)
     */
    public function eventBudgets()
    {
        return $this->hasMany(EventBudget::class, 'id_category', 'id_category');
    }
}