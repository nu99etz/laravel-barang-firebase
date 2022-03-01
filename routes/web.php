<?php

use Illuminate\Support\Facades\Route;

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

if (Route::middleware(['guest'])->group(function () {
    Route::get('login', 'AuthController@index')->name('auth.login');
    Route::post('login', 'AuthController@doLogin')->name('auth.doLogin');
}));

if(Route::middleware(['auth'])->group(function() {
    Route::get('logout', 'AuthController@logout')->name('auth.logout');
    Route::resource('log_barang', 'LogBarangController');
    Route::get('log_barang/{log_barang}', 'LogBarangController@datatable')->name('log_barang.datatable');
}));

Route::get('/', 'BarangController@index')->name('barang.index');
Route::resource('barang', 'BarangController');
Route::post('barang/datatable', 'BarangController@datatable')->name('barang.datatable');
