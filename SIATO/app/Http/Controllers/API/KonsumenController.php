<?php

namespace App\Http\Controllers\API;

use App\Konsumen;
use App\Http\Resources\Konsumen as KonsumenResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class KonsumenController extends Controller
{
    var $permitted_role = ['0', '1'];

    var $nullable = [];
    var $uneditable = [];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'nama' => 'alpha_spaces|max:64',
        'nomor_telepon' => 'numeric|digits_between:10,13',
        'alamat' => '',
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
            $this->response['data'] = KonsumenResource::collection(Konsumen::all());
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
            $konsumen = new Konsumen;

            if(AppHelper::isFillableFilled($request, $konsumen->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $konsumen->fill($request->only($konsumen->getFillable()));
    
                    if($konsumen->save()) {
                        $this->response['message'] = 'Berhasil menambah data konsumen.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data konsumen.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data konsumen yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data konsumen yang dimasukkan tidak lengkap.';
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
            $konsumen = Konsumen::find($id);

            if($konsumen) {
                $this->response['data'] = new KonsumenResource($konsumen);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Konsumen tidak ditemukan.';
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
            $konsumen = Konsumen::find($id);

            if($konsumen) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $konsumen->fill(array_filter(collect($request->only($konsumen->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($konsumen->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data konsumen.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data konsumen.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data konsumen yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Konsumen tidak ditemukan.';
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
            $konsumen = Konsumen::find($id);
            
            if($konsumen) {
                if($konsumen->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data konsumen.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data konsumen.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Konsumen tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
