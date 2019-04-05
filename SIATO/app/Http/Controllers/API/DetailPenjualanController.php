<?php

namespace App\Http\Controllers\API;

use App\Penjualan;
use App\DetailPenjualan;
use App\Http\Resources\DetailPenjualan as DetailPenjualanResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class DetailPenjualanController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = ['nomor_polisi_kendaraan', 'id_montir'];
    var $uneditable = ['id_penjualan'];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'id_penjualan' => 'integer|exists:penjualan,id',
        'nomor_polisi_kendaraan' => 'alpha_num|max:12|exists:kendaraan,nomor_polisi',
        'id_montir' => 'integer|exists:pegawai,id'
    ];

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
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $detail_penjualan = new DetailPenjualan;

            if(AppHelper::isFillableFilled($request, $detail_penjualan->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_penjualan->fill($request->only($detail_penjualan->getFillable()));
    
                    if($detail_penjualan->save()) {
                        $this->response['message'] = 'Berhasil menambah data detail penjualan.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data detail penjualan.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data detail penjualan yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail penjualan yang dimasukkan tidak lengkap.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
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
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $penjualan = Penjualan::find($id);

            if($penjualan) {
                $this->response['data'] = DetailPenjualanResource::collection($penjualan->detail);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail penjualan tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
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
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $detail_penjualan = DetailPenjualan::find($id);

            if($detail_penjualan) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_penjualan->fill(array_filter(collect($request->only($detail_penjualan->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($detail_penjualan->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data detail penjualan.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data detail penjualan.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data detail penjualan yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail penjualan tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
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
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $detail_penjualan = DetailPenjualan::find($id);

            if($detail_penjualan) {
                if($detail_penjualan->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data detail penjualan.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data detail penjualan.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail penjualan tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
