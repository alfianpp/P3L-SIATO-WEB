<?php

namespace App\Http\Controllers\API;

use App\Kendaraan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Classes\APIResponse;

use APIHelper;

class RiwayatTransaksiKonsumenController extends Controller
{
    var $response;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->response = new APIResponse;
    }

    public function login(Request $request)
    {
        if ($request->filled(['nomor_polisi', 'nomor_telepon'])) {
            $kendaraan = Kendaraan::find($request->nomor_polisi);

            if ($kendaraan) {
                if ($kendaraan->pemilik->nomor_telepon == $request->nomor_telepon) {
                    $this->response->message = 'Berhasil masuk.';
                } else {
                    $this->response->error = true;
                    $this->response->message = 'Data tidak cocok.';
                }
            } else {
                $this->response->error = true;
                $this->response->message = 'Kendaraan tidak ditemukan.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data yang dimasukkan tidak lengkap.';
        }

        return $this->response->make();
    }
}
