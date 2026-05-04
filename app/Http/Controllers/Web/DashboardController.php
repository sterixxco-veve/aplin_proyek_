<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🔹 Organization user
        $organizations = $user->organizations;

        // 🔹 Event yang dia ikuti (via event_committees)
        $events = \App\Models\Event::whereHas('committees', function ($q) use ($user) {
            $q->where('id_user', $user->id_user);
        })->get();

        return view('dashboard', compact('organizations', 'events'));
    }
}