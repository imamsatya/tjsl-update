<?php

namespace App\Http\Controllers\LaporanRealisasi\Bulanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\PilarPembangunan;
use App\Models\Tpb;
use App\Models\AnggaranTpb;
use App\Models\VersiPilar;
use App\Models\CoreSubject;
use App\Models\TargetTpb;
use App\Models\LogTargetTpb;
use App\Models\Bulan;
use App\Models\JenisKegiatan;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\SatuanUkur;
use App\Models\PumkBulan;
use App\Models\LogPumkBulan;

use Datatables;
use DB;
use Session;
class PumkController extends Controller
{

    public function __construct()
    {

        $this->__route = 'laporan_realisasi.bulanan.pumk';
        $this->pagetitle = 'Laporan Realisasi PUMK';
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
        $perusahaan_id = $request->perusahaan_id ?? 1;

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

        if($request->kriteria_program) {
            // dd(true);
            $kriteria_program = explode(',', $request->kriteria_program);
            foreach ($kriteria_program as $key => $kriteria) {
                # harusnya pakai orWhere tapi error di index
                if ($kriteria == 'prioritas') {
                    $anggaran = $anggaran->where('target_tpbs.kriteria_program_prioritas', true);
                } elseif ($kriteria == 'csv') {
                    $anggaran = $anggaran->where('target_tpbs.kriteria_program_csv', true);
                } elseif ($kriteria == 'umum') {
                    $anggaran = $anggaran->where('target_tpbs.kriteria_program_umum', true);
                }
            }
        }

        $bulan = $request->bulan ??  (int) date('n');

        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            // 'anggaran' => $anggaran,
            // 'anggaran_pilar' => $anggaran_pilar,
            // 'anggaran_bumn' => $anggaran_bumn,
            'pilar' => PilarPembangunan::select(DB::raw('DISTINCT ON (nama) *'))->where('is_active', true)->orderBy('nama')->orderBy('id')->get(),
            'tpb' => Tpb::select(DB::raw('DISTINCT ON (no_tpb) *'))->orderBy('no_tpb')->orderBy('id')->get(),
            'bulan' => Bulan::all(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'bulan_id' =>  $bulan,
            // 'jenis_anggaran' => $jenis_anggaran,
            // 'kriteria_program' => $kriteria_program ?? [],
            // 'pilar_pembangunan_id' => $request->pilar_pembangunan,
            'tpb_id' => $request->tpb,
            'pilar_pembangunan_id' => $request->pilar_pembangunan,
            // 'tpb_id' => $request->tpb,
            'view_only' => $view_only,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $perusahaan = Perusahaan::where('id', $request->perusahaan_id)->first();
            if ($request->actionform == 'edit') {
                $pumk_bulan = PumkBulan::where('id', $request->bulanan_pumk_id)->first();
                $perusahaan = Perusahaan::where('id', $pumk_bulan->perusahaan_id)->first();
            }
            // $data = TargetTpb::find((int)$request->input('program'));
            // $anggaran_tpbs = AnggaranTpb::find($data->anggaran_tpb_id ?? 1);
            // $perusahaan_id = $anggaran_tpbs->perusahaan_id;
            // $tahun = $anggaran_tpbs->tahun;
            // $tpbs_temp = Tpb::find($data->tpb_id);
            
            // dd($request);
            return view($this->__route . '.create', [
                'pagetitle' => $this->pagetitle,
                'actionform' => $request->actionform ?? 'insert',
                'bulan' => Bulan::all(),
                'tahun' => ($request->tahun ? $request->tahun : date('Y')),
                'perusahaan_id' => $request->perusahaan_id,
                'pumk_bulan_id' => $request->bulanan_pumk_id ?? null,
                'pumk_bulan' => $pumk_bulan ?? null ,
                'perusahaan' => $perusahaan
                // 'tpb' => DB::table('tpbs')->select('*')->whereIn('id', function($query) use($perusahaan_id, $tahun) {
                //     $query->select('relasi_pilar_tpbs.tpb_id as id')
                //         ->from('anggaran_tpbs')
                //         ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id','=','anggaran_tpbs.relasi_pilar_tpb_id')
                //         ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                //         ->where('anggaran_tpbs.tahun', $tahun);
                // })->where('tpbs.jenis_anggaran', $tpbs_temp->jenis_anggaran)->get(),
                // 'core_subject' => CoreSubject::get(),
                // 'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                // 'data' => $data,
                // 'id_program' => $request->input('program'),
                // 'tahun' => $tahun,
                // 'perusahaan_id' => $perusahaan_id
            ]);
        } catch (Exception $e) {
        }
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

       
       $cekData = PumkBulan::where('bulan_id', $request->bulan_id_create)
       ->where('tahun', $request->tahun_create)
       ->where('perusahaan_id', $request->perusahaan_id)
       ->first();
       
        if ($cekData) {
            $request->actionform ='edit';
        }
       if ($request->actionform === 'insert') {
           
        DB::beginTransaction();
        try {
            
            $pumk_bulan = new PumkBulan();
            $pumk_bulan->perusahaan_id = $request->perusahaan_id;
            $pumk_bulan->status_id = 2;//In Progress
            $pumk_bulan->tahun = $request->tahun_create;
            $pumk_bulan->bulan_id = $request->bulan_id_create;
            $pumk_bulan->nilai_penyaluran = $request->nilai_penyaluran;
            $pumk_bulan->nilai_penyaluran_melalui_bri = $request->nilai_penyaluran_melalui_bri;
            $pumk_bulan->nilai_penyaluran_melalui_pegadaian = $request->nilai_penyaluran_melalui_pegadaian;
            $pumk_bulan->jumlah_mb = $request->jumlah_mb;
            $pumk_bulan->jumlah_mb_naik_kelas = $request->jumlah_mb_naik_kelas;

            // $pumk_bulan->kolektabilitas_lancar = $request->kolektabilitas_lancar;
            // $pumk_bulan->kolektabilitas_lancar_jumlah_mb = $request->kolektabilitas_lancar_jumlah_mb;
            // $pumk_bulan->kolektabilitas_kurang_lancar = $request->kolektabilitas_kurang_lancar;
            // $pumk_bulan->kolektabilitas_kurang_lancar_jumlah_mb = $request->kolektabilitas_kurang_lancar_jumlah_mb;
            // $pumk_bulan->kolektabilitas_diragukan = $request->kolektabilitas_diragukan;
            // $pumk_bulan->kolektabilitas_diragukan_jumlah_mb = $request->kolektabilitas_diragukan_jumlah_mb;
            // $pumk_bulan->kolektabilitas_macet = $request->kolektabilitas_macet;
            // $pumk_bulan->kolektabilitas_macet_jumlah_mb = $request->kolektabilitas_macet_jumlah_mb;
            // $pumk_bulan->kolektabilitas_pinjaman_bermasalah = $request->kolektabilitas_pinjaman_bermasalah;
            // $pumk_bulan->kolektabilitas_pinjaman_bermasalah_jumlah_mb = $request->kolektabilitas_pinjaman_bermasalah_jumlah_mb;
            $pumk_bulan->save();

            PumkController::store_log($pumk_bulan->id,$pumk_bulan->status_id);
            DB::commit();
                Session::flash('success', "Berhasil Menyimpan Data Kegiatan");
                $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses tambah data',
                            'title' => 'Sukses'
                ];
                echo json_encode(['result' => true, 'data' => $result]);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                throw $th;
            }
       
       }

