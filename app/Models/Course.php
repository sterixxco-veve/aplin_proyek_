<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    protected $fillable = [
        'user_id', 
        'code', 
        'name', 
        'credits', 
        'semester_taken', 
        'difficulty', 
        'category'
    ];

    /**
     * Relasi ke jadwal kuliah (bisa banyak sesi per MK)
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Relasi ke nilai mata kuliah
     */
    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }
}