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
                if ($v == 'Admin BUMN') {
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

        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('is_active', true)->where('induk', 0)->orderBy('id', 'asc')->get(),
            // 'anggaran' => $anggaran,
            // 'anggaran_pilar' => $anggaran_pilar,
            // 'anggaran_bumn' => $anggaran_bumn,
            'pilar' => PilarPembangunan::select(DB::raw('DISTINCT ON (nama) *'))->where('is_active', true)->orderBy('nama')->orderBy('id')->get(),
            'tpb' => Tpb::select(DB::raw('DISTINCT ON (no_tpb) *'))->orderBy('no_tpb')->orderBy('id')->get(),
            'bulan' => Bulan::all(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => ($request->tahun ? $request->tahun : date('Y')),
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
            // $data = TargetTpb::find((int)$request->input('program'));
            // $anggaran_tpbs = AnggaranTpb::find($data->anggaran_tpb_id ?? 1);
            // $perusahaan_id = $anggaran_tpbs->perusahaan_id;
            // $tahun = $anggaran_tpbs->tahun;
            // $tpbs_temp = Tpb::find($data->tpb_id);
            return view($this->__route . '.create', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'insert',
                'bulan' => Bulan::all(),
                'tahun' => ($request->tahun ? $request->tahun : date('Y')),
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
}
