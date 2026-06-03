<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;

class Event extends Model
{
    protected $primaryKey = 'id_event';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_event',
        'id_org',
        'id_creator',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'id_creator', 'id_user');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\EventCategory::class, 'id_event_category', 'id_event_category');
    }

    public function rundowns()
    {
        return $this->hasMany(
            \App\Models\EventRundownItem::class,
            'id_event',
            'id_event'
        );
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

    public function partners()
    {
        return $this->hasMany(Partner::class, 'id_event', 'id_event')
            ->orderByDesc('created_at');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'id_event', 'id_event')
            ->orderByDesc('created_at');
    }

    public function documents()
    {
        return $this->hasMany(GeneratedDocument::class, 'id_event', 'id_event')
            ->orderByDesc('created_at');
    }

    public function expenses()
    {
        return $this->hasMany(ExpenseReport::class, 'id_event');
    }

    public function rundownItems()
    {
        return $this->hasMany(EventRundownItem::class, 'id_event', 'id_event')
            ->orderBy('day_number')
            ->orderBy('waktu_mulai');
    }

    public function scopeVisibleTo($query, $user)
    {
        $userId = $user instanceof User ? $user->id_user : $user;

        return $query->where(function ($eventQuery) use ($userId) {
            $eventQuery->where('id_creator', $userId)
                ->orWhereHas('committees', function ($committeeQuery) use ($userId) {
                    $committeeQuery->where('id_user', $userId);
                });
        });
    }

    public function isVisibleTo($user): bool
    {
        $userId = $user instanceof User ? $user->id_user : $user;

        return $this->id_creator === $userId
            || $this->committees()->where('id_user', $userId)->exists();
    }

    public function canManageBy($user): bool
    {
        return $this->isVisibleTo($user);
    }

    public function canManageCommitteeBy($user): bool
    {
        $userId = $user instanceof User ? $user->id_user : $user;

        if ($this->id_creator === $userId) {
            return true;
        }

        return $this->hasCommitteeRole($user, $this->bphRoles());
    }

    protected function bphRoles(): array
    {
        return [
            'ketua',
            'wakil ketua',
            'sekretaris',
            'bendahara',
            'ketua acara',
        ];
    }

    protected function koorRoles(): array
    {
        return [
            'koordinator',
        ];
    }

    protected function operationalRoles(): array
    {
        return array_merge($this->bphRoles(), $this->koorRoles());
    }

    protected function hasCommitteeRole($user, array $roles): bool
    {
        $userId = $user instanceof User ? $user->id_user : $user;

        return $this->committees()
            ->where('id_user', $userId)
            ->get()
            ->contains(function ($committee) use ($roles) {
                $role = Str::lower(trim($committee->jabatan ?? ''));

                return in_array($role, $roles, true);
            });
    }

    public function canManageOperationalBy($user): bool
    {
        $userId = $user instanceof User ? $user->id_user : $user;

        if ($this->id_creator === $userId) {
            return true;
        }

        return $this->hasCommitteeRole($user, $this->operationalRoles());
    }

    public function canManageRundownBy($user): bool
    {
        return $this->canManageOperationalBy($user);
    }

    public function canManagePartnerBy($user): bool
    {
        return $this->canManageOperationalBy($user);
    }

    public function canManageCertificateBy($user): bool
    {
        return $this->canManageOperationalBy($user);
    }

    public function canManageDocumentBy($user): bool
    {
        return $this->canManageOperationalBy($user);
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
        return (float) $this->expenses()
            ->where(function ($query) {
                $query->where('approval_status', 'accepted')
                    ->orWhere('is_reimbursed', true);
            })
            ->sum('sub_total');
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