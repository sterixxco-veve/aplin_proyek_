<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
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

        return view('dashboard', compact('organizations', 'categories', 'events'));
    }
}