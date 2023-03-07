<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('service-provinsi', 'App\Http\Controllers\ApiController@getprovinsi');
// Route::get('service-kota', 'App\Http\Controllers\ApiController@getkota');
// Route::get('service-kota-by-provinsi/{id_provinsi?}', 'App\Http\Controllers\ApiController@getkota_filter');
// Route::get('service-bumn-ref', 'App\Http\Controllers\ApiController@getreferensibumnaktif');
Route::get('service-tpb-ref', 'App\Http\Controllers\ApiController@getreferensitpb');
Route::get('service-pilar-ref', 'App\Http\Controllers\ApiController@getreferensipilar');
// Route::get('service-kodetujuan-ref', 'App\Http\Controllers\ApiController@getreferensikodetujuantpb');
// Route::get('service-kodeindikator-ref', 'App\Http\Controllers\ApiController@getreferensikodeindikator');
// Route::get('service-kodeindikator-ref-by-tpb/{id_tpb?}', 'App\Http\Controllers\ApiController@getreferensikodeindikator_filter');
// Route::get('service-pelaksanaanprogram-ref', 'App\Http\Controllers\ApiController@getreferensipelaksanaanprogram');
// Route::get('service-satuanukur-ref', 'App\Http\Controllers\ApiController@getreferensisatuanukur');
// Route::get('service-coresubject-ref', 'App\Http\Controllers\ApiController@getreferensicoresubject');
//Route::get('service-users', 'App\Http\Controllers\ApiController@getuserbumn');
Route::get('service-pilar-tpb', 'App\Http\Controllers\ApiController@getrelasipilartpb');
// Route::get('service-pilar-tpb-by-pilar/{id_pilar?}', 'App\Http\Controllers\ApiController@getrelasipilartpb_filter');
// Route::get('service-program-approved', 'App\Http\Controllers\ApiController@getprogramapproved');
// Route::get('service-program-owner', 'App\Http\Controllers\ApiController@getprogramowner');
// Route::get('service-program-owner-by-bumn/{id_bumn?}', 'App\Http\Controllers\ApiController@getprogramowner_filter');
// Route::get('service-program-owner-by-bumn-tahun/{id_bumn?}/{tahun?}', 'App\Http\Controllers\ApiController@getprogramowner_filter_bumn');