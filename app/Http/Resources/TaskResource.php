<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_task,
            'nama_tugas' => $this->nama_tugas,
            'brief' => $this->brief,
            'status' => $this->status,
            'deadline' => $this->deadline,

            'division' => [
                'id' => $this->division?->id_divisi,
                'nama' => $this->division?->nama_divisi,
            ],

            'assignee' => [
                'id' => $this->assignee?->id_user,
                'name' => $this->assignee?->name,
            ],
        ];
    }
}