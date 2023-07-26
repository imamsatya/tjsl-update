<?php

namespace App\Http\Controllers\RencanaKerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Menu;
use DB;
use Session;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;
use Carbon\Carbon;
use Auth;

class TbleController extends Controller
{

    public function __construct()
    {

        $this->__route = 'rencana_kerja.tble';
        $this->pagetitle = 'Tanda Bukti Lapor Elektronik - RKA';
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
                if ($v == 'Admin BUMN' || $v == 'Verifikator BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if ($v == 'Admin Stakeholder') {
                    $view_only = true;
                }
            }
        }
        $status = DB::table('statuses')->get();
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        $laporan_manajemen = DB::table('laporan_manajemens')->selectRaw('laporan_manajemens.*, perusahaans.id as perusahaan_id, perusahaans.nama_lengkap as nama_lengkap')
        ->leftJoin('perusahaans', 'perusahaans.id', '=', 'laporan_manajemens.perusahaan_id')->where('periode_laporan_id', $periode_rka_id);
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
            'tahun' => ($request->tahun ?? Carbon::now()->year),
            'perusahaan' => Perusahaan::where('induk', 0)->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'status' => $status,
            'status_id' => $request->status_laporan ?? ''
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

    public function datatable(Request $request)
    {
        // dd($request);
      
        $currentYear = date('Y');
        $perusahaan = Perusahaan::select('id', 'nama_lengkap')
            ->where('induk', 0)
            ->get();
           $newarray = [];
           foreach ($perusahaan as $key => $perusahaan_row) {
            for ($i = 2020; $i <= $currentYear; $i++) {    
                $item = $perusahaan_row;  
                $item['tahun'] = 'Rencana Kerja '.$i;
                array_push($newarray, $item);
            }
        }
        
            
        // dd($perusahaan->slice(0, 4));
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
       
        try {
            return datatables()->of($newarray)
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
                ->rawColumns(['id',  'nama_lengkap', 'tahun', 'action'])
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

    public function cetakDataById( $id, $tahun) {
        
        $perusahaan = Perusahaan::where('id', $id)->first();
        $menu_anggaran = Menu::where('route_name', 'anggaran_tpb.rka')->first()->label;
        $menu_program = Menu::where('route_name', 'rencana_kerja.program.index2')->first()->label;
        $menu_spdpumk = Menu::where('route_name', 'rencana_kerja.spdpumk_rka.index')->first()->label;
        $menu_laporan_manajemen = Menu::where('route_name', 'rencana_kerja.laporan_manajemen.index')->first()->label;
        // dd($menu_program);
        $data =  [
            [
                'jenis_laporan' => $menu_anggaran,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => null,
                'status' => null,
            ],
            [
                'jenis_laporan' => $menu_program,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => null,
                'status' => null,
            ],
            [
                'jenis_laporan' => $menu_spdpumk,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => null,
                'status' => null,
            ],
            [
                'jenis_laporan' => $menu_laporan_manajemen,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => null,
                'status' => null,
            ],
        ];
    
        //cek angaran
        $anggaran = DB::table('anggaran_tpbs')->where('perusahaan_id', $id)->where('tahun', $tahun)->orderBy('updated_at', 'desc')->get();

        //cek ada atau tidak
        if($anggaran?->first()){
            $data[0]['tanggal_update'] = $anggaran->first()->updated_at;
            $data[0]['status'] = "Finish";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($anggaran?->where('status_id', 2)->first()) {
            $data[0]['tanggal_update'] = $anggaran->where('status_id', 2)->first()->updated_at;
            $data[0]['status'] = "In Progress";
        }
      
        //cek program
        $anggaran = DB::table('anggaran_tpbs')->where('perusahaan_id', $id)->where('tahun', $tahun)->orderBy('target_tpbs.updated_at', 'desc')->join('target_tpbs', 'target_tpbs.anggaran_tpb_id', '=', 'anggaran_tpbs.id')->get();
         //cek ada atau tidak
         if($anggaran?->first()){
            $data[1]['tanggal_update'] = $anggaran->first()->updated_at;
            $data[1]['status'] = "Finish";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($anggaran?->where('status_id', 2)->first()) {
            $data[1]['tanggal_update'] = $anggaran->where('status_id', 2)->first()->updated_at;
            $data[1]['status'] = "In Progress";
        }

        //cek spd pumk
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        $spd_pumk = DB::table('pumk_anggarans')->where('bumn_id', $id)->where('tahun', $tahun)->where('periode_id', $periode_rka_id)->get();
       
        if($spd_pumk?->first()){
            $data[2]['tanggal_update'] = $spd_pumk->first()->updated_at;
            $data[2]['status'] = "Finish";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($spd_pumk?->where('status_id', 2)->first()) {
            $data[2]['tanggal_update'] = $spd_pumk->where('status_id', 2)->first()->updated_at;
            $data[2]['status'] = "In Progress";
        }
        //cek laporan manajemen rka
        $laporan_manajemen = DB::table('laporan_manajemens')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('periode_laporan_id', $periode_rka_id)->get();
        if($laporan_manajemen?->first() ){
            $data[3]['tanggal_update'] = $laporan_manajemen->first()->updated_at;
            $data[3]['status'] = "Finish";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress/unfilled
        if ($laporan_manajemen?->whereIn('status_id', [2, 3])->first()) {
            $data[3]['tanggal_update'] =$laporan_manajemen->whereIn('status_id', [2, 3])->first()->status_id === 2 ? $laporan_manajemen->whereIn('status_id', [2, 3])->first()->updated_at : null;
            $data[3]['status'] = $laporan_manajemen->whereIn('status_id', [2, 3])->first()->status_id === 2 ? 'In Progress' : null;
        }
        $tanggal_cetak = Carbon::now()->locale('id_ID')->isoFormat('D MMMM YYYY');
        $user = Auth::user();
        

        $pdf = PDF::loadView('rencana_kerja.tble.detailtemplate', 
        ['data' => $data,
         'perusahaan' => $perusahaan, 
         'tanggal_cetak' => $tanggal_cetak,
         'user' => $user])->setPaper('a4', 'portrait');
        return  $pdf->download($perusahaan->nama_lengkap.'-rka-'.$tahun.'.pdf');
    }

}
