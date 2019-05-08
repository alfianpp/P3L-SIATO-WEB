<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Penjualan;
use App\DetailPenjualan;

use App\Http\Resources\DetailPenjualan as DetailPenjualanResource;

use App\Classes\APIResponse;

use AppHelper;

class DetailPenjualanController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = ['nomor_polisi', 'id_montir'];
    var $uneditable = ['id_penjualan'];

    var $response;

    var $rules = [
        'id_penjualan' => 'integer|exists:penjualan,id',
        'nomor_polisi' => 'alpha_num|max:12|exists:kendaraan,nomor_polisi',
        'id_montir' => 'integer|exists:pegawai,id'
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $detail_penjualan = new DetailPenjualan;

        if(AppHelper::isFillableFilled($request, $detail_penjualan->getFillable(), $this->nullable)) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $detail_penjualan->fill($request->only($detail_penjualan->getFillable()));

                if($detail_penjualan->save()) {
                    $this->response->message = 'Berhasil menambah data detail penjualan.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data detail penjualan.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data detail penjualan yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data detail penjualan yang dimasukkan tidak lengkap.';
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
            $this->response->data = DetailPenjualanResource::collection($penjualan->detail);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data detail penjualan tidak ditemukan.';
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
        $detail_penjualan = DetailPenjualan::find($id);

        if($detail_penjualan) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $detail_penjualan->fill(array_filter(collect($request->only($detail_penjualan->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($detail_penjualan->save()) {
                    $this->response->message = 'Berhasil memperbarui data detail penjualan.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data detail penjualan.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data detail penjualan yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data detail penjualan tidak ditemukan.';
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
        $detail_penjualan = DetailPenjualan::find($id);

        if($detail_penjualan) {
            if($detail_penjualan->delete()) {
                $this->response->message = 'Berhasil menghapus data detail penjualan.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data detail penjualan.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data detail penjualan tidak ditemukan.';
        }

        return $this->response->make();
    }
}
