<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PengadaanBarang extends JsonResource
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
            'id_supplier' => $this->id_cabang,
            'total' => $this->total,
            'status' => $this->id_kasir,
            'tgl_transaksi' => $this->tgl_transaksi,
        ];
    }
}
