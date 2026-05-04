<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    // 🔥 pakai default Laravel
    protected $primaryKey = 'id_task';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_event',
        'id_divisi',
        'nama_tugas',
        'assigned_to',
        'status',
        'deadline',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'id_divisi', 'id_divisi');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id_user');
    }

    // =========================
    // HELPER
    // =========================

    public function isDone()
    {
        return $this->status === 'done';
    }

    public function isInProgress()
    {
        return $this->status === 'progress';
    }

    public function isTodo()
    {
        return $this->status === 'todo';
    }
}