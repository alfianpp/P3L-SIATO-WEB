<?php

namespace App\Http\Controllers\API;

use App\Cabang;
use App\Http\Resources\Cabang as CabangResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class CabangController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = ['nomor_telepon'];
    var $uneditable = [];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'nama' => 'alpha_spaces|max:64',
        'nomor_telepon' => 'numeric|digits_between:10,13',
        'alamat' => ''
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
            $this->response['data'] = CabangResource::collection(Cabang::all());
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
            $cabang = new Cabang;

            if(AppHelper::isFillableFilled($request, $cabang->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $cabang->fill($request->only($cabang->getFillable()));
    
                    if($cabang->save()) {
                        $this->response['message'] = 'Berhasil menambah data cabang.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data cabang.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data cabang yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data cabang yang dimasukkan tidak lengkap.';
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
            $cabang = Cabang::find($id);

            if($cabang) {
                $this->response['data'] = new CabangResource($cabang);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Pegawai tidak ditemukan.';
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
            $cabang = Cabang::find($id);

            if($cabang) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $cabang->fill(array_filter(collect($request->only($cabang->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($cabang->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data cabang.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data cabang.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data cabang yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Pegawai tidak ditemukan.';
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
            $cabang = Cabang::find($id);
            
            if($cabang) {
                if($cabang->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data cabang.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data cabang.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Pegawai tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
