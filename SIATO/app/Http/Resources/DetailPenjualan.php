<?php

namespace App\Http\Resources;

use App\Http\Resources\DetailPenjualanSpareparts as DetailPenjualanSparepartsResource;
use App\Http\Resources\DetailPenjualanJasaService as DetailPenjualanJasaServiceResource;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailPenjualan extends JsonResource
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
            'id_penjualan' => $this->id_penjualan,
            'nomor_polisi' => $this->nomor_polisi,
            'id_montir' => $this->id_montir,
            'spareparts' => DetailPenjualanSparepartsResource::collection($this->detailSpareparts),
            'jasa_service' => DetailPenjualanJasaServiceResource::collection($this->detailJasaService),
        ];
    }
}
