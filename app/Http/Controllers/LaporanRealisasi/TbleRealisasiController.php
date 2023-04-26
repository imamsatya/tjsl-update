<?php

namespace App\Http\Controllers\LaporanRealisasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perusahaan;
use DB;
use Session;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TbleRealisasiController extends Controller
{

    public function __construct()
    {

        $this->__route = 'laporan_realisasi.tble';
        $this->pagetitle = 'Tanda Bukti Lapor Elektronik - Realisasi';
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
        $status = DB::table('statuses')->get();
        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->get();
        $laporan_manajemen = DB::table('laporan_manajemens')->selectRaw('laporan_manajemens.*, perusahaans.id as perusahaan_id, perusahaans.nama_lengkap as nama_lengkap')
        ->leftJoin('perusahaans', 'perusahaans.id', '=', 'laporan_manajemens.perusahaan_id')->whereIn('periode_laporan_id', $periode->pluck('id')->toArray());
        if ($request->perusahaan_id) {

            $laporan_manajemen = $laporan_manajemen->where('perusahaan_id', $request->perusahaan_id);
        }


        if ($request->tahun) {

            $laporan_manajemen = $laporan_manajemen->where('tahun', $request->tahun);
        }

        if ($request->status_laporan) {

            $laporan_manajemen = $laporan_manajemen->where('status_id', $request->status_laporan);
        }

        $laporan_manajemen = $laporan_manajemen->get();
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Rencana Kerja - Tanda Bukti Lapor Elektronik - RKA',
            // 'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'tahun' => ($request->tahun ?? ''),
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'status' => $status,
            'status_id' => $request->status_laporan ?? '',
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
