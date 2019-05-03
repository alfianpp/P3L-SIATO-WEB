<?php

namespace App\Http\Controllers\API;

use App\Spareparts;
use App\HistoriBarang;

use App\Http\Resources\Spareparts as SparepartsResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use File;
use Image;

use AppHelper;
use APIHelper;

class SparepartsController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = [];
    var $uneditable = ['kode', 'stok'];

    var $response = [
        'error' => false,
        'message' => '',
        'data' => null
    ];

    var $rules = [
        'kode' => 'alpha_dash|max:12|unique:spareparts',
        'nama' => 'alpha_spaces|max:64',
        'merk' => 'alpha_spaces|max:32',
        'tipe' => 'alpha_spaces|max:32',
        'kode_peletakan' => 'alpha_dash|max:12',
        'harga_beli' => 'numeric|digits_between:1,11',
        'harga_jual' => 'numeric|digits_between:1,11',
        'stok' => 'integer|min:0',
        'stok_minimal' => 'integer|min:0',
        'gambar' => 'image64:jpeg,jpg,png'
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
            $this->response['data'] = SparepartsResource::collection(Spareparts::all());
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }
        
        return APIHelper::JSONResponse($this->response);
    }

    public function indexStokMinimal(Request $request)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $this->response['data'] = SparepartsResource::collection(
                Spareparts::whereColumn('stok', '<=', 'stok_minimal')->get()
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
            $spareparts = new Spareparts;

            if(AppHelper::isFillableFilled($request, $spareparts->getFillable(), $this->nullable)) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {                    
                    $spareparts->fill($request->only($spareparts->getFillable()));

                    if($request->filled('gambar')) {
                        $imgName = Carbon::now()->timestamp . '_' . $request->kode . '.' . explode('/', explode(':', substr($request->gambar, 0, strpos($request->gambar, ';')))[1])[1];
                        Image::make($request->gambar)->encode('jpg')->save(public_path('images/').$imgName);
                        $spareparts->url_gambar = $imgName;
                    }
    
                    if($spareparts->save()) {
                        $histori_barang = new HistoriBarang;

                        $histori_barang->kode_spareparts = $request->kode;
                        $histori_barang->keluar = 0;
                        $histori_barang->masuk = $request->stok;
                        $histori_barang->save();
                        
                        $this->response['message'] = 'Berhasil menambah data spareparts.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal menambah data spareparts.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data spareparts yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Data spareparts yang dimasukkan tidak lengkap.';
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
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $kode)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $spareparts = Spareparts::find($kode);

            if($spareparts) {
                $this->response['data'] = new SparepartsResource($spareparts);
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Spareparts tidak ditemukan.';
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
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $spareparts = Spareparts::find($kode);

            if($spareparts) {
                $validation = AppHelper::isValidRequest($request, $this->rules);
    
                if($validation['isValid']) {
                    $spareparts->fill(array_filter(collect($request->only($spareparts->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                        return ($value !== null);
                    }));

                    if($request->filled('gambar')) {
                        $imgName = Carbon::now()->timestamp . '_' . $spareparts->kode . '.' . explode('/', explode(':', substr($request->gambar, 0, strpos($request->gambar, ';')))[1])[1];
                        
                        File::delete(public_path('images/').$spareparts->url_gambar);
                        Image::make($request->gambar)->encode('jpg')->save(public_path('images/').$imgName);

                        $spareparts->url_gambar = $imgName;
                    }
    
                    if($spareparts->save()) {
                        $this->response['message'] = 'Berhasil memperbarui data spareparts.';
                    }
                    else {
                        $this->response['error'] = true;
                        $this->response['message'] = 'Gagal memperbarui data spareparts.';
                    }
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Data spareparts yang dimasukkan tidak valid.';
                    $this->response['data'] = $validation['errors'];
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Spareparts tidak ditemukan.';
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
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $kode)
    {
        if(APIHelper::isPermitted($request->api_key, $this->permitted_role)) {
            $spareparts = Spareparts::find($kode);

            if($spareparts) {
                if($spareparts->delete()) {
                    $this->response['message'] = 'Berhasil menghapus data spareparts.';
                }
                else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'Gagal menghapus data spareparts.';
                }
            }
            else {
                $this->response['error'] = true;
                $this->response['message'] = 'Spareparts tidak ditemukan.';
            }
        }
        else {
            $this->response['error'] = true;
            $this->response['message'] = 'Aksi tidak diizinkan.';
        }

        return APIHelper::JSONResponse($this->response);
    }

    private function uploadImage() {

    }
}
