<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Spareparts;
use App\PengadaanBarang;
use App\DetailPengadaanBarang;
use App\HistoriBarang;

use App\Http\Resources\DetailPengadaanBarang as DetailPengadaanBarangResource;

use App\Classes\APIResponse;

use Validator;

use AppHelper;

class DetailPengadaanBarangController extends Controller
{
    var $response;

    var $nullable = ['jumlah_datang', 'harga'];
    var $uneditable = ['id_pengadaan_barang', 'kode_spareparts'];

    var $rules = [
        'id_pengadaan_barang' => 'integer|exists:pengadaan_barang,id',
        'kode_spareparts' => 'alpha_dash|max:12|exists:spareparts,kode',
        'jumlah_pesan' => 'integer|min:1',
        'jumlah_datang' => 'integer|min:1',
        'harga' => 'numeric|digits_between:1,11'
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $detail_pengadaan_barang = new DetailPengadaanBarang;

        if(AppHelper::isFillableFilled($request, $detail_pengadaan_barang->getFillable(), $this->nullable)) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $detail_pengadaan_barang->fill($request->only($detail_pengadaan_barang->getFillable()));

                if($detail_pengadaan_barang->save()) {
                    $this->response->message = 'Berhasil menambah data pengadaan barang.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data pengadaan barang.';
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
        $pengadaan_barang = PengadaanBarang::find($id);

        if($pengadaan_barang) {
            $this->response->data = DetailPengadaanBarangResource::collection($pengadaan_barang->detail);
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
        $detail_pengadaan_barang = DetailPengadaanBarang::find($id);

        if($detail_pengadaan_barang) {
            $validation = AppHelper::isRequestValid($request, $this->rules);

            if(!$validation->fails()) {
                $detail_pengadaan_barang->fill(array_filter(collect($request->only($detail_pengadaan_barang->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($request->has(['jumlah_datang', 'harga'])) {
                    $detail_pengadaan_barang->jumlah_datang = $request->jumlah_datang;
                    $detail_pengadaan_barang->harga = $request->harga;

                    $histori_barang = new HistoriBarang;
                    $histori_barang->kode_spareparts = $detail_pengadaan_barang->kode_spareparts;
                    $histori_barang->keluar = 0;
                    $histori_barang->masuk = $request->jumlah_datang;
                    $histori_barang->save();

                    $spareparts = Spareparts::find($detail_pengadaan_barang->kode_spareparts);
                    $spareparts->stok = $spareparts->stok + $request->jumlah_datang;
                    $spareparts->save();

                    $pengadaan_barang = $detail_pengadaan_barang->pengadaan_barang;
                    if(count(DetailPengadaanBarang::where('id_pengadaan_barang', $pengadaan_barang->id)->whereNull('jumlah_datang')->get()) == 1) {
                        $total = 0;

                        $details = DetailPengadaanBarang::where('id_pengadaan_barang', $pengadaan_barang->id)->whereNotNull('jumlah_datang')->get();
                        foreach($details as $detail) {
                            $total = $total + ($detail->jumlah_datang * $detail->harga);
                        }

                        $pengadaan_barang->total = $total + ($request->jumlah_datang * $request->harga);
                        $pengadaan_barang->status = 3;
                        $pengadaan_barang->save();
                    }
                }

                if($detail_pengadaan_barang->save()) {
                    $this->response->message = 'Berhasil memperbarui data pengadaan barang.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data pengadaan barang.';
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
        $detail_pengadaan_barang = DetailPengadaanBarang::find($id);

        if($detail_pengadaan_barang) {
            if($detail_pengadaan_barang->delete()) {
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

    public function verifikasi(Request $request) {
        $error = false;
        $total = 0;

        if(!$request->has(['id_pengadaan_barang', 'detail_pengadaan_barang']) || count($request->detail_pengadaan_barang) == 0) {
            $this->response->error = true;
            $this->response->message = 'Data yang dimasukkan tidak lengkap.';
        }
        else {
            foreach($request->detail_pengadaan_barang as $detail) {
                if(!isset($detail['id']) || !isset($detail['jumlah_datang']) || !isset($detail['harga'])) {
                    $this->response->error = $error = true;
                    $this->response->message = 'Data yang dimasukkan tidak lengkap.';
                    break;
                }

                if(Validator::make($detail, [
                    'id' => 'integer|exists:detail_pengadaan_barang,id',
                    'jumlah_datang' => 'integer|min:1', 
                    'harga' => 'numeric|digits_between:1,11'
                    ])->fails()) {
                    $this->response->error = $error = true;
                    $this->response->message = 'Data yang dimasukkan tidak valid.';
                    break;
                }
            }

            if(!$error) {
                foreach($request->detail_pengadaan_barang as $detail) {
                    $detail_pengadaan_barang = DetailPengadaanBarang::find($detail['id']);
                    $detail_pengadaan_barang->jumlah_datang = $detail['jumlah_datang'];
                    $detail_pengadaan_barang->harga = $detail['harga'];
                    $detail_pengadaan_barang->save();
    
                    $histori_barang = new HistoriBarang;
                    $histori_barang->kode_spareparts = $detail_pengadaan_barang->kode_spareparts;
                    $histori_barang->keluar = 0;
                    $histori_barang->masuk = $detail['jumlah_datang'];
                    $histori_barang->save();
    
                    $spareparts = Spareparts::find($detail_pengadaan_barang->kode_spareparts);
                    $spareparts->stok = $spareparts->stok + $detail['jumlah_datang'];
                    $spareparts->save();
    
                    $total = $total + ($detail['jumlah_datang'] * $detail['harga']);
                }
    
                $pengadaan_barang = PengadaanBarang::find($request->id_pengadaan_barang);
                $pengadaan_barang->total = $total;
                $pengadaan_barang->status = 3;
                $pengadaan_barang->save();

                $this->response->message = 'Pengadaan barang berhasil diverifikasi.';
            }
        }

        return $this->response->make();
    }
}
