<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\AnggaranTpb;
use App\Models\Perusahaan;
use App\Models\PilarPembangunan;
use App\Models\Tpb;
use App\Models\VersiPilar;
use App\Models\User;
use App\Models\LogAnggaranTpb;
use App\Exports\AnggaranTpbExport;
use Session;
use DateTime;

class AnggaranTpbController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'anggaran_tpb';
        $this->pagetitle = 'RKA';
        $this->pageRouteName = 'anggaran_tpb.rka'; // dipake buat check periode menu + enable disable
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;

        $admin_bumn = false;
        $view_only = false;
        $isSuperAdmin = false;
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if ($v == 'Admin BUMN' || $v == 'Verifikator BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if ($v == 'Admin Stakeholder') {
                    $view_only = true;
                }
                if($v == 'Super Admin') {
                    $isSuperAdmin = true;
                }
            }
        }

        $anggaran       = AnggaranTpb::select('relasi_pilar_tpbs.pilar_pembangunan_id', 'anggaran_tpbs.*', 'tpbs.nama as tpb_nama', 'tpbs.no_tpb as no_tpb')
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id');
        $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id'); 
        $anggaran_bumn  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id');            

        if ($perusahaan_id) {
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
        }

        $tahun = $request->tahun ? $request->tahun : (int)date('Y');
        if ($tahun) {
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.tahun', $tahun);
        }

        
        if ($request->pilar_pembangunan) {
            $anggaran = $anggaran->where('pilar_pembangunans.nama', $request->pilar_pembangunan);
            $anggaran_pilar = $anggaran_pilar->where('pilar_pembangunans.nama', $request->pilar_pembangunan);
            $anggaran_bumn = $anggaran_bumn->where('pilar_pembangunans.nama', $request->pilar_pembangunan);
        }

        if ($request->tpb) {
            $anggaran = $anggaran->where('tpbs.no_tpb', $request->tpb);
            $anggaran_pilar = $anggaran_pilar->where('tpbs.no_tpb', $request->tpb);
            $anggaran_bumn = $anggaran_bumn->where('tpbs.no_tpb', $request->tpb);
        }

        if($request->status){
            $statusId = DB::table('statuss')->where('nama', $request->status)->first();
            if($statusId) {
                $anggaran = $anggaran->where('anggaran_tpbs.status_id', $statusId->id);
                $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.status_id', $statusId->id);
                $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.status_id', $statusId->id);
            }
        }                

        $anggaran_pilar = $anggaran_pilar->select(
            'anggaran_tpbs.perusahaan_id',
            'anggaran_tpbs.tahun',            
            // 'relasi_pilar_tpbs.pilar_pembangunan_id',
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid'),
            'pilar_pembangunans.nama as pilar_nama',
            // 'pilar_pembangunans.id as pilar_id'
            // 'pilar_pembangunans.id as pilar_id'
        )
            ->groupBy(
                // 'relasi_pilar_tpbs.pilar_pembangunan_id',
                // 'relasi_pilar_tpbs.pilar_pembangunan_id',
                'anggaran_tpbs.perusahaan_id',
                'anggaran_tpbs.tahun',
                'pilar_pembangunans.nama',
                // 'pilar_pembangunans.id',
                // 'pilar_pembangunans.id',

            )
            // ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
            ->orderBy('pilar_pembangunans.nama')
            // ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
            ->orderBy('pilar_pembangunans.nama')
            ->get();

        $anggaran_pilar = $anggaran_pilar->filter(function($data) {
            return $data->sum_anggaran_cid > 0 || $data->sum_anggaran_noncid > 0;
        });

        $anggaran_bumn = $anggaran_bumn->select(
            'anggaran_tpbs.perusahaan_id',
            'perusahaan_masters.nama_lengkap',
            'perusahaan_masters.id',            
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid')
        )
            ->groupBy('anggaran_tpbs.perusahaan_id')
            ->groupBy('perusahaan_masters.nama_lengkap')
            ->groupBy('perusahaan_masters.id')
            ->get();
        $anggaran = $anggaran->select('*', 'anggaran_tpbs.id as id_anggaran','pilar_pembangunans.nama as pilar_nama', 'tpbs.nama as tpb_nama', DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'))
                ->orderBy('pilar_pembangunans.nama')
                ->orderBy('no_tpb')->get();        

        $anggaran = $anggaran->filter(function($condition) {
            return $condition->anggaran_cid > 0 || $condition->anggaran_noncid > 0;
        });

        $countInprogress = $anggaran->filter(function($data) {
            return $data->status_id == 2;
        })->count();

        $countFinish = $anggaran->filter(function($data) {
            return $data->status_id == 1;
        })->count();
        
        $list_perusahaan = Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get();
        $currentNamaPerusahaan = $list_perusahaan->where('id', $perusahaan_id)->pluck('nama_lengkap');
        $currentNamaPerusahaan = count($currentNamaPerusahaan) ? $currentNamaPerusahaan[0] : 'ALL';

        // validasi availability untuk input data
        $menuRKA = DB::table('menus')->where('label', 'RKA')->first();
        $start = null;
        $end = null;
        $isOkToInput = true;
        if($menuRKA) {
            $periodeHasJenis = DB::table('periode_has_jenis')->where('jenis_laporan_id', $menuRKA->id)->first();
            if($periodeHasJenis) {
                $periodeLaporan = DB::table('periode_laporans')->where('is_active', 1)->where('id', $periodeHasJenis->periode_laporan_id)->first();
                if($periodeLaporan) {
                    $currentDate = new DateTime();                    
                    $start = new DateTime($periodeLaporan->tanggal_awal);
                    $end = new DateTime($periodeLaporan->tanggal_akhir);

                    if($currentDate < $start || $currentDate > $end) {
                        $isOkToInput = false;
                    }
                }
            }
        }

        $isEnableInputBySuperadmin = false;
        if($perusahaan_id) {
            $isEnableInputBySuperadmin = $anggaran->filter(function($data) {
                return $data->is_enable_input_by_superadmin == true;
            })->count();
        } else {
            $countEnable = $anggaran->filter(function($data) {
                return $data->is_enable_input_by_superadmin == true;
            })->count();

            $countDisable = $anggaran->filter(function($data) {
                return $data->is_enable_input_by_superadmin == false;
            })->count();

            if($countEnable == 0) $isEnableInputBySuperadmin = false;
            if($countDisable == 0) $isEnableInputBySuperadmin = true;
        }
       
        // dd($anggaran_bumn[0]);
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => $list_perusahaan,
            'anggaran' => $anggaran,
            'anggaran_pilar' => $anggaran_pilar,
            'anggaran_bumn' => $anggaran_bumn,
            'pilar' => PilarPembangunan::select(DB::raw('DISTINCT ON (nama) *'))->where('is_active', true)->orderBy('nama')->orderBy('id')->get(),
            'tpb' => Tpb::select(DB::raw('DISTINCT ON (no_tpb) *'))->orderBy('no_tpb')->orderBy('id')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'pilar_pembangunan_id' => $request->pilar_pembangunan,
            'tpb_id' => $request->tpb,
            'view_only' => $view_only,
            'pilar_pembangunan_id' => $request->pilar_pembangunan,
            'tpb_id' => $request->tpb,
            'view_only' => $view_only,
            'countInprogress' => $countInprogress,
            'perusahaan_nama' => $currentNamaPerusahaan,
            'countFinish' => $countFinish,
            'isOkToInput' => $isOkToInput,
            'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
            'isSuperAdmin' => $isSuperAdmin
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function log_status(Request $request)
    {    
        $log_anggaran_tpb_cid = LogAnggaranTpb::where('anggaran_tpb_id', (int) $request->input('id_cid'))
            ->orderBy('created_at')
            ->get();

        $log_anggaran_tpb_noncid = LogAnggaranTpb::where('anggaran_tpb_id', (int) $request->input('id_noncid'))
            ->orderBy('created_at')
            ->get();

        return view($this->__route . '.log_status', [
            'pagetitle' => 'Log Status',
            'log_cid' => $log_anggaran_tpb_cid,
            'log_noncid' => $log_anggaran_tpb_noncid
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = \Auth::user()->id_bumn;

        $admin_bumn = false;
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if ($v == 'Admin BUMN') {
                    $admin_bumn = true;
                }
            }
        }

        $anggaran_tpb = AnggaranTpb::get();
        $versi = VersiPilar::whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', date('Y-m-d'))->first();
        $versi_pilar_id = $versi->id;

        return view($this->__route . '.create', [
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'pilar' => PilarPembangunan::get(),
            'versi_pilar_id' => $versi_pilar_id,
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'data' => $anggaran_tpb
        ]);
    }

    public function create2($perusahaan_id, $tahun)
    {
        $versi = VersiPilar::whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', date('Y-m-d'))->first();
        $versi_pilar_id = $versi->id;
        // $pilars = DB::table('relasi_pilar_tpbs')
        //     ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        //     ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->where('versi_pilar_id', $versi->id)
        //     ->get(['relasi_pilar_tpbs.id', 'pilar_pembangunans.nama as pilar_name', 'pilar_pembangunans.jenis_anggaran as pilar_jenis_anggaran', 'tpbs.nama as tpb_name', 'tpbs.jenis_anggaran as tpb_jenis_anggaran']);


        // $current = AnggaranTpb::join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        //     ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        //     ->where('perusahaan_id', $perusahaan_id)
        //     ->where('tahun', $tahun)
        //     ->get();

        // $pilars = DB::table('relasi_pilar_tpbs')
        //     ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        //     ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->where('versi_pilar_id', $versi->id)
        //     ->get(['relasi_pilar_tpbs.id', 'pilar_pembangunans.nama as pilar_name', 'pilar_pembangunans.jenis_anggaran as pilar_jenis_anggaran', 'tpbs.nama as tpb_name', 'tpbs.jenis_anggaran as tpb_jenis_anggaran']);


        // $current = AnggaranTpb::join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        //     ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        //     ->where('perusahaan_id', $perusahaan_id)
        //     ->where('tahun', $tahun)
        //     ->get();

        // $pilars = DB::table('relasi_pilar_tpbs')
        //     ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        //     ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->leftJoin('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun){
        //         $join->on('anggaran_tpbs.relasi_pilar_tpb_id', '=', 'relasi_pilar_tpbs.id')
        //             ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
        //             ->where('anggaran_tpbs.tahun', $tahun);
        //     })
        //     ->where('versi_pilar_id', $versi->id)            
        //     ->get(['relasi_pilar_tpbs.id', 'pilar_pembangunans.nama as pilar_name', 'pilar_pembangunans.jenis_anggaran as pilar_jenis_anggaran', 'tpbs.nama as tpb_name', 'tpbs.jenis_anggaran as tpb_jenis_anggaran', 'anggaran_tpbs.anggaran', 'tpbs.no_tpb as tpb_no_tpb']);

        // if (count($current) > 0) {
        //     $actionform = 'update';
        // } else {
        //     $actionform = 'insert';
        // }

        // foreach ($pilars as $key => $pilar) {
        //     foreach ($current as $key => $current2) {

        //         if ($pilar->id == $current2->relasi_pilar_tpb_id) {

        //             $pilarArray = (array) $pilar; // convert object to array
        //             $pilarArray['anggaran'] = $current2->anggaran; // add new key
        //             $pilars[$key] = (object) $pilarArray; // convert array back to object and assign it to $pilars
        //         }
        //     }
        // }
        // $pilars = $pilars->groupBy([
        //     'pilar_name',
        //     function ($item) {
        //         return $item->tpb_name;
        //     }
        // ])->sortByDesc(null);

        // dd($pilars);

        $pilarMaster = DB::table('pilar_pembangunans')->select('nama', 'order_pilar')
            ->groupBy('nama', 'order_pilar')
            ->orderBy('order_pilar')
            ->get();

        $pilarTpbMaster = DB::table('relasi_pilar_tpbs as rpt')
            ->select('rpt.id', 'pp.nama as nama_pilar', 'tpbs.no_tpb', 
                'tpbs.nama as nama_tpb', 'pp.jenis_anggaran as ja_pilar',
                'tpbs.jenis_anggaran as ja_tpbs'
            )
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->where('versi_pilar_id', $versi->id)
            ->where('tpbs.is_active', true)
            ->where('pp.is_active', true)
            ->orderBy('pp.nama', 'asc')
            ->orderBy('tpb_id')
            ->get();

        $dataInput = [];
        foreach($pilarMaster as $pm) {
            $dataInput[$pm->nama] = [];
            $tempDataPilar = $pilarTpbMaster->where('nama_pilar', $pm->nama);
            foreach($tempDataPilar as $tdp) {

                $tempResult = DB::table('anggaran_tpbs')
                    ->where('relasi_pilar_tpb_id', $tdp->id)
                    ->where('perusahaan_id', $perusahaan_id)
                    ->where('tahun', $tahun)
                    ->first();

                if($tdp->ja_pilar == 'CID') {
                    $dataInput[$pm->nama][$tdp->no_tpb.' - '.$tdp->nama_tpb]['CID'] = $tempResult ? ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => $tempResult->anggaran] : ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => null];
                }

                if($tdp->ja_pilar == 'non CID') {
                    $dataInput[$pm->nama][$tdp->no_tpb.' - '.$tdp->nama_tpb]['non CID'] = $tempResult ? ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => $tempResult->anggaran] : ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => null];
                }
                
            }
        }

        // validasi availability untuk input data
        $menuRKA = DB::table('menus')->where('label', 'RKA')->first();
        $start = null;
        $end = null;
        $isOkToInput = true;
        if($menuRKA) {
            $periodeHasJenis = DB::table('periode_has_jenis')->where('jenis_laporan_id', $menuRKA->id)->first();
            if($periodeHasJenis) {
                $periodeLaporan = DB::table('periode_laporans')->where('is_active', 1)->where('id', $periodeHasJenis->periode_laporan_id)->first();
                if($periodeLaporan) {
                    $currentDate = new DateTime();                    
                    $start = new DateTime($periodeLaporan->tanggal_awal);
                    $end = new DateTime($periodeLaporan->tanggal_akhir);

                    if($currentDate < $start || $currentDate > $end) {
                        $isOkToInput = false;
                    }
                }
            }
        }

        $anggaran = DB::table('anggaran_tpbs')->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->get();
        $isEnableInputBySuperadmin = $anggaran->filter(function($data) {
            return $data->is_enable_input_by_superadmin == true;
        })->count();

        $countStatus = $anggaran->groupBy('status_id')->map(function($data) {
            return $data->count();
        });

        $isFinish = isset($countStatus['1']) && !isset($countStatus['2']);
        

        return view(
            $this->__route . '.create2',
            [
                'pagetitle' => $this->pagetitle,
                'breadcrumb' => '',
                // 'pilars' => $pilars,
                'perusahaan_id' => $perusahaan_id,
                'tahun' => $tahun,
                'actionform' => '-',
                'nama_perusahaan' => Perusahaan::find($perusahaan_id)->nama_lengkap,
                'isOkToInput' => $isOkToInput,
                'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
                'isFinish' => $isFinish,
                'dataInput' => $dataInput
                // 'pilar' => PilarPembangunan::get(),
                // 'versi_pilar_id' => $versi_pilar_id,
                // 'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                // 'admin_bumn' => $admin_bumn,
                // 'perusahaan_id' => $perusahaan_id,
                // 'data' => $anggaran_tpb
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];
        
        switch ($request->input('actionform')) {
            case 'insert':
                DB::beginTransaction();
                try {
                    $param['perusahaan_id'] = $request->perusahaan_id;
                    $param['tahun'] = $request->tahun;
                    $param['status_id'] = 2;
                    $param['user_id']  = \Auth::user()->id;

                    if ($request->perusahaan_id == '') {
                        $id_users = \Auth::user()->id;
                        $users = User::where('id', $id_users)->first();
                        $param['perusahaan_id'] = $users->id_bumn;
                    }

                    $validasi = true;
                    if ($request->tpb_id) {
                        $tpb_id = $request->tpb_id;
                        $anggaran = $request->anggaran;
                        for ($i = 0; $i < count($tpb_id); $i++) {
                            $param['relasi_pilar_tpb_id'] = $tpb_id[$i];
                            $param['anggaran'] = str_replace(',', '', $anggaran[$i]);

                            $checkdata = AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
                                ->where('tahun', $param['tahun'])
                                ->where('relasi_pilar_tpb_id', $param['relasi_pilar_tpb_id'])
                                ->first();

                            if ($checkdata != null) {
                                $validasi = false;
                                $validasi_msg = @$checkdata->relasi->tpb->no_tpb . ' - ' . @$checkdata->relasi->tpb->nama;
                            } else {
                                $data = AnggaranTpb::create((array)$param);
                                AnggaranTpbController::store_log($data->id, $param['status_id'], $param['anggaran'], 'RKA');
                            }
                        }
                    }

                    if ($validasi) {
                        DB::commit();
                        $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses tambah data',
                            'title' => 'Sukses'
                        ];
                    } else {
                        DB::rollback();
                        $result = [
                            'flag'  => 'warning',
                            'msg' => 'Data Anggaran ' . $validasi_msg . ' sudah ada',
                            'title' => 'Gagal'
                        ];
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    $result = [
                        'flag'  => 'warning',
                        'msg' => $e->getMessage(),
                        'title' => 'Gagal'
                    ];
                }

                break;

            case 'update':

                DB::beginTransaction();
                try {
                    $anggaran_tpb_cid = AnggaranTpb::find((int) $request->input('id_cid'));
                    $anggaran_tpb_noncid = AnggaranTpb::find((int) $request->input('id_noncid'));

                    if($anggaran_tpb_cid) {
                        $param_cid['anggaran'] = str_replace(',', '', $request->input('anggaran_cid'));
                        $anggaran_tpb_cid->update((array)$param_cid);
                        AnggaranTpbController::store_log($anggaran_tpb_cid->id, $anggaran_tpb_cid->status_id, $param_cid['anggaran'], 'RKA Revisi');
                    }

                    if($anggaran_tpb_noncid) {
                        $param_noncid['anggaran'] = str_replace(',', '', $request->input('anggaran_noncid'));
                        $anggaran_tpb_noncid->update((array)$param_noncid);
                        AnggaranTpbController::store_log($anggaran_tpb_noncid->id, $anggaran_tpb_noncid->status_id, $param_noncid['anggaran'], 'RKA Revisi');
                    }

                    DB::commit();
                    $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses ubah data',
                        'title' => 'Sukses'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    $result = [
                        'flag'  => 'warning',
                        'msg' => $e->getMessage(),
                        'title' => 'Gagal'
                    ];
                }

                break;
        }

        return response()->json($result);
    }

    public function store2(Request $request)
    {
        // dd($request);
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];
      
        $param['perusahaan_id'] = $request->perusahaan_id;
        $param['tahun'] = $request->tahun;
        $param['status_id'] = DB::table('statuss')->where('nama', 'In Progress')->first()->id;
        $param['status_id'] = DB::table('statuss')->where('nama', 'In Progress')->first()->id;
        $param['user_id']  = \Auth::user()->id;

        if ($request->perusahaan_id == '') {
            $id_users = \Auth::user()->id;
            $users = User::where('id', $id_users)->first();
            $param['perusahaan_id'] = $users->id_bumn;
        }
 
        foreach($request->tpbs_value as $value) {
            $param['anggaran'] = $value['value'];
            $param['relasi_pilar_tpb_id'] = $value['idrelasi'];

            $checkdata = AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
                    ->where('tahun', $param['tahun'])
                    ->where('relasi_pilar_tpb_id', $param['relasi_pilar_tpb_id'])
                    ->first();
            
            if($checkdata != null) { // update
                if($checkdata->anggaran != intval($param['anggaran'])) {
                    $checkdata->update(['anggaran' => intval($param['anggaran'])]);
                    AnggaranTpbController::store_log($checkdata->id, $checkdata->status_id, $param['anggaran'], 'RKA Revisi');
                    Session::flash('success', "Berhasil Mengubah Input Data RKA");
                    // return redirect()->route('anggaran_tpb.index')->with('success', 'Berhasil Mengubah Input Data RKA');
                }                
            } else { // insert
                if ($param['anggaran'] != null) {
                    $data = AnggaranTpb::create((array)$param);
                    AnggaranTpbController::store_log($data->id, $param['status_id'], $param['anggaran'], 'RKA');
                    Session::flash('success', "Berhasil Menyimpan Input Data RKA");
                }
               
                // return redirect()->route('anggaran_tpb.index')->with('success', 'Berhasil Menyimpan Input Data RKA');
            }
        }

        echo json_encode(['result' => true]);

        // $validasi = true;
        // if ($request->actionform == 'insert') {

        //     foreach ($request->tpbs_value as $key => $value) {
        //     foreach ($request->tpbs_value as $key => $value) {

        //         $param['anggaran'] = $value['value'];
        //         $param['relasi_pilar_tpb_id'] = $value['idrelasi'];
        //         $checkdata = AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
        //             ->where('tahun', $param['tahun'])
        //             ->where('relasi_pilar_tpb_id', $param['relasi_pilar_tpb_id'])
        //             ->first();
        //         $param['anggaran'] = $value['value'];
        //         $param['relasi_pilar_tpb_id'] = $value['idrelasi'];
        //         $checkdata = AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
        //             ->where('tahun', $param['tahun'])
        //             ->where('relasi_pilar_tpb_id', $param['relasi_pilar_tpb_id'])
        //             ->first();

        //         if ($checkdata != null) {
        //             $validasi = false;
        //             $validasi_msg = @$checkdata->relasi->tpb->no_tpb . ' - ' . @$checkdata->relasi->tpb->nama;
        //         } else {
        //             $data = AnggaranTpb::create((array)$param);
        //             AnggaranTpbController::store_log($data->id, $param['status_id'], $param['anggaran'], 'RKA');
        //         }
        //     }
        // }
        //         if ($checkdata != null) {
        //             $validasi = false;
        //             $validasi_msg = @$checkdata->relasi->tpb->no_tpb . ' - ' . @$checkdata->relasi->tpb->nama;
        //         } else {
        //             $data = AnggaranTpb::create((array)$param);
        //             AnggaranTpbController::store_log($data->id, $param['status_id'], $param['anggaran'], 'RKA');
        //         }
        //     }
        // }

        // if ($request->actionform == 'update') {
        //     # code...
        //     // dd($request->tpbs_value);
        //     $anggaran_tpb =  AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
        //         ->where('tahun', $param['tahun']);
        // if ($request->actionform == 'update') {
        //     # code...
        //     // dd($request->tpbs_value);
        //     $anggaran_tpb =  AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
        //         ->where('tahun', $param['tahun']);

        //     // dd($anggaran_tpb->get()); // Debugging statement
        //     foreach ($request->tpbs_value as $key => $tpb) {
        //         // dd(intval($tpb['value']));
        //         $anggaran_tpb_row = $anggaran_tpb->where('relasi_pilar_tpb_id', $tpb['idrelasi'])->first();
        //         // dd($anggaran_tpb_row);
        //     // dd($anggaran_tpb->get()); // Debugging statement
        //     foreach ($request->tpbs_value as $key => $tpb) {
        //         // dd(intval($tpb['value']));
        //         $anggaran_tpb_row = $anggaran_tpb->where('relasi_pilar_tpb_id', $tpb['idrelasi'])->first();
        //         // dd($anggaran_tpb_row);

        //         if (isset($anggaran_tpb_row)) {
        //             // dd(true);
        //             // $anggaran_tpb_row->anggaran = intval($tpb['value']);
        //             // $anggaran_tpb_row->save();
        //             $anggaran_tpb_row->update(['anggaran' => intval($tpb['value'])]);
        //             // dd($anggaran_tpb_row);
        //             try {
        //                 //code...
        //                 AnggaranTpbController::store_log($anggaran_tpb_row->id, $anggaran_tpb_row->status_id, $tpb['value'], 'RKA Revisi');
        //             } catch (\Throwable $th) {
        //                 throw $th;
        //                 // dd($th);
        //             }
        //         }
        //         // dd(false);
        //     }


        //     // $param['anggaran'] = str_replace(',', '', $request->input('anggaran'));
        //     // $anggaran_tpb->update((array)$param);

        //     // AnggaranTpbController::store_log($anggaran_tpb->id, $anggaran_tpb->status_id, $param['anggaran'], 'RKA Revisi');
        // }


        //         if (isset($anggaran_tpb_row)) {
        //             // dd(true);
        //             // $anggaran_tpb_row->anggaran = intval($tpb['value']);
        //             // $anggaran_tpb_row->save();
        //             $anggaran_tpb_row->update(['anggaran' => intval($tpb['value'])]);
        //             // dd($anggaran_tpb_row);
        //             try {
        //                 //code...
        //                 AnggaranTpbController::store_log($anggaran_tpb_row->id, $anggaran_tpb_row->status_id, $tpb['value'], 'RKA Revisi');
        //             } catch (\Throwable $th) {
        //                 throw $th;
        //                 // dd($th);
        //             }
        //         }
        //         // dd(false);
        //     }


        //     // $param['anggaran'] = str_replace(',', '', $request->input('anggaran'));
        //     // $anggaran_tpb->update((array)$param);

        //     // AnggaranTpbController::store_log($anggaran_tpb->id, $anggaran_tpb->status_id, $param['anggaran'], 'RKA Revisi');
        // }




        // switch ($request->input('actionform')) {
        //     case 'insert':
        //         DB::beginTransaction();
        //         try {
        //             $param['perusahaan_id'] = $request->perusahaan_id;
        //             $param['tahun'] = $request->tahun;
        //             $param['status_id'] = 2;
        //             $param['user_id']  = \Auth::user()->id;

        //             if ($request->perusahaan_id == '') {
        //                 $id_users = \Auth::user()->id;
        //                 $users = User::where('id', $id_users)->first();
        //                 $param['perusahaan_id'] = $users->id_bumn;
        //             }

        //             $validasi = true;
        //             if ($request->tpb_id) {
        //                 $tpb_id = $request->tpb_id;
        //                 $anggaran = $request->anggaran;
        //                 for ($i = 0; $i < count($tpb_id); $i++) {
        //                     $param['relasi_pilar_tpb_id'] = $tpb_id[$i];
        //                     $param['anggaran'] = str_replace(',', '', $anggaran[$i]);

        //                     $checkdata = AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
        //                         ->where('tahun', $param['tahun'])
        //                         ->where('relasi_pilar_tpb_id', $param['relasi_pilar_tpb_id'])
        //                         ->first();

        //                     if ($checkdata != null) {
        //                         $validasi = false;
        //                         $validasi_msg = @$checkdata->relasi->tpb->no_tpb . ' - ' . @$checkdata->relasi->tpb->nama;
        //                     } else {
        //                         $data = AnggaranTpb::create((array)$param);
        //                         AnggaranTpbController::store_log($data->id, $param['status_id'], $param['anggaran'], 'RKA');
        //                     }
        //                 }
        //             }

        //             if ($validasi) {
        //                 DB::commit();
        //                 $result = [
        //                     'flag'  => 'success',
        //                     'msg' => 'Sukses tambah data',
        //                     'title' => 'Sukses'
        //                 ];
        //             } else {
        //                 DB::rollback();
        //                 $result = [
        //                     'flag'  => 'warning',
        //                     'msg' => 'Data Anggaran ' . $validasi_msg . ' sudah ada',
        //                     'title' => 'Gagal'
        //                 ];
        //             }
        //         } catch (\Exception $e) {
        //             DB::rollback();
        //             $result = [
        //                 'flag'  => 'warning',
        //                 'msg' => $e->getMessage(),
        //                 'title' => 'Gagal'
        //             ];
        //         }

        //         break;

        //     case 'update':
        //         DB::beginTransaction();
        //         try {
        //             $anggaran_tpb = AnggaranTpb::find((int)$request->input('id'));
        //             $param['anggaran'] = str_replace(',', '', $request->input('anggaran'));
        //             $anggaran_tpb->update((array)$param);

        //             AnggaranTpbController::store_log($anggaran_tpb->id, $anggaran_tpb->status_id, $param['anggaran'], 'RKA Revisi');

        //             DB::commit();
        //             $result = [
        //                 'flag'  => 'success',
        //                 'msg' => 'Sukses ubah data',
        //                 'title' => 'Sukses'
        //             ];
        //         } catch (\Exception $e) {
        //             DB::rollback();
        //             $result = [
        //                 'flag'  => 'warning',
        //                 'msg' => $e->getMessage(),
        //                 'title' => 'Gagal'
        //             ];
        //         }

        //         break;
        // }

        // return response()->json($result);
        // Session::flash('success', "Berhasil Menyimpan Input Data RKA");
        // echo json_encode(['result' => true]);
        // Session::flash('success', "Berhasil Menyimpan Input Data RKA");
        // echo json_encode(['result' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {

        try {

            $anggaran_tpb_cid = AnggaranTpb::find((int)$request->input('id_cid'));
            $anggaran_tpb_noncid = AnggaranTpb::find((int)$request->input('id_noncid'));

            // validasi availability untuk input data
            $menuRKA = DB::table('menus')->where('label', 'RKA')->first();
            $start = null;
            $end = null;
            $isOkToInput = true;
            if($menuRKA) {
                $periodeHasJenis = DB::table('periode_has_jenis')->where('jenis_laporan_id', $menuRKA->id)->first();
                if($periodeHasJenis) {
                    $periodeLaporan = DB::table('periode_laporans')->where('is_active', 1)->where('id', $periodeHasJenis->periode_laporan_id)->first();
                    if($periodeLaporan) {
                        $currentDate = new DateTime();                    
                        $start = new DateTime($periodeLaporan->tanggal_awal);
                        $end = new DateTime($periodeLaporan->tanggal_akhir);

                        if($currentDate < $start || $currentDate > $end) {
                            $isOkToInput = false;
                        }
                    }
                }
            }

            if($anggaran_tpb_cid) {
                $isEnableInputBySuperadmin = $anggaran_tpb_cid->is_enable_input_by_superadmin; 
            } else if($anggaran_tpb_noncid) {
                $isEnableInputBySuperadmin = $anggaran_tpb_noncid->is_enable_input_by_superadmin; 
            } else {
                $isEnableInputBySuperadmin = false;
            }


            return view($this->__route . '.edit', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'tpb' => Tpb::get(),
                'pilar' => PilarPembangunan::get(),
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'data' => $anggaran_tpb_cid,
                'data_cid' => $anggaran_tpb_cid,
                'data_noncid' => $anggaran_tpb_noncid,
                'isOkToInput' => $isOkToInput,
                'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin
            ]);
        } catch (Exception $e) {
        }
    }

    public function verifikasiData(Request $request) {
        // DB::beginTransaction();
        // try {
        //     $list_id_anggaran = $request->input('anggaran_verifikasi');
        //     foreach($list_id_anggaran as $id_anggaran) {
        //         $data = AnggaranTpb::find((int) $id_anggaran);
        //         if($data && $data->status_id !== 1) {
        //             $param['status_id'] = 1;
        //             $data->update((array)$param);
        //             AnggaranTpbController::store_log($data->id, $data->status_id, $data->anggaran, 'RKA Revisi - Verifikasi');
        //         }
                
        //     }
        //     DB::commit();
        //     $result = [
        //         'flag'  => 'success',
        //         'msg' => 'Sukses verifikasi data',
        //         'title' => 'Sukses'
        //     ];
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     $result = [
        //         'flag'  => 'warning',
        //         'msg' => 'Gagal verifikasi data',
        //         'title' => 'Gagal'
        //     ];
        // }
        // return response()->json($result);

        DB::beginTransaction();
        try {
            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');

            $allDataUpdated = AnggaranTpb::where('status_id', '=', 2) // inprogress
                            ->where('anggaran', '>=', 0)
                            ->where('tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('perusahaan_id', $id_bumn);
                            })
                            ->get();

            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    AnggaranTpb::where('id', $data->id)->update(['status_id' => 1]);
                    AnggaranTpbController::store_log($data->id, 1, $data->anggaran, 'RKA Revisi - Validated');
                }
            }                                            
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses set data validated',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function deleteBySelect(Request $request) {
        DB::beginTransaction();
        try {
            $list_id_anggaran = $request->input('anggaran_deleted');
            foreach($list_id_anggaran as $id_anggaran) {
                $data = AnggaranTpb::find((int) $id_anggaran);
                $param['anggaran'] = 0;
                $data->update((array)$param);
                AnggaranTpbController::store_log($data->id, $data->status_id, $param['anggaran'], 'RKA Revisi - Delete');
            }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            // $data = AnggaranTpb::find((int)$request->input('id'));
            $data = AnggaranTpb::find((int) $request->input('anggaran'));
            // $data = AnggaranTpb::find((int)$request->input('id'));
            $data = AnggaranTpb::find((int) $request->input('anggaran'));
            $data->delete();

            // $log = LogAnggaranTpb::where('anggaran_tpb_id', (int)$request->input('id'));
            $log = LogAnggaranTpb::where('anggaran_tpb_id', (int) $request->input('anggaran'));
            // $log = LogAnggaranTpb::where('anggaran_tpb_id', (int)$request->input('id'));
            $log = LogAnggaranTpb::where('anggaran_tpb_id', (int) $request->input('anggaran'));
            $log->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_by_pilar(Request $request)
    {
        DB::beginTransaction();
        try {
            $pilarPembangunan = DB::table('pilar_pembangunans')->where('nama', $request->input('nama_pilar'))->get();
            foreach($pilarPembangunan as $pilar_pembangunan) {
                $data = AnggaranTpb::LeftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
                    ->where('anggaran_tpbs.perusahaan_id', (int)$request->input('perusahaan_id'))
                    ->where('anggaran_tpbs.tahun', (int)$request->input('tahun'))
                    ->where('relasi_pilar_tpbs.pilar_pembangunan_id', $pilar_pembangunan->id);
                foreach ($data as $a) {
                    $log = LogAnggaranTpb::where('anggaran_tpb_id', $pilar_pembangunan->id);
                    $log->delete();
                }
                $data->delete();
            }
            
            $pilarPembangunan = DB::table('pilar_pembangunans')->where('nama', $request->input('nama_pilar'))->get();
            foreach($pilarPembangunan as $pilar_pembangunan) {
                $data = AnggaranTpb::LeftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
                    ->where('anggaran_tpbs.perusahaan_id', (int)$request->input('perusahaan_id'))
                    ->where('anggaran_tpbs.tahun', (int)$request->input('tahun'))
                    ->where('relasi_pilar_tpbs.pilar_pembangunan_id', $pilar_pembangunan->id);
                foreach ($data as $a) {
                    $log = LogAnggaranTpb::where('anggaran_tpb_id', $pilar_pembangunan->id);
                    $log->delete();
                }
                $data->delete();
            }
            
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateform($request)
    {
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    public function export(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id');

        if ($request->perusahaan_id) {
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if ($request->tahun) {
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }

        // if ($request->pilar_pembangunan_id) {
        //     $anggaran = $anggaran->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        // }

        // if ($request->tpb_id) {
        //     $anggaran = $anggaran->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
        // }

        $anggaran = $anggaran->whereNotNull('anggaran')->orderBy('anggaran_tpbs.perusahaan_id')->orderBy('pilar_pembangunans.jenis_anggaran')->orderBy('pilar_pembangunans.nama')->orderBy('tpbs.id')->get();
        $namaFile = "Data Anggaran TPB " . date('dmY') . ".xlsx";
        return Excel::download(new AnggaranTpbExport($anggaran, $request->tahun), $namaFile);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validasi(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id)
            ->where('anggaran_tpbs.tahun', $request->tahun);

        DB::beginTransaction();
        try {
            $param['status_id'] = $request->status_id;

            $anggaran_tpb = $anggaran->get();
            foreach ($anggaran_tpb as $a) {
                AnggaranTpbController::store_log($a->id, $param['status_id'], $a->anggaran, '');
            }

            $anggaran->update($param);

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses validasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_status(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id');

        if ($request->perusahaan_id) {
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if ($request->tahun) {
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }

        $anggaran = $anggaran->first();

        $result['status_id'] = @$anggaran->status_id;

        return response()->json($result);
    }


    public static function store_log($anggaran_tpb_id, $status_id, $anggaran, $keterangan)
    {
        $param['anggaran'] = $anggaran;
        $param['anggaran_tpb_id'] = $anggaran_tpb_id;
        $param['status_id'] = $status_id;
        $param['keterangan'] = $keterangan;
        $param['user_id'] = \Auth::user()->id;
        LogAnggaranTpb::create((array)$param);
    }

    public function getDataPerusahaanTree(Request $request) {
        $perusahaan_id = $request->input('id');
        $tahun = $request->input('tahun');

        $result = DB::table('anggaran_tpbs as atpb')
            ->select('pp.order_pilar', 'pp.nama as nama_pilar', 
                DB::raw("sum(case when pp.jenis_anggaran = 'CID' then atpb.anggaran else 0 end) sum_cid"),
                DB::raw("sum(case when pp.jenis_anggaran = 'non CID' then atpb.anggaran else 0 end) sum_noncid"),
                DB::raw("count(case when status_id = 1 then 1 end) verified"),
                DB::raw("count(case when status_id = 2 then 1 end) inprogress"),
                DB::raw("count(case when status_id = 4 then 1 end) validated"),
            )
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('anggaran', '>=', 0)
            ->where('tahun', $tahun);

        if ($request->input('pilar_pembangunan')) {
            $result = $result->where('pp.nama', $request->pilar_pembangunan);
        }

        if($request->input('tpb')) {
            $result = $result->where('tpbs.no_tpb', $request->tpb);
        }

        if($request->status){
            $statusId = DB::table('statuss')->where('nama', $request->status)->first();
            if($statusId) {
                $result = $result->where('atpb.status_id', $statusId->id);
            }
        } 

        $result = $result->groupBy('pp.nama', 'pp.order_pilar')
            ->orderBy('pp.order_pilar')
            ->get();

        echo json_encode(array('result' => $result));
    }

    public function batalVerifikasiData(Request $request) {
        DB::beginTransaction();
        try {
            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');

            $allDataUpdated = AnggaranTpb::where('status_id', '=', 1)
                            ->where('anggaran', '>=', 0)
                            ->where('tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('perusahaan_id', $id_bumn);
                            })
                            ->get();

            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    AnggaranTpb::where('id', $data->id)->update(['status_id' => 2]);
                    AnggaranTpbController::store_log($data->id, 2, $data->anggaran, 'RKA Revisi - Unvalidated');
                }
            }                                            
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses unvalidated data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function enableDisableInputData(Request $request) {
        DB::beginTransaction();
        try {
            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');
            $status = $request->input('status') === 'enable' ? 1 : 0;

            AnggaranTpb::where('tahun', $tahun)
            ->when($id_bumn, function($query) use ($id_bumn) {
                return $query->where('perusahaan_id', $id_bumn);
            })
            ->update(['is_enable_input_by_superadmin' => $status]);

            // update log 
            $currentTime = date('Y-m-d H:i:s');
            if($id_bumn) {
                DB::table('log_enable_disable_input_datas')->insert([
                    'tipe' => 'RKA',
                    'status' => $status ? 'enable' : 'disable',
                    'perusahaan_id' => $id_bumn,
                    'tahun' => $tahun,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime
                ]);
            } else {
                $getAllPerusahaanOK = DB::table('anggaran_tpbs')->select('perusahaan_id')
                    ->where('tahun', $tahun)
                    ->groupBy('perusahaan_id')
                    ->havingRaw('COUNT(CASE WHEN anggaran > 0 THEN 1 END) > 0')
                    ->orderBy('perusahaan_id')
                    ->get();
                
                foreach($getAllPerusahaanOK as $perusahaan) {
                    DB::table('log_enable_disable_input_datas')->insert([
                        'tipe' => 'RKA',
                        'status' => $status ? 'enable' : 'disable',
                        'perusahaan_id' => $perusahaan->perusahaan_id,
                        'tahun' => $tahun,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime
                    ]);
                }
            }
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses '.$request->input('status').' input data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }    

    public function index2(Request $request) {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;

        $admin_bumn = false;
        $view_only = false;
        $isSuperAdmin = false;
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if ($v == 'Admin BUMN' || $v == 'Verifikator BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if ($v == 'Admin Stakeholder') {
                    $view_only = true;
                }
                if($v == 'Super Admin') {
                    $isSuperAdmin = true;
                }
            }
        }  

        $refEnable = $this->getReferensiEnable();
        
        $tahun = $request->tahun ? $request->tahun : (int)date('Y');
        
        $data = DB::table('anggaran_tpbs as atpb')
            ->select('atpb.perusahaan_id', 'perusahaan_masters.nama_lengkap', DB::raw("sum(case when pp.jenis_anggaran = 'CID' then atpb.anggaran end) as sum_cid"),
            DB::raw("sum(case when pp.jenis_anggaran = 'non CID' then atpb.anggaran end) as sum_noncid"),
            DB::raw("count(case when status_id = 1 then 1 end) verified"),
            DB::raw("count(case when status_id = 2 then 1 end) inprogress"),
            DB::raw("count(case when status_id = 4 then 1 end) validated"),
            DB::raw("(case when epp.id is not null then 1 else 0 end) enable_by_admin")
            // DB::raw("count(case when atpb.is_enable_input_by_superadmin = true then 1 end) enable_by_admin"),
            // DB::raw("count(case when atpb.is_enable_input_by_superadmin = false then 1 end) disable_by_admin")
            )
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('perusahaan_masters', 'perusahaan_masters.id', '=', 'atpb.perusahaan_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->leftJoin('enable_input_by_superadmin as epp', function($join) use ($refEnable) {
                $join->on('epp.perusahaan_id', '=', 'atpb.perusahaan_id')
                    ->on('epp.tahun', '=', DB::raw("CAST(atpb.tahun AS INTEGER)"))
                    ->where('epp.referensi_id', '=', $refEnable?->id);
            })
            ->where('anggaran', '>=', 0);
            
        
        if($perusahaan_id) {
            $data = $data->where('atpb.perusahaan_id', $perusahaan_id);
        }

        if ($tahun) {
            $data = $data->where('atpb.tahun', $tahun);
        }
        
        if ($request->pilar_pembangunan) {
            $data = $data->where('pp.nama', $request->pilar_pembangunan);
        }

        if ($request->tpb) {
            $data = $data->where('tpbs.no_tpb', $request->tpb);
        }

        if($request->status){
            $statusId = DB::table('statuss')->where('nama', $request->status)->first();
            if($statusId) {
                $data = $data->where('atpb.status_id', $statusId->id);
            }
        } 

        $data = $data->groupBy('atpb.perusahaan_id', 'perusahaan_masters.nama_lengkap', 'epp.id')
            ->orderBy('atpb.perusahaan_id')
            ->get();

        $countInprogress = $data->filter(function($row) {
            return $row->inprogress > 0;
        })->count();
        
        $countVerified = $data->filter(function($row) {
            return $row->verified > 0;
        })->count();

        $countValidated = $data->filter(function($row) {
            return $row->validated > 0;
        })->count();
                
        $list_perusahaan = Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get();
        $currentNamaPerusahaan = $list_perusahaan->where('id', $perusahaan_id)->pluck('nama_lengkap');
        $currentNamaPerusahaan = count($currentNamaPerusahaan) ? $currentNamaPerusahaan[0] : 'ALL';

        // validasi availability untuk input data
        $isOkToInput = $this->checkRule();

        // cek enable input by superadmin
        $list_enable = DB::table('enable_input_by_superadmin')
            ->where('referensi_id', $refEnable?->id)
            ->where('tahun', $tahun)
            ->when($perusahaan_id, function($query) use ($perusahaan_id) {
                return $query->where('perusahaan_id', $perusahaan_id);
            })
            ->get();
        
        $isEnableInputBySuperadmin = false;
        if($list_enable->count()) $isEnableInputBySuperadmin = true;
        
        // $isEnableInputBySuperadmin = false;
        // if($perusahaan_id) {
        //     // kalo ada satu aja yg enable -> status = ENABLE
        //     $isEnableInputBySuperadmin = $data->filter(function($row) {
        //         return $row->enable_by_admin > 0;
        //     })->count();
        // } else {
        //     $countEnable = $data->filter(function($row) {
        //         return $row->enable_by_admin > 0;
        //     })->count();

        //     $countDisable = $data->filter(function($row) {
        //         return $row->disable_by_admin > 0;
        //     })->count();

        //     if($countEnable == 0) $isEnableInputBySuperadmin = false;
        //     if($countDisable == 0) $isEnableInputBySuperadmin = true;
        // }

        //Kalau Unfilled
        // if ($request->status == 'Unfilled') {
        //     $idPerusahaanFilled = DB::table('anggaran_tpbs')->where('tahun', $request->tahun)->pluck('perusahaan_id')->unique()->values()->toArray();
            
        //     $perusahaanUnfilled = DB::table('perusahaans')->whereNotIn('id', $idPerusahaanFilled)->get();
        //     dd($perusahaanUnfilled);
        // }
        
        return view($this->__route . '.index2', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => $list_perusahaan,
            'pilar' => PilarPembangunan::select(DB::raw('DISTINCT ON (nama) *'))->where('is_active', true)->orderBy('nama')->orderBy('id')->get(),
            'tpb' => Tpb::select('no_tpb', 'nama')->groupBy('no_tpb', 'nama')->orderBy(DB::raw("substring(no_tpb, '^[^0-9]*'), (substring(no_tpb, '[0-9]+'))::int"))->get(),            
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => $tahun,
            'pilar_pembangunan_id' => $request->pilar_pembangunan ?? 0,
            'tpb_id' => $request->tpb ?? 0,
            'view_only' => $view_only,
            'countInprogress' => $countInprogress,
            'perusahaan_nama' => $currentNamaPerusahaan,
            'countVerified' => $countVerified,
            'isOkToInput' => $isOkToInput,
            'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
            'isSuperAdmin' => $isSuperAdmin,
            'data' => $data,
            'list_enable' => $list_enable,
            'countValidated' => $countValidated
        ]);
    }

    public function getDataPerusahaanPilarTree(Request $request) {
        $perusahaan_id = $request->input('id');
        $tahun = $request->input('tahun');
        $pilar = $request->input('pilar');

        $refEnable = $this->getReferensiEnable();

        $result = DB::table('anggaran_tpbs as atpb')
            ->select('tpbs.no_tpb', 'tpbs.nama as nama_tpb',
                DB::raw("sum(case when tpbs.jenis_anggaran = 'CID' then anggaran end) sum_cid"),
                DB::raw("sum(case when tpbs.jenis_anggaran = 'non CID' then anggaran end) sum_noncid"),
                DB::raw("count(case when status_id = 1 then 1 end) verified"),
                DB::raw("count(case when status_id = 2 then 1 end) inprogress"),
                DB::raw("count(case when status_id = 4 then 1 end) validated"),
                DB::raw("(case when epp.id is not null then 1 else 0 end) enable_by_admin")
                // DB::raw("count(case when atpb.is_enable_input_by_superadmin = true then 1 end) enable_by_admin"),
                // DB::raw("count(case when atpb.is_enable_input_by_superadmin = false then 1 end) disable_by_admin")
            )
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->leftJoin('enable_input_by_superadmin as epp', function($join) use ($refEnable) {
                $join->on('epp.perusahaan_id', '=', 'atpb.perusahaan_id')
                    ->on('epp.tahun', '=', DB::raw("CAST(atpb.tahun AS INTEGER)"))
                    ->where('epp.referensi_id', '=', $refEnable?->id);
            })
            ->where('atpb.perusahaan_id', $perusahaan_id)
            ->where('anggaran', '>=', 0)
            ->where('atpb.tahun', $tahun)
            ->where('pp.nama', str_replace("-", " ", $pilar));


        if($request->input('tpb')) {
            $result = $result->where('tpbs.no_tpb', $request->tpb);
        }

        if($request->status){
            $statusId = DB::table('statuss')->where('nama', $request->status)->first();
            if($statusId) {
                $result = $result->where('atpb.status_id', $statusId->id);
            }
        }    
        $result = $result->groupBy('tpbs.no_tpb', 'tpbs.nama', 'epp.id')            
            ->orderBy(DB::raw("substring(tpbs.no_tpb, '^[^0-9]*'), (substring(tpbs.no_tpb, '[0-9]+'))::int"))
            ->get();

        echo json_encode(array('result' => $result));
    }

    public function log_status2(Request $request)
    {    
        $no_tpb = $request->no_tpb;
        $nama_pilar = str_replace("-", " ", $request->nama_pilar);
        $id_perusahaan = $request->perusahaan;
        $tahun = $request->tahun;

        $data = DB::table('anggaran_tpbs as atpb')
            ->select('atpb.id as id_anggaran', 'tpbs.jenis_anggaran')
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->where('tpbs.no_tpb', $no_tpb)
            ->where('pp.nama', $nama_pilar)
            ->where('atpb.perusahaan_id', $id_perusahaan)
            ->where('atpb.tahun', $tahun)
            ->get();
        
        $id_cid = null;
        $id_noncid = null;
        foreach($data as $anggaran) {
            if($anggaran->jenis_anggaran == 'CID') $id_cid = $anggaran->id_anggaran;
            if($anggaran->jenis_anggaran == 'non CID') $id_noncid = $anggaran->id_anggaran;
        }

        $log_anggaran_tpb_cid = LogAnggaranTpb::where('anggaran_tpb_id', $id_cid)
            ->orderBy('created_at')
            ->get();

        $log_anggaran_tpb_noncid = LogAnggaranTpb::where('anggaran_tpb_id', $id_noncid)
            ->orderBy('created_at')
            ->get();

        return view($this->__route . '.log_status', [
            'pagetitle' => 'Log Status',
            'log_cid' => $log_anggaran_tpb_cid,
            'log_noncid' => $log_anggaran_tpb_noncid
        ]);
    }

    public function edit2(Request $request)
    {

        try {

            $id_users = \Auth::user()->id;
            $users = User::where('id', $id_users)->first();
            $isSuperAdmin = false;
            if (!empty($users->getRoleNames())) {
                foreach ($users->getRoleNames() as $v) {
                    if($v == 'Super Admin') {
                        $isSuperAdmin = true;
                    }
                }
            }

            $no_tpb = $request->no_tpb;
            $nama_pilar = str_replace("-", " ", $request->nama_pilar);
            $id_perusahaan = $request->perusahaan;
            $tahun = $request->tahun;

            $data = DB::table('anggaran_tpbs as atpb')
                ->select('atpb.id as id_anggaran', 'tpbs.jenis_anggaran')
                ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
                ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
                ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
                ->where('tpbs.no_tpb', $no_tpb)
                ->where('pp.nama', $nama_pilar)
                ->where('atpb.perusahaan_id', $id_perusahaan)
                ->where('atpb.tahun', $tahun)
                ->get();
            
            $anggaran_tpb_cid = null;
            $anggaran_tpb_noncid = null;

            foreach($data as $anggaran) {
                if($anggaran->jenis_anggaran == 'CID') $anggaran_tpb_cid = AnggaranTpb::find($anggaran->id_anggaran);
                if($anggaran->jenis_anggaran == 'non CID') $anggaran_tpb_noncid = AnggaranTpb::find($anggaran->id_anggaran);
            }

            // validasi availability untuk input data
            $isOkToInput = $this->checkRule();

            $refEnable = $this->getReferensiEnable();

            // cek enable input by superadmin
            $list_enable = DB::table('enable_input_by_superadmin')
                ->where('referensi_id', $refEnable?->id)
                ->where('tahun', $tahun)
                ->when($id_perusahaan, function($query) use ($id_perusahaan) {
                    return $query->where('perusahaan_id', $id_perusahaan);
                })
                ->get();
            
            $isEnableInputBySuperadmin = false;
            if($list_enable->count()) $isEnableInputBySuperadmin = true;
                    

            return view($this->__route . '.edit2', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                // 'tpb' => Tpb::get(),
                'tpb_selected' => Tpb::where('no_tpb', $no_tpb)->first(),
                // 'pilar' => PilarPembangunan::get(),
                // 'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'perusahaan_selected' => Perusahaan::find($id_perusahaan),
                'data_cid' => $anggaran_tpb_cid,
                'data_noncid' => $anggaran_tpb_noncid,
                'isOkToInput' => $isOkToInput,
                'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
                'isSuperAdmin' => $isSuperAdmin
            ]);
        } catch (Exception $e) {
        }
    }

    public function updateAnggaran(Request $request) {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        switch ($request->input('actionform')) {
            case 'update':
                DB::beginTransaction();
                try {
                    $anggaran_tpb_cid = AnggaranTpb::find((int) $request->input('id_cid'));
                    $anggaran_tpb_noncid = AnggaranTpb::find((int) $request->input('id_noncid'));

                    if($anggaran_tpb_cid) {
                        $param_cid['anggaran'] = str_replace(',', '', $request->input('anggaran_cid'));
                        $anggaran_tpb_cid->update((array)$param_cid);
                        AnggaranTpbController::store_log($anggaran_tpb_cid->id, $anggaran_tpb_cid->status_id, $param_cid['anggaran'], 'RKA Revisi');
                    }

                    if($anggaran_tpb_noncid) {
                        $param_noncid['anggaran'] = str_replace(',', '', $request->input('anggaran_noncid'));
                        $anggaran_tpb_noncid->update((array)$param_noncid);
                        AnggaranTpbController::store_log($anggaran_tpb_noncid->id, $anggaran_tpb_noncid->status_id, $param_noncid['anggaran'], 'RKA Revisi');
                    }

                    DB::commit();
                    $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses ubah data',
                        'title' => 'Sukses'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    $result = [
                        'flag'  => 'warning',
                        'msg' => $e->getMessage(),
                        'title' => 'Gagal'
                    ];
                }

                break;
        }

        return response()->json($result);
    }

    public function deleteAll($parameter, $isSuperAdmin) {
        $id_perusahaan = $parameter['perusahaan_id'];
        $tahun = $parameter['tahun'] ?? (int) date('Y');
        $pilar_pembangunan = $parameter['pilar_pembangunan'];
        $tpb = $parameter['tpb'];

        $datatemp = DB::table('anggaran_tpbs as atpb')
                    ->select('atpb.perusahaan_id', 'pp.nama as pilar_pembangunan', 'tpbs.no_tpb')
                    ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
                    ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
                    ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
                    ->where('atpb.tahun', $tahun)
                    ->where('atpb.anggaran', '>=', 0)
                    ->where('atpb.status_id', 2) // hanya yg in progress saja
                    ->when($id_perusahaan, function($query) use ($id_perusahaan) {
                        return $query->where('atpb.perusahaan_id', $id_perusahaan);
                    })
                    ->when($pilar_pembangunan, function($query) use ($pilar_pembangunan) {
                        return $query->where('pp.nama', $pilar_pembangunan);
                    })
                    ->when($tpb, function($query) use ($tpb) {
                        return $query->where('tpbs.no_tpb', $tpb);
                    })
                    ->groupBy('atpb.perusahaan_id','pp.nama', 'tpbs.no_tpb')
                    ->get();
        
        // validasi availability untuk input data
        $isOkToInput = $this->checkRule();   
        $refEnable = $this->getReferensiEnable();

        foreach($datatemp as $temp) {
            $id_perusahaan = $temp->perusahaan_id;
            // cek enable input by superadmin
            $list_enable = DB::table('enable_input_by_superadmin')
                ->where('referensi_id', $refEnable?->id)
                ->where('tahun', $tahun)
                ->when($id_perusahaan, function($query) use ($id_perusahaan) {
                    return $query->where('perusahaan_id', $id_perusahaan);
                })
                ->get();
            
            $isEnableInputBySuperadmin = false;
            if($list_enable->count()) $isEnableInputBySuperadmin = true;

            if($isOkToInput || $isEnableInputBySuperadmin || $isSuperAdmin) {
                $this->deleteDataPerusahaan($temp->no_tpb, $temp->pilar_pembangunan, $temp->perusahaan_id, $tahun);
            }
        }

    }

    public function deleteDataPerusahaan($no_tpb, $nama_pilar, $id_perusahaan, $tahun) {
        $data_anggaran = DB::table('anggaran_tpbs as atpb')
                ->select('atpb.id as id_anggaran', 'tpbs.jenis_anggaran')
                ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
                ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
                ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
                ->where('tpbs.no_tpb', $no_tpb)
                ->where('pp.nama', $nama_pilar)
                ->where('atpb.perusahaan_id', $id_perusahaan)
                ->where('atpb.tahun', $tahun)
                ->get();
            
        $anggaran_tpb_cid = null;
        $anggaran_tpb_noncid = null;

        foreach($data_anggaran as $anggaran) {
            if($anggaran->jenis_anggaran == 'CID') $anggaran_tpb_cid = AnggaranTpb::find($anggaran->id_anggaran);
            if($anggaran->jenis_anggaran == 'non CID') $anggaran_tpb_noncid = AnggaranTpb::find($anggaran->id_anggaran);
        }
        
        if($anggaran_tpb_cid) {

            // get list id program
            $program = DB::table('target_tpbs')->where('anggaran_tpb_id', $anggaran_tpb_cid->id)->get();
            $idProgram = $program->pluck('id')->toArray();

            // get list id kegiatan
            $kegiatan = DB::table('kegiatans')->whereIn('target_tpb_id', $idProgram)->get();
            $idKegiatan = $kegiatan->pluck('id')->toArray();

            // delete data from table
            DB::table('kegiatan_realisasis')->whereIn('kegiatan_id', $idKegiatan)->delete();
            DB::table('kegiatans')->whereIn('target_tpb_id', $idProgram)->delete();
            DB::table('target_tpbs')->whereIn('id', $idProgram)->delete();
            $anggaran_tpb_cid->delete();
            
            // $anggaran_tpb_cid->update(['anggaran' => 0]);
            // AnggaranTpbController::store_log($anggaran_tpb_cid->id, $anggaran_tpb_cid->status_id, 0, 'RKA Revisi - Delete');   
        }

        if($anggaran_tpb_noncid) {

            // get list id program
            $program = DB::table('target_tpbs')->where('anggaran_tpb_id', $anggaran_tpb_noncid->id)->get();
            $idProgram = $program->pluck('id')->toArray();

            // get list id kegiatan
            $kegiatan = DB::table('kegiatans')->whereIn('target_tpb_id', $idProgram)->get();
            $idKegiatan = $kegiatan->pluck('id')->toArray();

            // delete data from table
            DB::table('kegiatan_realisasis')->whereIn('kegiatan_id', $idKegiatan)->delete();
            DB::table('kegiatans')->whereIn('target_tpb_id', $idProgram)->delete();
            DB::table('target_tpbs')->whereIn('id', $idProgram)->delete();
            $anggaran_tpb_noncid->delete();

            // $anggaran_tpb_noncid->update(['anggaran' => 0]);
            // AnggaranTpbController::store_log($anggaran_tpb_noncid->id, $anggaran_tpb_noncid->status_id, 0, 'RKA Revisi - Delete');   
        }
    }

    public function deleteByIdSelect($list_data, $isSuperAdmin) {
        // validasi availability untuk input data
        $isOkToInput = $this->checkRule();  
        $refEnable = $this->getReferensiEnable();      

        foreach($list_data as $data) {
            $no_tpb = $data['no_tpb'];
            $nama_pilar = str_replace("-", " ", $data['nama_pilar']);
            $id_perusahaan = $data['perusahaan'];
            $tahun = $data['tahun'];

            // cek enable input by superadmin
            $list_enable = DB::table('enable_input_by_superadmin')
                ->where('referensi_id', $refEnable?->id)
                ->where('tahun', $tahun)
                ->when($id_perusahaan, function($query) use ($id_perusahaan) {
                    return $query->where('perusahaan_id', $id_perusahaan);
                })
                ->get();
            
            $isEnableInputBySuperadmin = false;
            if($list_enable->count()) $isEnableInputBySuperadmin = true;

            if($isOkToInput || $isEnableInputBySuperadmin || $isSuperAdmin) {
                $this->deleteDataPerusahaan($no_tpb, $nama_pilar, $id_perusahaan, $tahun);    
            }
        }
    }

    public function deleteBySelect2(Request $request) {
        DB::beginTransaction();
        try {

            $id_users = \Auth::user()->id;
            $users = User::where('id', $id_users)->first();
            $isSuperAdmin = false;
            if (!empty($users->getRoleNames())) {
                foreach ($users->getRoleNames() as $v) {
                    if($v == 'Super Admin') {
                        $isSuperAdmin = true;
                    }
                }
            }

            $isDeleteAll = filter_var($request->input('isDeleteAll'), FILTER_VALIDATE_BOOLEAN);            
            if($isDeleteAll) {
                $parameterSelectAll = $request->input('parameterSelectAll');
                $this->deleteAll($parameterSelectAll, $isSuperAdmin);
            } else {
                $list_data = $request->input('anggaran_deleted');
                $this->deleteByIdSelect($list_data, $isSuperAdmin);
            }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses',
            ];  
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal',
            ];
        }
        return response()->json($result);
    }

    public function createRKA($perusahaan_id, $tahun)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $isSuperAdmin = false;
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Super Admin') {
                    $isSuperAdmin = true;
                }
            }
        }

        $versi = VersiPilar::whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', date('Y-m-d'))->first();
        $versi_pilar_id = $versi->id;

        $pilarMaster = DB::table('pilar_pembangunans')->select('nama', 'order_pilar')
            ->groupBy('nama', 'order_pilar')
            ->orderBy('order_pilar')
            ->get();

        $pilarTpbMaster = DB::table('relasi_pilar_tpbs as rpt')
            ->select('rpt.id', 'pp.nama as nama_pilar', 'tpbs.no_tpb', 
                'tpbs.nama as nama_tpb', 'pp.jenis_anggaran as ja_pilar',
                'tpbs.jenis_anggaran as ja_tpbs'
            )
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->where('versi_pilar_id', $versi->id)
            ->where('tpbs.is_active', true)
            ->where('pp.is_active', true)
            ->orderBy('pp.nama', 'asc')
            ->orderBy('tpb_id')
            ->get();

        $dataInput = [];
        foreach($pilarMaster as $pm) {
            $dataInput[$pm->nama] = [];
            $tempDataPilar = $pilarTpbMaster->where('nama_pilar', $pm->nama);
            foreach($tempDataPilar as $tdp) {

                $tempResult = DB::table('anggaran_tpbs')
                    ->where('relasi_pilar_tpb_id', $tdp->id)
                    ->where('perusahaan_id', $perusahaan_id)
                    ->where('tahun', $tahun)
                    ->first();

                if($tdp->ja_pilar == 'CID') {
                    $dataInput[$pm->nama][$tdp->no_tpb.' - '.$tdp->nama_tpb]['CID'] = $tempResult ? ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => $tempResult->anggaran] : ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => null];
                }

                if($tdp->ja_pilar == 'non CID') {
                    $dataInput[$pm->nama][$tdp->no_tpb.' - '.$tdp->nama_tpb]['non CID'] = $tempResult ? ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => $tempResult->anggaran] : ['relasi_pilar_tpb_id' => $tdp->id, 'anggaran' => null];
                }
                
            }
        }

        // validasi availability untuk input data
        $isOkToInput = $this->checkRule();
        $refEnable = $this->getReferensiEnable(); // yovi

        // cek enable input by superadmin
        $list_enable = DB::table('enable_input_by_superadmin')
            ->where('referensi_id', $refEnable?->id)
            ->where('tahun', $tahun)
            ->when($perusahaan_id, function($query) use ($perusahaan_id) {
                return $query->where('perusahaan_id', $perusahaan_id);
            })
            ->get();
        
        $isEnableInputBySuperadmin = false;
        if($list_enable->count()) $isEnableInputBySuperadmin = true;

        $anggaran = DB::table('anggaran_tpbs')->where('anggaran', '>=', 0)->where('perusahaan_id', $perusahaan_id)->where('tahun', $tahun)->get();
        $countStatus = $anggaran->groupBy('status_id')->map(function($data) {
            return $data->count();
        });

        $isFinish = ( isset($countStatus['4']) || isset($countStatus['1']) ) && !isset($countStatus['2']);
        

        return view(
            $this->__route . '.create_rka',
            [
                'pagetitle' => $this->pagetitle,
                'breadcrumb' => '',
                'perusahaan_id' => $perusahaan_id,
                'tahun' => $tahun,
                'actionform' => '-',
                'nama_perusahaan' => Perusahaan::find($perusahaan_id)->nama_lengkap,
                'isOkToInput' => $isOkToInput,
                'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
                'isFinish' => $isFinish,
                'dataInput' => $dataInput,
                'isSuperAdmin' => $isSuperAdmin
            ]
        );
    }

    public function checkRule() {
        // validasi availability untuk input data
        $menuRKA = DB::table('menus')->where('route_name', $this->pageRouteName)->first();
        $start = null;
        $end = null;
        $isOkToInput = true;
        if($menuRKA) {
            $periodeHasJenis = DB::table('periode_has_jenis')->where('jenis_laporan_id', $menuRKA->id)->first();
            if($periodeHasJenis) {
                $periodeLaporan = DB::table('periode_laporans')->where('is_active', 1)->where('id', $periodeHasJenis->periode_laporan_id)->first();
                if($periodeLaporan) {
                    $currentDate = new DateTime();                    
                    $start = new DateTime($periodeLaporan->tanggal_awal);
                    $end = new DateTime($periodeLaporan->tanggal_akhir);

                    if($currentDate < $start || $currentDate > $end) {
                        $isOkToInput = false;
                    }
                }
            }
        }

        return $isOkToInput;        
    }

    public function getReferensiEnable() {
        $data = DB::table('referensi_enable_input_by_superadmin')
            ->where('route_name', $this->pageRouteName)
            ->first();
        return $data;
    }

    public function verifikasiDataFinal(Request $request) {
        DB::beginTransaction();
        try {
            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');

            $allDataUpdated = AnggaranTpb::where('status_id', '=', 1) // verified
                            ->where('anggaran', '>=', 0)
                            ->where('tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('perusahaan_id', $id_bumn);
                            })
                            ->get();

            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    AnggaranTpb::where('id', $data->id)->update(['status_id' => 4]);
                    AnggaranTpbController::store_log($data->id, 1, $data->anggaran, 'RKA Revisi - Validated');
                }
            }                                            
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses set data validated',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);        
    }

    public function batalVerifikasiDataFinal(Request $request) {
        DB::beginTransaction();
        try {
            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');

            $allDataUpdated = AnggaranTpb::where('status_id', '=', 4) // validated
                            ->where('anggaran', '>=', 0)
                            ->where('tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('perusahaan_id', $id_bumn);
                            })
                            ->get();

            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    AnggaranTpb::where('id', $data->id)->update(['status_id' => 2]); // set to in progress
                    AnggaranTpbController::store_log($data->id, 2, $data->anggaran, 'RKA Revisi - Unvalidated');
                }
            }                                            
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses unvalidate data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);  
    }
}
