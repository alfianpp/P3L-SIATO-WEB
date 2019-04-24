<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Partially\Kendaraan as KendaraanResource;
use App\Http\Resources\Partially\Pegawai as PegawaiResource;

class DetailPenjualan extends JsonResource
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
            'id' => $this->id,
            'id_penjualan' => $this->id_penjualan,
            'kendaraan' => new KendaraanResource($this->kendaraan),
            'montir' => new PegawaiResource($this->montir),
        ];
    }
}
