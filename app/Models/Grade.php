<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'user_id', 
        'course_id', 
        'letter_grade', 
        'numeric_point', 
        'is_final'
    ];

    protected $casts = [
        'is_final' => 'boolean',
        'numeric_point' => 'decimal:2'
    ];
}