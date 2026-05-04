<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id_cert,
            'nama_penerima' => $this->nama_penerima,
            'email_penerima' => $this->email_penerima,
            'file_url' => $this->file_url,
        ];
    }
}