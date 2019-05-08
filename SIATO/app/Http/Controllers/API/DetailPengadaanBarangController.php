<?php

namespace App\Http\Controllers\API;

use App\PengadaanBarang;
use App\DetailPengadaanBarang;
use App\HistoriBarang;
use App\Spareparts;
use App\Http\Resources\DetailPengadaanBarang as DetailPengadaanBarangResource;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Classes\APIResponse;

use AppHelper;
use APIHelper;

class DetailPengadaanBarangController extends Controller
{
    var $permitted_role = ['0'];

    var $nullable = ['jumlah_datang', 'harga'];
    var $uneditable = ['id_pengadaan_barang', 'kode_spareparts'];

    var $response;

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
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $detail_pengadaan_barang->fill($request->only($detail_pengadaan_barang->getFillable()));

                if($detail_pengadaan_barang->save()) {
                    $this->response->message = 'Berhasil menambah data detail pengadaan barang.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal menambah data detail pengadaan barang.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data detail pengadaan barang yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data detail pengadaan barang yang dimasukkan tidak lengkap.';
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
            $this->response->message = 'Data detail pengadaan barang tidak ditemukan.';
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
            $validation = AppHelper::isValidRequest($request, $this->rules);

            if($validation['isValid']) {
                $detail_pengadaan_barang->fill(array_filter(collect($request->only($detail_pengadaan_barang->getFillable()))->except($this->uneditable)->toArray(), function($value) {
                    return ($value !== null);
                }));

                if($detail_pengadaan_barang->save()) {
                    if($request->filled('jumlah_datang')) {
                        $histori_barang = new HistoriBarang;

                        $histori_barang->kode_spareparts = $detail_pengadaan_barang->kode_spareparts;
                        $histori_barang->keluar = 0;
                        $histori_barang->masuk = $request->jumlah_datang;
                        $histori_barang->save();

                        $spareparts = Spareparts::find($detail_pengadaan_barang->kode_spareparts);
                        $spareparts->stok = $spareparts->stok + $request->jumlah_datang;
                        $spareparts->save();
                    }

                    $this->response->message = 'Berhasil memperbarui data detail pengadaan barang.';
                }
                else {
                    $this->response->error = true;
                    $this->response->message = 'Gagal memperbarui data detail pengadaan barang.';
                }
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Data detail pengadaan barang yang dimasukkan tidak valid.';
                $this->response->data = $validation['errors'];
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data detail pengadaan barang tidak ditemukan.';
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
                $this->response->message = 'Berhasil menghapus data detail pengadaan barang.';
            }
            else {
                $this->response->error = true;
                $this->response->message = 'Gagal menghapus data detail pengadaan barang.';
            }
        }
        else {
            $this->response->error = true;
            $this->response->message = 'Data detail pengadaan barang tidak ditemukan.';
        }

        return $this->response->make();
    }

    public function verifikasi(Request $request) {
        // Cek data kosong
        foreach($request->detail_pengadaan_barang as $x) {
            $x = (object) $x;
            if(!$x->jumlah_datang || !$x->harga) {
                $this->response->error = true;
                $this->response->message = 'Data verifikasi pengadaan barang yang dimasukkan tidak lengkap.';
                break;
            }
        }

        foreach($request->detail_pengadaan_barang as $x) {
            $x = (object) $x;
            $detail_pengadaan_barang = DetailPengadaanBarang::find($x->id);
            $detail_pengadaan_barang->jumlah_datang = $x->jumlah_datang;
            $detail_pengadaan_barang->harga = $x->harga;

            $detail_pengadaan_barang->save();
        }

        return $this->response->make();
    }
}
