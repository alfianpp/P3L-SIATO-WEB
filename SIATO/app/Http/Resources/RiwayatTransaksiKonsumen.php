<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Partially\Spareparts as SparepartsResource;

class RiwayatTransaksiKonsumen extends JsonResource
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
            'detail' => new SparepartsResource($this->spareparts),
            'jumlah' => $this->jumlah,
            'harga' => $this->harga,
            'tgl_transaksi' => $this->detail_penjualan->penjualan->tgl_transaksi,
        ];
    }
}
