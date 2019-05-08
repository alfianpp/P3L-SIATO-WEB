<?php

namespace App\Http\Controllers\API;

use App\PengadaanBarang;
use App\Http\Resources\PengadaanBarang as PengadaanBarangResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Classes\APIResponse;

use AppHelper;
use APIHelper;

class PengadaanBarangController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = ['status'];
    var $uneditable = [];

    var $response;

    var $rules = [
        'id_supplier' => 'integer|exists:supplier,id',
        'status' => 'integer'
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
        $this->response->data = PengadaanBarangResource::collection(PengadaanBarang::all());

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
        $pengadaan_barang = new PengadaanBarang;

        if(AppHelper::isFillableFilled($request, $pengadaan_barang->getFillable(), $this->nullable)) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $pengadaan_barang->fill($request->only($pengadaan_barang->getFillable()));

                $pengadaan_barang->status = 1;

                if($pengadaan_barang->save()) {
                    $this->response->message = 'Berhasil menambah data pengadaan barang.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data pengadaan barang.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data pengadaan barang yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data pengadaan barang yang dimasukkan tidak lengkap.';
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
        $pengadaan_barang = PengadaanBarang::find($id);

        if($pengadaan_barang) {
            $this->response->data = new PengadaanBarangResource($pengadaan_barang);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data pengadaan barang tidak ditemukan.';
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
        $pengadaan_barang = PengadaanBarang::find($id);

        if($pengadaan_barang) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $pengadaan_barang->fill(array_filter(collect($request->only($pengadaan_barang->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));
                
                if($pengadaan_barang->save()) {
                    $this->response->message = 'Berhasil memperbarui data pengadaan barang.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data pengadaan barang.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data pengadaan barang yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data pengadaan barang tidak ditemukan.';
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
        $pengadaan_barang = PengadaanBarang::find($id);

        if($pengadaan_barang) {
            if($pengadaan_barang->delete()) {
                $this->response->message = 'Berhasil menghapus data pengadaan barang.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data pengadaan barang.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data pengadaan barang tidak ditemukan.';
        }

        return $this->response->make();
    }
}
