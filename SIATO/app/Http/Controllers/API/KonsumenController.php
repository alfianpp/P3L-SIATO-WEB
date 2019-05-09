<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Konsumen;

use App\Http\Resources\Konsumen as KonsumenResource;
use App\Http\Resources\Partially\Konsumen as KonsumenPartiallyResource;

use App\Classes\APIResponse;

use EloquentBuilder;

use AppHelper;

class KonsumenController extends Controller
{
    var $response;

    var $nullable = [];
    var $uneditable = [];

    var $rules = [
        'nama' => 'alpha_spaces|max:64',
        'nomor_telepon' => 'string|digits_between:10,13',
        'alamat' => ''
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
        $this->response->data = KonsumenResource::collection(Konsumen::all());

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
        $konsumen = new Konsumen;

        if(AppHelper::isFillableFilled($request, $konsumen->getFillable(), $this->nullable)) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $konsumen->fill($request->only($konsumen->getFillable()));

                if($konsumen->save()) {
                    $this->response->message = 'Berhasil menambah data konsumen.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data konsumen.';
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
        $konsumen = Konsumen::find($id);

        if($konsumen) {
            $this->response->data = new KonsumenResource($konsumen);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Konsumen tidak ditemukan.';
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
        $konsumen = Konsumen::find($id);

        if($konsumen) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $konsumen->fill(array_filter(collect($request->only($konsumen->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($konsumen->save()) {
                    $this->response->message = 'Berhasil memperbarui data konsumen.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data konsumen.';
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
            $this->response->message = 'Konsumen tidak ditemukan.';
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
        $konsumen = Konsumen::find($id);
        
        if($konsumen) {
            if($konsumen->delete()) {
                $this->response->message = 'Berhasil menghapus data konsumen.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data konsumen.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Konsumen tidak ditemukan.';
        }

        return $this->response->make();
    }

    public function search(Request $request) {
        $this->response->data = KonsumenPartiallyResource::collection(
            EloquentBuilder::to(Konsumen::class, $request->all())->get()
        );

        return $this->response->make();
    }
}
