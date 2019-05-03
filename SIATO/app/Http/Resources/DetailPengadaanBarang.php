<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Partially\Spareparts as SparepartsResource;

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
            'spareparts' => new SparepartsResource($this->spareparts),
            'jumlah_pesan' => $this->jumlah_pesan,
            'jumlah_datang' => $this->jumlah_datang,
            'harga' => $this->harga,
        ];
    }
}
