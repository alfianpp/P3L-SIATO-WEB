<?php

namespace App\Http\Resources\Partially;

use Illuminate\Http\Resources\Json\JsonResource;

class Pegawai extends JsonResource
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
        ];
    }
}
