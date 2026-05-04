<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_event,
            'nama_event' => $this->nama_event,
            'kategori' => $this->kategori,
            'tgl_mulai' => $this->tgl_mulai,
            'status' => $this->status,

            // ✅ NEW
            'progress' => $this->progress,
            'financial' => $this->financial_summary,

            'organization' => [
                'id' => $this->organization?->id_org,
                'nama' => $this->organization?->nama_org,
            ],
        ];
    }
}