<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\GeneratedDocument;
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

        $service->generate($docId);

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
        $event = Event::visibleTo(auth()->user())->findOrFail($eventId);
        abort_unless($event->canManageDocumentBy(auth()->user()), 403, 'Tidak punya akses');

        $request->validate([
            'document_type' => ['required', 'in:proposal,lpj,invitation_letter,mou_partner,certificate,other'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'in:draft,generated,final,archived,failed'],
            'notes' => ['nullable', 'string'],
            
            // Validasi Deskripsi Kegiatan: WAJIB diisi jika tipenya PROPOSAL
            'deskripsi_kegiatan' => ['required_if:document_type,proposal', 'nullable', 'string'],

            // Validasi LPJ tetap aman
            'implementation' => ['required_if:document_type,lpj', 'nullable', 'string'],
            'evaluation'     => ['required_if:document_type,lpj', 'nullable', 'string'],
            'kritik'         => ['required_if:document_type,lpj', 'nullable', 'string'],
            'saran'          => ['required_if:document_type,lpj', 'nullable', 'string'],
        ]);

        $payload = $request->except([
            '_token',
            'title',
            'document_type',
            'status',
            'notes',
        ]);

        if ($request->hasFile('organization_logo')) {
            $payload['organization_logo'] = $request->file('organization_logo')->store('document-logos', 'public');
        }

        // Sekarang $payload otomatis mengemas latar_belakang, tujuan, dan deskripsi_kegiatan ke dalam snapshot_data JSON dengan aman!
        $document = GeneratedDocument::create([
            'id_event' => $event->id_event,
            'document_type' => $request->document_type,
            'title' => $request->title,
            'status' => $request->status ?? 'draft',
            'notes' => $request->notes,
            'snapshot_data' => $payload, 
            'generated_by' => auth()->id(),
        ]);

        $fileUrl = (new DocumentService())->generate($document->id_document);

        $document->update([
            'file_url' => $fileUrl,
            'status' => $request->status === 'draft' ? 'draft' : 'generated',
            'generated_at' => now(),
        ]);

        return back()->with('success', 'Document berhasil di-generate');
    }
}