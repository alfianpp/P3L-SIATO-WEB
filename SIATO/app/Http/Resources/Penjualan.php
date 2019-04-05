<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Penjualan extends JsonResource
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
            'id_cabang' => $this->id_cabang,
            'jenis' => $this->jenis,
            'id_konsumen' => $this->id_konsumen,
            'subtotal' => $this->subtotal,
            'diskon' => $this->diskon,
            'total' => $this->total,
            'uang_diterima' => $this->uang_diterima,
            'id_cs' => $this->id_cs,
            'id_kasir' => $this->id_kasir,
            'status' => $this->status,
            'tgl_transaksi' => $this->tgl_transaksi,
        ];
    }
}
