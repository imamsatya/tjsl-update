<?php

namespace App\Http\Controllers\RencanaKerja;

use App\Models\User;
use App\Models\Perusahaan;
use App\Models\LogTargetTpb;
use App\Models\TargetTpb;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\PumkAnggaran;
use App\Models\LogPumkAnggaran;
use Session;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DateTime;
use Carbon\Carbon;

class SpdPumkRkaController extends Controller
{

    public function __construct()
    {

        $this->__route = 'rencana_kerja.spdpumk_rka';
        $this->pagetitle = 'SPD PUMK - RKA';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // if ($request->perusahaan_id) {
        //     dd($request->perusahaan_id);
        // }

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
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        $anggaran = DB::table('pumk_anggarans')
            ->selectRaw('pumk_anggarans.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'pumk_anggarans.bumn_id')
            ->where('periode_id', $periode_rka_id);
        if ($request->perusahaan_id) {

            $anggaran = $anggaran->where('bumn_id', $request->perusahaan_id);
        }


        if ($request->tahun) {

            $anggaran = $anggaran->where('tahun', $request->tahun);
        }

        if ($request->status_spd) {


            $anggaran = $anggaran->where('status_id', $request->status_spd);
        }
        
        $pumk_anggaran = $anggaran->orderBy('tahun', 'desc')->get();
        $totalIncome = $pumk_anggaran->sum('income_total');
        $totalOutcome = $pumk_anggaran->sum('outcome_total');
        $saldoAkhir = $pumk_anggaran->sum('saldo_akhir');
        // dd($pumk_anggaran->where('income_total', '<', 0));
        // dd($totalIncome."aaaa".$totalOutcome."aaaa".$saldoAkhir."aaaa");
        // dd($anggaran);
        // dd($anggaran[0]->nama_lengkap);

        $status = DB::table('statuss')->get();

         // validasi availability untuk input data
         $menuSPDPUMK_RKA = DB::table('menus')->where('route_name', 'rencana_kerja.spdpumk_rka.index')->first();
         $start = null;
         $end = null;
         $isOkToInput = true;
         if($menuSPDPUMK_RKA) {
            
             $periodeHasJenis = DB::table('periode_has_jenis')->where('jenis_laporan_id', $menuSPDPUMK_RKA->id)->first();
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

         if(Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin TJSL')){
            $isOkToInput = true;
         }

       


        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Rencana Kerja - SPD PUMK - RKA',
            // 'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'tahun' => ($request->tahun ?? Carbon::now()->year),
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'anggaran' => $pumk_anggaran,
            'status' => $status,
            'status_id' => $request->status_spd ?? '',
            'isOkToInput' => $isOkToInput,
            'totalIncome' => $totalIncome,
            'totalOutcome' => $totalOutcome,
            'saldoAkhir' => $saldoAkhir
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($perusahaan_id, $tahun)
    {
        //
        // if (count($current) > 0) {
        //     $actionform = 'update';
        // } else {
        //     $actionform = 'insert';
        // }
        $admin_bumn = false;
        // if (!empty($users->getRoleNames())) {
        //     foreach ($users->getRoleNames() as $v) {
        //         if ($v == 'Admin BUMN') {
        //             $admin_bumn = true;
        //         }
        //     }
        // }
        $currentDate = date('Y-m-d');
        $periode = DB::table('periode_laporans')
                ->selectRaw("*, ((DATE(NOW()) BETWEEN tanggal_awal AND tanggal_akhir) OR periode_laporans.is_active = false) AS isOkToInput")
                ->where('nama', 'RKA')
                ->first();
        $current = PumkAnggaran::where('bumn_id', $perusahaan_id)
            ->where('tahun', $tahun)
            ->where('periode_id', $periode->id)
            ->first();



        if ($current) {
            $actionform = 'update';
        } else {
            $actionform = 'insert';
        }

        // validasi availability untuk input data Super Admin dan Admin TJSL
        $isOkToInput = false;

        if(Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin TJSL')){
           $isOkToInput = true;
        }

        return view(
            $this->__route . '.create',
            [
                'pagetitle' => $this->pagetitle,
                'breadcrumb' => '',
                'data' => $current,
                'perusahaan_id' => $perusahaan_id,
                'tahun' => $tahun,
                'actionform' => $actionform,
                // 'pilar' => PilarPembangunan::get(),
                // 'versi_pilar_id' => $versi_pilar_id,
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'admin_bumn' => $admin_bumn,
                'periode' => $periode,
                'isOkToInput' => $isOkToInput
                // 'perusahaan_id' => $perusahaan_id,
                // 'data' => $anggaran_tpb
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request->spdpumk_rka['saldo_awal']);
        // dd($request);
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        // dd($periode_rka_id);

        // $anggaran_tpb = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
        // ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        // ->where('tahun', $request->tahun)
        // ->where('perusahaan_id', $request->perusahaan_id)
        // ->where('tpb_id', $request->data['tpb_id'])
        // ->first();

        switch ($request->input('actionform')) {
            case 'insert':
                DB::beginTransaction();

                try {
                    $validasi = true;
                    // $perusahaan_id = \Auth::user()->id_bumn;
                    $perusahaan_id = $request->perusahaan_id;
                    // $param = $request->all();
                    // $param = $request->except(['actionform', 'id', '_token']);
                    // if ($request->bumn_id == null) {
                    //     $param['bumn_id'] = $perusahaan_id;
                    // }
                    $param['tahun'] = $request->tahun;
                    $param['bumn_id'] = $request->perusahaan_id;
                    $param['periode_id'] = $periode_rka_id;
                    //dana tersedia
                    $param['saldo_awal'] = ($request->spdpumk_rka['saldo_awal'] == null || $request->spdpumk_rka['saldo_awal'] == 'NaN') ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_awal']);
                    $param['income_mitra_binaan'] = ($request->spdpumk_rka['pengembalian_mitra_binaan'] == null || $request->spdpumk_rka['pengembalian_mitra_binaan'] == 'NaN') ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_mitra_binaan']);
                    $param['income_bumn_pembina_lain'] = ($request->spdpumk_rka['pengembalian_bumn_penyalur'] == null || $request->spdpumk_rka['pengembalian_bumn_penyalur'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_bumn_penyalur']);
                    $param['income_jasa_adm_pumk'] = ($request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == null || $request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_admin_pumk']);
                    $param['income_adm_bank'] = ($request->spdpumk_rka['pendapatan_jasa_bank'] == null || $request->spdpumk_rka['pendapatan_jasa_bank'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_bank']);
                    $param['income_biaya_lainnya'] = ($request->spdpumk_rka['pendapatan_biaya_lainnya'] == null || $request->spdpumk_rka['pendapatan_biaya_lainnya'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_biaya_lainnya']);
                    $param['income_total'] = ($request->spdpumk_rka['total_dana_tersedia'] == null || $request->spdpumk_rka['total_dana_tersedia'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_tersedia']);
                    //dana disalurkan
                    $param['outcome_mandiri'] = ($request->spdpumk_rka['penyaluran_pumk_mandiri'] == null || $request->spdpumk_rka['penyaluran_pumk_mandiri'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_mandiri']);
                    $param['outcome_kolaborasi_bumn'] = ($request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == null || $request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_kolaborasi']);
                    $param['outcome_bumn_khusus'] = ($request->spdpumk_rka['penyaluran_pumk_khusus'] == null || $request->spdpumk_rka['penyaluran_pumk_khusus'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_khusus']);
                    $param['outcome_bri'] = ($request->spdpumk_rka['penyaluran_pumk_bri'] == null || $request->spdpumk_rka['penyaluran_pumk_bri'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_bri']);
                    $param['outcome_total'] = ($request->spdpumk_rka['total_dana_disalurkan'] == null || $request->spdpumk_rka['total_dana_disalurkan'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_disalurkan']);
                    $param['saldo_akhir'] = ($request->spdpumk_rka['saldo_akhir'] == null || $request->spdpumk_rka['saldo_akhir'] == 'NaN' )? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_akhir']);
                    $param['created_by'] = \Auth::user()->id;
                    $param['created_at'] = now();
                    // if($param['saldo_awal'] == 0 || $param['saldo_awal'] == null || $param['saldo_awal'] == ""){
                    //     $param['status_id'] = DB::table('statuses')->where('nama','Unfilled')->pluck('id')->first();
                    // }else{
                    $param['status_id'] = DB::table('statuses')->where('nama', 'ilike', '%In Progress%')->pluck('id')->first();
                    // } 
                    
                    $data = PumkAnggaran::create($param);
                    $log['pumk_anggaran_id'] = (int)$data->id;
                    $log['status_id'] = (int)$data->status_id;
                    $log['nilai_rka'] = (int)$data->saldo_awal;
                    $log['created_by_id'] = (int)$data->created_by;
                    $log['created_at'] = now();

                    SpdPumkRkaController::store_log($log);

                    //Insert Program TPB 8
                    //cari tpb 8 dulu
                    $tpb8cid_id = DB::table('tpbs')->where('no_tpb', 'TPB 8')->where('jenis_anggaran', 'CID')->first()?->id;
                    
                    //cek suatu perusahaan sudah ada program di tpb 8 dengan nama "Penyaluran PUMK" atau belum
                    if ($tpb8cid_id) {
                        $anggaran_tpb8 = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
                        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                        ->where('tahun', $request->tahun)
                        ->where('perusahaan_id', $request->perusahaan_id)
                        ->where('tpb_id', $tpb8cid_id)
                        ->first();
                        if ($anggaran_tpb8) {
                            $target_tpb = DB::table('target_tpbs')->where('anggaran_tpb_id', $anggaran_tpb8->id)->where('program', 'Penyaluran PUMK')->first();

                            //kalau $target_tpb null maka insert data baru
                            if (!$target_tpb ) {

                                $target_tpb = new TargetTpb();
                                $target_tpb->anggaran_tpb_id =  $anggaran_tpb8->id;
                                $target_tpb->program = 'Penyaluran PUMK';
                                $target_tpb->unit_owner = null;
                                $target_tpb->core_subject_id = null;
                                $target_tpb->tpb_id = $tpb8cid_id;
                                $target_tpb->anggaran_alokasi = $param['outcome_total'];
                                $target_tpb->status_id = 2; // In Progress
                        
                                //kriteria
                                $target_tpb->kriteria_program_prioritas = true;
                                $target_tpb->kriteria_program_csv = true;
                                $target_tpb->kriteria_program_umum = true;
    
                                $target_tpb->pelaksanaan_program = 'Mandiri';
                                $target_tpb->mitra_bumn_id = null;    
                                $target_tpb->save();
                        
                                SpdPumkRkaController::store_log_targetTPB8($target_tpb->id,$target_tpb->status_id);
                            }
                        }
                        
                       
                    }
                    if ($validasi) {
                        DB::commit();
                        Session::flash('success', "Berhasil Menyimpan Sumber dan Penggunaan Dana PUMK - RKA");

                        $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses tambah data',
                            'title' => 'Sukses'
                        ];
                        echo json_encode(['result' => true]);
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
                    $current = PumkAnggaran::where('bumn_id', $request->perusahaan_id)
                        ->where('tahun', $request->tahun)
                        ->where('periode_id', $periode_rka_id )
                        ->first();
                    
                    //dana tersedia
                    $current->saldo_awal = $request->spdpumk_rka['saldo_awal'] == null || $request->spdpumk_rka['saldo_awal'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_awal']);
                    $current->income_mitra_binaan = $request->spdpumk_rka['pengembalian_mitra_binaan'] == null || $request->spdpumk_rka['pengembalian_mitra_binaan'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_mitra_binaan']);
                    $current->income_bumn_pembina_lain = $request->spdpumk_rka['pengembalian_bumn_penyalur'] == null || $request->spdpumk_rka['pengembalian_bumn_penyalur'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_bumn_penyalur']);
                    $current->income_jasa_adm_pumk = $request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == null || $request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_admin_pumk']);
                    $current->income_adm_bank = $request->spdpumk_rka['pendapatan_jasa_bank'] == null || $request->spdpumk_rka['pendapatan_jasa_bank'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_bank']);
                    $current->income_biaya_lainnya = $request->spdpumk_rka['pendapatan_biaya_lainnya'] == null || $request->spdpumk_rka['pendapatan_biaya_lainnya'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_biaya_lainnya']);
                    $current->income_total = $request->spdpumk_rka['total_dana_tersedia'] == null || $request->spdpumk_rka['total_dana_tersedia'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_tersedia']);
                    //dana disalurkan
                    $current->outcome_mandiri = $request->spdpumk_rka['penyaluran_pumk_mandiri'] == null || $request->spdpumk_rka['penyaluran_pumk_mandiri'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_mandiri']);
                    $current->outcome_kolaborasi_bumn = $request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == null || $request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_kolaborasi']);
                    $current->outcome_bumn_khusus = $request->spdpumk_rka['penyaluran_pumk_khusus'] == null || $request->spdpumk_rka['penyaluran_pumk_khusus'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_khusus']);
                    $current->outcome_bri = $request->spdpumk_rka['penyaluran_pumk_bri'] == null || $request->spdpumk_rka['penyaluran_pumk_bri'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_bri']);
                    $current->outcome_total = $request->spdpumk_rka['total_dana_disalurkan'] == null || $request->spdpumk_rka['total_dana_disalurkan'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_disalurkan']);
                    $current->saldo_akhir = $request->spdpumk_rka['saldo_akhir'] == null || $request->spdpumk_rka['saldo_akhir'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_akhir']);
                    $current->updated_at = now();
                    $current->updated_by = \Auth::user()->id;
                    $current->save();
                  
                    $log['pumk_anggaran_id'] = (int)$current->id;
                    $log['status_id'] = (int)$current->status_id;
                    $log['nilai_rka'] = (int)$current->saldo_awal;
                    $log['created_by_id'] = (int)$current->updated_by;
                    $log['created_at'] = now();

                    SpdPumkRkaController::store_log($log);

                    DB::commit();
                    $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses ubah data',
                        'title' => 'Sukses'
                    ];
                    echo json_encode(['result' => true]);
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

        // return response()->json($result);
    }

    public static function store_log_targetTPB8($target_tpb_id, $status_id)
    {  
        $param['target_tpb_id'] = $target_tpb_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogTargetTpb::create((array)$param);
    }

    public static function store_log($log)
    {
        LogPumkAnggaran::insert($log);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $pumk_anggaran = DB::table('pumk_anggarans')
        ->selectRaw('pumk_anggarans.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap')
        ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'pumk_anggarans.bumn_id')
        ->where('pumk_anggarans.id', $request->id)->first();
        // dd($pumk_anggaran);

        return view($this->__route . '.show', [
            'pagetitle' => $this->pagetitle,
            'pumk_anggaran' => $pumk_anggaran
            
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestIds = $request->selectedData;
            PumkAnggaran::whereIn('id', $requestIds)->delete();

            Session::flash('success', "Berhasil menghapus SPD PUMK yang dipilih");

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

    public function datatable(Request $request)
    {
        // dd($request);

        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        $anggaran = DB::table('pumk_anggarans')
            ->selectRaw('pumk_anggarans.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'pumk_anggarans.bumn_id')
            ->where('periode_id', $periode_rka_id);
        if ($request->perusahaan_id) {

            $anggaran = $anggaran->where('bumn_id', $request->perusahaan_id);
        }


        if ($request->tahun) {

            $anggaran = $anggaran->where('tahun', $request->tahun);
        }

        if ($request->status_spd) {

            $anggaran = $anggaran->where('status_id', $request->status_spd);
        }

        $pumk_anggaran = $anggaran->orderBy('tahun', 'desc')->get();
        // dd($pumk_anggaran);
        try {
            return datatables()->of($pumk_anggaran)
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    // $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';
                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data '  . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="' . $id . '" data-nama="' . $row->nama . '" data-toggle="tooltip" title="Hapus data ' . $row->nama . '"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['id',  'tahun', 'nama_lengkap', 'income_total', 'outcome_total', 'saldo_akhir', 'status_id', 'action'])
                ->toJson();
        } catch (Exception $e) {
            return response([
                'draw'            => 0,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }
    }

    public function log_status(Request $request)
    {
        
        $log = LogPumkAnggaran::select('log_pumk_anggarans.*', 'users.name AS user', 'statuses.nama AS status')
            ->leftjoin('users', 'users.id', '=', 'log_pumk_anggarans.created_by_id')
            ->leftjoin('statuses', 'statuses.id', '=', 'log_pumk_anggarans.status_id')
            ->where('pumk_anggaran_id', (int)$request->input('id'))
            ->orderBy('created_at')
            ->get();
            // dd($log);
        return view($this->__route . '.log_status', [
            'pagetitle' => 'Log Status',
            'log' => $log
        ]);
    }

    public function verifikasiData(Request $request) {
        // dd($request->selectedData);

        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = PumkAnggaran::where('id', $selectedData)->first();
                if ($current->status_id == 2) {
                    $current->status_id = 1;
                    $current->save();

                    $log['pumk_anggaran_id'] = (int)$current->id;
                    $log['status_id'] = (int)$current->status_id;
                    $log['nilai_rka'] = (int)$current->saldo_awal;
                    $log['created_by_id'] = (int)\Auth::user()->id;
                    $log['created_at'] = now();

                    SpdPumkRkaController::store_log($log);

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses melakukan complete data',
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

    public function batalVerifikasiData(Request $request) {
        // dd($request->selectedData);

        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = PumkAnggaran::where('id', $selectedData)->first();
                if ($current->status_id == 1) {
                    $current->status_id = 2;
                    $current->save();

                    $log['pumk_anggaran_id'] = (int)$current->id;
                    $log['status_id'] = (int)$current->status_id;
                    $log['nilai_rka'] = (int)$current->saldo_awal;
                    $log['created_by_id'] = (int)\Auth::user()->id;
                    $log['created_at'] = now();

                    SpdPumkRkaController::store_log($log);

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses membatalkan complete data',
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

    public function finalVerifikasiData(Request $request) {
        // dd($request->selectedData);

        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = PumkAnggaran::where('id', $selectedData)->first();
                if ($current->status_id == 1) {
                    $current->status_id = 4;
                    $current->save();

                    $log['pumk_anggaran_id'] = (int)$current->id;
                    $log['status_id'] = (int)$current->status_id;
                    $log['nilai_rka'] = (int)$current->saldo_awal;
                    $log['created_by_id'] = (int)\Auth::user()->id;
                    $log['created_at'] = now();

                    SpdPumkRkaController::store_log($log);

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses verifikasi data',
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

    public function batalFinalVerifikasiData(Request $request){
        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = PumkAnggaran::where('id', $selectedData)->first();
                if ($current->status_id == 4) {
                    $current->status_id = 2;
                    $current->save();

                    $log['pumk_anggaran_id'] = (int)$current->id;
                    $log['status_id'] = (int)$current->status_id;
                    $log['nilai_rka'] = (int)$current->saldo_awal;
                    $log['created_by_id'] = (int)\Auth::user()->id;
                    $log['created_at'] = now();

                    SpdPumkRkaController::store_log($log);

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses verifikasi data',
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
