<?php

namespace App\Http\Controllers\API;

use App\Spareparts;
use App\DetailPenjualanSpareparts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class DetailPenjualanSparepartsController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = [];
    var $uneditable = ['id_detail_penjualan', 'kode_spareparts'];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'id_detail_penjualan' => 'integer|exists:detail_penjualan,id',
        'kode_spareparts' => 'alpha_dash|exists:spareparts,kode',
        'jumlah' => 'integer|min:1'
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
            $detail_penjualan_spareparts = new DetailPenjualanSpareparts;

            if(AppHelper::isFillableFilled($request, $detail_penjualan_spareparts->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_penjualan_spareparts->fill($request->only($detail_penjualan_spareparts->getFillable()));
                    
                    $spareparts = Spareparts::find($request->kode_spareparts);
                    $detail_penjualan_spareparts->harga = $spareparts->harga_jual;
    
                    if($detail_penjualan_spareparts->save()) {
                        $this->response['message'] = 'Berhasil menambah data penjualan spareparts.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data penjualan spareparts.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data penjualan spareparts yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan spareparts yang dimasukkan tidak lengkap.';
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            $detail_penjualan_spareparts = DetailPenjualanSpareparts::find($id);

            if($detail_penjualan_spareparts) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_penjualan_spareparts->fill(array_filter(collect($request->only($detail_penjualan_spareparts->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($detail_penjualan_spareparts->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data penjualan spareparts.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data penjualan spareparts.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data penjualan spareparts yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan spareparts tidak ditemukan.';
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
            $detail_penjualan_spareparts = DetailPenjualanSpareparts::find($id);

            if($detail_penjualan_spareparts) {
                if($detail_penjualan_spareparts->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data penjualan spareparts.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data penjualan spareparts.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan spareparts tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
