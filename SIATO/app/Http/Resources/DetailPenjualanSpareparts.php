<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Partially\Spareparts as SparepartsResource;

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
            'spareparts' => new SparepartsResource($this->spareparts),
            'jumlah' => $this->jumlah,
            'harga' => $this->harga,
        ];
    }
}
