<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return "Cleared!";
});

Route::get('/', function () {
    return view('index');
});

Route::get('/riwayat', 'RiwayatController@view');
Route::get('/test', 'API\RiwayatTransaksiKonsumenController@index');

Auth::routes();

Route::prefix('admin')->group(function () {
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

    Route::get('/', 'AdminController@dashboard')->name('admin.dashboard');

    Route::prefix('kelola')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('pegawai', 'AdminController@kelolaPegawai')->name('admin.kelola.pegawai');
        Route::get('spareparts', 'AdminController@kelolaSpareparts')->name('admin.kelola.spareparts');
        Route::get('konsumen', 'AdminController@kelolaKonsumen')->name('admin.kelola.konsumen');
        Route::get('cabang', 'AdminController@kelolaCabang')->name('admin.kelola.cabang');
        Route::get('kendaraan', 'AdminController@kelolaKendaraan')->name('admin.kelola.kendaraan');
        Route::get('supplier', 'AdminController@kelolaSupplier')->name('admin.kelola.supplier');
        Route::get('jasaservice', 'AdminController@kelolaJasaService')->name('admin.kelola.jasaservice');
    });

    Route::prefix('transaksi')->group(function () {
        Route::prefix('pengadaan_barang')->group(function () {
            Route::get('/', 'AdminController@pengadaanBarang')->name('admin.transaksi.pengadaan_barang');
            Route::get('/detail/{id}', 'AdminController@pengadaanBarangDetail')->name('admin.transaksi.pengadaan_barang.detail');
        });

        Route::prefix('penjualan')->group(function () {
            Route::get('/', 'AdminController@penjualan')->name('admin.transaksi.penjualan');
            Route::get('/detail/{id}', 'AdminController@penjualanDetail')->name('admin.transaksi.penjualan.detail');
        });

        Route::prefix('pembayaran')->group(function () {
            Route::get('/', 'AdminController@pembayaran')->name('admin.transaksi.pembayaran');
            Route::get('/detail/{id}', 'AdminController@pembayaranDetail')->name('admin.transaksi.pembayaran.detail');
        });
    });

    Route::prefix('laporan')->group(function () {
        Route::get('spareparts_terlaris', 'AdminController@laporan_spareparts_terlaris')->name('admin.laporan.spareparts_terlaris');
        Route::get('pendapatan_bulanan', 'AdminController@laporan_pendapatan_bulanan')->name('admin.laporan.pendapatan_bulanan');
        Route::get('pendapatan_tahunan', 'AdminController@laporan_pendapatan_tahunan')->name('admin.laporan.pendapatan_tahunan');
        Route::get('pengeluaran_bulanan', 'AdminController@laporan_pengeluaran_bulanan')->name('admin.laporan.pengeluaran_bulanan');
        Route::get('penjualan_jasa', 'AdminController@laporan_penjualan_jasa')->name('admin.laporan.penjualan_jasa');
        Route::get('sisa_stok', 'AdminController@laporan_sisa_stok')->name('admin.laporan.sisa_stok');
    });
});

Route::get('/home', 'HomeController@index')->name('home');
