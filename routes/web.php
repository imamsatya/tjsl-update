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
            
            Route::prefix('provinsi')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\ProvinsiController@index')->name('referensi.provinsi.index');
                Route::post('create', 'App\Http\Controllers\Referensi\ProvinsiController@create')->name('referensi.provinsi.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\ProvinsiController@edit')->name('referensi.provinsi.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\ProvinsiController@store')->name('referensi.provinsi.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\ProvinsiController@delete')->name('referensi.provinsi.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\ProvinsiController@datatable')->name('referensi.provinsi.datatable');
                Route::get('apisyncprovinsikota', function () {
                    $exitCode = Artisan::call('apisync:provinsikota');
                    return redirect('referensi/provinsi/index');
                })->name('referensi.provinsi.apisyncprovinsikota');
            });

            Route::prefix('kota')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\KotaController@index')->name('referensi.kota.index');
                Route::post('create', 'App\Http\Controllers\Referensi\KotaController@create')->name('referensi.kota.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\KotaController@edit')->name('referensi.kota.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\KotaController@store')->name('referensi.kota.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\KotaController@delete')->name('referensi.kota.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\KotaController@datatable')->name('referensi.kota.datatable');
                Route::get('apisyncprovinsikota', function () {
                    $exitCode = Artisan::call('apisync:provinsikota');
                    return redirect('referensi/kota/index');
                })->name('referensi.kota.apisyncprovinsikota');
            });

            Route::prefix('skala_usaha')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\SkalaUsahaController@index')->name('referensi.skala_usaha.index');
                Route::post('create', 'App\Http\Controllers\Referensi\SkalaUsahaController@create')->name('referensi.skala_usaha.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\SkalaUsahaController@edit')->name('referensi.skala_usaha.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\SkalaUsahaController@store')->name('referensi.skala_usaha.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\SkalaUsahaController@delete')->name('referensi.skala_usaha.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\SkalaUsahaController@datatable')->name('referensi.skala_usaha.datatable');
            });

            Route::prefix('core_subject')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\CoreSubjectController@index')->name('referensi.core_subject.index');
                Route::post('create', 'App\Http\Controllers\Referensi\CoreSubjectController@create')->name('referensi.core_subject.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\CoreSubjectController@edit')->name('referensi.core_subject.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\CoreSubjectController@store')->name('referensi.core_subject.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\CoreSubjectController@delete')->name('referensi.core_subject.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\CoreSubjectController@datatable')->name('referensi.core_subject.datatable');
            });

            Route::prefix('satuan_ukur')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\SatuanUkurController@index')->name('referensi.satuan_ukur.index');
                Route::post('create', 'App\Http\Controllers\Referensi\SatuanUkurController@create')->name('referensi.satuan_ukur.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\SatuanUkurController@edit')->name('referensi.satuan_ukur.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\SatuanUkurController@store')->name('referensi.satuan_ukur.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\SatuanUkurController@delete')->name('referensi.satuan_ukur.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\SatuanUkurController@datatable')->name('referensi.satuan_ukur.datatable');
            });

            Route::prefix('jenis_program')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\JenisProgramController@index')->name('referensi.jenis_program.index');
                Route::post('create', 'App\Http\Controllers\Referensi\JenisProgramController@create')->name('referensi.jenis_program.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\JenisProgramController@edit')->name('referensi.jenis_program.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\JenisProgramController@store')->name('referensi.jenis_program.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\JenisProgramController@delete')->name('referensi.jenis_program.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\JenisProgramController@datatable')->name('referensi.jenis_program.datatable');
            });

            Route::prefix('sektor_usaha')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\SektorUsahaController@index')->name('referensi.sektor_usaha.index');
                Route::post('create', 'App\Http\Controllers\Referensi\SektorUsahaController@create')->name('referensi.sektor_usaha.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\SektorUsahaController@edit')->name('referensi.sektor_usaha.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\SektorUsahaController@store')->name('referensi.sektor_usaha.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\SektorUsahaController@delete')->name('referensi.sektor_usaha.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\SektorUsahaController@datatable')->name('referensi.sektor_usaha.datatable');
            });

            Route::prefix('kolekbilitas_pendanaan')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\KolekbilitasPendanaanController@index')->name('referensi.kolekbilitas_pendanaan.index');
                Route::post('create', 'App\Http\Controllers\Referensi\KolekbilitasPendanaanController@create')->name('referensi.kolekbilitas_pendanaan.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\KolekbilitasPendanaanController@edit')->name('referensi.kolekbilitas_pendanaan.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\KolekbilitasPendanaanController@store')->name('referensi.kolekbilitas_pendanaan.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\KolekbilitasPendanaanController@delete')->name('referensi.kolekbilitas_pendanaan.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\KolekbilitasPendanaanController@datatable')->name('referensi.kolekbilitas_pendanaan.datatable');
            });

            Route::prefix('kondisi_pinjaman')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\KondisiPinjamanController@index')->name('referensi.kondisi_pinjaman.index');
                Route::post('create', 'App\Http\Controllers\Referensi\KondisiPinjamanController@create')->name('referensi.kondisi_pinjaman.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\KondisiPinjamanController@edit')->name('referensi.kondisi_pinjaman.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\KondisiPinjamanController@store')->name('referensi.kondisi_pinjaman.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\KondisiPinjamanController@delete')->name('referensi.kondisi_pinjaman.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\KondisiPinjamanController@datatable')->name('referensi.kondisi_pinjaman.datatable');
            });

            Route::prefix('jenis_pembayaran')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\JenisPembayaranController@index')->name('referensi.jenis_pembayaran.index');
                Route::post('create', 'App\Http\Controllers\Referensi\JenisPembayaranController@create')->name('referensi.jenis_pembayaran.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\JenisPembayaranController@edit')->name('referensi.jenis_pembayaran.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\JenisPembayaranController@store')->name('referensi.jenis_pembayaran.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\JenisPembayaranController@delete')->name('referensi.jenis_pembayaran.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\JenisPembayaranController@datatable')->name('referensi.jenis_pembayaran.datatable');
            });

            Route::prefix('bank_account')->group(function(){
                Route::get('index', 'App\Http\Controllers\Referensi\BankAccountController@index')->name('referensi.bank_account.index');
                Route::post('create', 'App\Http\Controllers\Referensi\BankAccountController@create')->name('referensi.bank_account.create');
                Route::post('edit', 'App\Http\Controllers\Referensi\BankAccountController@edit')->name('referensi.bank_account.edit');
                Route::post('store', 'App\Http\Controllers\Referensi\BankAccountController@store')->name('referensi.bank_account.store');
                Route::post('delete', 'App\Http\Controllers\Referensi\BankAccountController@delete')->name('referensi.bank_account.delete');
                Route::get('datatable', 'App\Http\Controllers\Referensi\BankAccountController@datatable')->name('referensi.bank_account.datatable');
                Route::get('apisyncbankaccount', function () {
                    $exitCode = Artisan::call('apisync:bankaccount');
                    return redirect('referensi/bank_account/index');
                })->name('referensi.bank_account.apisyncbankaccount');
            });

        });
        
        Route::prefix('target')->group(function () {
            Route::prefix('administrasi')->group(function(){
                Route::get('index', 'App\Http\Controllers\Target\AdministrasiController@index')->name('target.administrasi.index');
                Route::post('create', 'App\Http\Controllers\Target\AdministrasiController@create')->name('target.administrasi.create');
                Route::post('upload', 'App\Http\Controllers\Target\AdministrasiController@upload')->name('target.administrasi.upload');
                Route::post('download_template', 'App\Http\Controllers\Target\AdministrasiController@download_template')->name('target.administrasi.download_template');
                Route::post('get_status', 'App\Http\Controllers\Target\AdministrasiController@get_status')->name('target.administrasi.get_status');
                Route::post('detail', 'App\Http\Controllers\Target\AdministrasiController@detail')->name('target.administrasi.detail');
                Route::post('edit', 'App\Http\Controllers\Target\AdministrasiController@edit')->name('target.administrasi.edit');
                Route::post('export', 'App\Http\Controllers\Target\AdministrasiController@export')->name('target.administrasi.export');
                Route::post('store', 'App\Http\Controllers\Target\AdministrasiController@store')->name('target.administrasi.store');
                Route::post('delete', 'App\Http\Controllers\Target\AdministrasiController@delete')->name('target.administrasi.delete');
                Route::get('datatable', 'App\Http\Controllers\Target\AdministrasiController@datatable')->name('target.administrasi.datatable');
                Route::post('validasi', 'App\Http\Controllers\Target\AdministrasiController@validasi')->name('target.administrasi.validasi');
                Route::post('log_status', 'App\Http\Controllers\Target\AdministrasiController@log_status')->name('target.administrasi.log_status');
            });

            Route::prefix('kegiatan')->group(function(){
                Route::get('index', 'App\Http\Controllers\Target\KegiatanController@index')->name('target.kegiatan.index');
                Route::post('create', 'App\Http\Controllers\Target\KegiatanController@create')->name('target.kegiatan.create');
                Route::post('edit', 'App\Http\Controllers\Target\KegiatanController@edit')->name('target.kegiatan.edit');
                Route::post('store', 'App\Http\Controllers\Target\KegiatanController@store')->name('target.kegiatan.store');
                Route::post('delete', 'App\Http\Controllers\Target\KegiatanController@delete')->name('target.kegiatan.delete');
                Route::get('datatable', 'App\Http\Controllers\Target\KegiatanController@datatable')->name('target.kegiatan.datatable');
            });

            Route::prefix('upload_target')->group(function(){
                Route::get('index', 'App\Http\Controllers\Target\UploadTargetController@index')->name('target.upload_target.index');
                Route::post('create', 'App\Http\Controllers\Target\UploadTargetController@create')->name('target.upload_target.create');
                Route::post('edit', 'App\Http\Controllers\Target\UploadTargetController@edit')->name('target.upload_target.edit');
                Route::post('store', 'App\Http\Controllers\Target\UploadTargetController@store')->name('target.upload_target.store');
                Route::post('delete', 'App\Http\Controllers\Target\UploadTargetController@delete')->name('target.upload_target.delete');
                Route::get('datatable', 'App\Http\Controllers\Target\UploadTargetController@datatable')->name('target.upload_target.datatable');
                Route::post('export_berhasil', 'App\Http\Controllers\Target\UploadTargetController@export_berhasil')->name('target.upload_target.export_berhasil');
                Route::post('export_gagal', 'App\Http\Controllers\Target\UploadTargetController@export_gagal')->name('target.upload_target.export_gagal');
            });
        });
        
        Route::prefix('realisasi')->group(function () {
            Route::prefix('administrasi')->group(function(){
                Route::get('index', 'App\Http\Controllers\Realisasi\AdministrasiController@index')->name('realisasi.administrasi.index');
                Route::post('create', 'App\Http\Controllers\Realisasi\AdministrasiController@create')->name('realisasi.administrasi.create');
                Route::post('edit', 'App\Http\Controllers\Realisasi\AdministrasiController@edit')->name('realisasi.administrasi.edit');
                Route::post('store', 'App\Http\Controllers\Realisasi\AdministrasiController@store')->name('realisasi.administrasi.store');
                Route::post('delete', 'App\Http\Controllers\Realisasi\AdministrasiController@delete')->name('realisasi.administrasi.delete');
                Route::post('export', 'App\Http\Controllers\Realisasi\AdministrasiController@export')->name('realisasi.administrasi.export');
                Route::get('datatable', 'App\Http\Controllers\Realisasi\AdministrasiController@datatable')->name('realisasi.administrasi.datatable');
                Route::post('detail', 'App\Http\Controllers\Realisasi\AdministrasiController@detail')->name('realisasi.administrasi.detail');
                Route::post('download_template', 'App\Http\Controllers\Realisasi\AdministrasiController@download_template')->name('realisasi.administrasi.download_template');
                Route::post('upload', 'App\Http\Controllers\Realisasi\AdministrasiController@upload')->name('realisasi.administrasi.upload');
                Route::post('log_status', 'App\Http\Controllers\Realisasi\AdministrasiController@log_status')->name('realisasi.administrasi.log_status');
                Route::post('validasi', 'App\Http\Controllers\Realisasi\AdministrasiController@validasi')->name('realisasi.administrasi.validasi');
                Route::post('get_status', 'App\Http\Controllers\Realisasi\AdministrasiController@get_status')->name('realisasi.administrasi.get_status');
            });

            Route::prefix('upload_realisasi')->group(function(){
                Route::get('index', 'App\Http\Controllers\Realisasi\UploadRealisasiController@index')->name('realisasi.upload_realisasi.index');
                Route::post('create', 'App\Http\Controllers\Realisasi\UploadRealisasiController@create')->name('realisasi.upload_realisasi.create');
                Route::post('edit', 'App\Http\Controllers\Realisasi\UploadRealisasiController@edit')->name('realisasi.upload_realisasi.edit');
                Route::post('store', 'App\Http\Controllers\Realisasi\UploadRealisasiController@store')->name('realisasi.upload_realisasi.store');
                Route::post('delete', 'App\Http\Controllers\Realisasi\UploadRealisasiController@delete')->name('realisasi.upload_realisasi.delete');
                Route::get('datatable', 'App\Http\Controllers\Realisasi\UploadRealisasiController@datatable')->name('realisasi.upload_realisasi.datatable');
                Route::post('export_berhasil', 'App\Http\Controllers\Realisasi\UploadRealisasiController@export_berhasil')->name('realisasi.upload_target.export_berhasil');
                Route::post('export_gagal', 'App\Http\Controllers\Realisasi\UploadRealisasiController@export_gagal')->name('realisasi.upload_target.export_gagal');
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
            Route::post('get_status', 'App\Http\Controllers\AnggaranTpbController@get_status')->name('anggaran_tpb.get_status');
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
            Route::post('/getpumkanggaranbyperiode', 'App\Http\Controllers\FetchController@getPumkAnggaranByPeriode');
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


        Route::prefix('pumk')->group(function () {
            Route::prefix('anggaran')->group(function(){
                Route::get('index', 'App\Http\Controllers\PUMK\AnggaranController@index')->name('pumk.anggaran.index');
                Route::get('datatable', 'App\Http\Controllers\PUMK\AnggaranController@datatable')->name('pumk.anggaran.datatable');
                Route::post('create', 'App\Http\Controllers\PUMK\AnggaranController@create')->name('pumk.anggaran.create');
                Route::post('edit', 'App\Http\Controllers\PUMK\AnggaranController@edit')->name('pumk.anggaran.edit');
                Route::post('store', 'App\Http\Controllers\PUMK\AnggaranController@store')->name('pumk.anggaran.store');
                Route::post('delete', 'App\Http\Controllers\PUMK\AnggaranController@delete')->name('pumk.anggaran.delete');
                Route::post('updatestatus', 'App\Http\Controllers\PUMK\AnggaranController@update_status')->name('pumk.anggaran.updatestatus');
                Route::get('datatable', 'App\Http\Controllers\PUMK\AnggaranController@datatable')->name('pumk.anggaran.datatable');
                Route::post('show', 'App\Http\Controllers\PUMK\AnggaranController@show')->name('pumk.anggaran.show');
                Route::post('export', 'App\Http\Controllers\PUMK\AnggaranController@export')->name('pumk.anggaran.export');
                Route::post('log', 'App\Http\Controllers\PUMK\AnggaranController@log_status')->name('pumk.anggaran.log');
                Route::get('bumn-sync', 'App\Http\Controllers\PUMK\AnggaranController@sync')->name('pumk.anggaran.sync');
                Route::get('create-pdf/{id?}', 'App\Http\Controllers\PUMK\AnggaranController@exportPDF')->name('pumk.anggaran.create-pdf');
            });

            Route::prefix('data_mitra')->group(function(){
                Route::get('index', 'App\Http\Controllers\PUMK\MitraBinaanController@index')->name('pumk.data_mitra.index');
                Route::get('datatable', 'App\Http\Controllers\PUMK\MitraBinaanController@datatable')->name('pumk.data_mitra.datatable');
                Route::post('show/{id?}', 'App\Http\Controllers\PUMK\MitraBinaanController@show')->name('pumk.data_mitra.show');
                Route::post('delete', 'App\Http\Controllers\PUMK\MitraBinaanController@delete')->name('pumk.data_mitra.delete');
                Route::post('edit', 'App\Http\Controllers\PUMK\MitraBinaanController@edit')->name('pumk.data_mitra.edit');
                Route::post('store', 'App\Http\Controllers\PUMK\MitraBinaanController@store')->name('pumk.data_mitra.store');
                Route::post('export', 'App\Http\Controllers\PUMK\MitraBinaanController@export')->name('pumk.data_mitra.export');
            });

            Route::prefix('upload_data_mitra')->group(function(){
                Route::get('index', 'App\Http\Controllers\PUMK\UploadMitraBinaanController@index')->name('pumk.upload_data_mitra.index');
                Route::get('download_template', 'App\Http\Controllers\PUMK\UploadMitraBinaanController@download_template')->name('pumk.upload_data_mitra.download_template');
                Route::get('download_upload_berhasil/{kode?}', 'App\Http\Controllers\PUMK\UploadMitraBinaanController@download_upload_berhasil')->name('pumk.upload_data_mitra.download_upload_berhasil');
                Route::get('download_upload_gagal/{kode?}', 'App\Http\Controllers\PUMK\UploadMitraBinaanController@download_upload_gagal')->name('pumk.upload_data_mitra.download_upload_gagal');
                Route::post('store', 'App\Http\Controllers\PUMK\UploadMitraBinaanController@store')->name('pumk.upload_data_mitra.store');
                Route::get('datatable', 'App\Http\Controllers\PUMK\UploadMitraBinaanController@datatable')->name('pumk.upload_data_mitra.datatable');
            });
        });

}); // end login dengan cas

Route::get('cc', function(){
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    dd('cache & config clear successfully');
});