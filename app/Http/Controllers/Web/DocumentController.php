<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDocument;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index($eventId)
    {
        $event = Event::visibleTo(auth()->user())
            ->with([
                'documents.creator'
            ])
            ->findOrFail($eventId);

        $documents = $event->documents;

        $canManageDocument =
            $event->canManageDocumentBy(auth()->user());

        return view(
            'events.documents',
            compact(
                'event',
                'documents',
                'canManageDocument'
            )
        );
    }

    public function listEvent()
    {
        $events = Event::visibleTo(auth()->user())
            ->latest()
            ->get();

        return view(
            'events.documents-list',
            compact('events')
        );
    }

    // =====================================
    // GENERATE DOCUMENT
    // =====================================

    public function generate($docId)
    {
        $service = new DocumentService();

        $path = $service->generate($docId);

        return back()->with(
            'success',
            'Document berhasil di-generate'
        );
    }

    // =====================================
    // STORE DOCUMENT
    // =====================================

    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        // =========================
        // SAVE FORM PAYLOAD
        // =========================

        $payload = $request->except([
            '_token',
            'title',
            'document_type',
            'status',
            'notes',
        ]);

        // =========================
        // CREATE DOCUMENT ROW
        // =========================

        $document = EventDocument::create([

            'id_event' =>
                $event->id_event,

            'document_type' =>
                $request->document_type,

            'title' =>
                $request->title,

            'status' =>
                'draft',

            'notes' =>
                $request->notes,

            'generated_payload' =>
                json_encode($payload),

            'generated_by' =>
                auth()->id(),
        ]);

        // =========================
        // AUTO GENERATE PDF
        // =========================

      $service = new \App\Services\DocumentService();

        try {
            $filePath = $service->generate(
                $document->id_document
            );

            dd($filePath);

        } catch (\Exception $e) {

            dd($e->getMessage());
        }

        $document->update([
            'file_url' => $filePath,
            'status' => 'generated',
        ]);
        return redirect()
            ->back()
            ->with(
                'success',
                'Document berhasil di-generate'
            );
    }
}