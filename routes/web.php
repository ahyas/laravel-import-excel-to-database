<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::group(['middleware' => 'auth'], function () {
    
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('import_excel/pegawai', 'PegawaiController@index');
    Route::get('import_excel/download_template', 'PegawaiController@download_template')->name('import_excel.download_template');

    Route::get('api/v1/pegawai', 'PegawaiController@getDataPegawai')->name('api.v1.pegawai');

    Route::post('api/v1/pegawai/import', 'PegawaiController@importDataPegawai')->name('api.v1.import');

    Route::delete('api/v1/pegawai/clear', 'PegawaiController@clearDataPegawai')->name('api.v1.pegawai.clear');

});


Route::get('phpinfo', 'PhpInfoController@index');