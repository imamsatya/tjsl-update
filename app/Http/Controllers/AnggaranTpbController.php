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
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if ($v == 'Admin BUMN' || $v == 'Verifikator BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if ($v == 'Admin Stakeholder') {
                    $view_only = true;
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
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
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
            'perusahaans.nama_lengkap',
            'perusahaans.id',            
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid')
        )
            ->groupBy('anggaran_tpbs.perusahaan_id')
            ->groupBy('perusahaans.nama_lengkap')
            ->groupBy('perusahaans.id')
            ->get();
        $anggaran = $anggaran->select('*', 'anggaran_tpbs.id as id_anggaran','pilar_pembangunans.nama as pilar_nama', 'tpbs.nama as tpb_nama', DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'))
                ->orderBy('pilar_pembangunans.nama')
                ->orderBy('no_tpb')->get();        

        $anggaran = $anggaran->filter(function($condition) {
            return $condition->anggaran_cid > 0 || $condition->anggaran_noncid > 0;
        });

        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('is_active', true)->where('induk', 0)->orderBy('id', 'asc')->get(),
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
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function log_status(Request $request)
    {        
        $log_anggaran_tpb_cid = LogAnggaranTpb::where('anggaran_tpb_id', (int)$request->input('id_cid'))
            ->orderBy('created_at')
            ->get();

        $log_anggaran_tpb_noncid = LogAnggaranTpb::where('anggaran_tpb_id', (int)$request->input('id_noncid'))
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

        $pilars = DB::table('relasi_pilar_tpbs')
            ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun){
                $join->on('anggaran_tpbs.relasi_pilar_tpb_id', '=', 'relasi_pilar_tpbs.id')
                    ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                    ->where('anggaran_tpbs.tahun', $tahun);
            })
            ->where('versi_pilar_id', $versi->id)            
            ->get(['relasi_pilar_tpbs.id', 'pilar_pembangunans.nama as pilar_name', 'pilar_pembangunans.jenis_anggaran as pilar_jenis_anggaran', 'tpbs.nama as tpb_name', 'tpbs.jenis_anggaran as tpb_jenis_anggaran', 'anggaran_tpbs.anggaran', 'tpbs.no_tpb as tpb_no_tpb']);

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
        $pilars = $pilars->groupBy([
            'pilar_name',
            function ($item) {
                return $item->tpb_name;
            }
        ])->sortByDesc(null);

        // dd($pilars);


        return view(
            $this->__route . '.create2',
            [
                'pagetitle' => $this->pagetitle,
                'breadcrumb' => '',
                'pilars' => $pilars,
                'perusahaan_id' => $perusahaan_id,
                'tahun' => $tahun,
                'actionform' => '-',
                'nama_perusahaan' => Perusahaan::find($perusahaan_id)->nama_lengkap 
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
                $data = AnggaranTpb::create((array)$param);
                AnggaranTpbController::store_log($data->id, $param['status_id'], $param['anggaran'], 'RKA');
                Session::flash('success', "Berhasil Menyimpan Input Data RKA");
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

            return view($this->__route . '.edit', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'tpb' => Tpb::get(),
                'pilar' => PilarPembangunan::get(),
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'data' => $anggaran_tpb_cid,
                'data_cid' => $anggaran_tpb_cid,
                'data_noncid' => $anggaran_tpb_noncid
            ]);
        } catch (Exception $e) {
        }
    }

    public function verifikasiData(Request $request) {
        DB::beginTransaction();
        try {
            $list_id_anggaran = $request->input('anggaran_verifikasi');
            foreach($list_id_anggaran as $id_anggaran) {
                $data = AnggaranTpb::find((int) $id_anggaran);
                if($data && $data->status_id !== 1) {
                    $param['status_id'] = 1;
                    $data->update((array)$param);
                    AnggaranTpbController::store_log($data->id, $data->status_id, $data->anggaran, 'RKA Revisi - Verifikasi');
                }
                
            }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal verifikasi data',
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
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id');

        if ($request->perusahaan_id) {
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if ($request->tahun) {
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }

        if ($request->pilar_pembangunan_id) {
            $anggaran = $anggaran->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if ($request->tpb_id) {
            $anggaran = $anggaran->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
        }

        $anggaran = $anggaran->get();
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
}
