<?php

namespace App\Http\Controllers;

use App\Konsumen;
use App\Kendaraan;
use App\Penjualan;
use App\DetailPenjualanJasaService;
use App\DetailPenjualanSpareparts;

use App\Http\Resources\RiwayatTransaksiKonsumen as RiwayatTransaksiKonsumenResource;
use App\Http\Resources\DetailPenjualanSpareparts as DetailPenjualanSparepartsResource;
use App\Http\Resources\DetailPenjualanJasaService as DetailPenjualanJasaServiceResource;

use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function view(Request $request) {
        $a['error'] = false;
        $a['message'] = '';
        $a['data'] = null;

        if($request->filled(['nomor_polisi', 'nomor_telepon'])) {
            $kendaraan = Kendaraan::where('nomor_polisi', $request->nomor_polisi)->first();

            if($kendaraan) {
                $konsumen = Konsumen::where('id', $kendaraan->id_pemilik)->first();

                if($konsumen->nomor_telepon == $request->nomor_telepon) {
                    /*
                    $detail_penjualan_jasaservice = DetailPenjualanJasaServiceResource::collection(
                        DetailPenjualanJasaService::whereHas('detail_penjualan', function($query) use ($request) {
                            $query->where('nomor_polisi', $request->nomor_polisi);
                        })->get()
                    );

                    $detail_penjualan_spareparts = DetailPenjualanSparepartsResource::collection(
                        DetailPenjualanSpareparts::whereHas('detail_penjualan', function($query) use ($request) {
                            $query->where('nomor_polisi', $request->nomor_polisi);
                        })->get()
                    );

                    $riwayat['spareparts'] = $detail_penjualan_spareparts;
                    $riwayat['jasa_service'] = $detail_penjualan_jasaservice;
                    */

                    $riwayat = RiwayatTransaksiKonsumenResource::collection(
                        DetailPenjualanSpareparts::whereHas('detail_penjualan', function($query) use ($request) {
                            $query->where('nomor_polisi', $request->nomor_polisi);
                        })->get()
                    );

                    return $riwayat;
                }
                else {
                    $a['error'] = true;
                    $a['message'] = 'Tidak dapat menampilkan riwayat.';
                }
            }
            else {
                $a['error'] = true;
                $a['message'] = 'Kendaraan tidak di temukan.';
            }
        }

        return view('riwayat', $a);
    }
}
