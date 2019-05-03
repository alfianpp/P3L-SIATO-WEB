<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Partially\Konsumen as KonsumenResource;

class Kendaraan extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'nomor_polisi' => $this->nomor_polisi,
            'merk' => $this->merk,
            'tipe' => $this->tipe,
            'pemilik' => new KonsumenResource($this->konsumen),
        ];
    }
}
