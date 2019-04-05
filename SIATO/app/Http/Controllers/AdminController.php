<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
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

    public function kelolaCabang()
    {
        return view('admin.kelola.cabang');
    }
}
