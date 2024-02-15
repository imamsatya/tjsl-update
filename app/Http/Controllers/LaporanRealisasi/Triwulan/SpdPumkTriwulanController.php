<?php

namespace App\Http\Controllers\LaporanRealisasi\Triwulan;

use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Kegiatan;
use App\Models\KegiatanRealisasi;
use App\Models\LogKegiatan;
use App\Models\SubKegiatan;
use App\Models\LogAnggaranTpb;
use App\Models\AnggaranTpb;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\PumkAnggaran;
use App\Models\LogPumkAnggaran;
use App\Models\VersiPilar;
use Session;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use DateTime;
use Carbon\Carbon;
class SpdPumkTriwulanController extends Controller
{

    public function __construct()
    {

        $this->__route = 'laporan_realisasi.triwulan.spd_pumk';
        $this->pagetitle = 'Sumber dan Penggunaan Dana PUMK - TRIWULAN';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        // $perusahaan_id = $request->perusahaan_id;
        $perusahaan_id = $request->perusahaan_id ? (Crypt::decryptString($request->perusahaan_id)) : null ;

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
        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->where('jenis_periode', 'standar')->where('is_visible', 'true')->orderBy('urutan')->get();
        $anggaran = DB::table('pumk_anggarans')
            ->selectRaw('pumk_anggarans.*,
             perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap,
             periode_laporans.id as periode_laporans_id, periode_laporans.nama as periode_laporans_nama')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'pumk_anggarans.bumn_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', '=', 'pumk_anggarans.periode_id')
            ->whereIn('periode_id', $periode->pluck('id')->toArray());
        if ($perusahaan_id) {

            $anggaran = $anggaran->where('bumn_id', $perusahaan_id);
        }

        $tahun = $request->tahun ?? date("Y");
        if ($tahun) {

            $anggaran = $anggaran->where('tahun', $tahun);
        }

        if ($request->status_spd) {


            $anggaran = $anggaran->where('status_id', $request->status_spd);
        }

        if ($request->periode_laporan) {


            $anggaran = $anggaran->where('periode_id', $request->periode_laporan);
        }
        $anggaran = $anggaran->orderBy('tahun', 'desc')->get();

        
        $totalIncome = $anggaran->sum('income_total');
        $totalOutcome = $anggaran->sum('outcome_total');
        $saldoAkhir = $anggaran->sum('saldo_akhir');
        //  dd($totalIncome."aaaa".$totalOutcome."aaaa".$saldoAkhir."aaaa");
        // dd($anggaran);
        // dd($anggaran[0]->nama_lengkap);

        $status = DB::table('statuss')->get();

           // validasi availability untuk input data Super Admin dan Admin TJSL
           $isOkToInput = false;

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
            'anggaran' => $anggaran,
            'status' => $status,
            'status_id' => $request->status_spd ?? '',
            'periode'=>$periode,
            'periode_id' => $request->periode_laporan ?? '',
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
    public function create($perusahaan_id, $tahun, $periode_id)
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
        
        $perusahaan_id = $perusahaan_id ? (Crypt::decryptString($perusahaan_id)) : null ;

        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->where('is_visible', 'true')->get();
        $selectedPeriode = DB::table('periode_laporans')->where('id', $periode_id)
        ->selectRaw("*, ((DATE(NOW()) BETWEEN tanggal_awal AND tanggal_akhir) OR periode_laporans.is_active = false) AS isOkToInput")
        ->first();
        $current = PumkAnggaran::where('bumn_id', $perusahaan_id)
            ->where('tahun', $tahun)
            ->where('periode_id', $periode_id)
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
                'periode'=>$periode,
                'periode_id' => $periode_id,
                'selectedPeriode' => $selectedPeriode,
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
     
        
        
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];
        // $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        // dd($periode_rka_id);
        
        switch ($request->input('actionform')) {
            case 'insert':

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
                    $param['periode_id'] = $request->periode_id;
                    //dana tersedia
                    $param['saldo_awal'] = $request->spdpumk_rka['saldo_awal'] == null || $request->spdpumk_rka['saldo_awal'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_awal']);
                    $param['income_mitra_binaan'] = $request->spdpumk_rka['pengembalian_mitra_binaan'] == null || $request->spdpumk_rka['pengembalian_mitra_binaan'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_mitra_binaan']);
                    $param['income_bumn_pembina_lain'] = $request->spdpumk_rka['pengembalian_bumn_penyalur'] == null || $request->spdpumk_rka['pengembalian_bumn_penyalur'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_bumn_penyalur']);
                    $param['income_jasa_adm_pumk'] = $request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == null || $request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_admin_pumk']);
                    $param['income_adm_bank'] = $request->spdpumk_rka['pendapatan_jasa_bank'] == null || $request->spdpumk_rka['pendapatan_jasa_bank'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_bank']);
                    $param['income_biaya_lainnya'] = $request->spdpumk_rka['pendapatan_biaya_lainnya'] == null || $request->spdpumk_rka['pendapatan_biaya_lainnya'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_biaya_lainnya']);
                    $param['income_total'] = $request->spdpumk_rka['total_dana_tersedia'] == null || $request->spdpumk_rka['total_dana_tersedia'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_tersedia']);
                    //dana disalurkan
                    $param['outcome_mandiri'] = $request->spdpumk_rka['penyaluran_pumk_mandiri'] == null || $request->spdpumk_rka['penyaluran_pumk_mandiri'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_mandiri']);
                    $param['outcome_kolaborasi_bumn'] = $request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == null || $request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_kolaborasi']);
                    $param['outcome_bumn_khusus'] = $request->spdpumk_rka['penyaluran_pumk_khusus'] == null || $request->spdpumk_rka['penyaluran_pumk_khusus'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_khusus']);
                    $param['outcome_bri'] = $request->spdpumk_rka['penyaluran_pumk_bri'] == null || $request->spdpumk_rka['penyaluran_pumk_bri'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_bri']);
                    $param['outcome_total'] = $request->spdpumk_rka['total_dana_disalurkan'] == null || $request->spdpumk_rka['total_dana_disalurkan'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_disalurkan']);
                    $param['saldo_akhir'] = $request->spdpumk_rka['saldo_akhir'] == null || $request->spdpumk_rka['saldo_akhir'] == 'NaN' ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_akhir']);
                    $param['created_by'] = \Auth::user()->id;
                    $param['created_at'] = now();
                    // if($param['saldo_awal'] == 0 || $param['saldo_awal'] == null || $param['saldo_awal'] == ""){
                    //     $param['status_id'] = DB::table('statuses')->where('nama','Unfilled')->pluck('id')->first();
                    // }else{
                    $param['status_id'] = DB::table('statuses')->where('nama', 'ilike', '%In Progress%')->pluck('id')->first();
                    // } 
                    // dd($param);
                    $data = PumkAnggaran::create($param);
                    
                    $log['pumk_anggaran_id'] = (int)$data->id;
                    $log['status_id'] = (int)$data->status_id;
                    $log['nilai_rka'] = (int)$data->saldo_awal;
                    $log['created_by_id'] = (int)$data->created_by;
                    $log['created_at'] = now();

                    SpdPumkTriwulanController::store_log($log);
                    
                    //Insert to Kegiatan Realisasi
                     
                    //periode_id ganti ke bulan
                    if ($request->periode_id == 1) {
                        //Maret
                        $bulan_id = 3; 
                    }
                    if ($request->periode_id == 2) {
                        //Juni
                        $bulan_id = 6;
                    }
                    if ($request->periode_id == 3) {
                        //Sept
                        $bulan_id = 9; 
                    }
                    if ($request->periode_id == 5) {
                        //Des
                        $bulan_id = 12;
                    }
                    //cari tpb 8 dulu
                    $tpb8cid_id = DB::table('tpbs')->where('no_tpb', 'TPB 8')->where('jenis_anggaran', 'CID')->first()?->id;
                    if (!$tpb8cid_id) {
                        DB::rollback();
                        $result = [
                            'flag'  => 'warning',
                            'msg' => 'Data TPB 8 belum tersedia ',
                            'title' => 'Gagal'
                        ];
                        return response()->json($result);
                        break;
                     
                    }
                    if ($tpb8cid_id) {
                        $anggaran_tpb8 = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
                        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                        ->where('tahun', $request->tahun)
                        ->where('perusahaan_id', $request->perusahaan_id)
                        ->where('tpb_id', $tpb8cid_id)
                        ->first();
                        //kalau tidak ada , maka tpb 8 dibuat samadengan spdpumk
                        if (!$anggaran_tpb8){
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
                            $relasi_pilar_tpb8_id = $pilarTpbMaster->where('no_tpb', 'TPB 8')->where('ja_tpbs', 'CID')->first();
                            
                            $param_tpb['perusahaan_id'] = $request->perusahaan_id;
                            $param_tpb['tahun'] = $request->tahun;
                            $param_tpb['user_id']  = \Auth::user()->id;
                            $param_tpb['relasi_pilar_tpb_id'] = $relasi_pilar_tpb8_id->id;
                            $param_tpb['anggaran'] = str_replace(',', '',  $param['outcome_total']);
                            $param_tpb['status_id'] = DB::table('statuss')->where('nama', 'In Progress')->first()->id;

                            $data = AnggaranTpb::create((array)$param_tpb);
                            SpdPumkTriwulanController::store_log_anggaran($data->id, $param_tpb['status_id'], $param_tpb['anggaran'], 'RKA');
                       
                            //get lagi
                            $anggaran_tpb8 = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
                            ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                            ->where('tahun', $request->tahun)
                            ->where('perusahaan_id', $request->perusahaan_id)
                            ->where('tpb_id', $tpb8cid_id)
                            ->first();
                  
                        }
                        if ($anggaran_tpb8) {
                            $target_tpb = DB::table('target_tpbs')->where('anggaran_tpb_id', $anggaran_tpb8->id)->where('program', 'Penyaluran PUMK')->first();
                            
                            if ($target_tpb) {
                                $cek_kegiatan = Kegiatan::join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id');
                
                                $cek_kegiatan = $cek_kegiatan
                                ->where('target_tpb_id',$target_tpb->id )
                                // ->where('kota_id',  $request->data['kota_kabupaten'])
                                ->where('kegiatan', 'Penyaluran PUMK')
                                ->where('bulan', $bulan_id)
                                ->where('tahun', $request->tahun)
                                ->first();

                                //kalau kegiatan ga ada (!= null) maka input kegiatan
                                if (!$cek_kegiatan) {
                                    $kegiatan = new Kegiatan();
                                    $kegiatan->target_tpb_id = $target_tpb->id;
                                    $kegiatan->kegiatan = 'Penyaluran PUMK';
                                    $kegiatan->provinsi_id = null;
                                    $kegiatan->kota_id = null;
                                    $kegiatan->indikator = null;
                                    $kegiatan->satuan_ukur_id = null;
                                    $kegiatan->anggaran_alokasi = $param['outcome_total'];
                                    $kegiatan->jenis_kegiatan_id = null;
                                    $kegiatan->keterangan_kegiatan = null;
                                    $kegiatan->save();
                            
                                    $kegiatanGroup = Kegiatan::where('kegiatan', $kegiatan->kegiatan)
                                    ->where('target_tpb_id',  $kegiatan->target_tpb_id)
                                    ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                                    ->orderBy('kegiatan_realisasis.bulan', 'desc')
                                    ->first();
                                    $kumulatif_anggaran =  $kegiatan->anggaran_alokasi;
                                    if ($kegiatanGroup) {
                                        $kumulatif_anggaran = $kumulatif_anggaran + $kegiatanGroup->anggaran_total;
                                    }

                                    $kegiatanRealisasi = new KegiatanRealisasi();
                                    $kegiatanRealisasi->kegiatan_id = $kegiatan->id;
                                    $kegiatanRealisasi->bulan = $bulan_id;
                                    $kegiatanRealisasi->tahun = $request->tahun;
                                    // target,realisasi -> null
                                    $kegiatanRealisasi->anggaran = $kegiatan->anggaran_alokasi;
                                    $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran;
                                    $kegiatanRealisasi->status_id = 2;//in progress
                                    $kegiatanRealisasi->save();
                        
                                    SpdPumkTriwulanController::store_log_kegiatan($kegiatanRealisasi->id,$kegiatanRealisasi->status_id);
                                }
                            }
                        }
                        
                    }

                    if ($validasi) {
                        DB::commit();
                        Session::flash('success', "Berhasil Menyimpan Sumber dan Penggunaan Dana PUMK - Realisasi");

                        $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses tambah data',
                            'title' => 'Sukses'
                        ];
                        return response()->json($result);
                        echo json_encode(['result' => true]);
                    } else {
                        DB::rollback();
                        $result = [
                            'flag'  => 'warning',
                            'msg' => 'Data Anggaran ' . $validasi_msg . ' sudah ada',
                            'title' => 'Gagal'
                        ];
                        return response()->json($result);
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    $result = [
                        'flag'  => 'warning',
                        'msg' => $e->getMessage(),
                        'title' => 'Gagal'
                    ];
                    return response()->json($result);
                }

                break;


            case 'update':
                
                DB::beginTransaction();
                try {
                    $current = PumkAnggaran::where('bumn_id', $request->perusahaan_id)
                        ->where('tahun', $request->tahun)
                        ->where('periode_id', $request->periode_id )
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
               

                    //sampai sini
                    $log['pumk_anggaran_id'] = (int)$current->id;
                    $log['status_id'] = (int)$current->status_id;
                    $log['nilai_rka'] = (int)$current->saldo_awal;
                    $log['created_by_id'] = (int)$current->updated_by;
                    $log['created_at'] = now();

                    SpdPumkTriwulanController::store_log($log);
                   

                    // dd('halo');
                     //Update to Kegiatan Realisasi
                     
                    //periode_id ganti ke bulan
                    if ($request->periode_id == 1) {
                        //Maret
                        $bulan_id = 3; 
                    }
                    if ($request->periode_id == 2) {
                        //Juni
                        $bulan_id = 6;
                       
                    }
                    if ($request->periode_id == 3) {
                        //Sept
                        $bulan_id = 9; 
                    }
                    if ($request->periode_id == 5) {
                        //Des
                        $bulan_id = 12;
                    }
                    
                    //cari tpb 8 dulu
                    $tpb8cid_id = DB::table('tpbs')->where('no_tpb', 'TPB 8')->where('jenis_anggaran', 'CID')->first()?->id;
                  
                    if ($tpb8cid_id) {
                        $anggaran_tpb8 = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
                        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                        ->where('tahun', $request->tahun)
                        ->where('perusahaan_id', $request->perusahaan_id)
                        ->where('tpb_id', $tpb8cid_id)
                        ->first();
                       
                        if ($anggaran_tpb8) {
                            $target_tpb = DB::table('target_tpbs')->where('anggaran_tpb_id', $anggaran_tpb8->id)->where('program', 'Penyaluran PUMK')->first();
                          
                            if ($target_tpb) {
                                $cek_kegiatan = Kegiatan::join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id');
                
                                $cek_kegiatan = $cek_kegiatan
                                ->where('target_tpb_id',$target_tpb->id )
                                // ->where('kota_id',  $request->data['kota_kabupaten'])
                                ->where('kegiatan', 'Penyaluran PUMK')
                                ->where('bulan', $bulan_id)
                                ->where('tahun', $request->tahun)
                                ->first();
                               
                                
                                //kalau kegiatan ga ada (!= null) maka input kegiatan
                                if ($cek_kegiatan) {
                                   $kegiatan = Kegiatan::where('id', $cek_kegiatan->kegiatan_id)->first();
                                   $kegiatan->anggaran_alokasi = $current->outcome_total ;
                                   $kegiatan->save();
                                  
                                    $kegiatanRealisasi = KegiatanRealisasi::where('kegiatan_id',$kegiatan->id )->first();
                                    $kegiatanRealisasi->anggaran = $kegiatan->anggaran_alokasi;
                                    $kegiatanRealisasi->save();
                               
                                                            
                        
                                    SpdPumkTriwulanController::store_log_kegiatan($kegiatanRealisasi->id,$kegiatanRealisasi->status_id);

                                    //cek ulang kumulatif anggaran versi sebelumnya
                                    $kegiatanGroupOld = Kegiatan::where('kegiatan', $kegiatan->kegiatan)
                                    ->where('target_tpb_id', $kegiatan->target_tpb_id)
                                    ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                                    ->orderBy('kegiatan_realisasis.bulan')
                                    ->get();
                                  
                                    $kumulatif_anggaran_old = 0;
                                    foreach ($kegiatanGroupOld as $key => $kegiatan) {
                                
                                        $kumulatif_anggaran_old = $kumulatif_anggaran_old + $kegiatan->anggaran;
                                        $kegiatanRealisasi = KegiatanRealisasi::where('id', $kegiatan->id )->first();
                                        $kegiatanRealisasi->anggaran = $kegiatan->anggaran_alokasi;
                                        $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran_old;
                                        $kegiatanRealisasi->save();
                                    }

                                    //cek ulang kumulatif anggaran versi baru
                                    $kegiatanGroupNew = Kegiatan::where('kegiatan',  $kegiatan->kegiatan)
                                    ->where('target_tpb_id', $kegiatan->target_tpb_id)
                                    ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                                    ->orderBy('kegiatan_realisasis.bulan')
                                    ->get();

                                    $kumulatif_anggaran_new = 0;
                                    foreach ($kegiatanGroupNew as $key => $kegiatan) {
                                
                                        $kumulatif_anggaran_new = $kumulatif_anggaran_new + $kegiatan->anggaran;
                                        $kegiatanRealisasi = KegiatanRealisasi::where('id', $kegiatan->id )->first();
                                        $kegiatanRealisasi->anggaran = $kegiatan->anggaran_alokasi;
                                        $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran_new;
                                        $kegiatanRealisasi->save();
                                    }
                                }
                            }
                        }
                        
                    }
                    // dd('sebelum commit');
                    DB::commit();
                    $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses ubah data',
                        'title' => 'Sukses'
                    ];
                    return response()->json($result);
                } catch (\Exception $e) {
                    DB::rollback();
                    $result = [
                        'flag'  => 'warning',
                        'msg' => $e->getMessage(),
                        'title' => 'Gagal'
                    ];
                    return response()->json($result);
                }

                break;
        }

        // return response()->json($result);
    }

    public static function store_log($log)
    {
        LogPumkAnggaran::insert($log);
    }

    public static function store_log_kegiatan($kegiatan_id, $status_id)
    {  
        $param['kegiatan_id'] = $kegiatan_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogKegiatan::create((array)$param);
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
        ->selectRaw('pumk_anggarans.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap, periode_laporans.nama as nama_periode')
        ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'pumk_anggarans.bumn_id')
        ->join('periode_laporans', 'periode_laporans.id', 'pumk_anggarans.periode_id')
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

            //delete kegiatan penyaluran pumk
            foreach ($requestIds as $key => $value) {
                $pumk_anggaran = PumkAnggaran::where('id', $value)->first();
            
                if ($pumk_anggaran->periode_id == 1) {
                    //Maret
                    $bulan_id = 3; 
                }
                if ($pumk_anggaran->periode_id == 2) {
                    //Juni
                    $bulan_id = 6;
                   
                }
                if ($pumk_anggaran->periode_id == 3) {
                    //Sept
                    $bulan_id = 9; 
                }
                if ($pumk_anggaran->periode_id == 5) {
                    //Des
                    $bulan_id = 12;
                }
                //cari tpb 8 dulu
                $tpb8cid_id = DB::table('tpbs')->where('no_tpb', 'TPB 8')->where('jenis_anggaran', 'CID')->first()?->id;
                 
                    if ($tpb8cid_id) {
                            $anggaran_tpb8 = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
                            ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                            ->where('tahun', $pumk_anggaran->tahun)
                            ->where('perusahaan_id', $pumk_anggaran->bumn_id)
                            ->where('tpb_id', $tpb8cid_id)
                            ->first();

                        
                        $target_tpb = DB::table('target_tpbs')->where('anggaran_tpb_id', $anggaran_tpb8->id)->where('program', 'Penyaluran PUMK')->first();

                        $cek_kegiatan = Kegiatan::join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id');
                        
                        $cek_kegiatan = $cek_kegiatan
                            ->where('target_tpb_id',$target_tpb->id )
                            ->where('kegiatan', 'Penyaluran PUMK')
                            ->where('bulan', $bulan_id)
                            ->where('tahun', $pumk_anggaran->tahun)
                            ->first();
                    }
             
                $kegiatan = Kegiatan::where('id', $cek_kegiatan->kegiatan_id)->first();
                $kegiatan_realisasis = KegiatanRealisasi::where('kegiatan_id', $cek_kegiatan->kegiatan_id)->first();
                //delete cek kegiatan
                $kegiatan_realisasis->delete();
                $kegiatan->delete();

                //cek ulang kumulatif anggaran versi sebelumnya
                $kegiatanGroupOld = Kegiatan::where('kegiatan', $cek_kegiatan->kegiatan)
                ->where('target_tpb_id', $target_tpb->id)
                ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->orderBy('kegiatan_realisasis.bulan')
                ->get();
    
                $kumulatif_anggaran_old = 0;
                foreach ($kegiatanGroupOld as $key => $kegiatanOld) {
               
                    $kumulatif_anggaran_old = $kumulatif_anggaran_old + $kegiatanOld->anggaran;
                    $kegiatanRealisasi = KegiatanRealisasi::where('id', $kegiatanOld->id )->first();
                    $kegiatanRealisasi->anggaran = $kegiatanOld->anggaran_alokasi;
                    $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran_old;
                    $kegiatanRealisasi->save();
                }
              
            }

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

        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->get();
        
        if(Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin TJSL')){
            $anggaran = DB::table('pumk_anggarans')
            ->selectRaw('pumk_anggarans.*,
             perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap,
             periode_laporans.id as periode_laporans_id, periode_laporans.nama as periode_laporans_nama, 
             TRUE AS isoktoinput'
             )
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'pumk_anggarans.bumn_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', '=', 'pumk_anggarans.periode_id')
            ->whereIn('periode_id', $periode->pluck('id')->toArray());
         }
         else{
            $anggaran = DB::table('pumk_anggarans')
            ->selectRaw('pumk_anggarans.*,
             perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap,
             periode_laporans.id as periode_laporans_id, periode_laporans.nama as periode_laporans_nama, 
             CASE
                WHEN CURRENT_DATE BETWEEN periode_laporans.tanggal_awal AND periode_laporans.tanggal_akhir
                OR periode_laporans.is_active = FALSE
                THEN TRUE
             ELSE FALSE
             END AS isoktoinput'
             )
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'pumk_anggarans.bumn_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', '=', 'pumk_anggarans.periode_id')
            ->whereIn('periode_id', $periode->pluck('id')->toArray());
         }
        
       
        if ($request->perusahaan_id) {

            $anggaran = $anggaran->where('bumn_id', $request->perusahaan_id);
        }


        if ($request->tahun) {

            $anggaran = $anggaran->where('tahun', $request->tahun);
        }

        if ($request->status_spd) {


            $anggaran = $anggaran->where('status_id', $request->status_spd);
        }

        if ($request->periode_laporan) {


            $anggaran = $anggaran->where('periode_id', $request->periode_laporan);
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
                    $log['created_by_id'] = (int)$current->updated_by;
                    $log['created_at'] = now();
                    SpdPumkTriwulanController::store_log($log);

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
                    $log['created_by_id'] = (int)$current->updated_by;
                    $log['created_at'] = now();

                    SpdPumkTriwulanController::store_log($log);

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses membatalkan verifikasi data',
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
                    $log['created_by_id'] = (int)$current->updated_by;
                    $log['created_at'] = now();

                    SpdPumkTriwulanController::store_log($log);

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses  validasi data',
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

    public function batalFinalVerifikasiData(Request $request) {
        // dd($request->selectedData);

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
                    $log['created_by_id'] = (int)$current->updated_by;
                    $log['created_at'] = now();

                    SpdPumkTriwulanController::store_log($log);

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses  membatalkan validasi data',
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

    public function store_log_anggaran($anggaran_tpb_id, $status_id, $anggaran, $keterangan)
    {
        $param['anggaran'] = $anggaran;
        $param['anggaran_tpb_id'] = $anggaran_tpb_id;
        $param['status_id'] = $status_id;
        $param['keterangan'] = $keterangan;
        $param['user_id'] = \Auth::user()->id;
        LogAnggaranTpb::create((array)$param);
    }

}
