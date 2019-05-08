<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * show dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function kelolaPegawai()
    {
        return view('admin.kelola.pegawai');
    }

    public function kelolaSpareparts()
    {
        return view('admin.kelola.spareparts');
    }

    public function kelolaSupplier()
    {
        return view('admin.kelola.supplier');
    }

    public function kelolaJasaService()
    {
        return view('admin.kelola.jasaservice');
    }

    public function kelolaKonsumen()
    {
        return view('admin.kelola.konsumen');
    }

    public function kelolaKendaraan()
    {
        return view('admin.kelola.kendaraan');
    }

    public function kelolaCabang()
    {
        return view('admin.kelola.cabang');
    }

    public function pengadaanBarang()
    {
        return view('admin.pengadaan_barang.index');
    }

    public function pengadaanBarangDetail($id)
    {
        return view('admin.pengadaan_barang.detail', ['id' => $id]);
    }

    public function penjualan()
    {
        return view('admin.penjualan.index');
    }

    public function penjualanDetail($id)
    {
        return view('admin.penjualan.detail', ['id' => $id]);
    }
}
