<?php

namespace App\Http\Controllers\API;

use App\Pegawai;
use App\Penjualan;
use App\DetailPenjualan;
use App\HistoriBarang;
use App\Spareparts;
use App\Http\Resources\Penjualan as PenjualanResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Classes\APIResponse;

use AppHelper;
use APIHelper;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class PenjualanController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = ['diskon', 'uang_diterima', 'id_cs', 'id_kasir', 'status'];
    var $uneditable = [];

    var $response;

    var $rules = [
        'id_cabang' => 'integer|exists:cabang,id',
        'jenis' => 'alpha',
        'id_konsumen' => 'integer|exists:konsumen,id',
        'diskon' => 'numeric|digits_between:1,11',
        'uang_diterima' => 'numeric|digits_between:1,11',
        'id_cs' => 'integer|exists:pegawai,id',
        'id_kasir' => 'integer|exists:pegawai,id',
        'status' => 'integer'
    ];

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->response = new APIResponse;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->response->data = PenjualanResource::collection(Penjualan::all());

        return $this->response->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $penjualan = new Penjualan;

        if(AppHelper::isFillableFilled($request, $penjualan->getFillable(), $this->nullable)) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $penjualan->fill($request->only($penjualan->getFillable()));

                $pegawai = Pegawai::where('api_key', '=', $request->api_key)->first();
                
                $penjualan->id_cs = $pegawai->id;
                $penjualan->status = 1;

                if($penjualan->save()) {
                    if($request->jenis == 'SP') {
                        $detail_penjualan = new DetailPenjualan;
                        $detail_penjualan->id_penjualan = $penjualan->id;
                        $detail_penjualan->save();
                    }

                    $this->response->message = 'Berhasil menambah data penjualan.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data penjualan.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data penjualan yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan yang dimasukkan tidak lengkap.';
        }

        return $this->response->make();
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);

        if($penjualan) {
            $this->response->data = new PenjualanResource($penjualan);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan tidak ditemukan.';
        }

        return $this->response->make();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);

        if($penjualan) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $penjualan->fill(array_filter(collect($request->only($penjualan->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($request->filled('status') && $request->status == 2) {
                    foreach ($penjualan->detail as $detail) {
                        foreach ($detail->spareparts as $penjualan_spareparts) {
                            $histori_barang = new HistoriBarang;

                            $histori_barang->kode_spareparts = $penjualan_spareparts->kode_spareparts;
                            $histori_barang->keluar = $penjualan_spareparts->jumlah;
                            $histori_barang->masuk = 0;
                            $histori_barang->save();

                            $spareparts = Spareparts::find($penjualan_spareparts->kode_spareparts);
                            $spareparts->stok = $spareparts->stok - $penjualan_spareparts->jumlah;
                            $spareparts->save();

                            if($spareparts->stok <= $spareparts->stok_minimal) {
                                $title = 'Pemberitahuan Stok';
                                $message = 'Stok ' . $spareparts->nama . ' sudah mencapai/dibawah stok minimal.';
                                $this->sendPushNotification($title, $message);
                            }
                        }
                    }
                }
                
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
                $this->response->message = 'Data penjualan yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan tidak ditemukan.';
        }

        return $this->response->make();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);

        if($penjualan) {
            if($penjualan->delete()) {
                $this->response->message = 'Berhasil menghapus data penjualan.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data penjualan.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan tidak ditemukan.';
        }

        return $this->response->make();
    }

    private function sendPushNotification($title, $body)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder();
        $notificationBuilder->setTitle($title)
                            ->setBody($body)
                            ->setSound('default');

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $token = "e_Jrc4Xg-vA:APA91bGjzxVcjfbw-b1wMDDJwMVFErjeSBDTfGITzD07DdEBhiMNI4-PLjxZZkJSRLHgscbYIZHeWsArTcjV5AmIWuVP8hl8SnJjrWrEt5tEF8LlBQfDtiorN8FtVPdRKJqo3gasIOSZ";

        $downstreamResponse = FCM::sendTo($token, $option, $notification);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();
    }
}
