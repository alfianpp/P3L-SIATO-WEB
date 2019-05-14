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

    public function pembayaran()
    {
        return view('admin.pembayaran.index');
    }

    public function pembayaranDetail($id)
    {
        return view('admin.pembayaran.detail', ['id' => $id]);
    }

    public function laporan_spareparts_terlaris()
    {
        return view('admin.laporan.spareparts-terlaris');
    }

    public function laporan_pendapatan_bulanan()
    {
        return view('admin.laporan.pendapatan-bulanan');
    }

    public function laporan_pendapatan_tahunan()
    {
        return view('admin.laporan.pendapatan-tahunan');
    }

    public function laporan_pengeluaran_bulanan()
    {
        return view('admin.laporan.pengeluaran-bulanan');
    }

    public function laporan_penjualan_jasa()
    {
        return view('admin.laporan.penjualan-jasa');
    }

    public function laporan_sisa_stok()
    {
        return view('admin.laporan.sisa-stok');
    }
}
