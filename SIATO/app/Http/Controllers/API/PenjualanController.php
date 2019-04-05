<?php

namespace App\Http\Controllers\API;

use App\Penjualan;
use App\Http\Resources\Penjualan as PenjualanResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class PenjualanController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = ['diskon', 'uang_diterima', 'id_kasir'];
    var $uneditable = [];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'id_cabang' => 'integer|exists:cabang,id',
        'jenis' => 'alpha',
        'id_konsumen' => 'integer|exists:konsumen,id',
        'diskon' => 'numeric|digits_between:1,11',
        'uang_diterima' => 'numeric|digits_between:1,11',
        'id_cs' => 'integer|exists:pegawai,id',
        'id_kasir' => 'integer|exists:pegawai,id'
    ];

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $this->response['data'] = PenjualanResource::collection(Penjualan::all());
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
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
            $penjualan = new Penjualan;

            if(AppHelper::isFillableFilled($request, $penjualan->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $penjualan->fill($request->only($penjualan->getFillable()));
    
                    $penjualan->status = 1;
    
                    if($penjualan->save()) {
                        $this->response['message'] = 'Berhasil menambah data penjualan.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data penjualan.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data penjualan yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan yang dimasukkan tidak lengkap.';
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
                $this->response['data'] = new PenjualanResource($penjualan);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan tidak ditemukan.';
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
            $penjualan = Penjualan::find($id);

            if($penjualan) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $penjualan->fill(array_filter(collect($request->only($penjualan->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
                    
                    if($penjualan->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data penjualan.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data penjualan.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data penjualan yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan tidak ditemukan.';
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
            $penjualan = Penjualan::find($id);

            if($penjualan) {
                if($penjualan->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data penjualan.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data penjualan.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data penjualan tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
