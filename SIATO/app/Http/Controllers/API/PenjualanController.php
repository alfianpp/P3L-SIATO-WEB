<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Pegawai;
use App\Penjualan;
use App\DetailPenjualan;
use App\HistoriBarang;
use App\Spareparts;

use App\Http\Resources\Penjualan as PenjualanResource;

use App\Classes\APIResponse;

use AppHelper;
use FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class PenjualanController extends Controller
{
    var $response;

    var $nullable = ['diskon', 'uang_diterima', 'id_cs', 'id_kasir', 'status'];
    var $uneditable = [];

    var $rules = [
        'id_cabang' => 'integer|exists:cabang,id',
        'jenis' => 'alpha',
        'id_konsumen' => 'integer|exists:konsumen,id',
        'diskon' => 'numeric|digits_between:1,11',
        'uang_diterima' => 'numeric|digits_between:1,11',
        'id_cs' => 'integer|exists:pegawai,id',
        'id_kasir' => 'integer|exists:pegawai,id',
        'status' => 'integer|digits:1'
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
        $pegawai = Pegawai::where('api_key', '=', $request->api_key)->first();

        if($pegawai->role == 0) {
            $this->response->data = PenjualanResource::collection(Penjualan::all());
        }
        else if($pegawai->role == 1) {
            $this->response->data = PenjualanResource::collection(
                Penjualan::where('status', '1')->get()
            );
        }
        else if($pegawai->role == 2) {
            $this->response->data = PenjualanResource::collection(
                Penjualan::where('status', '2')->get()
            );
        }

        return $this->response->make();
    }

    public function indexColumn($column)
    {
        $this->response->data = DB::table('penjualan')->distinct()->pluck($column);

        if($column == 'tgl_transaksi') {
            $_temp = [];

            foreach($this->response->data as $tgl_transaksi) {
                $tgl_transaksi = Carbon::parse($tgl_transaksi)->format('d-m-Y');

                if(!in_array($tgl_transaksi, $_temp)) {
                    array_push($_temp, $tgl_transaksi);
                }
            }

            $this->response->data = $_temp;
        }
        
        return $this->response->make();
    }

    public function indexWhere(Request $request, $column, $value)
    {
        $this->response->data = PenjualanResource::collection(
            Penjualan::where($column, '=', $value)->get()
        );

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
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $penjualan->fill($request->only($penjualan->getFillable()));

                $pegawai = Pegawai::where('api_key', '=', $request->api_key)->first();
                
                $penjualan->id_cs = $pegawai->id;

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
                $this->response->message = 'Data yang dimasukkan tidak valid.';
                $this->response->data = $validation->errors();
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data yang dimasukkan tidak lengkap.';
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
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $penjualan->fill(array_filter(collect($request->only($penjualan->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($request->filled('status') && $request->status == 2) {
                    $subtotal = 0;
                    $under = 0;

                    foreach ($penjualan->detail as $detail) {
                        if($penjualan->jenis == 'SV') {
                            foreach($detail->spareparts as $detail_penjualan_spareparts) {
                                $detail_penjualan_spareparts->delete();
                            }
                        }
                        else if($penjualan->jenis == 'SP') {
                            foreach($detail->jasa_service as $detail_penjualan_jasaservice) {
                                $detail_penjualan_jasaservice->delete();
                            }

                            $detail->nomor_polisi = null;
                            $detail->id_montir = null;
                            $detail->save();
                        }

                        if($penjualan->jenis == 'SP' || $penjualan->jenis == 'SS') {
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
                                    $under++;
                                }
    
                                $subtotal = $subtotal + ($penjualan_spareparts->jumlah * $penjualan_spareparts->harga);
                            }
                        }

                        if($penjualan->jenis == 'SV' || $penjualan->jenis == 'SS') {
                            foreach($detail->jasa_service as $penjualan_jasaservice) {
                                $subtotal = $subtotal + ($penjualan_jasaservice->jumlah * $penjualan_jasaservice->harga);
                            }
                        }
                    }

                    $penjualan->subtotal = $subtotal;

                    if($under > 0) {
                        $title = 'Pemberitahuan Stok';
                        $message = 'Ada ' . $under . ' spareparts yang sudah mencapai/dibawah stok minimal.';
                        $this->sendPushNotification($title, $message);
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

        $token = "d8SWjX_RBUg:APA91bH35lvbEqkTfmUxxv-x18qFed6ngz4ipO7nkxcETU7YRrJcsjkeD9PGuSB458NXTlAVstmcEHLkD7c06CMBWkCguyTiAqDx5cOMybzur6n-FVoZ1iaMjgy4F5tL_l09oju5Pjsi";

        $downstreamResponse = FCM::sendTo($token, $option, $notification);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();
    }
}
