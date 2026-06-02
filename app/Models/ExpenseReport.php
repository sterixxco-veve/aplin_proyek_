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
        'approval_status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'is_reimbursed',
        'reimbursed_at'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'qty' => 'integer',
        'is_reimbursed' => 'boolean',
        'approved_at' => 'datetime',
        'reimbursed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // ✅ AUTO SUBTOTAL + normalisasi status finance.
        static::saving(function ($model) {
            $model->sub_total = $model->qty * $model->nominal;

            // Data lama/UI mungkin memakai "declined". Database harus memakai "rejected".
            if ($model->approval_status === 'declined') {
                $model->approval_status = 'rejected';
            }

            if (!in_array($model->approval_status, ['pending', 'accepted', 'rejected'], true)) {
                $model->approval_status = 'pending';
            }

            // Rejected/pending tidak boleh ikut status reimbursed.
            if ($model->approval_status !== 'accepted') {
                $model->is_reimbursed = false;
                $model->reimbursed_at = null;
            }

            if ($model->approval_status !== 'rejected') {
                $model->rejection_reason = null;
            }
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

    public function getDisplayStatusAttribute(): string
    {
        if ($this->approval_status === 'rejected') {
            return 'declined';
        }

        if ($this->approval_status === 'accepted' && $this->is_reimbursed) {
            return 'reimbursed';
        }

        return $this->approval_status ?: 'pending';
    }

    public function getDisplayStatusLabelAttribute(): string
    {
        return match ($this->display_status) {
            'declined' => 'Declined',
            'reimbursed' => 'Reimbursed',
            'accepted' => 'Accepted',
            default => 'Pending',
        };
    }

    public function isLockedForModification(): bool
    {
        // Keputusan terakhir (revisi dosen): status finance tidak mengunci aksi edit/delete.
        // Permission tetap dikontrol oleh role di controller.
        return false;
    }
}
