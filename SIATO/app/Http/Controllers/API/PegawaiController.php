<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Pegawai;

use App\Http\Resources\Pegawai as PegawaiResource;

use App\Classes\APIResponse;

use AppHelper;

class PegawaiController extends Controller
{
    var $response;

    var $nullable = [];
    var $uneditable = ['username'];

    var $rules = [
        'username' => 'alpha_num|max:12|unique:pegawai',
        'password' => 'min:8',
        'nama' => 'alpha_spaces|max:64',
        'nomor_telepon' => 'string|digits_between:10,13',
        'alamat' => '',
        'gaji' => 'numeric|digits_between:1,11',
        'role' => 'integer|digits:1'
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

    public function login(Request $request)
    {
        $credentials = ['username', 'password'];

        if($request->filled($credentials)) {
            if(Auth::guard('admin')->attempt($request->only($credentials))) {
                $this->response->message = 'Berhasil login.';
                $this->response->data = Pegawai::where('username', $request->username)->first();
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Username atau password salah.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data yang dimasukan tidak lengkap.';
        }

        return $this->response->make();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->response->data = PegawaiResource::collection(
            Pegawai::where('role', '!=', '0')->get()
        );
        
        return $this->response->make();
    }

    public function indexWhere(Request $request, $column, $value)
    {
        $this->response->data = PegawaiResource::collection(
            Pegawai::where($column, '=', $value)->get()
        );
        
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
        $pegawai = new Pegawai;

        if(AppHelper::isFillableFilled($request, $pegawai->getFillable(), $this->nullable)) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $pegawai->fill($request->only($pegawai->getFillable()));

                $pegawai->password = Hash::make($request->password);
                $pegawai->api_key = Str::random(12);

                if($pegawai->save()) {
                    $this->response->message = 'Berhasil menambah data pegawai.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data pegawai.';
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
        $pegawai = Pegawai::find($id);

        if($pegawai) {
            $this->response->data = new PegawaiResource($pegawai);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Pegawai tidak ditemukan.';
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
        $pegawai = Pegawai::find($id);

        if($pegawai) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $pegawai->fill(array_filter(collect($request->only($pegawai->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($request->has('password')) {
                    $pegawai->password = Hash::make($request->password);
                }

                if($pegawai->save()) {
                    $this->response->message = 'Berhasil memperbarui data pegawai.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data pegawai.';
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
            $this->response->message = 'Pegawai tidak ditemukan.';
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
        $pegawai = Pegawai::find($id);

        if($pegawai) {
            if($pegawai->delete()) {
                $this->response->message = 'Berhasil menghapus data pegawai.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data pegawai.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Pegawai tidak ditemukan.';
        }

        return $this->response->make();
    }
}