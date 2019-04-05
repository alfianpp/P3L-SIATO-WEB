<?php

namespace App\Http\Controllers\API;

use App\JasaService;
use App\Http\Resources\JasaService as JasaServiceResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;

class JasaServiceController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = [];
    var $uneditable = [];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'nama' => 'alpha_spaces|max:64|unique:jasa_service',
        'harga_jual' => 'numeric|digits_between:1,11',
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
            $this->response['data'] = JasaServiceResource::collection(JasaService::all());
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
            $jasaservice = new JasaService;

            if(AppHelper::isFillableFilled($request, $jasaservice->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $jasaservice->fill($request->only($jasaservice->getFillable()));
    
                    if($jasaservice->save()) {
                        $this->response['message'] = 'Berhasil menambah data jasa service.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data jasa service.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data jasa service yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data jasa service yang dimasukkan tidak lengkap.';
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
            $jasaservice = JasaService::find($id);

            if($jasaservice) {
                $this->response['data'] = new JasaServiceResource($jasaservice);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Jasa service tidak ditemukan.';
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
            $jasaservice = JasaService::find($id);

            if($jasaservice) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $jasaservice->fill(array_filter(collect($request->only($jasaservice->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($jasaservice->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data jasa service.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data jasa service.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data jasa service yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Jasa service tidak ditemukan.';
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
            $jasaservice = JasaService::find($id);
            
            if($jasaservice) {
                if($jasaservice->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data jasa service.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data jasa service.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Jasa service tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}
