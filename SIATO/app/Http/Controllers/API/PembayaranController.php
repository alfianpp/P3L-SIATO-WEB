<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Pegawai;
use App\Penjualan;

use App\Http\Resources\Penjualan as PenjualanResource;
use App\Http\Resources\Pembayaran as PembayaranResource;

use App\Classes\APIResponse;

use AppHelper;

class PembayaranController extends Controller
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

    public function index(Request $request)
    {
        $this->response->data = PenjualanResource::collection(
            Penjualan::where('status', 2)->orWhere('status', 3)->get()
        );

        return $this->response->make();
    }

    public function show(Request $request, $id)
    {
        $this->response->data = new PembayaranResource(Penjualan::find($id));

        return $this->response->make();
    }

    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);

        if($penjualan) {
            $validation = AppHelper::isRequestValid($request, [
                'diskon' => 'required|numeric|digits_between:1,11',
                'uang_diterima' => 'required|numeric|min:'.($penjualan->subtotal - $request->diskon).'|digits_between:1,11'
            ]);

            if(!$validation->fails()) {
                $penjualan->diskon = $request->diskon;
                $penjualan->total = $penjualan->subtotal - $request->diskon;
                $penjualan->uang_diterima = $request->uang_diterima;
                $penjualan->status = 3;

                $pegawai = Pegawai::where('api_key', '=', $request->api_key)->first();
                $penjualan->id_kasir = $pegawai->id;

                if($penjualan->save()) {
                    $this->response->message = 'Berhasil memperbarui data penjualan.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data penjualan.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data yang dimasukkan tidak valid.';
                $this->response->data = $validation->errors();
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan tidak ditemukan.';
        }

        return $this->response->make();
    }
}
