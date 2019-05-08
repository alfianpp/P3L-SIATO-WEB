<?php

namespace App\Http\Controllers\API;

use App\Supplier;
use App\Http\Resources\Supplier as SupplierResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Classes\APIResponse;

use AppHelper;
use APIHelper;

class SupplierController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = [];
    var $uneditable = [];

    var $response;

    var $rules = [
        'nama' => 'alpha_spaces|max:64',
        'alamat' => '',
        'nama_sales' => 'alpha_spaces|max:64',
        'nomor_telepon_sales' => 'numeric|digits_between:10,13'
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
        $this->response->data = SupplierResource::collection(Supplier::all());

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
        $supplier = new Supplier;

        if(AppHelper::isFillableFilled($request, $supplier->getFillable(), $this->nullable)) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $supplier->fill($request->only($supplier->getFillable()));

                if($supplier->save()) {
                    $this->response->message = 'Berhasil menambah data supplier.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data supplier.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data supplier yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data supplier yang dimasukkan tidak lengkap.';
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
        $supplier = Supplier::find($id);

        if($supplier) {
            $this->response->data = new SupplierResource($supplier);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Supplier tidak ditemukan.';
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
        $supplier = Supplier::find($id);

        if($supplier) {
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $supplier->fill(array_filter(collect($request->only($supplier->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($supplier->save()) {
                    $this->response->message = 'Berhasil memperbarui data supplier.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data supplier.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data supplier yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Supplier tidak ditemukan.';
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
        $supplier = Supplier::find($id);
        
        if($supplier) {
            if($supplier->delete()) {
                $this->response->message = 'Berhasil menghapus data supplier.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data supplier.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Supplier tidak ditemukan.';
        }

        return $this->response->make();
    }
}
