<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('pegawai', 'API\PegawaiController@login');
});

Route::prefix('data')->group(function () {
    // PEGAWAI
    Route::apiResource('pegawai', 'API\PegawaiController')->except(['index', 'show']);
    Route::post('pegawai/index', 'API\PegawaiController@index');
    Route::post('pegawai/{pegawai}', 'API\PegawaiController@show');

    Route::post('pegawai/index/{column}/{value}', 'API\PegawaiController@indexWhere');

    // SPAREPARTS
    Route::apiResource('spareparts', 'API\SparepartsController')->except(['index', 'show']);
    Route::post('spareparts/index', 'API\SparepartsController@index');
    Route::post('spareparts/{spareparts}', 'API\SparepartsController@show');
    Route::get('spareparts/index/search', 'API\SparepartsController@search');
    Route::get('spareparts/index/{column}', 'API\SparepartsController@indexColumn');

    Route::post('spareparts/index/stokminimal', 'API\SparepartsController@indexStokMinimal');

    // SUPPLIER
    Route::apiResource('supplier', 'API\SupplierController')->except(['index', 'show']);
    Route::post('supplier/index', 'API\SupplierController@index');
    Route::post('supplier/{supplier}', 'API\SupplierController@show');

    // JASA SERVICE
    Route::apiResource('jasaservice', 'API\JasaServiceController')->except(['index', 'show']);
    Route::post('jasaservice/index', 'API\JasaServiceController@index');
    Route::post('jasaservice/{jasaservice}', 'API\JasaServiceController@show');

    // KONSUMEN
    Route::apiResource('konsumen', 'API\KonsumenController')->except(['index', 'show']);
    Route::post('konsumen/index', 'API\KonsumenController@index');
    Route::post('konsumen/{konsumen}', 'API\KonsumenController@show');
    Route::get('konsumen/index/search', 'API\KonsumenController@search');

    // KENDARAAN
    Route::apiResource('kendaraan', 'API\KendaraanController')->except(['index', 'show']);
    Route::post('kendaraan/index', 'API\KendaraanController@index');
    Route::post('kendaraan/{kendaraan}', 'API\KendaraanController@show');
    Route::get('kendaraan/index/{column}', 'API\KendaraanController@indexColumn');

    Route::post('kendaraan/index/{column}/{value}', 'API\KendaraanController@indexWhere');

    // CABANG
    Route::apiResource('cabang', 'API\CabangController')->except(['index', 'show']);
    Route::post('cabang/index', 'API\CabangController@index');
    Route::post('cabang/{cabang}', 'API\CabangController@show');
});

Route::prefix('transaksi')->group(function () {
    Route::prefix('pengadaan')->group(function () {
        Route::apiResource('data', 'API\PengadaanBarangController')->except(['index', 'show']);
        Route::post('index', 'API\PengadaanBarangController@index');
        Route::post('data/{pengadaan}', 'API\PengadaanBarangController@show');

        Route::apiResource('detail', 'API\DetailPengadaanBarangController')->except(['index', 'show']);
        Route::post('detail/{pengadaan}', 'API\DetailPengadaanBarangController@show');
        Route::post('verifikasi', 'API\DetailPengadaanBarangController@verifikasi');
    });

    Route::prefix('penjualan')->group(function () {
        Route::apiResource('data', 'API\PenjualanController')->except(['index', 'show']);
        Route::post('index', 'API\PenjualanController@index');
        Route::post('data/{penjualan}', 'API\PenjualanController@show');

        Route::apiResource('detail', 'API\DetailPenjualanController')->except(['index', 'show']);
        Route::post('detail/{penjualan}', 'API\DetailPenjualanController@show');

        Route::apiResource('detail/spareparts/data', 'API\DetailPenjualanSparepartsController')->except(['index', 'show']);
        Route::post('detail/spareparts/{id_detail_penjualan}', 'API\DetailPenjualanSparepartsController@show');

        Route::apiResource('detail/jasaservice/data', 'API\DetailPenjualanJasaServiceController')->except(['index', 'show']);
        Route::post('detail/jasaservice/{id_detail_penjualan}', 'API\DetailPenjualanJasaServiceController@show');
    });
});

Route::prefix('riwayat')->group(function () {
    Route::post('login', 'API\RiwayatTransaksiKonsumenController@login');
    Route::post('index', 'API\RiwayatTransaksiKonsumenController@index');
});