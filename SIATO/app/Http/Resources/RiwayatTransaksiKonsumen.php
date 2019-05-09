<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Partially\Pegawai as PegawaiResource;
use App\Http\Resources\Partially\Spareparts as SparepartsResource;
use App\Http\Resources\Partially\Cabang as CabangResource;

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
            'cabang' =>  new CabangResource($this->cabang),
            'jenis' => $this->jenis,
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
