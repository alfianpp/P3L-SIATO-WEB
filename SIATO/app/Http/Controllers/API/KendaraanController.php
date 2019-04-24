<?php

namespace App\Http\Controllers\API;

use App\Kendaraan;
use App\Http\Resources\Kendaraan as KendaraanResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class KendaraanController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = [];
    var $uneditable = ['nomor_polisi'];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'nomor_polisi' => 'alpha_num|max:12|unique:kendaraan',
        'merk' => 'alpha_spaces|max:32',
        'tipe' => 'alpha_num_spaces|max:32',
        'id_pemilik' => 'integer|digits:1|exists:konsumen,id'
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
            $this->response['data'] = KendaraanResource::collection(Kendaraan::all());
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }

    public function indexWhere(Request $request, $column, $value)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $this->response['data'] = KendaraanResource::collection(
                Kendaraan::where($column, '=', $value)->get()
            );
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
            $kendaraan = new Kendaraan;

            if(AppHelper::isFillableFilled($request, $kendaraan->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $kendaraan->fill($request->only($kendaraan->getFillable()));
    
                    if($kendaraan->save()) {
                        $this->response['message'] = 'Berhasil menambah data kendaraan.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data kendaraan.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data kendaraan yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data kendaraan yang dimasukkan tidak lengkap.';
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
     * @param  string  $nomor_polisi
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $nomor_polisi)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $kendaraan = Kendaraan::find($nomor_polisi);

            if($kendaraan) {
                $this->response['data'] = new KendaraanResource($kendaraan);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Kendaraan tidak ditemukan.';
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
     * @param  string  $nomor_polisi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nomor_polisi)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $kendaraan = Kendaraan::find($nomor_polisi);

            if($kendaraan) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $kendaraan->fill(array_filter(collect($request->only($kendaraan->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($kendaraan->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data kendaraan.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data kendaraan.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data kendaraan yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Kendaraan tidak ditemukan.';
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
     * @param  string  $nomor_polisi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $nomor_polisi)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $kendaraan = Kendaraan::find($nomor_polisi);
            
            if($kendaraan) {
                if($kendaraan->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data kendaraan.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data kendaraan.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Kendaraan tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
