<?php

namespace App\Http\Controllers\LaporanRealisasi;

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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

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
        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->where('jenis_periode', 'standar')->orderBy('urutan')->get();
        $laporan_manajemen = DB::table('laporan_manajemens')->selectRaw('laporan_manajemens.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap')
        ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'laporan_manajemens.perusahaan_id')->whereIn('periode_laporan_id', $periode->pluck('id')->toArray());
        if ($request->perusahaan_id) {

            $laporan_manajemen = $laporan_manajemen->where('perusahaan_id', $request->perusahaan_id);
        }


        if ($request->tahun) {

            $laporan_manajemen = $laporan_manajemen->where('tahun', $request->tahun);
        }

        if ($request->periode_id) {

            $laporan_manajemen = $laporan_manajemen->where('periode_laporan_id', $request->periode_id);
        }

        $laporan_manajemen = $laporan_manajemen->get();
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Rencana Kerja - Tanda Bukti Lapor Elektronik - RKA',
            // 'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'tahun' => ($request->tahun ?? Carbon::now()->year),
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'status' => $status,
            'status_id' => $request->status_laporan ?? '',
            'periode'=>$periode,
            'periode_id' => $request->periode_id ?? '',
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
            ->get();
           $newarray = [];
           foreach ($perusahaan as $key => $perusahaan_row) {
            for ($i = 2020; $i <= $currentYear; $i++) {    
                $item = $perusahaan_row;  
                $item['tahun'] = 'Rencana Kerja '.$i;
                $newarray[] = $item;
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
                ->rawColumns(['id',  'nama_lengkap', 'tahunx', 'action'])
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

    public function cetakDataById( $id, $tahun, $periode_id) {
        $perusahaan = Perusahaan::where('id', $id)->first();
        $kegiatan = DB::table('kegiatans')
                    ->join('kegiatan_realisasis', function($join) use ( $tahun) {
                        $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                            ->where('kegiatan_realisasis.tahun', $tahun);
                    })
                    ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
                    ->join('anggaran_tpbs', function($join) use ($id, $tahun) {
                        $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                            ->where('anggaran_tpbs.perusahaan_id', $id)
                            ->where('anggaran_tpbs.tahun', $tahun);
                    })
                    ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                    ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
                    ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
                    ->join('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
                    ->join('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
                    ->join('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
                    ->join('bulans', 'bulans.id', '=', 'kegiatan_realisasis.bulan')
                    ->orderBy('kegiatans.updated_at', 'desc')
                    ->select(
                        'kegiatans.*',
                        'kegiatan_realisasis.bulan as kegiatan_realisasi_bulan',
                        'kegiatan_realisasis.tahun as kegiatan_realisasi_tahun',
                        'kegiatan_realisasis.anggaran as kegiatan_realisasi_anggaran',
                        'kegiatan_realisasis.anggaran_total as kegiatan_realisasi_anggaran_total',
                        'kegiatan_realisasis.status_id as kegiatan_realisasi_status_id',
                        'target_tpbs.program as target_tpb_program',
                        'jenis_kegiatans.nama as jenis_kegiatan_nama',
                        'provinsis.nama as provinsi_nama',
                        'kotas.nama as kota_nama',
                        'anggaran_tpbs.id as anggaran_tpb_id',
                        'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
                        'tpbs.id as tpb_id',
                        'tpbs.jenis_anggaran',
                        'satuan_ukur.nama as satuan_ukur_nama',
                        'bulans.nama as bulan_nama'
                    )
                    ->get();

        $spd_pumk = DB::table('pumk_anggarans')->where('bumn_id', $id)->where('tahun', $tahun)->where('periode_id', $periode_id)->orderBy('updated_at', 'desc')->get();
        $periode_laporan = DB::table('periode_laporans')->where('id', $periode_id)->first();

        //Menu
        $menu_kegiatan = Menu::where('route_name', 'laporan_realisasi.bulanan.kegiatan.index')->first()->label;
        $menu_pumk = Menu::where('route_name', 'laporan_realisasi.bulanan.pumk.index')->first()->label;
        $menu_laporan_manajemen = Menu::where('route_name', 'laporan_realisasi.triwulan.laporan_manajemen.index')->first()->label; 
        $menu_spdpumk = Menu::where('route_name', 'laporan_realisasi.triwulan.spd_pumk.index')->first()->label;
        $first_two_characters = substr($periode_laporan->nama, 0, 2);
        if ($first_two_characters === 'TW') {
           $data = [];
           if ($periode_laporan->nama ==  "TW I"){
            $jumlah_bulan = 3;
           }
           if ($periode_laporan->nama ==  "TW II"){
            $jumlah_bulan = 6;
           }
           if ($periode_laporan->nama ==  "TW III"){
            $jumlah_bulan = 9;
           }
           if ($periode_laporan->nama ==  "TW IV"){
            $jumlah_bulan = 12;
           }

           //cek Kegiatan
           
           for ($i=0; $i < $jumlah_bulan; $i++) { 
                $bulan_id = $i+1;
                $bulan_nama = DB::table('bulans')->where('id', $bulan_id)->first()->nama;
                $kegiatan_bulan = $kegiatan?->where('kegiatan_realisasi_bulan', $bulan_id)->first();
                
                $data_kegiatan_bulan['jenis_laporan'] =  $menu_kegiatan;
                $data_kegiatan_bulan['periode'] = $periode_laporan->nama.'-'.$tahun;
                $data_kegiatan_bulan['bulan'] = $bulan_nama ;
                $data_kegiatan_bulan['tanggal_update'] = $kegiatan_bulan?->updated_at ?? 'Unfilled';
                $data_kegiatan_bulan['status'] =  'Unfilled';

                //kalau ada yg inprogress walaupun 1 sudah pasti in progress
                if ($kegiatan?->where('kegiatan_realisasi_bulan', $bulan_id)->where('kegiatan_realisasi_status_id', 2)->first()) {
                    $data_kegiatan_bulan['tanggal_update'] =$kegiatan?->where('kegiatan_realisasi_bulan', $bulan_id)->where('kegiatan_realisasi_status_id', 2)->first()->updated_at;
                    $data_kegiatan_bulan['status'] = "In Progress";
                }
                // dd($kegiatan);

                $totalKegiatan = count($kegiatan->where('kegiatan_realisasi_bulan', $bulan_id));
                $totalVerifiedKegiatan = count($kegiatan?->where('kegiatan_realisasi_bulan', $bulan_id)->where('kegiatan_realisasi_status_id', 1));
                $totalValidatedKegiatan = count($kegiatan?->where('kegiatan_realisasi_bulan', $bulan_id)->where('kegiatan_realisasi_status_id', 4));
        
                //Verified
               if ($totalKegiatan == $totalVerifiedKegiatan && $totalKegiatan != 0) {
                    $data_kegiatan_bulan['tanggal_update'] =$kegiatan?->where('kegiatan_realisasi_bulan', $bulan_id)->where('kegiatan_realisasi_status_id', 1)->first()->updated_at;
                    $data_kegiatan_bulan['status'] = "Verified";
               }
               //Validated
               if ($totalKegiatan == $totalVerifiedKegiatan && $totalKegiatan != 0) {
                    $data_kegiatan_bulan['tanggal_update'] =$kegiatan?->where('kegiatan_realisasi_bulan', $bulan_id)->where('kegiatan_realisasi_status_id', 4)->first()->updated_at;
                    $data_kegiatan_bulan['status'] = "Validated";
               }

                
                $data[] = $data_kegiatan_bulan;
            }
            
            //cek PUMK
            
            for ($i=0; $i < $jumlah_bulan; $i++) { 
                $bulan_id = $i+1;
                $bulan_nama = DB::table('bulans')->where('id', $bulan_id)->first()->nama;
                $pumk_bulan = DB::table('pumk_bulans')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('bulan_id', $bulan_id)->orderBy('updated_at', 'desc')->first();

                $data_pumk_bulan['jenis_laporan'] =  $menu_pumk;
                $data_pumk_bulan['periode'] = $periode_laporan->nama.'-'.$tahun;
                $data_pumk_bulan['bulan'] = $bulan_nama ;
                $data_pumk_bulan['tanggal_update'] = $pumk_bulan?->updated_at ?? 'Unfilled';
                $data_pumk_bulan['status'] =  'Unfilled';

                //kalau ada yg inprogress walaupun 1 sudah pasti in progress
                $pumk_bulan_in_progress = DB::table('pumk_bulans')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('bulan_id', $bulan_id)->where('status_id', 2)->orderBy('updated_at', 'desc')->first();
                if ($pumk_bulan_in_progress) {
                    $data_pumk_bulan['tanggal_update'] = $pumk_bulan_in_progress?->updated_at;
                    $data_pumk_bulan['status'] = "In Progress";
                }
                $totalPumkBulan= count( DB::table('pumk_bulans')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('bulan_id', $bulan_id)->get());
                $totalPumkBulanVerified = count( DB::table('pumk_bulans')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('bulan_id', $bulan_id)->where('status_id', 1)->get());
                $totalPumkBulanValidated = count( DB::table('pumk_bulans')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('bulan_id', $bulan_id)->where('status_id', 4)->get());

                if($totalPumkBulan == $totalPumkBulanVerified && $totalPumkBulan != 0){
                    $data_pumk_bulan['tanggal_update'] = DB::table('pumk_bulans')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('bulan_id', $bulan_id)->where('status_id', 1)->first()->updated_at;
                    $data_pumk_bulan['status'] = "Verified";
                }

                if($totalPumkBulan == $totalPumkBulanValidated && $totalPumkBulan != 0){
                    $data_pumk_bulan['tanggal_update'] = DB::table('pumk_bulans')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('bulan_id', $bulan_id)->where('status_id', 4)->first()->updated_at;
                    $data_pumk_bulan['status'] = "Validated";
                }
                $data[] = $data_pumk_bulan;
            }

            //Cek spd_pumk
            
            $data_spd_pumk_bulan = [];
            $data_spd_pumk_bulan['jenis_laporan'] = $menu_spdpumk;
            $data_spd_pumk_bulan['periode'] = $periode_laporan->nama.'-'.$tahun;
            $data_spd_pumk_bulan['tanggal_update'] = 'Unfilled';
            $data_spd_pumk_bulan['status'] = 'Unfilled';

            $totalSPDPUMK_bulan = count ($spd_pumk);
            $totalVerifiedSPDPUMK_bulan = count($spd_pumk?->where('status_id', 1));
            $totalValidatedSPDPUMK_bulan = count($spd_pumk?->where('status_id', 4));
            
            if($totalSPDPUMK_bulan == $totalVerifiedSPDPUMK_bulan && $totalSPDPUMK_bulan != 0){
                $data_spd_pumk_bulan['tanggal_update'] = $spd_pumk?->first()->updated_at;
                $data_spd_pumk_bulan['status'] =  "Verified" ;
            }
            if($totalSPDPUMK_bulan == $totalValidatedSPDPUMK_bulan && $totalSPDPUMK_bulan != 0){
                $data_spd_pumk_bulan['tanggal_update'] = $spd_pumk?->first()->updated_at;
                $data_spd_pumk_bulan['status'] =  "Validated" ;
            }
            //kalau ada yg inprogress walaupun 1 sudah pasti in progress/unfilled
            if ($spd_pumk?->where('status_id', 2)->first()) {
                $data_spd_pumk_bulan['tanggal_update'] = $spd_pumk->where('status_id', 2)->first()->updated_at;
                $data_spd_pumk_bulan['status'] = "In Progress";
            }
      
            $data[] = $data_spd_pumk_bulan;

            //cek laporan manajemen
            $laporan_manajemen = DB::table('laporan_manajemens')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('periode_laporan_id', $periode_id)->orderBy('updated_at', 'desc')->get();
            $data_laporan_manajemen['jenis_laporan'] = $menu_laporan_manajemen;
            $data_laporan_manajemen['periode'] = $periode_laporan->nama.'-'.$tahun;
            $data_laporan_manajemen['tanggal_update'] = 'Unfilled';
            $data_laporan_manajemen['status'] =   'Unfilled';
            if($laporan_manajemen?->first() ){
             
                $data_laporan_manajemen['jenis_laporan'] =  $menu_laporan_manajemen;
                $data_laporan_manajemen['periode'] = $periode_laporan->nama.'-'.$tahun;
                $data_laporan_manajemen['tanggal_update'] = $laporan_manajemen->first()->updated_at ?? 'Unfilled';
                $data_laporan_manajemen['status'] =  $laporan_manajemen->first()->updated_at ? "Finish" : 'Unfilled';
                
            }

            //Verified
            if ($laporan_manajemen?->whereIn('status_id', 1)->first()) {
                $data_laporan_manajemen['tanggal_update'] = $laporan_manajemen?->where('status_id', 1)->first()->updated_at;
                $data_laporan_manajemen['status'] = 'Verified';
            }

            //Validated
            if ($laporan_manajemen?->whereIn('status_id', 4)->first()) {
                $data_laporan_manajemen['tanggal_update'] = $laporan_manajemen?->whereIn('status_id', 4)->first()->updated_at;
                $data_laporan_manajemen['status'] = 'Validated';
            }
            //kalau ada yg inprogress walaupun 1 sudah pasti in progress
            if ($laporan_manajemen?->whereIn('status_id', [2, 3])->first()) {
                $data_laporan_manajemen['tanggal_update'] = $laporan_manajemen?->whereIn('status_id', [2, 3])->first()->updated_at ?? 'Unfilled';
                
                $data_laporan_manajemen['status'] = $laporan_manajemen?->whereIn('status_id', [2, 3])->first()->status_id === 2 ? "In Progress" : 'Unfilled';
            }
            $data[] = $data_laporan_manajemen;
            
        } else {
            $data =  [
           
                [
                    'jenis_laporan' => $menu_kegiatan,
                    'periode' => $periode_laporan->nama.'-'.$tahun,
                    'tanggal_update' => 'Unfilled',
                    'status' => 'Unfilled',
                ],
                [
                    'jenis_laporan' => $menu_spdpumk,
                    'periode' => $periode_laporan->nama.'-'.$tahun,
                    'tanggal_update' => 'Unfilled',
                    'status' => 'Unfilled',
                ],
                [
                    'jenis_laporan' => $menu_laporan_manajemen,
                    'periode' => $periode_laporan->nama.'-'.$tahun,
                    'tanggal_update' => 'Unfilled',
                    'status' => 'Unfilled',
                ],
            ];

            //cek kegiatan
            
            if($kegiatan?->first()){
                $data[0]['tanggal_update'] = $kegiatan->first()->updated_at;
                $data[0]['status'] = "Finish";
            }

            $totalKegiatan = count($kegiatan);
            $totalVerifiedKegiatan = count($kegiatan?->where('kegiatan_realisasi_status_id', 1));
            $totalValidatedKegiatan = count($kegiatan?->where('kegiatan_realisasi_status_id', 4));
            // dd($totalKegiatan. ' '.$totalVerifiedKegiatan. ' '. $totalValidatedKegiatan);
            //Verified
           if ($totalKegiatan == $totalVerifiedKegiatan && $totalKegiatan != 0) {
                $data[0]['tanggal_update'] =$kegiatan?->where('kegiatan_realisasi_status_id', 1)->first()->updated_at;
                $data[0]['status'] = "Verified";
           }
           //Validated
           if ($totalKegiatan == $totalVerifiedKegiatan && $totalKegiatan != 0) {
                $data[0]['tanggal_update'] =$kegiatan?->where('kegiatan_realisasi_status_id', 4)->first()->updated_at;
                $data[0]['status'] = "Validated";
           }
    
            //kalau ada yg inprogress walaupun 1 sudah pasti in progress
            if ($kegiatan?->where('kegiatan_realisasi_status_id', 2)->first()) {
                $data[0]['tanggal_update'] = $kegiatan->where('kegiatan_realisasi_status_id', 2)->first()->updated_at;
                $data[0]['status'] = "In Progress";
            }

            //cek spd_pumk       
            //Verified
            if($spd_pumk?->where('status_id', 1)->first()){
                $data[1]['tanggal_update'] = $spd_pumk->first()->updated_at;
                $data[1]['status'] = "Verified";
            }

            //Validated
            if($spd_pumk?->where('status_id', 4)->first()){
                $data[1]['tanggal_update'] = $spd_pumk->first()->updated_at;
                $data[1]['status'] = "Validated";
            }
            //kalau ada yg inprogress walaupun 1 sudah pasti in progress/unfilled
            if ($spd_pumk?->where('status_id', 2)->first()) {
                $data[1]['tanggal_update'] = $spd_pumk->where('status_id', 2)->first()->updated_at;
                $data[1]['status'] = "In Progress";
            }
            //cek laporan manajemen 
            $laporan_manajemen = DB::table('laporan_manajemens')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('periode_laporan_id', $periode_id)->orderBy('updated_at', 'desc')->get();
            //Verified
            if($laporan_manajemen?->where('status_id', 1)->first() ){
                $data[2]['tanggal_update'] = $laporan_manajemen->first()->updated_at;
                $data[2]['status'] = "Verified";
            }
            //Validated
            if($laporan_manajemen?->where('status_id', 4)->first() ){
                $data[2]['tanggal_update'] = $laporan_manajemen->first()->updated_at;
                $data[2]['status'] = "Validated";
            }
            //kalau ada yg inprogress walaupun 1 sudah pasti in progress
            if ($laporan_manajemen?->whereIn('status_id', [2, 3])->first()) {
                $data[2]['tanggal_update'] = $laporan_manajemen?->whereIn('status_id', [2, 3])->first()->status_id === 2 ? $laporan_manajemen->whereIn('status_id', [2, 3])->first()->updated_at : 'Unfilled';
                $data[2]['status'] =  $laporan_manajemen?->whereIn('status_id', [2, 3])->first()->status_id === 2 ? "In Progress" : 'Unfilled';
            }
        }
        
        // dd($data);
      
        $tanggal_cetak = Carbon::now()->locale('id_ID')->isoFormat('D MMMM YYYY');
        $user = Auth::user();

        //V2
        $encryptedId = Crypt::encryptString($id);
        $encryptedTanggalCetak = Crypt::encryptString($tanggal_cetak);
        $redirectRoute = route('verifikasi.index', ['id' => $encryptedId, 'tahun' => $tahun, 'tanggal_cetak' => $encryptedTanggalCetak, 'periode' => $periode_laporan->nama]);
        // $qrCode = QrCode::format('png')
        // ->size(300)
        // ->margin(20)
        // ->generate($redirectRoute);

        // Storage::disk('local')->put('qr_code.png', $qrCode);

        // // Load your custom image
        // $customImage = Image::make('logo_only.png');

        // // Calculate the new size for the custom image (e.g., 100x100 pixels)
        // $newCustomWidth = 50;
        // $newCustomHeight = 50;

        // // Resize the custom image
        // $customImage->resize($newCustomWidth, $newCustomHeight);

        // // Calculate the position to overlay the custom image in the center of the QR code
        // $qrCodeImage = Image::make(Storage::disk('local')->path('qr_code.png'));
        // $qrCodeWidth = $qrCodeImage->getWidth();
        // $qrCodeHeight = $qrCodeImage->getHeight();
        // $customWidth = $customImage->width();
        // $customHeight = $customImage->height();
        // $overlayX = ($qrCodeWidth - $customWidth) / 2;
        // $overlayY = ($qrCodeHeight - $customHeight) / 2;

        // // Overlay the custom image onto the QR code
        // $qrCodeImage->insert($customImage, 'top-left', $overlayX, $overlayY);

        // // Save or display the merged image
        // $qrCodeImage->save('merged_qr_code.png');
        // $qrCodeImagePath = asset('merged_qr_code.png');

        // // Optionally, you can delete the temporary QR code image
        // Storage::disk('local')->delete('qr_code.png');
        $barcode = new DNS2D();
        
        // Generate the QR code
        $qrCodeImage = $barcode->getBarcodePNG($redirectRoute, 'QRCODE,H', 1, 1);

        // Create an image object from the QR code
        $img = Image::make($qrCodeImage);

        // Load the logo image
        $logo = Image::make('logo_only.png');

        // Resize the logo image to 30% of its original size
        $logo->resize($logo->width() * 0.03, $logo->height() * 0.03);

        // Insert the logo into the center of the QR code image
        $img->insert($logo, 'center');

        // Encode the image as a data URL
        $dataUrl = $img->encode('data-url')->encoded;
        

        $pdf = PDF::loadView('laporan_realisasi.tble.detailtemplate', 
        ['data' => $data,
         'perusahaan' => $perusahaan, 
         'tanggal_cetak' => $tanggal_cetak,
         'user' => $user,
         'qrCodeImage' => $dataUrl])->setPaper('a4', 'portrait');
        return  $pdf->download($perusahaan->nama_lengkap.'-'.$periode_laporan->nama.'-'.$tahun.'.pdf');
    }
}
