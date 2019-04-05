<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailPenjualanJasaService extends JsonResource
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
            'id_jasaservice' => $this->id_jasaservice,
            'jumlah' => $this->jumlah,
            'harga' => $this->harga,
        ];
    }
}
