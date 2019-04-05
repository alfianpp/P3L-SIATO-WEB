<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Kendaraan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nomor_polisi' => $this->nomor_polisi,
            'merk' => $this->merk,
            'tipe' => $this->tipe,
            'id_pemilik' => $this->id_pemilik,
        ];
    }
}
