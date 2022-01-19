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

Route::get('service-provinsi', 'App\Http\Controllers\ApiController@getprovinsi');
Route::get('service-kota', 'App\Http\Controllers\ApiController@getkota');
Route::get('service-bumn-ref', 'App\Http\Controllers\ApiController@getreferensibumnaktif');
Route::get('service-tpb-ref', 'App\Http\Controllers\ApiController@getreferensitpb');
Route::get('service-pilar-ref', 'App\Http\Controllers\ApiController@getreferensipilar');
Route::get('service-kodetujuan-ref', 'App\Http\Controllers\ApiController@getreferensikodetujuantpb');
Route::get('service-kodeindikator-ref', 'App\Http\Controllers\ApiController@getreferensikodeindikator');
