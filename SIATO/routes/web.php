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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('admin')->group(function() {
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

    Route::get('/', 'AdminController@dashboard')->name('admin.dashboard');

    Route::prefix('kelola')->group(function() {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        Route::get('pegawai', 'AdminController@kelolaPegawai')->name('admin.kelola.pegawai');
        Route::get('spareparts', 'AdminController@kelolaSpareparts')->name('admin.kelola.spareparts');
        Route::get('cabang', 'AdminController@kelolaCabang')->name('admin.kelola.cabang');
    });
});

Route::get('/home', 'HomeController@index')->name('home');