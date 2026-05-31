<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;

class CertificateController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $events = Event::visibleTo($user)
            ->withCount('certificates')
            ->latest()
            ->get();

        return view(
            'certificates.index',
            compact('events')
        );
    }

    public function showEvent($eventId)
    {
        $event = Event::visibleTo(auth()->user())
            ->with('certificates')
            ->findOrFail($eventId);

        return view('certificates.show', [
            'event' => $event,
            'certificates' => $event->certificates,
            'canManageCertificate' => $event->canManageCertificateBy(auth()->user()),
            'templatePath' => session('template_path')
        ]);
    }
}