       if ($request->actionform === 'edit') {
        DB::beginTransaction();

        try {
            if ($request->pumk_bulan_id == null) {
                $pumk_bulan = $cekData;
            }

            if ($request->pumk_bulan_id != null) {
                # code...
                $pumk_bulan = PumkBulan::where('id', $request->pumk_bulan_id)->first();
            }
            
            // dd($request->tahun_create);
            
            
            $pumk_bulan->tahun = $request->tahun_create;
            $pumk_bulan->bulan_id = $request->bulan_id_create;
            $pumk_bulan->nilai_penyaluran = $request->nilai_penyaluran;
            $pumk_bulan->nilai_penyaluran_melalui_bri = $request->nilai_penyaluran_melalui_bri;
            $pumk_bulan->nilai_penyaluran_melalui_pegadaian = $request->nilai_penyaluran_melalui_pegadaian;
            $pumk_bulan->jumlah_mb = $request->jumlah_mb;
            $pumk_bulan->jumlah_mb_naik_kelas = $request->jumlah_mb_naik_kelas;

            // $pumk_bulan->kolektabilitas_lancar = $request->kolektabilitas_lancar;
            // $pumk_bulan->kolektabilitas_lancar_jumlah_mb = $request->kolektabilitas_lancar_jumlah_mb;
            // $pumk_bulan->kolektabilitas_kurang_lancar = $request->kolektabilitas_kurang_lancar;
            // $pumk_bulan->kolektabilitas_kurang_lancar_jumlah_mb = $request->kolektabilitas_kurang_lancar_jumlah_mb;
            // $pumk_bulan->kolektabilitas_diragukan = $request->kolektabilitas_diragukan;
            // $pumk_bulan->kolektabilitas_diragukan_jumlah_mb = $request->kolektabilitas_diragukan_jumlah_mb;
            // $pumk_bulan->kolektabilitas_macet = $request->kolektabilitas_macet;
            // $pumk_bulan->kolektabilitas_macet_jumlah_mb = $request->kolektabilitas_macet_jumlah_mb;
            // $pumk_bulan->kolektabilitas_pinjaman_bermasalah = $request->kolektabilitas_pinjaman_bermasalah;
            // $pumk_bulan->kolektabilitas_pinjaman_bermasalah_jumlah_mb = $request->kolektabilitas_pinjaman_bermasalah_jumlah_mb;
            $pumk_bulan->save();

            PumkController::store_log($pumk_bulan->id,$pumk_bulan->status_id);
            DB::commit();
                Session::flash('success', "Berhasil Menyimpan Data Kegiatan");
                $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses mengubah data',
                            'title' => 'Sukses'
                ];
                echo json_encode(['result' => true, 'data' => $result]);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                throw $th;
            }
       }
      
        

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

    public function delete(Request $request) {
        
      
        
         
       
        DB::beginTransaction();
        try {
            $requestIds = $request->data_deleted;
            PumkBulan::whereIn('id', $requestIds)->delete();
           
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
        
        // $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        // $laporan_manajemen = DB::table('laporan_manajemens')->selectRaw('laporan_manajemens.*, perusahaans.id as perusahaan_id, perusahaans.nama_lengkap as nama_lengkap')
        // ->leftJoin('perusahaans', 'perusahaans.id', '=', 'laporan_manajemens.perusahaan_id')->where('periode_laporan_id', $periode_rka_id)->where('perusahaans.induk', 0);
        // dd($request->bulan);
        $perusahaan_id = $request->perusahaan_id ?? 1;
        $bulan = $request->bulan ?? (int) date('n');
        $tahun = $request->tahun ?? date('Y');
        $pumk_bulan = DB::table('pumk_bulans')
        ->where('perusahaan_id', $perusahaan_id)
        ->where('tahun', $tahun)
        ->join('bulans', 'bulans.id', '=', 'pumk_bulans.bulan_id')
        ->join('statuses', 'statuses.id', '=', 'pumk_bulans.status_id')
        ->orderBy('bulans.id')
        ->select(
            'pumk_bulans.*',
            'statuses.nama as status',
            'bulans.nama as bulan'
        );
    
        if ($bulan !== 'all') {
            $pumk_bulan->where('bulan_id', $bulan);
        }
       

   

    
        // if ($request->pilar_pembangunan_id) {

        //     $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        // }

        // if ($request->tpb_id) {

        //     $kegiatan = $kegiatan->where('tpbs.id', $request->tpb_id);
        // }

        // if ($request->program_id) {

        //     $kegiatan = $kegiatan->where('target_tpbs.id', $request->program_id);
        // }

        // if ($request->jenis_kegiatan) {

        //     $kegiatan = $kegiatan->where('jenis_kegiatans.id', $request->jenis_kegiatan);
        // }

        $pumk_bulan = $pumk_bulan->get();
    //    dd($pumk_bulan);
        try {
            return datatables()->of($pumk_bulan)
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
                ->rawColumns(['id', 'bulan_id',  'action'])
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

    public static function store_log($pumk_bulan_id, $status_id)
    {  
        $param['pumk_bulan_id'] = $pumk_bulan_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogPumkBulan::create((array)$param);
    }

    public function log_status(Request $request)
    {
        $pumk_bulan = PumkBulan::where('id', (int)$request->input('id'))->first();

        $log = LogPumkBulan::select('log_pumk_bulans.*', 'users.name AS user', 'statuses.nama AS status')
            ->leftjoin('users', 'users.id', '=', 'log_pumk_bulans.user_id')
            ->leftjoin('statuses', 'statuses.id', '=', 'log_pumk_bulans.status_id')
            ->where('pumk_bulan_id', $pumk_bulan->id)
            ->orderBy('created_at')
            ->get();

        return view($this->__route . '.log_status', [
            'pagetitle' => 'Log Status',
            'log' => $log
        ]);
    }

    public function kolektabilitas_view(Request $request)
    {   
        $pumk_bulan = PumkBulan::join('bulans', 'bulans.id', '=', 'pumk_bulans.bulan_id')
            ->join('statuses', 'statuses.id', '=', 'pumk_bulans.status_id')
            ->select(
                'pumk_bulans.*',
                'statuses.nama as status',
                'bulans.nama as bulan'
            )
            ->where('pumk_bulans.id', (int)$request->input('id'))
            ->first();
        return view($this->__route . '.kolektabilitas', [
            'pagetitle' => 'Data Kolektabilitas',
            'pumk_bulan' => $pumk_bulan
        ]);
    }

    public function verifikasiData(Request $request) {

        
        DB::beginTransaction();
        try {
            foreach ($request->pumk_verifikasi as $key => $pumk_id) {
                $pumk = PumkBulan::where('id', $pumk_id)->first();
                if ($pumk->status_id == 2) {
                    $pumk->status_id = 1;
                    $pumk->save();
                    PumkController::store_log($pumk->id,$pumk->status_id);
                }
                
                
               
                
        }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses Verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal Verifikasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function batalVerifikasiData(Request $request) {

        
        DB::beginTransaction();
        try {
            foreach ($request->pumk_verifikasi as $key => $pumk_id) {
                $pumk = PumkBulan::where('id', $pumk_id)->first();
                if ($pumk->status_id == 1) {
                    $pumk->status_id = 2;
                    $pumk->save();
                    PumkController::store_log($pumk->id,$pumk->status_id);
                }
                
               
                
        }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses membatalkan verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal membatalkan verifikasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function finalVerifikasiData(Request $request) {

        
        DB::beginTransaction();
        try {
            foreach ($request->pumk_verifikasi as $key => $pumk_id) {
                $pumk = PumkBulan::where('id', $pumk_id)->first();
                if ($pumk->status_id == 1) {
                    $pumk->status_id = 4;
                    $pumk->save();
                    PumkController::store_log($pumk->id,$pumk->status_id);
                }
                
               
                
        }
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
                'msg' => 'Gagal validasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function batalFinalVerifikasiData(Request $request) {

        
        DB::beginTransaction();
        try {
            foreach ($request->pumk_verifikasi as $key => $pumk_id) {
                $pumk = PumkBulan::where('id', $pumk_id)->first();
                if ($pumk->status_id == 4) {
                    $pumk->status_id = 2;
                    $pumk->save();
                    PumkController::store_log($pumk->id,$pumk->status_id);
                }
                
               
                
        }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses membatalkan validasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal membatalkan validasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function getData(Request $request){
        
        $data = PumkBulan::where('bulan_id', $request->bulan_id)
        ->where('tahun', $request->tahun)
        ->where('perusahaan_id', $request->perusahaan_id)
        ->first();

        return $data;
    }
}
