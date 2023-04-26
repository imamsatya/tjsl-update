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
            ->selectRaw('pumk_anggarans.*, perusahaans.id as perusahaan_id, perusahaans.nama_lengkap as nama_lengkap')
            ->leftJoin('perusahaans', 'perusahaans.id', '=', 'pumk_anggarans.bumn_id')
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
        $anggaran = $anggaran->get();
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
    public function create()
    {
        //
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
