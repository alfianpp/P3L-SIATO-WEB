<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailPengadaanBarang extends JsonResource
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
            'id_pengadaan_barang' => $this->id_pengadaan_barang,
            'kode_spareparts' => $this->kode_spareparts,
            'jumlah_pesan' => $this->jumlah_pesan,
            'jumlah_datang' => $this->jumlah_datang,
            'harga' => $this->harga,
        ];
    }
}
