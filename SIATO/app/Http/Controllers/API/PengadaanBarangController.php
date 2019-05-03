<?php

namespace App\Http\Controllers\API;

use App\PengadaanBarang;
use App\Http\Resources\PengadaanBarang as PengadaanBarangResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class PengadaanBarangController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = ['status'];
    var $uneditable = [];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'id_supplier' => 'integer|exists:supplier,id',
        'status' => 'integer'
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
            $this->response['data'] = PengadaanBarangResource::collection(PengadaanBarang::all());
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
            $pengadaan_barang = new PengadaanBarang;

            if(AppHelper::isFillableFilled($request, $pengadaan_barang->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $pengadaan_barang->fill($request->only($pengadaan_barang->getFillable()));
    
                    $pengadaan_barang->status = 1;
    
                    if($pengadaan_barang->save()) {
                        $this->response['message'] = 'Berhasil menambah data pengadaan barang.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data pengadaan barang.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data pengadaan barang yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data pengadaan barang yang dimasukkan tidak lengkap.';
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
            $pengadaan_barang = PengadaanBarang::find($id);

            if($pengadaan_barang) {
                $this->response['data'] = new PengadaanBarangResource($pengadaan_barang);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data pengadaan barang tidak ditemukan.';
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
            $pengadaan_barang = PengadaanBarang::find($id);

            if($pengadaan_barang) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $pengadaan_barang->fill(array_filter(collect($request->only($pengadaan_barang->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
                    
                    if($pengadaan_barang->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data pengadaan barang.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data pengadaan barang.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data pengadaan barang yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data pengadaan barang tidak ditemukan.';
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
            $pengadaan_barang = PengadaanBarang::find($id);

            if($pengadaan_barang) {
                if($pengadaan_barang->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data pengadaan barang.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data pengadaan barang.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data pengadaan barang tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
