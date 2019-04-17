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

    public function kelolaKonsumen()
    {
        return view('admin.kelola.konsumen');
    }

    public function kelolaCabang()
    {
        return view('admin.kelola.cabang');
    }

    public function kelolaKendaraan()
    {
        return view('admin.kelola.kendaraan');
    }
}
