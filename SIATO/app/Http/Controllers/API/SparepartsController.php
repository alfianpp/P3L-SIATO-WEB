<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Spareparts;
use App\HistoriBarang;

use App\Http\Resources\Spareparts as SparepartsResource;

use App\Classes\APIResponse;

use File;
use Image;
use EloquentBuilder;

use AppHelper;

class SparepartsController extends Controller
{
    var $response;

    var $nullable = [];
    var $uneditable = ['kode', 'stok'];

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
        $this->response->data = SparepartsResource::collection(Spareparts::all());
        
        return $this->response->make();
    }

    public function indexColumn($column)
    {
        $this->response->data = DB::table('spareparts')->distinct()->pluck($column);

        return $this->response->make();
    }

    public function indexStokMinimal(Request $request)
    {
        $this->response->data = SparepartsResource::collection(
            Spareparts::whereColumn('stok', '<=', 'stok_minimal')->get()
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
        $spareparts = new Spareparts;

        if(AppHelper::isFillableFilled($request, $spareparts->getFillable(), $this->nullable)) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
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
                    
                    $this->response->message = 'Berhasil menambah data spareparts.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data spareparts.';
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
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $kode)
    {
        $spareparts = Spareparts::find($kode);

        if($spareparts) {
            $this->response->data = new SparepartsResource($spareparts);
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Spareparts tidak ditemukan.';
        }

        return $this->response->make();
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
                    $this->response->message = 'Berhasil memperbarui data spareparts.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data spareparts.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Spareparts tidak ditemukan.';
        }

        return $this->response->make();
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
        $spareparts = Spareparts::find($kode);

        if($spareparts) {
            if($spareparts->delete()) {
                $this->response->message = 'Berhasil menghapus data spareparts.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data spareparts.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Spareparts tidak ditemukan.';
        }

        return $this->response->make();
    }

    public function search(Request $request) {
        $this->response->data = EloquentBuilder::to(Spareparts::class, $request->all())->get();

        return $this->response->make();
    }
}
