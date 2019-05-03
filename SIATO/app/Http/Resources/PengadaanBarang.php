<?php

namespace App\Http\Resources;

use App\Http\Resources\Partially\Supplier as SupplierResource;

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
            'supplier' => new SupplierResource($this->supplier),
            'total' => $this->total,
            'status' => $this->status,
            'tgl_transaksi' => $this->tgl_transaksi,
        ];
    }
}
