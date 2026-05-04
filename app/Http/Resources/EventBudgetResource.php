<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventBudgetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_budget,
            'keterangan' => $this->keterangan,
            'qty' => $this->qty,
            'nominal_rencana' => $this->nominal_rencana,
            'sub_total' => $this->sub_total,

            'category' => [
                'id' => $this->category?->id_category,
                'nama' => $this->category?->nama_kategori,
            ],
        ];
    }
}