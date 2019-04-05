<?php

namespace App\Http\Resources;

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
            'tipe' => $this->tipe,
            'kode_peletakan' => $this->kode_peletakan,
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'stok' => $this->stok,
            'stok_minimal' => $this->stok_minimal,
            'url_gambar' => $this->url_gambar,
        ];
    }
}
