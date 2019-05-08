<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\JasaService;
use App\DetailPenjualan;
use App\DetailPenjualanJasaService;

use App\Http\Resources\DetailPenjualanJasaService as DetailPenjualanJasaServiceResource;

use App\Classes\APIResponse;

use AppHelper;

class DetailPenjualanJasaServiceController extends Controller
{
    var $response;

    var $nullable = [];
    var $uneditable = ['id_detail_penjualan', 'id_jasaservice'];

    var $rules = [
        'id_detail_penjualan' => 'integer|exists:detail_penjualan,id',
        'id_jasaservice' => 'integer|exists:jasa_service,id',
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
        $detail_penjualan_jasaservice = new DetailPenjualanJasaService;

        if(AppHelper::isFillableFilled($request, $detail_penjualan_jasaservice->getFillable(), $this->nullable)) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $detail_penjualan_jasaservice->fill($request->only($detail_penjualan_jasaservice->getFillable()));
                
                $detail_penjualan_jasaservice->jumlah = 1;

                $jasa_service = JasaService::find($request->id_jasaservice);
                $detail_penjualan_jasaservice->harga = $jasa_service->harga_jual;

                if($detail_penjualan_jasaservice->save()) {
                    $this->response->message = 'Berhasil menambah data penjualan jasa service.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data penjualan jasa service.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data penjualan jasa service yang dimasukkan tidak valid.';
                $this->response->data = $validation->errors();
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan jasa service yang dimasukkan tidak lengkap.';
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
        $detail_penjualan = DetailPenjualan::find($id);

        if($detail_penjualan) {
            $this->response->data = DetailPenjualanJasaServiceResource::collection($detail_penjualan->jasa_service);
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
        $detail_penjualan_jasaservice = DetailPenjualanJasaService::find($id);

        if($detail_penjualan_jasaservice) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $detail_penjualan_jasaservice->fill(array_filter(collect($request->only($detail_penjualan_jasaservice->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($detail_penjualan_jasaservice->save()) {
                    $this->response->message = 'Berhasil memperbarui data penjualan jasa service.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data penjualan jasa service.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data penjualan jasa service yang dimasukkan tidak valid.';
                $this->response->data = $validation->errors();
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan jasa service tidak ditemukan.';
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
        $detail_penjualan_jasaservice = DetailPenjualanJasaService::find($id);

        if($detail_penjualan_jasaservice) {
            if($detail_penjualan_jasaservice->delete()) {
                $this->response->message = 'Berhasil menghapus data penjualan jasa service.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data penjualan jasa service.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data penjualan jasa service tidak ditemukan.';
        }

        return $this->response->make();
    }
}
