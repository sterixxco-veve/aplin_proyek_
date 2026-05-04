<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventCommitteeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_comm,
            'jabatan' => $this->jabatan,

            'user' => [
                'id' => $this->user?->id_user,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
            ],

            'division' => [
                'id' => $this->division?->id_divisi,
                'nama' => $this->division?->nama_divisi,
            ],
        ];
    }
}