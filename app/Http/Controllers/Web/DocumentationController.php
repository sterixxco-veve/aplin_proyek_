<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\DocumentationLink;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        $events = Event::visibleTo(auth()->user())
            ->with('documentationLinks')
            ->latest()
            ->get();

        return view('documentation.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('documentationLinks');

        return view('documentation.show', compact('event'));
    }

    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $request->validate([
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|max:5120',
            'google_drive_link' => 'nullable|url'
        ]);

        if ($request->hasFile('photos')) {

            foreach ($request->file('photos') as $photo) {

                $path = $photo->store(
                    'documentations',
                    'public'
                );

                DocumentationLink::create([
                    'id_event' => $event->id_event,
                    'file_path' => $path,
                    'google_drive_link' =>
                        $request->google_drive_link
                ]);
            }
        }

        return back()->with(
            'success',
            'Documentation saved successfully.'
        );
    }
}