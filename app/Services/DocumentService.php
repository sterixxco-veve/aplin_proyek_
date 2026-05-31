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
        $event = $doc->event;
        $organization = $event?->organization;
        $eventDate = $event?->tgl_mulai ? \Carbon\Carbon::parse($event->tgl_mulai) : null;

        // =========================
        // FALLBACK DATA
        // =========================

        $data['title'] = $doc->title;
        $data['document'] = $doc;
        $data['event'] = $event;
        $data['event_name'] = $data['event_name'] ?? $event?->nama_event;
        $data['organization'] = $organization;
        $data['organization_name'] = $data['organization_name'] ?? $organization?->nama_org;
        $data['organization_logo'] = $data['organization_logo'] ?? $organization?->logo_path;
        $data['academic_year'] = $data['academic_year'] ?? ($eventDate
            ? $eventDate->format('Y') . '/' . $eventDate->copy()->addYear()->format('Y')
            : null);
        $data['event_date'] = $data['event_date'] ?? ($eventDate?->format('Y-m-d'));
        $data['venue'] = $data['venue'] ?? $data['event_location'] ?? $data['realized_venue'] ?? null;
        $data['event_location'] = $data['event_location'] ?? $data['venue'] ?? null;
        $data['date_sent'] = $data['date_sent'] ?? now()->format('d F Y');
        $data['subject'] = $data['subject'] ?? ('Undangan ' . ($event?->nama_event ?? 'Kegiatan'));
        $data['invitation_body_text'] = $data['invitation_body_text'] ?? 'Dengan hormat, kami mengundang Bapak/Ibu untuk menghadiri kegiatan tersebut.';
        $data['realized_date'] = $data['realized_date'] ?? $data['realization_date'] ?? ($eventDate?->format('Y-m-d'));
        $data['realized_venue'] = $data['realized_venue'] ?? $data['venue'] ?? $data['event_location'] ?? null;
        $data['participant_count'] = $data['participant_count'] ?? 0;
        $data['internal_count'] = $data['internal_count'] ?? 0;
        $data['public_count'] = $data['public_count'] ?? 0;
        $data['execution_summary'] = $data['execution_summary'] ?? $data['implementation'] ?? null;
        $data['first_party'] = $data['first_party'] ?? $organization?->nama_org;
        $data['first_party_role'] = $data['first_party_role'] ?? 'Pihak Pertama';
        $data['second_party_role'] = $data['second_party_role'] ?? 'Pihak Kedua';

        // =========================
        // ORGANIZATION
        // =========================

        $data['organization'] = $organization;

        // =========================
        // RUNDOWN
        // =========================
        $data['rundowns'] =
            $event?->rundowns ?? collect();

        // =========================
        // COMMITTEE
        // =========================

        $data['committees'] =
            $event?->committees ?? collect();

        // =========================
        // BUDGET
        // =========================

        $data['budgets'] =
            $event?->budgets ?? collect();

        // =========================
        // DEFAULT TARGET
        // =========================
        $data['target_sma'] = $data['target_sma'] ?? 0;
        $data['target_mahasiswa'] = $data['target_mahasiswa'] ?? 0;
        $data['target_umum'] = $data['target_umum'] ?? 0;

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