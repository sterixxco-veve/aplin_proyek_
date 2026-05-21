<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;

class DocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $events = Event::visibleTo($user)
            ->with(['documents.creator'])
            ->latest()
            ->get();

        $documents = $events->flatMap(function ($event) {
            return $event->documents->map(function ($document) use ($event) {
                $document->setRelation('event', $event);

                return $document;
            });
        })->values();

        return view('documents.index', compact('documents'));
    }
}
