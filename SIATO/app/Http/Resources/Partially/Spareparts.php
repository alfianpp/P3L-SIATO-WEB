<?php

namespace App\Http\Resources\Partially;

use Illuminate\Http\Resources\Json\JsonResource;

class Spareparts extends JsonResource
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
            'kode' => $this->kode,
            'nama' => $this->nama,
            'merk' => $this->merk,
        ];
    }
}
