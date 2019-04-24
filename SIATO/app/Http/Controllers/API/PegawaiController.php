<?php

namespace App\Http\Controllers\API;

use App\Pegawai;
use App\Http\Resources\Pegawai as PegawaiResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use AppHelper;
use APIHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = [];
    var $uneditable = ['username'];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'username' => 'alpha_num|max:12|unique:pegawai',
        'password' => 'min:8',
        'nama' => 'alpha_spaces|max:64',
        'nomor_telepon' => 'numeric|digits_between:10,13',
        'alamat' => '',
        'gaji' => 'numeric|digits_between:1,11',
        'role' => 'integer|digits:1'
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
            $this->response['data'] = PegawaiResource::collection(
                Pegawai::where('role', '!=', '0')->get()
            );
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
            $this->response['data'] = PegawaiResource::collection(
                Pegawai::where($column, '=', $value)->get()
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
            $pegawai = new Pegawai;

            if(AppHelper::isFillableFilled($request, $pegawai->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);

                if($validation['isValid']) {
                    $pegawai->fill($request->only($pegawai->getFillable()));

                    $pegawai->password = Hash::make($request->password);
                    $pegawai->api_key = Str::random(12);

                    if($pegawai->save()) {
                        $this->response['message'] = 'Berhasil menambah data pegawai.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data pegawai.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data pegawai yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data pegawai yang dimasukkan tidak lengkap.';
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
            $pegawai = Pegawai::find($id);

            if($pegawai) {
                $this->response['data'] = new PegawaiResource($pegawai);
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
            $pegawai = Pegawai::find($id);

            if($pegawai) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $pegawai->fill(array_filter(collect($request->only($pegawai->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));
    
                    if($request->has('password')) {
                        $pegawai->password = Hash::make($request->password);
                    }
    
                    if($pegawai->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data pegawai.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data pegawai.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data pegawai yang dimasukkan tidak valid.';
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
            $pegawai = Pegawai::find($id);

            if($pegawai) {
                if($pegawai->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data pegawai.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data pegawai.';
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

    public function login(Request $request)
    {
        $credentials = ['username', 'password'];

        if($request->filled($credentials)) {
            if(Auth::guard('admin')->attempt($request->only($credentials))) {
                $this->response['message'] = 'Login berhasil.';
                $this->response['data'] = Pegawai::where('username', $request->username)->first();
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Username atau password salah.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Data login tidak lengkap.';
        }

        return APIHelper::JSONResponse($this->response);
    }
}