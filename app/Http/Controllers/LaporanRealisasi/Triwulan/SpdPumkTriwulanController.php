<?php

namespace App\Http\Controllers\LaporanRealisasi\Triwulan;

use App\Models\User;
use App\Models\Perusahaan;
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
        $perusahaan_id = $request->perusahaan_id;

        $admin_bumn = false;
        $view_only = false;
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if ($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if ($v == 'Admin Stakeholder') {
                    $view_only = true;
                }
            }
        }
        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->get();
        $anggaran = DB::table('pumk_anggarans')
            ->selectRaw('pumk_anggarans.*,
             perusahaans.id as perusahaan_id, perusahaans.nama_lengkap as nama_lengkap,
             periode_laporans.id as periode_laporans_id, periode_laporans.nama as periode_laporans_nama')
            ->leftJoin('perusahaans', 'perusahaans.id', '=', 'pumk_anggarans.bumn_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', '=', 'pumk_anggarans.periode_id')
            ->whereIn('periode_id', $periode->pluck('id')->toArray());
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
        $anggaran = $anggaran->orderBy('tahun', 'desc')->get();
        // dd($anggaran);
        // dd($anggaran[0]->nama_lengkap);

        $status = DB::table('statuss')->get();



        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Rencana Kerja - SPD PUMK - RKA',
            // 'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'tahun' => ($request->tahun ?? ''),
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'anggaran' => $anggaran,
            'status' => $status,
            'status_id' => $request->status_spd ?? '',
            'periode'=>$periode,
            'periode_id' => $request->periode_laporan ?? '',
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
        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->get();
        $current = PumkAnggaran::where('bumn_id', $perusahaan_id)
            ->where('tahun', $tahun)
            ->where('periode_id', $periode_id)
            ->first();



        if ($current) {
            $actionform = 'update';
        } else {
            $actionform = 'insert';
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
                    $param['saldo_awal'] = $request->spdpumk_rka['saldo_awal'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_awal']);
                    $param['income_mitra_binaan'] = $request->spdpumk_rka['pengembalian_mitra_binaan'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_mitra_binaan']);
                    $param['income_bumn_pembina_lain'] = $request->spdpumk_rka['pengembalian_bumn_penyalur'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_bumn_penyalur']);
                    $param['income_jasa_adm_pumk'] = $request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_admin_pumk']);
                    $param['income_adm_bank'] = $request->spdpumk_rka['pendapatan_jasa_bank'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_bank']);
                    $param['income_biaya_lainnya'] = $request->spdpumk_rka['pendapatan_biaya_lainnya'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_biaya_lainnya']);
                    $param['income_total'] = $request->spdpumk_rka['total_dana_tersedia'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_tersedia']);
                    //dana disalurkan
                    $param['outcome_mandiri'] = $request->spdpumk_rka['penyaluran_pumk_mandiri'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_mandiri']);
                    $param['outcome_kolaborasi_bumn'] = $request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_kolaborasi']);
                    $param['outcome_bumn_khusus'] = $request->spdpumk_rka['penyaluran_pumk_khusus'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_khusus']);
                    $param['outcome_bri'] = $request->spdpumk_rka['penyaluran_pumk_bri'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_bri']);
                    $param['outcome_total'] = $request->spdpumk_rka['total_dana_disalurkan'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_disalurkan']);
                    $param['saldo_akhir'] = $request->spdpumk_rka['saldo_akhir'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_akhir']);
                    $param['created_by'] = \Auth::user()->id;
                    $param['created_at'] = now();
                    // if($param['saldo_awal'] == 0 || $param['saldo_awal'] == null || $param['saldo_awal'] == ""){
                    //     $param['status_id'] = DB::table('statuses')->where('nama','Unfilled')->pluck('id')->first();
                    // }else{
                    $param['status_id'] = DB::table('statuses')->where('nama', 'ilike', '%In Progress%')->pluck('id')->first();
                    // } 
                    // dd($param);
                    $data = PumkAnggaran::create($param);

                    if ($validasi) {
                        DB::commit();
                        Session::flash('success', "Berhasil Menyimpan Sumber dan Penggunaan Dana PUMK - Realisasi");

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
                        ->where('periode_id', $request->periode_id )
                        ->first();

                    //dana tersedia
                    $current->saldo_awal = $request->spdpumk_rka['saldo_awal'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_awal']);
                    $current->income_mitra_binaan = $request->spdpumk_rka['pengembalian_mitra_binaan'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_mitra_binaan']);
                    $current->income_bumn_pembina_lain = $request->spdpumk_rka['pengembalian_bumn_penyalur'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pengembalian_bumn_penyalur']);
                    $current->income_jasa_adm_pumk = $request->spdpumk_rka['pendapatan_jasa_admin_pumk'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_admin_pumk']);
                    $current->income_adm_bank = $request->spdpumk_rka['pendapatan_jasa_bank'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_jasa_bank']);
                    $current->income_biaya_lainnya = $request->spdpumk_rka['pendapatan_biaya_lainnya'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['pendapatan_biaya_lainnya']);
                    $current->income_total = $request->spdpumk_rka['total_dana_tersedia'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_tersedia']);
                    //dana disalurkan
                    $current->outcome_mandiri = $request->spdpumk_rka['penyaluran_pumk_mandiri'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_mandiri']);
                    $current->outcome_kolaborasi_bumn = $request->spdpumk_rka['penyaluran_pumk_kolaborasi'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_kolaborasi']);
                    $current->outcome_bumn_khusus = $request->spdpumk_rka['penyaluran_pumk_khusus'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_khusus']);
                    $current->outcome_bri = $request->spdpumk_rka['penyaluran_pumk_bri'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['penyaluran_pumk_bri']);
                    $current->outcome_total = $request->spdpumk_rka['total_dana_disalurkan'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['total_dana_disalurkan']);
                    $current->saldo_akhir = $request->spdpumk_rka['saldo_akhir'] == null ? 0 : preg_replace('/[^-0-9]/', '', $request->spdpumk_rka['saldo_akhir']);
                    $current->updated_at = now();
                    $current->updated_by = \Auth::user()->id;
                    $current->save();

                    $log['pumk_anggaran_id'] = (int)$current->id;
                    $log['status_id'] = (int)$current->status_id;
                    $log['nilai_rka'] = (int)$current->saldo_awal;
                    $log['created_by_id'] = (int)$current->updated_by;
                    $log['created_at'] = now();

                    SpdPumkTriwulanController::store_log($log);

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

        // return response()->json($result);
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
    public function show($id)
    {
        //
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

    public function datatable(Request $request)
    {
        // dd($request);

        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->get();
        $anggaran = DB::table('pumk_anggarans')
            ->selectRaw('pumk_anggarans.*,
             perusahaans.id as perusahaan_id, perusahaans.nama_lengkap as nama_lengkap,
             periode_laporans.id as periode_laporans_id, periode_laporans.nama as periode_laporans_nama')
            ->leftJoin('perusahaans', 'perusahaans.id', '=', 'pumk_anggarans.bumn_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', '=', 'pumk_anggarans.periode_id')
            ->whereIn('periode_id', $periode->pluck('id')->toArray());
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

}