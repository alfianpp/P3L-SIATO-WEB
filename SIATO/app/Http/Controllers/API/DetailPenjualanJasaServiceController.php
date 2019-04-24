<?php

namespace App\Http\Controllers\API;

use App\JasaService;
use App\DetailPenjualan;
use App\DetailPenjualanJasaService;
use App\Http\Resources\DetailPenjualanJasaService as DetailPenjualanJasaServiceResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class DetailPenjualanJasaServiceController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = [];
    var $uneditable = ['id_detail_penjualan', 'id_jasaservice'];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'id_detail_penjualan' => 'integer|exists:detail_penjualan,id',
        'id_jasaservice' => 'integer|exists:jasa_service,id',
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
            $detail_penjualan_jasaservice = new DetailPenjualanJasaService;

            if(AppHelper::isFillableFilled($request, $detail_penjualan_jasaservice->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_penjualan_jasaservice->fill($request->only($detail_penjualan_jasaservice->getFillable()));
                    
                    $detail_penjualan_jasaservice->jumlah = 1;

                    $jasa_service = JasaService::find($request->id_jasaservice);
                    $detail_penjualan_jasaservice->harga = $jasa_service->harga_jual;
    
                    if($detail_penjualan_jasaservice->save()) {
                        $this->response['message'] = 'Berhasil menambah data penjualan jasa service.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data penjualan jasa service.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data penjualan jasa service yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan jasa service yang dimasukkan tidak lengkap.';
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
            $detail_penjualan = DetailPenjualan::find($id);

            if($detail_penjualan) {
                $this->response['data'] = DetailPenjualanJasaServiceResource::collection($detail_penjualan->detailJasaService);
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
            $detail_penjualan_jasaservice = DetailPenjualanJasaService::find($id);

            if($detail_penjualan_jasaservice) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_penjualan_jasaservice->fill(array_filter(collect($request->only($detail_penjualan_jasaservice->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($detail_penjualan_jasaservice->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data penjualan jasa service.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data penjualan jasa service.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data penjualan jasa service yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan jasa service tidak ditemukan.';
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
            $detail_penjualan_jasaservice = DetailPenjualanJasaService::find($id);

            if($detail_penjualan_jasaservice) {
                if($detail_penjualan_jasaservice->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data penjualan jasa service.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data penjualan jasa service.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan jasa service tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
