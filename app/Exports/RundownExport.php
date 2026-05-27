<?php

namespace App\Exports;

use App\Models\EventRundownItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RundownExport implements FromCollection, WithHeadings
{
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function collection()
    {
        return EventRundownItem::where('id_event', $this->eventId)
            ->with('assignedCommittee.user')
            ->orderBy('day_number')
            ->orderBy('waktu_mulai')
            ->get()
            ->map(function ($item) {
                return [
                    'hari' => $item->day_number,
                    'sesi' => $item->session_group ?? '',
                    'waktu_mulai' => $item->waktu_mulai,
                    'waktu_selesai' => $item->waktu_selesai,
                    'kegiatan' => $item->kegiatan,
                    'penanggung_jawab' => $item->assignedCommittee?->user?->name ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Hari',
            'Sesi',
            'Waktu Mulai',
            'Waktu Selesai',
            'Kegiatan',
            'Penanggung Jawab',
        ];
    }
}