<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EventBudget;
use App\Models\ExpenseReport;
use App\Models\Event;
use App\Models\EventCategory;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🔹 Organization user
        $organizations = $user->organizations;
        $categories = EventCategory::orderBy('nama_kategori')->get();

        // 🔹 Event yang dia ikuti (via event_committees)
        $events = \App\Models\Event::visibleTo($user)->latest()->get();
        $visibleEventIds = $events->pluck('id_event');
        $totalBudget = (float) EventBudget::whereIn('id_event', $visibleEventIds)->sum('sub_total');
        $totalExpense = (float) ExpenseReport::whereIn('id_event', $visibleEventIds)
            ->where(function ($query) {
                $query->where('approval_status', 'accepted')
                    ->orWhere('is_reimbursed', true);
            })
            ->sum('sub_total');
        $financeSummary = [
            'total_budget' => $totalBudget,
            'total_expense' => $totalExpense,
            'remaining' => $totalBudget - $totalExpense,
        ];

        return view('dashboard', compact('organizations', 'categories', 'events', 'financeSummary'));
    }
}