<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailPenjualanSpareparts extends JsonResource
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
            'id_detail_penjualan' => $this->id_detail_penjualan,
            'kode_spareparts' => $this->kode_spareparts,
            'jumlah' => $this->jumlah,
            'harga' => $this->harga,
        ];
    }
}
