<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvitationLetterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_surat,
            'tipe_surat' => $this->tipe_surat,
            'nama_penerima' => $this->nama_penerima,
            'talk_title' => $this->talk_title,
            'hari_acara' => $this->hari_acara,
            'waktu_acara' => $this->waktu_acara,
            'tempat_acara' => $this->tempat_acara,
            'file_url' => $this->file_url,
        ];
    }
}