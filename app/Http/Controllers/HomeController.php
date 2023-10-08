<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Tpb;
use App\Models\Kegiatan;
use App\Models\AnggaranTpb;
use App\Models\KolekbilitasPendanaan;
use App\Models\PumkMitraBinaan;
use App\Models\PeriodeLaporan;
use App\Models\Status;
use App\Models\Bulan;
use App\Models\PilarPembangunan;
use App\Models\OwnerProgram;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'home';
        $this->pagetitle = 'Dashboard';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
       
        $id_users = \Auth::user()->id;
        $currentYear = date('Y');
        // $id_users = 16; // dummy
        

        // // Get the user instance you want to authenticate
        // $user = User::find($id_users);

        // // Authenticate the user
        // Auth::login($user);
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;
        $tahun = ($request->tahun ? $request->tahun : date('Y'));
        $admin_bumn = false;
        $super_admin = false;
        $admin_tjsl = false;
      
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if ($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                    // $perusahaan_id = 16;
                }
                if ($v == 'Super Admin') {
                    $super_admin = true;
                    $perusahaan_id = $request->perusahaan_id;
                }
                if ($v == 'Admin TJSL') {
                    $admin_tjsl = true;
                    $perusahaan_id = $request->perusahaan_id;
                }
            }
        }

        //sinkronisasi data kegiatan by id bumn
        try {
            $id_bumn = auth()->user()->id_bumn;
            if ($id_bumn) {
                // $call = \Artisan::call('syncbumn:activity');    
            }
        } catch (\Exception $e) {
        }
        // dd($this->__route);
        return view($this->__route . '.index', [
            'users' => $users,
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan_id' => $perusahaan_id,
            'tahun' => $tahun,
            'admin_bumn' => $admin_bumn,
            'tpb' => TPB::all(),
            'tpb_id' => $request->tpb_id,
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'filter_bumn_id' => $perusahaan_id,
            'filter_periode_id' => $request->periode_id,
            'filter_status_id' => $request->status_id,
            'filter_tahun' => $request->tahun,
            'filter_owner_id' => $request->owner_id,
            'bulan' => Bulan::get(),
            'owner' => OwnerProgram::get(),
            'menuStatus' => $this->getMenuStatus($currentYear, $perusahaan_id)
        ]);
    }

    public function getMenuStatus($tahun, $perusahaan_id){
        $user = Auth::user();
        $perusahaan_id = $perusahaan_id ?? $user->id_bumn;
        $tahun = $tahun ?? date('Y');
        
        //RKA
        $rka_menu_anggaran = Menu::where('route_name', 'anggaran_tpb.rka')->first()->label;
        $rka_menu_program = Menu::where('route_name', 'rencana_kerja.program.index2')->first()->label;
        $rka_menu_spdpumk = Menu::where('route_name', 'rencana_kerja.spdpumk_rka.index')->first()->label;
        $rka_laporan_manajemen = Menu::where('route_name', 'rencana_kerja.laporan_manajemen.index')->first()->label;

        //Laporan Realisasi
        $menu_kegiatan = Menu::where('route_name', 'laporan_realisasi.bulanan.kegiatan.index')->first()->label;
        $menu_pumk = Menu::where('route_name', 'laporan_realisasi.bulanan.pumk.index')->first()->label;
        $menu_spdpumk = Menu::where('route_name', 'laporan_realisasi.triwulan.spd_pumk.index')->first()->label;
        $menu_laporan_manajemen = Menu::where('route_name', 'laporan_realisasi.triwulan.laporan_manajemen.index')->first()->label; 
        

        $data = [
            //RKA
            [
                'menu' => $rka_menu_anggaran,
                'rka' => true,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => false,
                'tw4' => false,
                'audited' => false,
                'class' => 'rka',
                'support_props' => null
            ],
            [
                'menu' => $rka_menu_program,
                'rka' => true,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => false,
                'tw4' => false,
                'audited' => false,
                'class' => 'program'
            ],
            [
                'menu' => $rka_menu_spdpumk,
                'rka' => true,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => false,
                'tw4' => false,
                'audited' => false,
                'class' => 'spdpumk_rka'
            ],
            [
                'menu' => $rka_laporan_manajemen,
                'rka' => true,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => false,
                'tw4' => false,
                'audited' => false,
                'class' => 'laporan_manajemen_rka'
            ],
            //Laporan Realisasi
            [
                'menu' => $menu_kegiatan,
                'rka' => false,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => [
                    'value' => null,
                    'id'=>null
                ],
                'tw4' => false,
                'audited' => [
                    'value' => null,
                    'id'=>null
                ],
                'class' => 'kegiatan'
            ],
            [
                'menu' => $menu_pumk,
                'rka' => false,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => [
                    'value' => null,
                    'id'=>null
                ],
                'tw4' => false,
                'audited' => [
                    'value' => null,
                    'id'=>null
                ],
                'class' => 'pumk'
            ],
            [
                'menu' => $menu_spdpumk,
                'rka' => false,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => false,
                'tw4' => false,
                'audited' => false,
                'class' => 'spdpumk_bulan'
            ],
            [
                'menu' => $menu_laporan_manajemen,
                'rka' => false,
                'tw1' => false,
                'tw2' => false,
                'tw3' => false,
                'prognosa' => false,
                'tw4' => false,
                'audited' => false,
                'class' => 'laporan_manajemen_bulan'
            ],
        ];
        //rka
        // $anggaran = DB::table('anggaran_tpbs')->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->orderBy('updated_at', 'asc')->get();
        $anggaran = DB::table('anggaran_tpbs as atpb')
        ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
        ->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->orderBy('atpb.updated_at', 'asc')->get();
        
        //cek ada atau tidak
        $totalAnggaranCount = count($anggaran);
        $statusAnggaranVerifiedCount = count($anggaran->where('status_id', 1));
        $statusAnggaranValidatedCount = count($anggaran->where('status_id', 4));
        //Verified
        if($totalAnggaranCount == $statusAnggaranVerifiedCount && $totalAnggaranCount != 0){
            $data[0]['rka'] = "Verified";

            $pilar = DB::table('pilar_pembangunans')->where('id', $anggaran?->first()->pilar_pembangunan_id)->first();
            $data[0]['support_props']['no_tpb'] = $anggaran?->first()->no_tpb;
            $data[0]['support_props']['nama_pilar'] = $pilar->nama;
        }
        //Validated
        if($totalAnggaranCount == $statusAnggaranValidatedCount && $totalAnggaranCount != 0){
            // dd('halo');
            $data[0]['rka'] = "Validated";
            $pilar = DB::table('pilar_pembangunans')->where('id', $anggaran?->first()->pilar_pembangunan_id)->first();
            $data[0]['support_props']['no_tpb'] = $anggaran?->first()->no_tpb;
            $data[0]['support_props']['nama_pilar'] = $pilar->nama;
        }

        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($anggaran?->where('status_id', 2)->first()) {
            $data[0]['rka'] = "In Progress";
            $pilar = DB::table('pilar_pembangunans')->where('id', $anggaran?->where('status_id', 2)->first()->pilar_pembangunan_id)->first();
            $data[0]['support_props']['no_tpb'] = $anggaran?->where('status_id', 2)->first()->no_tpb;
            $data[0]['support_props']['nama_pilar'] = $pilar->nama;
          
        }
        if(count($anggaran) == 0){
            $data[0]['rka'] = "Unfilled";
            $data[0]['support_props']['no_tpb'] = null;
            $data[0]['support_props']['nama_pilar'] = null;
        };
      
        //program rka
        $program_rka = DB::table('anggaran_tpbs')->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->orderBy('target_tpbs.updated_at', 'asc')->join('target_tpbs', 'target_tpbs.anggaran_tpb_id', '=', 'anggaran_tpbs.id')->get();
        $totalProgramRKACount = count($program_rka);
        $statusProgramRKAVerifiedCount = count($program_rka->where('status_id', 1));
        $statusProgramRKAValidatedCount = count($program_rka->where('status_id', 4));
        //Verified
        if($totalProgramRKACount == $statusProgramRKAVerifiedCount && $totalProgramRKACount != 0){
            $data[1]['rka'] = "Verified";
            $data[1]['id'] = $program_rka->first()->id;
        }
        //Validated
        if($totalProgramRKACount == $statusProgramRKAValidatedCount && $totalProgramRKACount != 0){
            $data[1]['rka'] = "Validated";
            $data[1]['id'] = $program_rka->first()->id;
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($program_rka?->where('status_id', 2)->first()) {
            $data[1]['rka'] = "In Progress";
            $data[1]['id'] = $program_rka?->where('status_id', 2)->first()->id;
        }
        if(count($program_rka) == 0){
            $data[1]['rka'] = "Unfilled";
            $data[1]['id'] = null;
        };
     

        //spdpumk rka
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        $spd_pumk = DB::table('pumk_anggarans')->where('bumn_id', $perusahaan_id)->where('tahun', $tahun)->where('periode_id', $periode_rka_id)->get();
        
        $totalSpdpumkCount = count($spd_pumk);
        $statusSpdpumkVerifiedCount = count($spd_pumk->where('status_id', 1));
        $statusSpdpumkValidatedCount = count($spd_pumk->where('status_id', 4));
        //Verified
        if($totalSpdpumkCount == $statusProgramRKAVerifiedCount && $totalSpdpumkCount != 0){
            $data[2]['rka'] = "Verified";
            $data[2]['id'] = $spd_pumk->first()->id;
        }
        //Validated
        if ($totalSpdpumkCount == $statusSpdpumkValidatedCount && $totalSpdpumkCount != 0) {
            $data[2]['rka'] = "Validated";
            $data[2]['id'] = $spd_pumk->first()->id;
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($spd_pumk?->where('status_id', 2)->first()) {
            $data[2]['rka'] = "In Progress";
            $data[2]['id'] = $spd_pumk?->where('status_id', 2)->first()->id;
        }
        if(count($spd_pumk) == 0){
            $data[2]['rka'] = "Unfilled";
            $data[2]['id'] = null;
        };
        
        //laporan manajemen rka
        $laporan_manajemen = DB::table('laporan_manajemens')->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->where('periode_laporan_id', $periode_rka_id)->get();
        $totalLaporanManajemenCount = count($laporan_manajemen);
        $statusLaporanManajemenVerifiedCount = count($laporan_manajemen->where('status_id', 1));
        $statusLaporanManajemenValidatedCount = count($laporan_manajemen->where('status_id', 4));
        //Verified
        if($totalLaporanManajemenCount == $statusLaporanManajemenVerifiedCount && $totalLaporanManajemenCount != 0){
            $data[3]['rka'] = "Verified";
            $data[3]['id'] = $laporan_manajemen->first()->id;
        }
        //Validated
        if($totalLaporanManajemenCount == $statusLaporanManajemenValidatedCount && $totalLaporanManajemenCount != 0){
            $data[3]['rka'] = "Validated";
            $data[3]['id'] = $laporan_manajemen->first()->id;
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress/unfilled
        if ($laporan_manajemen?->whereIn('status_id', [2, 3])->first()) {
            $data[3]['rka'] = $laporan_manajemen->whereIn('status_id', [2, 3])->first()->status_id === 2 ? 'In Progress' : 'Unfilled';
            $data[3]['id'] = $laporan_manajemen->whereIn('status_id', [2, 3])->first()->id;
        }
        if(count($laporan_manajemen) == 0){
            $data[3]['rka'] = "Unfilled";
            $data[3]['id'] = null;
        };


        //Laporan Realisasi
         $kegiatan = DB::table('kegiatans')
                    ->join('kegiatan_realisasis', function($join) use ( $tahun) {
                        $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                            ->where('kegiatan_realisasis.tahun', $tahun);
                    })
                    ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
                    ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
                        $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                            ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                            ->where('anggaran_tpbs.tahun', $tahun);
                    })
                    ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                    ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
                    ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
                    ->join('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
                    ->join('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
                    ->join('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
                    ->join('bulans', 'bulans.id', '=', 'kegiatan_realisasis.bulan')
                    ->orderBy('kegiatans.updated_at', 'asc')
                    ->select(
                        'kegiatans.*',
                        'kegiatan_realisasis.bulan as kegiatan_realisasi_bulan',
                        'kegiatan_realisasis.tahun as kegiatan_realisasi_tahun',
                        'kegiatan_realisasis.anggaran as kegiatan_realisasi_anggaran',
                        'kegiatan_realisasis.anggaran_total as kegiatan_realisasi_anggaran_total',
                        'kegiatan_realisasis.status_id as kegiatan_realisasi_status_id',
                        'target_tpbs.program as target_tpb_program',
                        'jenis_kegiatans.nama as jenis_kegiatan_nama',
                        'provinsis.nama as provinsi_nama',
                        'kotas.nama as kota_nama',
                        'anggaran_tpbs.id as anggaran_tpb_id',
                        'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
                        'tpbs.id as tpb_id',
                        'tpbs.jenis_anggaran',
                        'satuan_ukur.nama as satuan_ukur_nama',
                        'bulans.nama as bulan_nama'
                    )
                    ->get();
                    $spd_pumk = DB::table('pumk_anggarans')->where('bumn_id', $perusahaan_id)->where('tahun', $tahun)->orderBy('updated_at', 'desc')->get();
                    $laporan_manajemen = DB::table('laporan_manajemens')->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->orderBy('updated_at', 'desc')->get();
                    $periode_laporan = DB::table('periode_laporans')->where('jenis_periode', 'standar')->where('nama', '!=', 'RKA')->orderBy('id', 'asc')->get();
                    $pumk = DB::table('pumk_bulans')->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->orderBy('updated_at', 'desc')->get();
                    // dd($pumk);
        //kegiatan, index 4
        foreach ($periode_laporan as $key => $value) {
            $periodeId = $value->id;
            //TW 1
            if ($periodeId === 1) {
                $kegiatan_bulan = $kegiatan?->whereIn('kegiatan_realisasi_bulan', [1,2,3]);  
                if (count($kegiatan_bulan) > 0) {
                    if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)) > 0) {
                        $data[4]['tw1']['value'] = "In Progress";
                        $data[4]['tw1']['id'] = $kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)->first()->id;
                    } else {
                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 1)) == count($kegiatan_bulan)) {
                            $data[4]['tw1']['value'] = "Verified";
                            $data[4]['tw1']['id'] = $kegiatan_bulan->first()->id;
                        }
                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 4)) == count($kegiatan_bulan)) {
                            $data[4]['tw1']['value'] = "Validated";
                            $data[4]['tw1']['id'] = $kegiatan_bulan->first()->id;
                        }
                    }
                }else {
                    $data[4]['tw1']['value'] = "Unfilled";
                    $data[4]['tw1']['id'] = null;
                }
            }

            //TW 2
            if ($periodeId === 2) {
                $kegiatan_bulan = $kegiatan?->whereIn('kegiatan_realisasi_bulan', [4,5,6]);  
                if (count($kegiatan_bulan) > 0) {
                    if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)) > 0) {
                        $data[4]['tw2']['value'] = "In Progress";
                        $data[4]['tw2']['id'] = $kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)->first()->id;
                    } else {
                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 1)) == count($kegiatan_bulan)) {
                            $data[4]['tw2']['value'] = "Verified";
                            $data[4]['tw2']['id'] = $kegiatan_bulan->first()->id;
                        }

                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 4)) == count($kegiatan_bulan)) {
                            $data[4]['tw2']['value'] = "Validated";
                            $data[4]['tw2']['id'] = $kegiatan_bulan->first()->id;
                        }
                    }
                }else {
                    $data[4]['tw2']['value'] = "Unfilled";
                    $data[4]['tw2']['id'] = null;
                }
            }
            // dd($data);

            //TW 3
            if ($periodeId === 3) {
                $kegiatan_bulan = $kegiatan?->whereIn('kegiatan_realisasi_bulan', [7,8,9]);  
                if (count($kegiatan_bulan) > 0) {
                    if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)) > 0) {
                        $data[4]['tw3']['value'] = "In Progress";
                        $data[4]['tw3']['id'] = $kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)->first()->id;
                    } else {
                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 1)) == count($kegiatan_bulan)) {
                            $data[4]['tw3']['value'] = "Verified";
                            $data[4]['tw3']['id'] = $kegiatan_bulan->first()->id;
                        }
                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 4)) == count($kegiatan_bulan)) {
                            $data[4]['tw3']['value'] = "Validated";
                            $data[4]['tw3']['id'] = $kegiatan_bulan->first()->id;
                        }
                    }
                }else {
                    $data[4]['tw3']['value'] = "Unfilled";
                    $data[4]['tw3']['id'] = null;
                }
            }

            //TW 4
            if ($periodeId === 5) {
                $kegiatan_bulan = $kegiatan?->whereIn('kegiatan_realisasi_bulan', [10,11,12]);  
               
                if (count($kegiatan_bulan) > 0) {
                    if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)) > 0) {
                        $data[4]['tw4']['value'] = "In Progress";
                        $data[4]['tw4']['id'] = $kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)->first()->id;
                    } else {
                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 1)) == count($kegiatan_bulan)) {
                            $data[4]['tw4']['value'] = "Verified";
                            $data[4]['tw4']['id'] = $kegiatan_bulan->first()->id;
                        }

                        if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 4)) == count($kegiatan_bulan)) {
                            $data[4]['tw4']['value'] = "Validated";
                            $data[4]['tw4']['id'] = $kegiatan_bulan->first()->id;
                        }

                        
                    }
                }else {
                    $data[4]['tw4']['value'] = "Unfilled";
                    $data[4]['tw4']['id'] = null;
                }
            }
            
            //Prognosa
            if ($periodeId === 6) {
                // $kegiatan_bulan = $kegiatan?->whereIn('kegiatan_realisasi_bulan', [10,11,12]);  
                // if ($kegiatan_bulan) {
                //     if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)) > 0) {
                //         $data[4]['prognosa'] = "In Progress";
                //     } else {
                //         if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 1)) == count($kegiatan_bulan)) {
                //             $data[4]['prognosa'] = "Finish";
                //         }
                //     }
                // }else {
                //     $data[4]['prognosa'] = "Unfilled";
                // }
            }

            //Audited
            if ($periodeId === 7) {
                $kegiatan_bulan = $kegiatan?->whereIn('kegiatan_realisasi_bulan', [10,11,12]);  
                // if ($kegiatan_bulan) {
                //     if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 2)) > 0) {
                //         $data[4]['audited'] = "In Progress";
                //     } else {
                //         if (count($kegiatan_bulan->where('kegiatan_realisasi_status_id', 1)) == count($kegiatan_bulan)) {
                //             $data[4]['audited'] = "Finish";
                //         }
                //     }
                // }else {
                //     $data[4]['audited'] = "Unfilled";
                // }
            }
        }
        //pumk, index 5
        foreach ($periode_laporan as $key => $value) {
            $periodeId = $value->id;
            //TW 1
            if ($periodeId === 1) {
                $pumk_bulan = $pumk?->whereIn('bulan_id', [1,2,3]);  
                if (count($pumk_bulan) > 0) {
                    if (count($pumk_bulan->where('status_id', 2)) > 0) {
                        $data[5]['tw1']['value'] = "In Progress";
                        $data[5]['tw1']['id'] = $pumk_bulan->where('status_id', 2)->first()->id;
                    } else {
                        if (count($pumk_bulan->where('status_id', 1)) == count($pumk_bulan)) {
                            $data[5]['tw1']['value'] = "Verified";
                            $data[5]['tw1']['id'] = $pumk_bulan->first()->id;
                        }
                        if (count($pumk_bulan->where('status_id', 4)) == count($pumk_bulan)) {
                            $data[5]['tw1']['value'] = "Validated";
                            $data[5]['tw1']['id'] = $pumk_bulan->first()->id;
                        }
                    }
                }else {
                    $data[5]['tw1']['value'] = "Unfilled";
                    $data[5]['tw1']['id'] = null;
                }
            }

            //TW 2
            if ($periodeId === 2) {
                $pumk_bulan = $pumk?->whereIn('bulan_id', [4,5,6]);  
                if (count($pumk_bulan) > 0) {
                    if (count($pumk_bulan->where('status_id', 2)) > 0) {
                        $data[5]['tw2']['value'] = "In Progress";
                        $data[5]['tw2']['id'] = $pumk_bulan->where('status_id', 2)->first()->id;
                    } else {
                        if (count($pumk_bulan->where('status_id', 1)) == count($pumk_bulan)) {
                            $data[5]['tw2']['value'] = "Verified";
                            $data[5]['tw2']['id'] = $pumk_bulan->first()->id;
                        }
                        if (count($pumk_bulan->where('status_id', 4)) == count($pumk_bulan)) {
                            $data[5]['tw2']['value'] = "Validated";
                            $data[5]['tw2']['id'] = $pumk_bulan->first()->id;
                        }
                    }
                }else {
                    $data[5]['tw2']['value'] = "Unfilled";
                    $data[5]['tw2']['id'] = null;
                }
            }

            //TW 3
            if ($periodeId === 3) {
                $pumk_bulan = $pumk?->whereIn('bulan_id', [7,8,9]);  
                if (count($pumk_bulan) > 0) {
                    if (count($pumk_bulan->where('status_id', 2)) > 0) {
                        $data[5]['tw3']['value'] = "In Progress";
                        $data[5]['tw3']['id'] = $pumk_bulan->where('status_id', 2)->first()->id;
                    } else {
                        if (count($pumk_bulan->where('status_id', 1)) == count($pumk_bulan)) {
                            $data[5]['tw3']['value'] = "Verified";
                            $data[5]['tw3']['id'] = $pumk_bulan->first()->id;
                        }
                        if (count($pumk_bulan->where('status_id', 4)) == count($pumk_bulan)) {
                            $data[5]['tw3']['value'] = "Validated";
                            $data[5]['tw3']['id'] = $pumk_bulan->first()->id;
                        }
                    }
                }else {
                    $data[5]['tw3']['value'] = "Unfilled";
                    $data[5]['tw3']['id'] = null;
                }
            }

            //TW 4
            if ($periodeId === 5) {
                $pumk_bulan = $pumk?->whereIn('bulan_id', [10,11,12]);  
               
                if (count($pumk_bulan) > 0) {
                    if (count($pumk_bulan->where('status_id', 2)) > 0) {
                        $data[5]['tw4']['value'] = "In Progress";
                        $data[5]['tw4']['id'] = $pumk_bulan->where('status_id', 2)->first()->id;
                    } else {
                        if (count($pumk_bulan->where('status_id', 1)) == count($pumk_bulan)) {
                            $data[5]['tw4']['value'] = "Verified";
                            $data[5]['tw4']['id'] = $pumk_bulan->first()->id;
                        }
                        if (count($pumk_bulan->where('status_id', 4)) == count($pumk_bulan)) {
                            $data[5]['tw4']['value'] = "Validated";
                            $data[5]['tw4']['id'] = $pumk_bulan->first()->id;
                        }
                    }
                }else {
                    $data[5]['tw4']['value'] = "Unfilled";
                    $data[5]['tw4']['null'] = null;
                }
            }

            //Prognosa
            if ($periodeId === 6) {
                // $pumk_bulan = $pumk?->whereIn('bulan_id', [10,11,12]);  
                // if ($pumk_bulan) {
                //     if (count($pumk_bulan->where('status_id', 2)) > 0) {
                //         $data[5]['prognosa'] = "In Progress";
                //     } else {
                //         if (count($pumk_bulan->where('status_id', 1)) == count($pumk_bulan)) {
                //             $data[5]['prognosa'] = "Finish";
                //         }
                //     }
                // }else {
                //     $data[5]['prognosa'] = "Unfilled";
                // }
            }

            //Audited
            if ($periodeId === 7) {
                //$pumk_bulan = $pumk?->whereIn('bulan_id', [10,11,12]);  
                // if ($pumk_bulan) {
                //     if (count($pumk_bulan->where('status_id', 2)) > 0) {
                //         $data[5]['audited'] = "In Progress";
                //     } else {
                //         if (count($pumk_bulan->where('status_id', 1)) == count($pumk_bulan)) {
                //             $data[5]['audited'] = "Finish";
                //         }
                //     }
                // }else {
                //     $data[5]['audited'] = "Unfilled";
                // }
            }
        }
        //spd pumk, index 6
        foreach ($periode_laporan as $key => $value) {
            $periodeId = $value->id;
            //TW 1
            if ($periodeId === 1) {
                $spd_pumk_periode = $spd_pumk->where('periode_id', $periodeId)->first();
            
                if ($spd_pumk_periode) {
                    if ($spd_pumk_periode->status_id == 2) {
                        $data[6]['tw1']['value'] = "In Progress";
                        $data[6]['tw1']['id'] = $spd_pumk_periode->id;

                    } 
                    if ($spd_pumk_periode->status_id == 1) {
                        $data[6]['tw1']['value'] = "Verified";
                        $data[6]['tw1']['id'] = $spd_pumk_periode->id;
                    } 

                    if ($spd_pumk_periode->status_id == 4) {
                        $data[6]['tw1']['value'] = "Validated";
                        $data[6]['tw1']['id'] = $spd_pumk_periode->id;
                    } 
                }else {
                    $data[6]['tw1']['value'] = "Unfilled";
                    $data[6]['tw1']['id'] = null;
                }
            }

            //TW 2
            if ($periodeId === 2) {
                
                $spd_pumk_periode = $spd_pumk->where('periode_id', $periodeId)->first();
                
                if ($spd_pumk_periode) {
                    if ($spd_pumk_periode->status_id == 2) {
                        $data[6]['tw2']['value'] = "In Progress";
                        $data[6]['tw2']['id'] = $spd_pumk_periode->id;

                    } 

                    if ($spd_pumk_periode->status_id == 1) {
                        $data[6]['tw2']['value'] = "Verified";
                        $data[6]['tw2']['id'] = $spd_pumk_periode->id;
                    } 

                    if ($spd_pumk_periode->status_id == 4) {
                        $data[6]['tw2']['value'] = "Validated";
                        $data[6]['tw2']['id'] = $spd_pumk_periode->id;
                    } 


                }else {
                    $data[6]['tw2']['value'] = "Unfilled";
                    $data[6]['tw2']['id'] = null;
                }
            }

            //TW 3
            if ($periodeId === 3) {
                $spd_pumk_periode = $spd_pumk->where('periode_id', $periodeId)->first();
            
                if ($spd_pumk_periode) {
                    if ($spd_pumk_periode->status_id == 2) {
                        $data[6]['tw3']['value'] = "In Progress";
                        $data[6]['tw3']['id'] = $spd_pumk_periode->id;

                    } 
                    if ($spd_pumk_periode->status_id == 1) {
                        $data[6]['tw3']['value'] = "Verified";
                        $data[6]['tw3']['id'] = $spd_pumk_periode->id;

                    } 
                    if ($spd_pumk_periode->status_id == 4) {
                        $data[6]['tw3']['value'] = "Validated";
                        $data[6]['tw3']['id'] = $spd_pumk_periode->id;

                    } 
                }else {
                    $data[6]['tw3']['value'] = "Unfilled";
                    $data[6]['tw3']['id'] = null;
                }
            }

            //TW 4
            if ($periodeId === 5) {
                $spd_pumk_periode = $spd_pumk->where('periode_id', $periodeId)->first();
            
                if ($spd_pumk_periode) {
                    if ($spd_pumk_periode->status_id == 2) {
                        $data[6]['tw4']['value'] = "In Progress";
                        $data[6]['tw4']['id'] = $spd_pumk_periode->id;

                    } 
                    if ($spd_pumk_periode->status_id == 1) {
                        $data[6]['tw4']['value'] = "Verified";
                        $data[6]['tw4']['id'] = $spd_pumk_periode->id;
                    }
                    if ($spd_pumk_periode->status_id == 4) {
                        $data[6]['tw4']['value'] = "Validated";
                        $data[6]['tw4']['id'] = $spd_pumk_periode->id;
                    }
                }else {
                    $data[6]['tw4']['value'] = "Unfilled";
                    $data[6]['tw4']['id'] = null;
                }
            }

            //Prognosa
            if ($periodeId === 6) {
                $spd_pumk_periode = $spd_pumk->where('periode_id', $periodeId)->first();
            
                if ($spd_pumk_periode) {
                    if ($spd_pumk_periode->status_id == 2) {
                        $data[6]['prognosa']['value'] = "In Progress";
                        $data[6]['prognosa']['id'] = $spd_pumk_periode->id;

                    } 

                    if ($spd_pumk_periode->status_id == 1) {
                        $data[6]['prognosa']['value'] = "Verified";
                        $data[6]['prognosa']['id'] = $spd_pumk_periode->id;
                    }

                    if ($spd_pumk_periode->status_id == 4) {
                        $data[6]['prognosa']['value'] = "Validated";
                        $data[6]['prognosa']['id'] = $spd_pumk_periode->id;
                    }
                }else {
                    $data[6]['prognosa']['value'] = "Unfilled";
                    $data[6]['prognosa']['id'] = null;
                }
            }

            //Audited
            if ($periodeId === 7) {
                $spd_pumk_periode = $spd_pumk->where('periode_id', $periodeId)->first();
            
                if ($spd_pumk_periode) {
                    if ($spd_pumk_periode->status_id == 2) {
                        $data[6]['audited']['value'] = "In Progress";
                        $data[6]['audited']['id'] = $spd_pumk_periode->id;

                    } 
                    if ($spd_pumk_periode->status_id == 1) {
                        $data[6]['audited']['value'] = "Verified";
                        $data[6]['audited']['id'] = $spd_pumk_periode->id;

                    }
                    if ($spd_pumk_periode->status_id == 4) {
                        $data[6]['audited']['value'] = "Validated";
                        $data[6]['audited']['id'] = $spd_pumk_periode->id;

                    }
                }else {
                    $data[6]['audited']['value'] = "Unfilled";
                    $data[6]['audited']['id'] = null;
                }
            }
        }
        //laporan manajemen, index 7
        foreach ($periode_laporan as $key => $value) {
            $periodeId = $value->id;
            //TW 1
            if ($periodeId === 1) {
                $laporan_manajemen_periode = $laporan_manajemen->where('periode_id', $periodeId)->first();
            
                if ($laporan_manajemen_periode) {
                    if ($laporan_manajemen_periode->status_id == 2) {
                        $data[7]['tw1']['value'] = "In Progress";
                        $data[7]['tw1']['id'] = $laporan_manajemen_periode->id;
                    } 

                    if ($laporan_manajemen_periode->status_id == 1) {
                        $data[7]['tw1']['value'] = "Verified";
                        $data[7]['tw1']['id'] = $laporan_manajemen_periode->id;
                    } 

                    if ($laporan_manajemen_periode->status_id == 4) {
                        $data[7]['tw1']['value'] = "Validated";
                        $data[7]['tw1']['id'] = $laporan_manajemen_periode->id;
                    } 
                }else {
                    $data[7]['tw1']['value'] = "Unfilled";
                    $data[7]['tw1']['id'] = null;

                }
            }

            //TW 2
            if ($periodeId === 2) {
                
                $laporan_manajemen_periode = $laporan_manajemen->where('periode_id', $periodeId)->first();
                
                if ($laporan_manajemen_periode) {
                    if ($laporan_manajemen_periode->status_id == 2) {
                        $data[7]['tw2']['value'] = "In Progress";
                        $data[7]['tw2']['id'] = $laporan_manajemen_periode->id;
                    } 
                    
                    if ($laporan_manajemen_periode->status_id == 1) {
                        $data[7]['tw2']['value'] = "Verified";
                        $data[7]['tw2']['id'] = $laporan_manajemen_periode->id;
                    }
                    if ($laporan_manajemen_periode->status_id == 4) {
                        $data[7]['tw2']['value'] = "Validated";
                        $data[7]['tw2']['id'] = $laporan_manajemen_periode->id;
                    }
                }else {
                    $data[7]['tw2']['value'] = "Unfilled";
                    $data[7]['tw2']['id'] = null;

                }
            }

            //TW 3
            if ($periodeId === 3) {
                $laporan_manajemen_periode = $laporan_manajemen->where('periode_id', $periodeId)->first();
            
                if ($laporan_manajemen_periode) {
                    if ($laporan_manajemen_periode->status_id == 2) {
                        $data[7]['tw3']['value'] = "In Progress";
                        $data[7]['tw3']['id'] = $laporan_manajemen_periode->id;
                    } 

                    if ($laporan_manajemen_periode->status_id == 1) {
                        $data[7]['tw3']['value'] = "Verified";
                        $data[7]['tw3']['id'] = $laporan_manajemen_periode->id;
                    } 

                    if ($laporan_manajemen_periode->status_id == 4) {
                        $data[7]['tw3']['value'] = "Validated";
                        $data[7]['tw3']['id'] = $laporan_manajemen_periode->id;
                    } 
                }else {
                    $data[7]['tw3']['value'] = "Unfilled";
                    $data[7]['tw3']['id'] = null;

                }
            }

            //TW 4
            if ($periodeId === 5) {
                $laporan_manajemen_periode = $laporan_manajemen->where('periode_id', $periodeId)->first();
            
                if ($laporan_manajemen_periode) {
                    if ($laporan_manajemen_periode->status_id == 2) {
                        $data[7]['tw4']['value'] = "In Progress";
                        $data[7]['tw4']['id'] = $laporan_manajemen_periode->id;
                    } 

                    if ($laporan_manajemen_periode->status_id == 1) {
                        $data[7]['tw4']['value'] = "Verified";
                        $data[7]['tw4']['id'] = $laporan_manajemen_periode->id;
                    } 

                    
                    if ($laporan_manajemen_periode->status_id == 4) {
                        $data[7]['tw4']['value'] = "Validated";
                        $data[7]['tw4']['id'] = $laporan_manajemen_periode->id;
                    } 
                }else {
                    $data[7]['tw4']['value'] = "Unfilled";
                    $data[7]['tw4']['id'] = null;

                }
            }

            //Prognosa
            if ($periodeId === 6) {
                $laporan_manajemen_periode = $laporan_manajemen->where('periode_id', $periodeId)->first();
            
                if ($laporan_manajemen_periode) {
                    if ($laporan_manajemen_periode->status_id == 2) {
                        $data[7]['prognosa']['value'] = "In Progress";
                        $data[7]['prognosa']['id'] = $laporan_manajemen_periode->id;
                    } 

                    if ($laporan_manajemen_periode->status_id == 1) {
                        $data[7]['prognosa']['value'] = "Verified";
                        $data[7]['prognosa']['id'] = $laporan_manajemen_periode->id;
                    }

                    if ($laporan_manajemen_periode->status_id == 4) {
                        $data[7]['prognosa']['value'] = "Validated";
                        $data[7]['prognosa']['id'] = $laporan_manajemen_periode->id;
                    }
                }else {
                    $data[7]['prognosa']['value'] = "Unfilled";
                    $data[7]['prognosa']['id'] = null;

                }
            }

            //Audited
            if ($periodeId === 7) {
                $laporan_manajemen_periode = $laporan_manajemen->where('periode_id', $periodeId)->first();
            
                if ($laporan_manajemen_periode) {
                    if ($laporan_manajemen_periode->status_id == 2) {
                        $data[7]['audited']['value'] = "In Progress";
                        $data[7]['audited']['id'] = $laporan_manajemen_periode->id;
                    } 

                    if ($laporan_manajemen_periode->status_id == 1) {
                        $data[7]['audited']['value'] = "Verified";
                        $data[7]['audited']['id'] = $laporan_manajemen_periode->id;
                    }

                    
                    if ($laporan_manajemen_periode->status_id == 4) {
                        $data[7]['audited']['value'] = "Validated";
                        $data[7]['audited']['id'] = $laporan_manajemen_periode->id;
                    }
                }else {
                    $data[7]['audited']['value'] = "Unfilled";
                    $data[7]['audited']['id'] = null;

                }
            }
        }
        
        return $data;
    }

    public function allstatus(Request $request){
        
        return $this->getMenuStatus($request->tahunStatus, $request->perusahaan_id);
    }

    public function chartpumk(Request $request)
    {
        $perusahaan = $request->perusahaan_id_danapumk;
        // jika filter tahun kosong maka default tahun berjalan saat ini
        $tahun = $request->tahun_danapumk ? $request->tahun_danapumk : (int)date('Y');

        $mitra = DB::table('bulans')
            ->selectRaw('bulans.nama as bulan_text')
            ->selectRaw('bulans.id as bulan_angka')
            ->selectRaw("count(pumk_mitra_binaans.*) as count_mitra")
            ->leftjoin('pumk_mitra_binaans', 'bulans.id', '=', 'pumk_mitra_binaans.bulan')
            ->where('pumk_mitra_binaans.is_arsip', false)
            ->whereRaw("EXTRACT(MONTH from to_date(pumk_mitra_binaans.tgl_awal, 'DD/MM/YYYY'))  = pumk_mitra_binaans.bulan")
            ->whereRaw("EXTRACT(YEAR from to_date(pumk_mitra_binaans.tgl_awal, 'DD/MM/YYYY'))  = pumk_mitra_binaans.tahun")
            ->where(function ($query) use ($perusahaan, $tahun) {
                if ($perusahaan) {
                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                }
                if ($tahun) {
                    $query->whereRaw("EXTRACT(YEAR from to_date(pumk_mitra_binaans.tgl_awal, 'DD/MM/YYYY'))  = " . $tahun . "");
                }
            })
            ->groupby('bulans.nama', 'bulan_angka')
            ->orderby('bulans.id', 'ASC')
            ->get();

        $nominal = DB::table('bulans')
            ->selectRaw('bulans.nama as bulan_text')
            ->selectRaw('bulans.id as bulan_angka')
            ->leftjoin('pumk_mitra_binaans', 'bulans.id', '=', 'pumk_mitra_binaans.bulan')
            ->selectRaw("sum(pumk_mitra_binaans.nominal_pendanaan) as sum_nominal")
            ->where('pumk_mitra_binaans.is_arsip', false)
            ->whereRaw("EXTRACT(MONTH from to_date(pumk_mitra_binaans.tgl_awal, 'DD/MM/YYYY'))  = pumk_mitra_binaans.bulan")
            ->whereRaw("EXTRACT(YEAR from to_date(pumk_mitra_binaans.tgl_awal, 'DD/MM/YYYY'))  = pumk_mitra_binaans.tahun")
            ->where(function ($query) use ($perusahaan, $tahun) {
                if ($perusahaan) {
                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                }
                if ($tahun) {
                    $query->whereRaw("EXTRACT(YEAR from to_date(pumk_mitra_binaans.tgl_awal, 'DD/MM/YYYY'))  = " . $tahun . "");
                }
            })
            ->groupby('bulans.nama', 'bulan_angka')
            ->orderby('bulans.id', 'ASC')
            ->get();


        $result_bln = [];
        foreach ($mitra as $bln) {
            $result_bln[] = $bln->bulan_text;
        }

        $result_mitra = [];
        foreach ($mitra as $v) {
            $result_mitra[] = $v->count_mitra;
        }

        $result_nom = [];
        foreach ($nominal as $v) {
            $result_nom[] = (float)number_format(($v->sum_nominal / 1000000000), 3, '.', '');
        }
        $json['bulan'] = $result_bln;
        $json['mitra'] = $result_mitra;
        $json['nominal'] = $result_nom;
        $json['tahun'] = $tahun ? 'Tahun ' . $tahun : '';

        return response()->json($json);
    }

    public function chartmb(Request $request)
    {
        try {
            $json = [];
            $kolek = KolekbilitasPendanaan::select('nama')->pluck('nama');
            $perusahaan = $request->perusahaan_id_pumk;
            $bulan = $request->bulan_pumk ? (int)$request->bulan_pumk : ((int)date('m') - 1);
            $tahun = $request->tahun_pumk;

            $id_lancar = KolekbilitasPendanaan::where('nama', 'ilike', '%lancar%')->pluck('id')->first();
            $id_kurang_lancar = KolekbilitasPendanaan::where('nama', 'ilike', '%kurang% %lancar%')->pluck('id')->first();
            $id_diragukan = KolekbilitasPendanaan::where('nama', 'ilike', '%diragukan%')->pluck('id')->first();
            $id_bermasalah = KolekbilitasPendanaan::where('nama', 'ilike', '%bermasalah%')->pluck('id')->first();
            $id_macet = KolekbilitasPendanaan::where('nama', 'ilike', '%macet%')->pluck('id')->first();

            $json['mitra_lancar'] =  PumkMitraBinaan::select('pumk_mitra_binaans.*', 'kolekbilitas_pendanaan.nama')
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_lancar)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->count();

            $json['saldo_lancar'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total', 'kolekbilitas_pendanaan.nama'))
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_lancar)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->pluck('total')->first();

            $json['mitra_kurang_lancar'] = PumkMitraBinaan::select('pumk_mitra_binaans.*', 'kolekbilitas_pendanaan.nama')
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_kurang_lancar)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->count();

            $json['saldo_kurang_lancar'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total', 'kolekbilitas_pendanaan.nama'))
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_kurang_lancar)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->pluck('total')->first();

            $json['mitra_diragukan'] = PumkMitraBinaan::select('pumk_mitra_binaans.*', 'kolekbilitas_pendanaan.nama')
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_diragukan)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->count();

            $json['saldo_diragukan'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total', 'kolekbilitas_pendanaan.nama'))
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_diragukan)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->pluck('total')->first();


            $json['mitra_macet'] = PumkMitraBinaan::select('pumk_mitra_binaans.*', 'kolekbilitas_pendanaan.nama')
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_macet)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->count();

            $json['saldo_macet'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total', 'kolekbilitas_pendanaan.nama'))
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_macet)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->pluck('total')->first();


            $json['mitra_bermasalah'] = PumkMitraBinaan::select('pumk_mitra_binaans.*', 'kolekbilitas_pendanaan.nama')
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_bermasalah)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->count();

            $json['saldo_bermasalah'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total', 'kolekbilitas_pendanaan.nama'))
                ->leftjoin('kolekbilitas_pendanaan', 'kolekbilitas_pendanaan.id', 'pumk_mitra_binaans.kolektibilitas_id')
                ->where('kolekbilitas_pendanaan.id', $id_bermasalah)
                ->where('pumk_mitra_binaans.is_arsip', false)
                ->where(function ($query) use ($perusahaan, $bulan, $tahun) {
                    if ($perusahaan) {
                        $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                    }
                    if ($bulan) {
                        $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                    }
                    if ($tahun) {
                        $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                    }
                })
                ->pluck('total')->first();


            $json['bumn'] = '';
            $json['bulan'] = '';
            $json['tahun'] = '';

            if ($perusahaan) {
                $bumn = Perusahaan::find($perusahaan);
                $json['bumn'] = ' ' . $bumn->nama_lengkap;
            }

            if ($bulan) {
                $bulan = Bulan::find($bulan);
                $json['bulan'] = 'Bulan ' . $bulan->nama;
            }

            if ($tahun) {
                $json['tahun'] = 'Tahun ' . $tahun;
            }

            return response()->json($json);
        } catch (\Exception $e) {
            $json = [];
            return response()->json($json);
        }
    }

    public function chartrealisasi(Request $request)
    {
        // dd($request);
        try {
            
            $jenis_anggaran = $request->owner_id;
            if ($request->owner_id == 'all') {
                $jenis_anggaran = ['CID', 'non CID'];
                $pilar[0] = PilarPembangunan::where('nama', 'Pilar Pembangunan Sosial')->whereIn('jenis_anggaran', $jenis_anggaran)->get();
                $pilar[1] = PilarPembangunan::where('nama', 'Pilar Pembangunan Ekonomi')->whereIn('jenis_anggaran', $jenis_anggaran)->get();
                $pilar[2] = PilarPembangunan::where('nama', 'Pilar Pembangunan Lingkungan')->whereIn('jenis_anggaran', $jenis_anggaran)->get();
                $pilar[3] = PilarPembangunan::where('nama', 'Pilar Pembangunan Hukum dan Tata Kelola')->whereIn('jenis_anggaran', $jenis_anggaran)->get();

                for ($i = 0; $i < 4; $i++) {
                    $pilarIds = $pilar[$i]->pluck('id')->toArray();
                    $kegiatan[$i] = Kegiatan::Select(DB::Raw('sum(kegiatan_realisasis.anggaran) as realisasi'))
                        ->leftJoin('target_tpbs', 'target_tpbs.id', '=', 'kegiatans.target_tpb_id')
                        ->leftJoin('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                        ->leftJoin('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                        ->whereIn('relasi_pilar_tpbs.pilar_pembangunan_id', $pilarIds)
                        ->where('kegiatans.is_invalid_aplikasitjsl', false)
                        ->where('kegiatan_realisasis.is_invalid_aplikasitjsl', false);
    
                    $anggaran[$i] = AnggaranTpb::Select(DB::Raw('sum(anggaran_tpbs.anggaran) as anggaran'))
                        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                        // ->leftJoin('target_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                        ->whereIn('relasi_pilar_tpbs.pilar_pembangunan_id', $pilarIds);
    
                    if ($request->perusahaan_id && $request->perusahaan_id != 'all') {
                        $kegiatan[$i] = $kegiatan[$i]->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
                        $anggaran[$i] = $anggaran[$i]->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
                    }
                    if ($request->tahun && $request->tahun != 'all') {
                        $kegiatan[$i] = $kegiatan[$i]->where('kegiatan_realisasis.tahun', $request->tahun);
                        $anggaran[$i] = $anggaran[$i]->where('anggaran_tpbs.tahun', $request->tahun);
                    }
                    // if ($request->owner_id && $request->owner_id != 'all') {
                    //     $kegiatan[$i] = $kegiatan[$i]->where('target_tpbs.id_owner', (int)$request->owner_id);
                    //     // $anggaran[$i] = $anggaran[$i]->where('target_tpbs.id_owner',(int)$request->owner_id);
                    // }
                    // dd($anggaran[$i]->get());
                    // dd($kegiatan[$i]->get());
                    $kegiatan[$i] = $kegiatan[$i]->first();
                    $anggaran[$i] = $anggaran[$i]->first();
    
                    $arr['realisasi'][$i] = 0;
                    $arr['target'][$i] = 0;
                    $arr['sisa'][$i] = 0;
                    $arr['pilar'][$i] = 0;
    
                    if ($anggaran[$i]->anggaran > 0) {
                        $arr['realisasi'][$i] = number_format($kegiatan[$i]->realisasi, 0, '.', '.');
                        $arr['target'][$i] = number_format($anggaran[$i]->anggaran, 0, '.', '.');
                        $arr['sisa'][$i] = number_format(($anggaran[$i]->anggaran - $kegiatan[$i]->realisasi), 0, '.', '.');
                        $arr['pilar'][$i] = $kegiatan[$i]->realisasi / $anggaran[$i]->anggaran * 100;
                    }
                }
    
                $json['realisasi1'] = $arr['realisasi'][0];
                $json['realisasi2'] = $arr['realisasi'][1];
                $json['realisasi3'] = $arr['realisasi'][2];
                $json['realisasi4'] = $arr['realisasi'][3];
                $json['target1'] = $arr['target'][0];
                $json['target2'] = $arr['target'][1];
                $json['target3'] = $arr['target'][2];
                $json['target4'] = $arr['target'][3];
                $json['sisa1'] = $arr['sisa'][0];
                $json['sisa2'] = $arr['sisa'][1];
                $json['sisa3'] = $arr['sisa'][2];
                $json['sisa4'] = $arr['sisa'][3];
                $json['pilar1'] = $arr['pilar'][0];
                $json['pilar2'] = $arr['pilar'][1];
                $json['pilar3'] = $arr['pilar'][2];
                $json['pilar4'] = $arr['pilar'][3];
            }
            else{
                //bukan All
                $pilar[0] = PilarPembangunan::where('nama', 'Pilar Pembangunan Sosial')->where('jenis_anggaran', $jenis_anggaran)->first();
                $pilar[1] = PilarPembangunan::where('nama', 'Pilar Pembangunan Ekonomi')->where('jenis_anggaran', $jenis_anggaran)->first();
                $pilar[2] = PilarPembangunan::where('nama', 'Pilar Pembangunan Lingkungan')->where('jenis_anggaran', $jenis_anggaran)->first();
                $pilar[3] = PilarPembangunan::where('nama', 'Pilar Pembangunan Hukum dan Tata Kelola')->where('jenis_anggaran', $jenis_anggaran)->first();
                // dd($pilar);
                for ($i = 0; $i < 4; $i++) {
                    $kegiatan[$i] = Kegiatan::Select(DB::Raw('sum(kegiatan_realisasis.anggaran) as realisasi'))
                        ->leftJoin('target_tpbs', 'target_tpbs.id', '=', 'kegiatans.target_tpb_id')
                        ->leftJoin('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                        ->leftJoin('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                        ->where('relasi_pilar_tpbs.pilar_pembangunan_id', $pilar[$i]->id)
                        ->where('kegiatans.is_invalid_aplikasitjsl', false)
                        ->where('kegiatan_realisasis.is_invalid_aplikasitjsl', false);
    
                    $anggaran[$i] = AnggaranTpb::Select(DB::Raw('sum(anggaran_tpbs.anggaran) as anggaran'))
                        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                        // ->leftJoin('target_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                        ->where('relasi_pilar_tpbs.pilar_pembangunan_id', $pilar[$i]->id);
    
                    if ($request->perusahaan_id && $request->perusahaan_id != 'all') {
                        $kegiatan[$i] = $kegiatan[$i]->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
                        $anggaran[$i] = $anggaran[$i]->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
                    }
                    if ($request->tahun && $request->tahun != 'all') {
                        $kegiatan[$i] = $kegiatan[$i]->where('kegiatan_realisasis.tahun', $request->tahun);
                        $anggaran[$i] = $anggaran[$i]->where('anggaran_tpbs.tahun', $request->tahun);
                    }
                    // if ($request->owner_id && $request->owner_id != 'all') {
                    //     $kegiatan[$i] = $kegiatan[$i]->where('target_tpbs.id_owner', (int)$request->owner_id);
                    //     // $anggaran[$i] = $anggaran[$i]->where('target_tpbs.id_owner',(int)$request->owner_id);
                    // }
    
                    $kegiatan[$i] = $kegiatan[$i]->first();
                    $anggaran[$i] = $anggaran[$i]->first();
    
                    $arr['realisasi'][$i] = 0;
                    $arr['target'][$i] = 0;
                    $arr['sisa'][$i] = 0;
                    $arr['pilar'][$i] = 0;
    
                    if ($anggaran[$i]->anggaran > 0) {
                        $arr['realisasi'][$i] = number_format($kegiatan[$i]->realisasi, 0, '.', '.');
                        $arr['target'][$i] = number_format($anggaran[$i]->anggaran, 0, '.', '.');
                        $arr['sisa'][$i] = number_format(($anggaran[$i]->anggaran - $kegiatan[$i]->realisasi), 0, '.', '.');
                        $arr['pilar'][$i] = $kegiatan[$i]->realisasi / $anggaran[$i]->anggaran * 100;
                    }
                }
    
                $json['realisasi1'] = $arr['realisasi'][0];
                $json['realisasi2'] = $arr['realisasi'][1];
                $json['realisasi3'] = $arr['realisasi'][2];
                $json['realisasi4'] = $arr['realisasi'][3];
                $json['target1'] = $arr['target'][0];
                $json['target2'] = $arr['target'][1];
                $json['target3'] = $arr['target'][2];
                $json['target4'] = $arr['target'][3];
                $json['sisa1'] = $arr['sisa'][0];
                $json['sisa2'] = $arr['sisa'][1];
                $json['sisa3'] = $arr['sisa'][2];
                $json['sisa4'] = $arr['sisa'][3];
                $json['pilar1'] = $arr['pilar'][0];
                $json['pilar2'] = $arr['pilar'][1];
                $json['pilar3'] = $arr['pilar'][2];
                $json['pilar4'] = $arr['pilar'][3];
            }
           

            

            return response()->json($json);
        } catch (\Exception $e) {
            $json = [];
            return response()->json($json);
        }
    }

    public function charttpb(Request $request)
    {
        try {
            $kegiatan = Kegiatan::Select(DB::Raw('sum(kegiatan_realisasis.anggaran) as realisasi'))
                ->leftJoin('target_tpbs', 'target_tpbs.id', '=', 'kegiatans.target_tpb_id')
                ->leftJoin('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                ->leftJoin('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->where('kegiatans.is_invalid_aplikasitjsl', false)
                ->where('kegiatan_realisasis.is_invalid_aplikasitjsl', false);



            $anggaran = AnggaranTpb::Select(DB::Raw('sum(anggaran_tpbs.anggaran) as anggaran'))
                ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id');
            //->leftJoin('target_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id');

            if ($request->tpb_id && $request->tpb_id != 'all') {
                $kegiatan = $kegiatan->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
                $anggaran = $anggaran->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
            }
            if ($request->perusahaan_id && $request->perusahaan_id != 'all') {
                $kegiatan = $kegiatan->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
                $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
            }
            if ($request->tahun && $request->tahun != 'all') {
                $kegiatan = $kegiatan->where('kegiatan_realisasis.tahun', $request->tahun);
                $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
            }
            if ($request->owner_id && $request->owner_id != 'all') {
                $kegiatan = $kegiatan->where('target_tpbs.id_owner', (int)$request->owner_id);
                //$anggaran = $anggaran->where('target_tpbs.id_owner',(int)$request->owner_id);
            }

            $kegiatan = $kegiatan->first();
            $anggaran = $anggaran->first();

            $json['realisasi'] = 0;
            $json['target'] = 0;
            $json['sisa'] = 0;
            $json['tpb'] = 0;

            if ($anggaran->anggaran > 0) {
                $json['realisasi'] = number_format($kegiatan->realisasi, 0, '.', '.');
                $json['target'] = number_format($anggaran->anggaran, 0, '.', '.');
                $json['sisa'] = number_format(($anggaran->anggaran - $kegiatan->realisasi), 0, '.', '.');
                $json['tpb'] = $kegiatan->realisasi / $anggaran->anggaran * 100;
            }

            return response()->json($json);
        } catch (\Exception $e) {
            $json = [];
            return response()->json($json);
        }
    }
}
