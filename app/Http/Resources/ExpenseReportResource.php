<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_expense,
            'nama_pengeluaran' => $this->nama_pengeluaran,
            'nominal' => $this->nominal,
            'qty' => $this->qty,
            'sub_total' => $this->sub_total,
            'is_reimbursed' => $this->is_reimbursed,

            'user' => [
                'id' => $this->user?->id_user,
                'name' => $this->user?->name,
            ],
        ];
    }
}