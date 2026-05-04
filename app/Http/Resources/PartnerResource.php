<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_partner,
            'nama_partner' => $this->nama_partner,
            'jenis_partner' => $this->jenis_partner,
            'status' => $this->status,

            'pic' => [
                'id' => $this->pic?->id_user,
                'name' => $this->pic?->name,
            ],
        ];
    }
}