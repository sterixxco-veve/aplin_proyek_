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
            ->with(['certificates'])
            ->latest()
            ->get();

        $certificates = $events->flatMap(function ($event) {
            return $event->certificates->map(function ($certificate) use ($event) {
                $certificate->setRelation('event', $event);

                return $certificate;
            });
        })->values();

        return view('certificates.index', compact('certificates'));
    }
}
