<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Partially\Cabang as CabangResource;
use App\Http\Resources\Konsumen as KonsumenResource;
use App\Http\Resources\Partially\Pegawai as PegawaiResource;

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
            'cabang' =>  new CabangResource($this->cabang),
            'jenis' => $this->jenis,
            'konsumen' => new KonsumenResource($this->konsumen),
            'subtotal' => $this->subtotal,
            'diskon' => $this->diskon,
            'total' => $this->total,
            'uang_diterima' => $this->uang_diterima,
            'cs' => new PegawaiResource($this->cs),
            'kasir' => new PegawaiResource($this->kasir),
            'status' => $this->status,
            'tgl_transaksi' => $this->tgl_transaksi,
        ];
    }
}
