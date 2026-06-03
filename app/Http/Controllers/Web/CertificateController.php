<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Certificate;

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

    public function verify($token)
    {
        $certificate = Certificate::with('event')
            ->where('qr_token', $token)
            ->first();

        if (!$certificate) {
            abort(404);
        }

        return view(
            'certificates.verify',
            compact('certificate')
        );
    }
}
