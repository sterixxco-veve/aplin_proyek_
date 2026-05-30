<?php

namespace App\Services;

use App\Models\GeneratedDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function generate($docId)
    {
        $doc = GeneratedDocument::with([
            'event',
            'event.organization',
            'event.rundowns',
            'event.committees.user',
            'event.budgets.category'
        ])->findOrFail($docId);

        // =========================
        // DOCUMENT PAYLOAD
        // =========================

        $data = $doc->snapshot_data ?? [];

        // =========================
        // FALLBACK DATA
        // =========================

        $data['title'] = $doc->title;
        $data['document'] = $doc;
        $data['event'] = $doc->event;

        // =========================
        // ORGANIZATION
        // =========================

        $data['organization'] =
            $doc->event->organization ?? null;

        // =========================
        // RUNDOWN
        // =========================

        $data['rundowns'] =
            $doc->event->rundowns ?? collect();

        // =========================
        // COMMITTEE
        // =========================

        $data['committees'] =
            $doc->event->committees ?? collect();

        // =========================
        // BUDGET
        // =========================

        $data['budgets'] =
            $doc->event->budgets ?? collect();

        // =========================
        // DEFAULT ORGANIZATION NAME
        // =========================

        if (
            empty($data['organization_name']) &&
            isset($doc->event->organization)
        ) {
            $data['organization_name'] =
                $doc->event->organization->nama_org;
        }

        // =========================
        // DEFAULT LOGO
        // =========================

        if (
            empty($data['organization_logo']) &&
            isset($doc->event->organization)
        ) {
            $data['organization_logo'] =
                $doc->event->organization->logo_path;
        }

        // =========================
        // DEFAULT TARGET
        // =========================

        $data['target_sma'] =
            $data['target_sma'] ?? 0;

        $data['target_mahasiswa'] =
            $data['target_mahasiswa'] ?? 0;

        $data['target_umum'] =
            $data['target_umum'] ?? 0;

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

        $pdf->setPaper('a4');

        // =========================
        // STORAGE PATH
        // =========================

        $path =
            'documents/doc_' .
            $doc->id_document .
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