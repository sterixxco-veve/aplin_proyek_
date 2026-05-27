<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\GeneratedDocument;

class DocumentService
{
    public function generate($docId)
    {
        $doc = GeneratedDocument::with('event')
            ->findOrFail($docId);

        // =========================
        // DOCUMENT PAYLOAD
        // =========================

        $data = $doc->snapshot_data ?? [];

        // fallback tambahan
        $data['title'] = $doc->title;
        $data['document'] = $doc;
        $data['event'] = $doc->event;

        // =========================
        // TEMPLATE MAPPING
        // =========================

        $template = match ($doc->document_type) {

            'proposal' =>
                'documents.proposal-template',

            'lpj' =>
                'documents.lpj-template',

            'invitation_letter' =>
                'documents.invitation-letter-template',

            'mou_partner' =>
                'documents.mou-template',

            default =>
                'documents.proposal-template',
        };

        // =========================
        // GENERATE PDF
        // =========================

        $pdf = Pdf::loadView(
            $template,
            $data
        );

        // =========================
        // STORAGE PATH
        // =========================

        $path =
            'documents/doc_' .
            $docId .
            '_' .
            time() .
            '.pdf';

        // =========================
        // SAVE PDF
        // =========================

        Storage::disk('public')->put(
            $path,
            $pdf->output()
        );

        // =========================
        // RETURN URL
        // =========================

        return Storage::url($path);
    }
}