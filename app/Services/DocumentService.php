<?php

namespace App\Services;

use App\Models\GeneratedDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function generate($docId)
    {
        // Memastikan semua relasi termuat dengan aman (termasuk rundownItems)
        $doc = GeneratedDocument::with([
            'event',
            'event.organization',
            'event.rundownItems',
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
        // FALLBACK DATA & RE-MAPPING
        // =========================
        $data['title'] = $doc->title;
        $data['document'] = $doc;
        $data['event'] = $event;
        $data['event_name'] = $data['event_name'] ?? $event?->nama_event;
        $data['organization'] = $organization;
        $data['organization_name'] = $data['organization_name'] ?? $organization?->nama_org;
        $data['organization_logo'] = $data['organization_logo'] ?? $organization?->logo_path;
        
        // FIX PERBAIKAN UTAMA: Paksa bypass langsung mengambil data valid & murni dari tabel events di DB
        // Ini mencegah bug if null akibat input readonly di view modal wizard
        $data['latar_belakang'] = !empty($event?->latar_belakang) ? $event->latar_belakang : ($data['latar_belakang'] ?? 'Belum ada data latar belakang.');
        $data['tujuan'] = !empty($event?->tujuan) ? $event->tujuan : ($data['tujuan'] ?? 'Belum ada data tujuan.');
        
        // Deskripsi kegiatan khusus Proposal dinamis (diambil dari snapshot input form modal Step 3)
        $data['deskripsi_kegiatan'] = $data['deskripsi_kegiatan'] ?? $data['description_text'] ?? null;

        $data['academic_year'] = $data['academic_year'] ?? ($eventDate
            ? $eventDate->format('Y') . '/' . $eventDate->copy()->addYear()->format('Y')
            : null);
            
        $data['event_date'] = $data['event_date'] ?? ($eventDate?->format('Y-m-d'));
        $data['venue'] = $data['venue'] ?? $data['event_location'] ?? $data['realized_venue'] ?? null;
        $data['event_location'] = $data['event_location'] ?? $data['venue'] ?? null;
        $data['date_sent'] = $data['date_sent'] ?? now()->format('d F Y');
        $data['subject'] = $data['subject'] ?? ('Undangan ' . ($event?->nama_event ?? 'Kegiatan'));
        $data['invitation_body_text'] = $data['invitation_body_text'] ?? 'Dengan hormat, kami mengundang Bapak/Ibu untuk menghadiri kegiatan tersebut.';
        
        // =========================
        // LPJ DATA MAPPING FIX
        // =========================
        $data['realized_date'] = $data['realized_date'] ?? $data['realization_date'] ?? ($eventDate?->format('Y-m-d'));
        $data['realized_venue'] = $data['realized_venue'] ?? $data['venue'] ?? $data['event_location'] ?? null;
        $data['participant_count'] = $data['participant_count'] ?? 0;
        
        // Memetakan form evaluasi & feedback baru agar bisa dicetak langsung di LPJ Template PDF
        $data['execution_summary'] = $data['execution_summary'] ?? $data['implementation'] ?? null;
        $data['implementation'] = $data['implementation'] ?? $data['execution_summary'] ?? null;
        $data['evaluation'] = $data['evaluation'] ?? null;
        $data['kritik'] = $data['kritik'] ?? null;
        $data['saran'] = $data['saran'] ?? null;

        $data['internal_count'] = $data['internal_count'] ?? 0;
        $data['public_count'] = $data['public_count'] ?? 0;
        
        $data['first_party'] = $data['first_party'] ?? $organization?->nama_org;
        $data['first_party_role'] = $data['first_party_role'] ?? 'Pihak Pertama';
        $data['second_party_role'] = $data['second_party_role'] ?? 'Pihak Kedua';

        // =========================
        // RUNDOWN DATA ADAPTER
        // =========================
        $data['rundowns'] = $event?->rundownItems ?? $event?->rundowns ?? collect();

        // =========================
        // COMMITTEE
        // =========================
        $data['committees'] = $event?->committees ?? collect();

        // =========================
        // PARTICIPANTS
        // =========================
        $data['participants'] = ($event?->certificates ?? collect())
            ->map(fn ($cert) => (object) [
                'name' => $cert->nama_penerima,
                'nrp' => $cert->nrp_penerima ?? '',
                'prodi' => $cert->prodi ?? '',
            ]);

        // =========================
        // BUDGET MAPPING RE-ENGINEERING
        // =========================
        $pemasukanItems = [];
        $pengeluaranItems = [];

        // Mengambil data relasi anggaran dari model event (Proposal Budget)
        $mappedBudgets = $event?->budgets ?? collect();

        if ($doc->document_type === 'lpj') {
            // Untuk LPJ:
            // 1. PEMASUKAN = SELURUH DATA DARI BUDGET PROPOSAL (karena itu adalah total anggaran dana yang disetujui untuk kegiatan)
            $pemasukanItems = $mappedBudgets->all();

            // 2. PENGELUARAN = DATA REALISASI DARI FINANCE (ExpenseReport) yang berstatus accepted atau reimbursed
            $mappedExpenses = $event?->expenses()
                ->where(function ($query) {
                    $query->where('approval_status', 'accepted')
                        ->orWhere('is_reimbursed', true);
                })->get() ?? collect();

            foreach ($mappedExpenses as $exp) {
                // Jembatan kompatibilitas dengan template blade agar tidak kosong saat membaca $peng->keterangan
                $exp->keterangan = $exp->nama_pengeluaran ?? $exp->keterangan ?? '-';
                $pengeluaranItems[] = $exp;
            }
        } else {
            // Untuk Proposal & dokumen selain LPJ:
            foreach ($mappedBudgets as $b) {
                $categoryName = strtolower($b->category?->nama_kategori ?? '');

                if (str_contains($categoryName, 'pemasukan')) {
                    $pemasukanItems[] = $b;
                } else {
                    $pengeluaranItems[] = $b;
                }
            }
        }

        // Masukkan kembali ke array $data utama agar dibaca oleh engine DomPDF
        $data['pemasukanItems'] = $pemasukanItems;
        $data['pengeluaranItems'] = $pengeluaranItems;
        

        // =========================
        // DEFAULT TARGET PROPOSAL
        // =========================
        $data['target_sma'] = $data['target_sma'] ?? 0;
        $data['target_mahasiswa'] = $data['target_mahasiswa'] ?? 0;
        $data['target_umum'] = $data['target_umum'] ?? 0;

        // =========================
        // TEMPLATE MAPPING
        // =========================
        $template = match ($doc->document_type) {
            'proposal'          => 'documents.proposal-template',
            'lpj'               => 'documents.lpj-template',
            'invitation_letter' => 'documents.invitation-letter-template',
            'mou_partner'       => 'documents.mou-template',
            default             => 'documents.proposal-template',
        };

        // =========================
        // GENERATE PDF
        // =========================
        $pdf = Pdf::loadView($template, $data);
        $pdf->setPaper('a4');

        // =========================
        // STORAGE PATH
        // =========================
        $path = 'documents/doc_' . $doc->id_document . '_' . time() . '.pdf';

        // =========================
        // SAVE PDF
        // =========================
        Storage::disk('public')->put($path, $pdf->output());

        // =========================
        // RETURN URL
        // =========================
        return Storage::url($path);
    }
}