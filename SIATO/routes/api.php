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
    Route::apiResource('pegawai', 'API\PegawaiController')->except(['index', 'show']);
    Route::post('pegawai/index', 'API\PegawaiController@index');
    Route::post('pegawai/{pegawai}', 'API\PegawaiController@show');

    Route::apiResource('spareparts', 'API\SparepartsController')->except(['index', 'show']);
    Route::post('spareparts/index', 'API\SparepartsController@index');
    Route::post('spareparts/{spareparts}', 'API\SparepartsController@show');

    Route::apiResource('supplier', 'API\SupplierController')->except(['index', 'show']);
    Route::post('supplier/index', 'API\SupplierController@index');
    Route::post('supplier/{supplier}', 'API\SupplierController@show');

    Route::apiResource('jasaservice', 'API\JasaServiceController')->except(['index', 'show']);
    Route::post('jasaservice/index', 'API\JasaServiceController@index');
    Route::post('jasaservice/{jasaservice}', 'API\JasaServiceController@show');

    Route::apiResource('konsumen', 'API\KonsumenController')->except(['index', 'show']);
    Route::post('konsumen/index', 'API\KonsumenController@index');
    Route::post('konsumen/{konsumen}', 'API\KonsumenController@show');

    Route::apiResource('kendaraan', 'API\KendaraanController')->except(['index', 'show']);
    Route::post('kendaraan/index', 'API\KendaraanController@index');
    Route::post('kendaraan/{kendaraan}', 'API\KendaraanController@show');

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
    });

    Route::prefix('penjualan')->group(function () {
        Route::apiResource('data', 'API\PenjualanController')->except(['index', 'show']);
        Route::post('index', 'API\PenjualanController@index');
        Route::post('data/{penjualan}', 'API\PenjualanController@show');

        Route::apiResource('detail', 'API\DetailPenjualanController')->except(['index', 'show']);
        Route::post('detail/{penjualan}', 'API\DetailPenjualanController@show');
    });
});