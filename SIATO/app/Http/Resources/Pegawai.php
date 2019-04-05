<?php

namespace App\Http\Resources;

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
            'username' => $this->username,
            'nama' => $this->nama,
            'nomor_telepon' => $this->nomor_telepon,
            'alamat' => $this->alamat,
            'gaji' => $this->gaji,
            'role' => $this->role,
            'api_key' => $this->api_key,
        ];
    }
}
