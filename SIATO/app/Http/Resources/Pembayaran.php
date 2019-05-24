<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

use App\Http\Resources\Partially\Pegawai as PegawaiResource;
use App\Http\Resources\Konsumen as KonsumenResource;
use App\Http\Resources\Partially\Kendaraan as KendaraanResource;
use App\Http\Resources\DetailPenjualanSpareparts as DetailPenjualanSparepartsResource;
use App\Http\Resources\DetailPenjualanJasaService as DetailPenjualanJasaServiceResource;

class Pembayaran extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nomor_transaksi = $this->jenis . '-' . Carbon::parse($this->tgl_transaksi)->format('dmy') . '-' . $this->id;
        $kendaraan = [];
        $spareparts = [];
        $subtotal_spareparts = 0;
        $jasa_service = [];
        $subtotal_jasaservice = 0;
        $montir = [];

        foreach($this->detail as $detail_penjualan) {
            array_push($kendaraan, new KendaraanResource($detail_penjualan->kendaraan));
            array_push($montir, new PegawaiResource($detail_penjualan->montir));

            foreach($detail_penjualan->spareparts as $detail_penjualan_spareparts) {
                array_push($spareparts, new DetailPenjualanSparepartsResource($detail_penjualan_spareparts));
                $subtotal_spareparts += $detail_penjualan_spareparts->jumlah * $detail_penjualan_spareparts->harga;
            }

            foreach($detail_penjualan->jasa_service as $detail_penjualan_jasaservice) {
                array_push($jasa_service, new DetailPenjualanJasaServiceResource($detail_penjualan_jasaservice));
                $subtotal_jasaservice += $detail_penjualan_jasaservice->jumlah * $detail_penjualan_jasaservice->harga;
            }
        }

        return [
            'nomor_transaksi' => $nomor_transaksi,
            'konsumen' => new KonsumenResource($this->konsumen),
            'kendaraan' => $kendaraan,
            'spareparts' => $spareparts,
            'jasa_service' => $jasa_service,
            'subtotal' => [
                'penjualan' => $this->subtotal,
                'spareparts' => $subtotal_spareparts,
                'jasa_service' => $subtotal_jasaservice
            ],
            'diskon' => $this->diskon,
            'total' => $this->total,
            'cs' => new PegawaiResource($this->cs),
            'kasir' => new PegawaiResource($this->kasir),
            'montir' => $montir,
            'status' => $this->status,
            'tgl_transaksi' => $this->tgl_transaksi
        ];
    }
}
