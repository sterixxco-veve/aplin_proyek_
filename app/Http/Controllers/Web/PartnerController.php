<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;

class PartnerController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $events = Event::visibleTo($user)
            ->with(['partners.pic'])
            ->latest()
            ->get();

        $partners = $events->flatMap(function ($event) {
            return $event->partners->map(function ($partner) use ($event) {
                $partner->setRelation('event', $event);

                return $partner;
            });
        })->values();

        return view('partners.index', compact('partners'));
    }
}
