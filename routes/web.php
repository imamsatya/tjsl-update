<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TjslUser;
use App\Http\Middleware\CasAuth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::view('forbidden', 'errors.login');
// login dengan cas
Route::middleware([CasAuth::class, TjslUser::class])->group(function () {
   Route::get('logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

    // login tanpa cas
    //    Route::get('/', 'App\Http\Controllers\HomeController@index')->name('home');
        Route::get('/', 'App\Http\Controllers\HomeController@index')->name('dashboard.index');

        Route::prefix('role')->group(function(){
            Route::get('index', 'App\Http\Controllers\RoleController@index')->name('role.index');
            Route::post('create', 'App\Http\Controllers\RoleController@create')->name('role.create');
            Route::post('edit', 'App\Http\Controllers\RoleController@edit')->name('role.edit');
            Route::post('store', 'App\Http\Controllers\RoleController@store')->name('role.store');
            Route::post('delete', 'App\Http\Controllers\RoleController@delete')->name('role.delete');
            Route::get('datatable', 'App\Http\Controllers\RoleController@datatable')->name('role.datatable');
            Route::get('gettreemenubyrole/{id?}', 'App\Http\Controllers\RoleController@gettreemenubyrole')->name('role.gettreemenubyrole');
        });

        Route::prefix('user')->group(function(){
            Route::get('index', 'App\Http\Controllers\UserController@index')->name('user.index');
            Route::post('create', 'App\Http\Controllers\UserController@create')->name('user.create');
            Route::post('edit', 'App\Http\Controllers\UserController@edit')->name('user.edit');
            Route::post('store', 'App\Http\Controllers\UserController@store')->name('user.store');
            Route::post('delete', 'App\Http\Controllers\UserController@delete')->name('user.delete');
            Route::post('checkuser', 'App\Http\Controllers\UserController@checkuser')->name('user.checkuser');
            Route::get('datatable', 'App\Http\Controllers\UserController@datatable')->name('user.datatable');
        });

        Route::prefix('referensi')->group(function () {

            Route::prefix('versi_pilar')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\VersiPilarController@index')->name('referensi.versi_pilar.index');
                Route::post('create', 'App\Http\Controllers\Referensi\VersiPilarController@create')->name('referensi.versi_pilar.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\VersiPilarController@edit')->name('referensi.versi_pilar.edit');
                Route::post('edit_pilar', 'App\Http\Controllers\Referensi\VersiPilarController@edit_pilar')->name('referensi.versi_pilar.edit_pilar');
                Route::post('add_pilar', 'App\Http\Controllers\Referensi\VersiPilarController@add_pilar')->name('referensi.versi_pilar.add_pilar');
                Route::post('store', 'App\Http\Controllers\Referensi\VersiPilarController@store')->name('referensi.versi_pilar.store');
                Route::post('store_pilar', 'App\Http\Controllers\Referensi\VersiPilarController@store_pilar')->name('referensi.versi_pilar.store_pilar');
                Route::post('delete', 'App\Http\Controllers\Referensi\VersiPilarController@delete')->name('referensi.versi_pilar.delete');
                Route::post('delete_pilar', 'App\Http\Controllers\Referensi\VersiPilarController@delete_pilar')->name('referensi.versi_pilar.delete_pilar');
                Route::post('update_status', 'App\Http\Controllers\Referensi\VersiPilarController@update_status')->name('referensi.versi_pilar.update_status');
                Route::get('datatable', 'App\Http\Controllers\Referensi\VersiPilarController@datatable')->name('referensi.versi_pilar.datatable');
            });

            Route::prefix('pilar_pembangunan')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\PilarPembangunanController@index')->name('referensi.pilar_pembangunan.index');
                Route::post('create', 'App\Http\Controllers\Referensi\PilarPembangunanController@create')->name('referensi.pilar_pembangunan.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\PilarPembangunanController@edit')->name('referensi.pilar_pembangunan.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\PilarPembangunanController@store')->name('referensi.pilar_pembangunan.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\PilarPembangunanController@delete')->name('referensi.pilar_pembangunan.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\PilarPembangunanController@datatable')->name('referensi.pilar_pembangunan.datatable');
            });

            Route::prefix('tpb')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\TpbController@index')->name('referensi.tpb.index');
                Route::post('create', 'App\Http\Controllers\Referensi\TpbController@create')->name('referensi.tpb.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\TpbController@edit')->name('referensi.tpb.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\TpbController@store')->name('referensi.tpb.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\TpbController@delete')->name('referensi.tpb.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\TpbController@datatable')->name('referensi.tpb.datatable');
            });
            
            Route::prefix('status')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\StatusController@index')->name('referensi.status.index');
                Route::post('create', 'App\Http\Controllers\Referensi\StatusController@create')->name('referensi.status.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\StatusController@edit')->name('referensi.status.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\StatusController@store')->name('referensi.status.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\StatusController@delete')->name('referensi.status.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\StatusController@datatable')->name('referensi.status.datatable');
            });
            
            Route::prefix('cara_penyaluran')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\CaraPenyaluranController@index')->name('referensi.cara_penyaluran.index');
                Route::post('create', 'App\Http\Controllers\Referensi\CaraPenyaluranController@create')->name('referensi.cara_penyaluran.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\CaraPenyaluranController@edit')->name('referensi.cara_penyaluran.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\CaraPenyaluranController@store')->name('referensi.cara_penyaluran.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\CaraPenyaluranController@delete')->name('referensi.cara_penyaluran.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\CaraPenyaluranController@datatable')->name('referensi.cara_penyaluran.datatable');
            });
            
            Route::prefix('kode_indikator')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\KodeIndikatorController@index')->name('referensi.kode_indikator.index');
                Route::post('create', 'App\Http\Controllers\Referensi\KodeIndikatorController@create')->name('referensi.kode_indikator.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\KodeIndikatorController@edit')->name('referensi.kode_indikator.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\KodeIndikatorController@store')->name('referensi.kode_indikator.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\KodeIndikatorController@delete')->name('referensi.kode_indikator.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\KodeIndikatorController@datatable')->name('referensi.kode_indikator.datatable');
            });
            
            Route::prefix('perusahaan')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\PerusahaanController@index')->name('referensi.perusahaan.index');
                Route::post('create', 'App\Http\Controllers\Referensi\PerusahaanController@create')->name('referensi.perusahaan.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\PerusahaanController@edit')->name('referensi.perusahaan.edit');
                Route::post('update_active', 'App\Http\Controllers\Referensi\PerusahaanController@update_active')->name('referensi.perusahaan.update_active');
                Route::post('store', 'App\Http\Controllers\Referensi\PerusahaanController@store')->name('referensi.perusahaan.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\PerusahaanController@delete')->name('referensi.perusahaan.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\PerusahaanController@datatable')->name('referensi.perusahaan.datatable');
                
                Route::get('silababumnsync', function () {
                    $exitCode = Artisan::call('silaba:bumnsync');
                    return redirect('referensi/perusahaan/index');
                })->name('referensi.perusahaan.silababumnsync');
            });
            
            Route::prefix('periode_laporan')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\PeriodeLaporanController@index')->name('referensi.periode_laporan.index');
                Route::post('create', 'App\Http\Controllers\Referensi\PeriodeLaporanController@create')->name('referensi.periode_laporan.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\PeriodeLaporanController@edit')->name('referensi.periode_laporan.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\PeriodeLaporanController@store')->name('referensi.periode_laporan.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\PeriodeLaporanController@delete')->name('referensi.periode_laporan.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\PeriodeLaporanController@datatable')->name('referensi.periode_laporan.datatable');
            });
        });

        Route::prefix('menu')->group(function(){
            Route::get('index', 'App\Http\Controllers\MenuController@index')->name('menu.index');
            Route::post('create', 'App\Http\Controllers\MenuController@create')->name('menu.create');
            Route::post('edit', 'App\Http\Controllers\MenuController@edit')->name('menu.edit');
            Route::post('store', 'App\Http\Controllers\MenuController@store')->name('menu.store');
            Route::post('delete', 'App\Http\Controllers\MenuController@delete')->name('menu.delete');
            Route::post('gettreemenu', 'App\Http\Controllers\MenuController@gettreemenu')->name('menu.gettreemenu');
            Route::post('submitchangestructure', 'App\Http\Controllers\MenuController@submitchangestructure')->name('menu.submitchangestructure');
            Route::get('datatable', 'App\Http\Controllers\MenuController@datatable')->name('menu.datatable');
        });

        Route::prefix('permission')->group(function(){
            Route::get('index', 'App\Http\Controllers\PermissionController@index')->name('permission.index');
            Route::post('create', 'App\Http\Controllers\PermissionController@create')->name('permission.create');
            Route::post('edit', 'App\Http\Controllers\PermissionController@edit')->name('permission.edit');
            Route::post('store', 'App\Http\Controllers\PermissionController@store')->name('permission.store');
            Route::post('delete', 'App\Http\Controllers\PermissionController@delete')->name('permission.delete');
            Route::get('datatable', 'App\Http\Controllers\PermissionController@datatable')->name('permission.datatable');
        });

        Route::prefix('anggaran_tpb')->group(function(){
            Route::get('index', 'App\Http\Controllers\AnggaranTpbController@index')->name('anggaran_tpb.index');
            Route::post('create', 'App\Http\Controllers\AnggaranTpbController@create')->name('anggaran_tpb.create');
            Route::post('edit', 'App\Http\Controllers\AnggaranTpbController@edit')->name('anggaran_tpb.edit');
            Route::post('store', 'App\Http\Controllers\AnggaranTpbController@store')->name('anggaran_tpb.store');
            Route::post('delete', 'App\Http\Controllers\AnggaranTpbController@delete')->name('anggaran_tpb.delete');
            Route::post('delete_by_pilar', 'App\Http\Controllers\AnggaranTpbController@delete_by_pilar')->name('anggaran_tpb.delete_by_pilar');
            Route::get('datatable', 'App\Http\Controllers\AnggaranTpbController@datatable')->name('anggaran_tpb.datatable');
            Route::post('export', 'App\Http\Controllers\AnggaranTpbController@export')->name('anggaran_tpb.export');
            Route::post('validasi', 'App\Http\Controllers\AnggaranTpbController@validasi')->name('anggaran_tpb.validasi');
            Route::post('get_status', 'App\Http\Controllers\AnggaranTpbController@getStatus')->name('anggaran_tpb.get_status');
            Route::post('log_status', 'App\Http\Controllers\AnggaranTpbController@log_status')->name('anggaran_tpb.log_status');
        });

        Route::prefix('laporan_manajemen')->group(function(){
            Route::get('index', 'App\Http\Controllers\LaporanManajemenController@index')->name('laporan_manajemen.index');
            Route::post('create', 'App\Http\Controllers\LaporanManajemenController@create')->name('laporan_manajemen.create');
            Route::post('edit', 'App\Http\Controllers\LaporanManajemenController@edit')->name('laporan_manajemen.edit');
            Route::post('store', 'App\Http\Controllers\LaporanManajemenController@store')->name('laporan_manajemen.store');
            Route::post('delete', 'App\Http\Controllers\LaporanManajemenController@delete')->name('laporan_manajemen.delete');
            Route::get('datatable', 'App\Http\Controllers\LaporanManajemenController@datatable')->name('laporan_manajemen.datatable');
            Route::post('validasi', 'App\Http\Controllers\LaporanManajemenController@validasi')->name('laporan_manajemen.validasi');
            Route::post('log_status', 'App\Http\Controllers\LaporanManajemenController@log_status')->name('laporan_manajemen.log_status');
        });
        Auth::routes();


        // Fetch Route
        Route::prefix('fetch')->group(function(){
            Route::post('/gettpbbypilar', 'App\Http\Controllers\FetchController@getTpbByPilar');
        });

        Route::prefix('general')->group(function(){
            Route::get('fetchpilihaninputan', 'App\Http\Controllers\GeneralController@fetchpilihaninputan')->name('general.fetchpilihaninputan');
            Route::get('fecthwilayah', 'App\Http\Controllers\GeneralController@fecthwilayah')->name('general.fecthwilayah');
            Route::get('fetchparentmenu', 'App\Http\Controllers\GeneralController@fetchparentmenu')->name('general.fetchparentmenu');
            Route::get('fetchparentunit', 'App\Http\Controllers\GeneralController@fetchparentunit')->name('general.fetchparentunit');
            Route::get('fetchkategoriuser', 'App\Http\Controllers\GeneralController@fetchkategoriuser')->name('general.fetchkategoriuser');
            Route::get('fetchbumnactive', 'App\Http\Controllers\GeneralController@fetchbumnactive')->name('general.fetchbumnactive');
            Route::get('fetchassessment', 'App\Http\Controllers\GeneralController@fetchassessment')->name('general.fetchassessment');
            Route::get('fetchrole', 'App\Http\Controllers\GeneralController@fetchrole')->name('general.fetchrole');
        });

}); // end login dengan cas

Route::get('cc', function(){
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    dd('cache & config clear successfully');
});