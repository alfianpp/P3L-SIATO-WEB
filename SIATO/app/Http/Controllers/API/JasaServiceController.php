<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\JasaService;

use App\Http\Resources\JasaService as JasaServiceResource;

use App\Classes\APIResponse;

use AppHelper;

class JasaServiceController extends Controller
{
    var $response;

    var $nullable = [];
    var $uneditable = [];

    var $rules = [
        'nama' => 'alpha_spaces|max:64',
        'harga_jual' => 'numeric|digits_between:1,11'
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->response->data = JasaServiceResource::collection(JasaService::all());

        return $this->response->make();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jasaservice = new JasaService;

        if(AppHelper::isFillableFilled($request, $jasaservice->getFillable(), $this->nullable)) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $jasaservice->fill($request->only($jasaservice->getFillable()));

                if($jasaservice->save()) {
                    $this->response->message = 'Berhasil menambah data jasa service.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data jasa service.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data yang dimasukkan tidak valid.';
                $this->response->data = $validation->errors();
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data yang dimasukkan tidak lengkap.';
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
        $jasaservice = JasaService::find($id);

        if($jasaservice) {
            $this->response->data = new JasaServiceResource($jasaservice);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Jasa service tidak ditemukan.';
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
        $jasaservice = JasaService::find($id);

        if($jasaservice) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $jasaservice->fill(array_filter(collect($request->only($jasaservice->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($jasaservice->save()) {
                    $this->response->message = 'Berhasil memperbarui data jasa service.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data jasa service.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data yang dimasukkan tidak valid.';
                $this->response->data = $validation->errors();
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Jasa service tidak ditemukan.';
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
        $jasaservice = JasaService::find($id);
        
        if($jasaservice) {
            if($jasaservice->delete()) {
                $this->response->message = 'Berhasil menghapus data jasa service.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data jasa service.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Jasa service tidak ditemukan.';
        }

        return $this->response->make();
    }
}
