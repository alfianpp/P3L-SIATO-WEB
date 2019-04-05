<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Konsumen extends JsonResource
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
            'nama' => $this->nama,
            'nomor_telepon' => $this->nomor_telepon,
            'alamat' => $this->alamat,
        ];
    }
}
