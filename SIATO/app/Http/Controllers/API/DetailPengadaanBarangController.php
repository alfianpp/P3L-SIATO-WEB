<?php

namespace App\Http\Controllers\API;

use App\PengadaanBarang;
use App\DetailPengadaanBarang;
use App\Http\Resources\DetailPengadaanBarang as DetailPengadaanBarangResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class DetailPengadaanBarangController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = ['jumlah_datang'];
    var $uneditable = ['id_pengadaan_barang', 'kode_spareparts'];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'id_pengadaan_barang' => 'integer|exists:pengadaan_barang,id',
        'kode_spareparts' => 'alpha_dash|max:12|exists:spareparts,kode',
        'jumlah_pesan' => 'integer|min:1',
        'jumlah_datang' => 'integer|min:1'
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
            $detail_pengadaan_barang = new DetailPengadaanBarang;

            if(AppHelper::isFillableFilled($request, $detail_pengadaan_barang->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_pengadaan_barang->fill($request->only($detail_pengadaan_barang->getFillable()));
    
                    if($detail_pengadaan_barang->save()) {
                        $this->response['message'] = 'Berhasil menambah data detail pengadaan barang.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data detail pengadaan barang.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data detail pengadaan barang yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail pengadaan barang yang dimasukkan tidak lengkap.';
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
                $this->response['data'] = DetailPengadaanBarangResource::collection($pengadaan_barang->detail);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail pengadaan barang tidak ditemukan.';
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
            $detail_pengadaan_barang = DetailPengadaanBarang::find($id);

            if($detail_pengadaan_barang) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $detail_pengadaan_barang->fill(array_filter(collect($request->only($detail_pengadaan_barang->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($detail_pengadaan_barang->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data detail pengadaan barang.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data detail pengadaan barang.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data detail pengadaan barang yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail pengadaan barang tidak ditemukan.';
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
            $detail_pengadaan_barang = DetailPengadaanBarang::find($id);

            if($detail_pengadaan_barang) {
                if($detail_pengadaan_barang->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data detail pengadaan barang.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data detail pengadaan barang.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data detail pengadaan barang tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
