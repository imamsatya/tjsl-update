<?php

namespace App\Http\Controllers\RencanaKerja;

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
use DB;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProgramTpbExport;
use DateTime;

class ProgramController extends Controller
{
    public function __construct()
    {

        $this->__route = 'rencana_kerja.program';
        $this->pagetitle = 'Data Program - TPB';
        $this->pageRouteName = 'rencana_kerja.program.index2'; // dipake buat check periode menu + enable disable
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->kriteria_program);
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        

        $admin_bumn = false;
        $view_only = false;
        $perusahaan_id = $request->perusahaan_id ?? 1;
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

        $anggaran       = AnggaranTpb::select('relasi_pilar_tpbs.pilar_pembangunan_id', 'anggaran_tpbs.*', 'tpbs.nama as tpb_nama', 'tpbs.no_tpb as no_tpb'        
        )
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id');            

            $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id');            
            
        $anggaran_bumn  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id');

        $anggaran_program  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id');

        
        if ($perusahaan_id) {
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_program = $anggaran_program->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
        }

        $tahun = $request->tahun ? $request->tahun : (int)date('Y');
        if ($tahun) {
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_program = $anggaran_program->where('anggaran_tpbs.tahun', $tahun);
        }

        $pilar_pembangunan = $request->pilar_pembangunan;
        if ($pilar_pembangunan) {
            $anggaran = $anggaran->where('pilar_pembangunans.id', $pilar_pembangunan);
            $anggaran_pilar = $anggaran_pilar->where('pilar_pembangunans.id', $pilar_pembangunan);
            $anggaran_bumn = $anggaran_bumn->where('pilar_pembangunans.id', $pilar_pembangunan);
            $anggaran_program = $anggaran_program->where('pilar_pembangunans.id', $pilar_pembangunan);
        }

        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        if($jenis_anggaran) {
            $anggaran = $anggaran->where('tpbs.jenis_anggaran', $jenis_anggaran);
            $anggaran_pilar = $anggaran_pilar->where('tpbs.jenis_anggaran', $jenis_anggaran);
            $anggaran_bumn = $anggaran_bumn->where('tpbs.jenis_anggaran', $jenis_anggaran);
            $anggaran_program = $anggaran_program->where('tpbs.jenis_anggaran', $jenis_anggaran);
        }
     
        $tpb = $request->tpb;
        if ($tpb) {
            $anggaran = $anggaran->where('tpbs.id', $tpb);
            $anggaran_pilar = $anggaran_pilar->where('tpbs.id', $tpb);
            $anggaran_bumn = $anggaran_bumn->where('tpbs.id', $tpb);
            $anggaran_program = $anggaran_program->where('tpbs.id', $tpb);
        }

        $kriteria_program = explode(',', $request->kriteria_program);
        if(count($kriteria_program)) {

            $anggaran_program = $anggaran_program->where(function($query) use ($kriteria_program) {
                if(in_array('prioritas', $kriteria_program)) {
                    $query->orWhere('kriteria_program_prioritas', true);
                }
    
                if(in_array('csv', $kriteria_program)) {
                    $query->orWhere('kriteria_program_csv', true);
                }
    
                if(in_array('umum', $kriteria_program)) {
                    $query->orWhere('kriteria_program_umum', true);
                }    
            });
            
        }
      

        $anggaran_pilar = $anggaran_pilar->select(
            'pilar_pembangunans.order_pilar',
            'anggaran_tpbs.perusahaan_id',
            'anggaran_tpbs.tahun',            
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid'),
            'pilar_pembangunans.nama as pilar_nama',
        )
            ->groupBy(
                'anggaran_tpbs.perusahaan_id',
                'anggaran_tpbs.tahun',
                'pilar_pembangunans.nama',
                'pilar_pembangunans.order_pilar'
            )
            ->orderBy('pilar_pembangunans.order_pilar')
            ->get();

        $anggaran_bumn = $anggaran_bumn->select(
            'anggaran_tpbs.perusahaan_id',
            'perusahaan_masters.nama_lengkap',
            'perusahaan_masters.id',            
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid')
        )
            ->groupBy('anggaran_tpbs.perusahaan_id')
            ->groupBy('perusahaan_masters.nama_lengkap')
            ->groupBy('perusahaan_masters.id')
            ->get();

        $anggaran_program = $anggaran_program->select('target_tpbs.*', 'tpbs.*', 'target_tpbs.id as id_target_tpbs', 'pilar_pembangunans.nama as pilar_nama','tpbs.nama as tpb_nama', 
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'),
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_alokasi end) as anggaran_alokasi_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran_alokasi end) as anggaran_alokasi_cid')
        )
        ->orderBy('pilar_pembangunans.nama')
        ->orderBy('no_tpb')
        ->get();  

        $anggaran = $anggaran->select('*', 'tpbs.id as id_tpbs', 'anggaran_tpbs.id as id_anggaran',
        'pilar_pembangunans.nama as pilar_nama', 'tpbs.nama as tpb_nama', DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'))
                ->orderBy('pilar_pembangunans.nama')
                ->orderBy('tpbs.id')
                ->get(); 

        $countInprogress = $anggaran_program->filter(function($data) {
            return $data->status_id == 2;
        })->count();

        $countFinish = $anggaran_program->filter(function($data) {
            return $data->status_id == 1;
        })->count();

                // dd($jenis_anggaran);
        $list_perusahaan = Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get();
        $currentNamaPerusahaan = $list_perusahaan->where('id', $perusahaan_id)->pluck('nama_lengkap');
        $currentNamaPerusahaan = count($currentNamaPerusahaan) ? $currentNamaPerusahaan[0] : 'ALL';
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => $list_perusahaan,
            'anggaran' => $anggaran,
            'anggaran_pilar' => $anggaran_pilar,
            'anggaran_bumn' => $anggaran_bumn,
            'anggaran_program' => $anggaran_program,
            // 'pilar' => PilarPembangunan::select(DB::raw('DISTINCT ON (nama) *'))->where('is_active', true)->orderBy('nama')->orderBy('id')->get(),
            // 'tpb' => Tpb::select(DB::raw('DISTINCT ON (no_tpb) *'))->orderBy('no_tpb')->orderBy('id')->get(),
            'pilar' => PilarPembangunan::get(),
            'tpb' => Tpb::get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'jenis_anggaran' => $jenis_anggaran,
            'kriteria_program' => $kriteria_program ?? [],
            'pilar_pembangunan_id' => $request->pilar_pembangunan,
            'tpb_id' => $request->tpb,
            'view_only' => $view_only,
            'pilar_pembangunan_id' => $request->pilar_pembangunan,
            'tpb_id' => $request->tpb,
            'view_only' => $view_only,
            'countInprogress' => $countInprogress,
            'perusahaan_nama' => $currentNamaPerusahaan,
            'countFinish' => $countFinish
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($perusahaan_id, $tahun, $jenis_anggaran)
    {
        //untuk View tabelnya
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();

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

        $anggaran       = AnggaranTpb::select('relasi_pilar_tpbs.pilar_pembangunan_id', 'anggaran_tpbs.*', 'tpbs.nama as tpb_nama', 'tpbs.no_tpb as no_tpb'        
        )
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
            ->where('anggaran_tpbs.tahun', $tahun);

        $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
            ->where('anggaran_tpbs.tahun', $tahun);
                      

        $anggaran_program  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id')
            ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
            ->where('anggaran_tpbs.tahun', $tahun);     

        
        $jenis_anggaran = explode('-', $jenis_anggaran);
        $jenis_anggaran = implode(' ', $jenis_anggaran);        


        $anggaran_pilar = $anggaran_pilar->select(
            'pilar_pembangunans.order_pilar',
            'anggaran_tpbs.perusahaan_id',
            'anggaran_tpbs.tahun',            
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid'),
            'pilar_pembangunans.nama as pilar_nama',
        )
            ->groupBy(
                'anggaran_tpbs.perusahaan_id',
                'anggaran_tpbs.tahun',
                'pilar_pembangunans.nama',
                'pilar_pembangunans.order_pilar'
            )
            ->orderBy('pilar_pembangunans.order_pilar')
            ->get();

        $anggaran = $anggaran->select('*', 'tpbs.id as id_tpbs', 'anggaran_tpbs.id as id_anggaran',
        'pilar_pembangunans.nama as pilar_nama', 'tpbs.nama as tpb_nama', DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'))
                ->orderBy('pilar_pembangunans.nama')
                ->orderBy('tpbs.id')
                ->get();   
                
        $anggaran_program = $anggaran_program->select('target_tpbs.*', 'tpbs.*', 'target_tpbs.id as id_target_tpbs', 'pilar_pembangunans.nama as pilar_nama','tpbs.nama as tpb_nama', 
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'),
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_alokasi end) as anggaran_alokasi_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran_alokasi end) as anggaran_alokasi_cid')
        )
        ->orderBy('pilar_pembangunans.nama')
        ->orderBy('no_tpb')
        ->get();  

     
        return view(
            $this->__route . '.create',
            [
                'pagetitle' => $this->pagetitle,
                'breadcrumb' => '',
                'perusahaan_id' => $perusahaan_id,
                'tahun' => $tahun,
                'actionform' => '-',
                'nama_perusahaan' => Perusahaan::find($perusahaan_id)->nama_lengkap,
                // 'pilar' => PilarPembangunan::get(),
                // 'versi_pilar_id' => $versi_pilar_id,
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'admin_bumn' => $admin_bumn,
                // 'tpb' => Tpb::get(),
                'tpb' => DB::table('tpbs')->select('*')->whereIn('id', function($query) use($perusahaan_id, $tahun) {
                    $query->select('relasi_pilar_tpbs.tpb_id as id')
                        ->from('anggaran_tpbs')
                        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id','=','anggaran_tpbs.relasi_pilar_tpb_id')
                        ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                        ->where('anggaran_tpbs.tahun', $tahun)
                        ->where('anggaran_tpbs.anggaran', '>', 0);
                })->get(),
                'tpb_id' => $request->tpb ?? '',
                'core_subject' => CoreSubject::get(),
                // 'perusahaan_id' => $perusahaan_id,
                // 'data' => $anggaran_tpb
                //untuk View
                'jenis_anggaran' => $jenis_anggaran,
                'anggaran_pilar' => $anggaran_pilar,
                'anggaran' => $anggaran,
                'anggaran_program' => $anggaran_program,
                'view_only' => $view_only,
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
        $anggaran_tpb = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        ->where('tahun', $request->tahun)
        ->where('perusahaan_id', $request->perusahaan_id)
        ->where('tpb_id', $request->data['tpb_id'])
        ->first();
       
        // dd($anggaran_tpb[0]->id);
        DB::beginTransaction();
        try {           
            //code...
            $target_tpb = new TargetTpb();
            $target_tpb->anggaran_tpb_id = $anggaran_tpb->id;
            $target_tpb->program = $request->data['nama_program'];
            $target_tpb->unit_owner = $request->data['unit_owner'];
            $target_tpb->core_subject_id = $request->data['core_subject_id'];
            $target_tpb->tpb_id = $request->data['tpb_id'];
            $target_tpb->anggaran_alokasi = $request->data['alokasi_anggaran'];
            $target_tpb->status_id = 2; // In Progress
    
            //kriteria
            foreach ($request->data['kriteria_program'] as $key => $value) {
               
                if ($value === 'prioritas') {
                    $target_tpb->kriteria_program_prioritas = true;
                }
                if ($value === 'csv') {
                    $target_tpb->kriteria_program_csv = true;
                }
                if ($value === 'umum') {
                    $target_tpb->kriteria_program_umum = true;
                }
            }

            $target_tpb->pelaksanaan_program = $request->data['pelaksanaan_program'];
            $target_tpb->mitra_bumn_id = $request->data['mitra_bumn'];
            // if ($request->data['program_multiyears'] === 'ya') {
            //     $target_tpb->multi_years = true;
            // }
            $target_tpb->save();
    
            ProgramController::store_log($target_tpb->id,$target_tpb->status_id);
            DB::commit();
            Session::flash('success', "Berhasil Menyimpan Data Program TPB");
            $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses tambah data',
                        'title' => 'Sukses'
            ];
            echo json_encode(['result' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
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
    public function edit(Request $request)
    {
        try {
            $data = TargetTpb::find((int)$request->input('program'));
            $anggaran_tpbs = AnggaranTpb::find($data->anggaran_tpb_id);
            $perusahaan_id = $anggaran_tpbs->perusahaan_id;
            $tahun = $anggaran_tpbs->tahun;
            $tpbs_temp = Tpb::find($data->tpb_id);
            return view($this->__route . '.edit', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'tpb' => DB::table('tpbs')->select('*')->whereIn('id', function($query) use($perusahaan_id, $tahun) {
                    $query->select('relasi_pilar_tpbs.tpb_id as id')
                        ->from('anggaran_tpbs')
                        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id','=','anggaran_tpbs.relasi_pilar_tpb_id')
                        ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                        ->where('anggaran_tpbs.tahun', $tahun);
                })->where('tpbs.jenis_anggaran', $tpbs_temp->jenis_anggaran)->get(),
                'core_subject' => CoreSubject::get(),
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'data' => $data,
                'id_program' => $request->input('program'),
                'tahun' => $tahun,
                'perusahaan_id' => $perusahaan_id
            ]);
        } catch (Exception $e) {
        }
    }

    public function editStore(Request $request) {
        DB::beginTransaction();
        try {
            $data = $request->input('data');
            $target_tpb = TargetTpb::find((int) $data['id_program']);            

            if($target_tpb->anggaran_tpb_id != (int) $data['tpb_id_edit']) {

                $anggaran_tpb = DB::table('anggaran_tpbs')->select('anggaran_tpbs.*')        
                ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                ->where('tahun', $data['tahun_edit'])
                ->where('perusahaan_id', $data['perusahaan_edit'])
                ->where('tpb_id', $data['tpb_id_edit'])
                ->first();

                $target_tpb->anggaran_tpb_id = $anggaran_tpb->id;
            }
            
            $target_tpb->program = $data['nama_program_edit'];
            $target_tpb->unit_owner = $data['unit_owner_edit'];
            $target_tpb->core_subject_id = $data['core_subject_id_edit'];
            $target_tpb->tpb_id = $data['tpb_id_edit'];
            $target_tpb->anggaran_alokasi = $data['alokasi_anggaran_edit'];

            //kriteria
            $kriteria = explode(',', $data['kriteria_used']);
            $target_tpb->kriteria_program_prioritas = in_array('prioritas', $kriteria) ? true : false;
            $target_tpb->kriteria_program_csv = in_array('csv', $kriteria) ? true : false;
            $target_tpb->kriteria_program_umum = in_array('umum', $kriteria) ? true : false;            

            $target_tpb->pelaksanaan_program = $data['pelaksanaan_program_edit'];
            $target_tpb->mitra_bumn_id = $data['mitra_bumn_edit'];
            // if ($data['program_multiyears_edit'] === 'ya') {
            //     $target_tpb->multi_years = true;
            // }
            $target_tpb->save();            
            ProgramController::store_log($target_tpb->id,$target_tpb->status_id);
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Berhasil memperbarui data!',
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

    public static function store_log($target_tpb_id, $status_id)
    {  
        $param['target_tpb_id'] = $target_tpb_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogTargetTpb::create((array)$param);
    }

    public function deleteAll($parameter) {
        $id_perusahaan = $parameter['perusahaan_id'];
        $tahun = $parameter['tahun'] ?? (int) date('Y');
        $pilar_pembangunan = $parameter['pilar_pembangunan'];
        $tpb = $parameter['tpb'];
        $jenis_anggaran = $parameter['jenis_anggaran'] ?? 'CID';
        $kriteria_program = explode(',', $parameter['kriteria_program']);

        $datatemp = DB::table('target_tpbs as tt')
                    ->select('tt.*')
                    ->join('anggaran_tpbs as atpb', 'atpb.id', '=', 'tt.anggaran_tpb_id')
                    ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
                    ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
                    ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
                    ->where('atpb.tahun', $tahun)
                    ->where('atpb.anggaran', '>=', 0)
                    ->where('pp.jenis_anggaran', $jenis_anggaran)
                    ->where('tpbs.jenis_anggaran', $jenis_anggaran)
                    ->where('tt.status_id', 2) // hanya yg in progress saja
                    ->when($id_perusahaan, function($query) use ($id_perusahaan) {
                        return $query->where('atpb.perusahaan_id', $id_perusahaan);
                    })
                    ->when($pilar_pembangunan, function($query) use ($pilar_pembangunan) {
                        return $query->where('pp.id', $pilar_pembangunan);
                    })
                    ->when($tpb, function($query) use ($tpb) {
                        return $query->where('tpbs.id', $tpb);
                    })
                    ->when(count($kriteria_program), function($query) use ($kriteria_program) {
                        return $query->where(function($queryKriteria) use ($kriteria_program) {
                            if(in_array('prioritas', $kriteria_program)) {
                                $queryKriteria->orWhere('kriteria_program_prioritas', true);
                            }
                
                            if(in_array('csv', $kriteria_program)) {
                                $queryKriteria->orWhere('kriteria_program_csv', true);
                            }
                
                            if(in_array('umum', $kriteria_program)) {
                                $queryKriteria->orWhere('kriteria_program_umum', true);
                            }    
                        });
                    })
                    ->get();

        return $datatemp->pluck('id')->toArray();        
    }

    public function executeDeleteProgram($list_id_program) {
        $listKegiatan = DB::table('kegiatans')->select('id')->whereIn('target_tpb_id', $list_id_program)->get();
        $idListKegiatan = $listKegiatan->pluck('id')->toArray();

        DB::table('kegiatan_realisasis')->whereIn('kegiatan_id', $idListKegiatan)->delete();
        DB::table('kegiatans')->whereIn('target_tpb_id', $list_id_program)->delete();
        DB::table('target_tpbs')->whereIn('id', $list_id_program)->delete();
    }

    public function delete(Request $request) {
        DB::beginTransaction();
        try {
            $list_id_program = $request->input('program_deleted');

            $isDeleteAll = filter_var($request->input('isDeleteAll'), FILTER_VALIDATE_BOOLEAN);
            if($isDeleteAll) {
                $parameterSelectAll = $request->input('parameterSelectAll');
                $list_id_program = $this->deleteAll($parameterSelectAll);
            }
            
            $this->executeDeleteProgram($list_id_program);
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

    public function log_status(Request $request)
    {

        $log = LogTargetTpb::select('log_target_tpbs.*', 'users.name AS user', 'statuses.nama AS status')
            ->leftjoin('users', 'users.id', '=', 'log_target_tpbs.user_id')
            ->leftjoin('statuses', 'statuses.id', '=', 'log_target_tpbs.status_id')
            ->where('target_tpb_id', (int)$request->input('id'))
            ->orderBy('created_at')
            ->get();

        return view($this->__route . '.log_status', [
            'pagetitle' => 'Log Status',
            'log' => $log
        ]);
    }

    public function verifikasiData(Request $request) {
        // DB::beginTransaction();
        // try {
        //     $list_id = $request->input('program');
        //     foreach($list_id as $program) {
        //         $data = TargetTpb::find((int) $program);
        //         if($data && $data->status_id !== 1) {
        //             $param['status_id'] = 1;
        //             $data->update((array)$param);
        //             ProgramController::store_log($data->id,$data->status_id);
        //         }
        //     }
        //     DB::commit();
        //     $result = [
        //         'flag'  => 'success',
        //         'msg' => 'Sukses verifikasi data',
        //         'title' => 'Sukses'
        //     ];
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     $result = [
        //         'flag'  => 'warning',
        //         'msg' => 'Gagal verifikasi data',
        //         'title' => 'Gagal'
        //     ];
        // }
        // return response()->json($result);

        DB::beginTransaction();
        try {

            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');

            $allDataUpdated = TargetTpb::select('target_tpbs.*')
                            ->join('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                            ->where('target_tpbs.status_id', '=', 2)
                            ->where('anggaran_tpbs.tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('anggaran_tpbs.perusahaan_id', $id_bumn);
                            })
                            ->get();
            
            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    TargetTpb::where('id', $data->id)->update(['status_id' => 1]);
                    ProgramController::store_log($data->id,1);
                }
            } 

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses set data verified',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal set data verified',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function export(Request $request) {

        $anggaran_program  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id');

       //temp comment 
        if ($request->perusahaan_id) {            
            $anggaran_program = $anggaran_program->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if ($request->tahun) {            
            $anggaran_program = $anggaran_program->where('anggaran_tpbs.tahun', $request->tahun);
        }


        //commented
        // if ($request->pilar_pembangunan_id) {
        //     $anggaran_program = $anggaran_program->where('pilar_pembangunans.id', $request->pilar_pembangunan_id);
        // }

        // if($request->jenis_anggaran) {
        //     $anggaran_program = $anggaran_program->where('tpbs.jenis_anggaran', $request->jenis_anggaran);
        // }
     
        // if ($request->tpb_id) {
        //     $anggaran_program = $anggaran_program->where('tpbs.id', $request->tpb_id);
        // }

        // $kriteria_program = explode(',', $request->kriteria_program);
        // if(count($kriteria_program)) {

        //     $anggaran_program = $anggaran_program->where(function($query) use ($kriteria_program) {
        //         if(in_array('prioritas', $kriteria_program)) {
        //             $query->orWhere('kriteria_program_prioritas', true);
        //         }
    
        //         if(in_array('csv', $kriteria_program)) {
        //             $query->orWhere('kriteria_program_csv', true);
        //         }
    
        //         if(in_array('umum', $kriteria_program)) {
        //             $query->orWhere('kriteria_program_umum', true);
        //         }    
        //     });
            
        // }
      

        $anggaran_program = $anggaran_program->select('target_tpbs.*', 'tpbs.*', 'target_tpbs.id as id_target_tpbs', 'pilar_pembangunans.nama as pilar_nama','tpbs.nama as tpb_nama', 
            'perusahaan_masters.nama_lengkap as nama_perusahaan',
            'pilar_pembangunans.jenis_anggaran as jenis_anggaran_pilar',
            'tpbs.jenis_anggaran as jenis_anggaran_tpb',
            'anggaran_tpbs.anggaran as anggaran_tpb',
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'),
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_alokasi end) as anggaran_alokasi_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran_alokasi end) as anggaran_alokasi_cid')
        )
        ->orderBy('perusahaan_masters.id')
        ->orderBy('pilar_pembangunans.jenis_anggaran')
        ->orderBy('pilar_pembangunans.nama')
        ->orderBy('tpbs.id')
        ->orderBy('target_tpbs.id')
        ->get();  
        
        // dd($anggaran_program[0]);

        $namaFile = "Data Program TPB ".date('dmY').".xlsx";
        return Excel::download(new ProgramTpbExport($anggaran_program, $request->tahun), $namaFile);        
    }

    public function batalVerifikasiData(Request $request) {
        DB::beginTransaction();
        try {
            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');


            $allDataUpdated = TargetTpb::select('target_tpbs.*')
                            ->join('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                            ->where('target_tpbs.status_id', '=', 1)
                            ->where('anggaran_tpbs.tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('anggaran_tpbs.perusahaan_id', $id_bumn);
                            })
                            ->get();

            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    TargetTpb::where('id', $data->id)->update(['status_id' => 2]);
                    ProgramController::store_log($data->id,2);
                }
            }                                            
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses set data unverified',
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

    public function index2(Request $request)
    {
        // dd($request->kriteria_program);
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        

        $admin_bumn = false;
        $view_only = false;
        $isSuperAdmin = false;
        $perusahaan_id = $request->perusahaan_id;
        if (!empty($users->getRoleNames())) {
            foreach ($users->getRoleNames() as $v) {
                if ($v == 'Admin BUMN' || $v == 'Verifikator BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if ($v == 'Admin Stakeholder') {
                    $view_only = true;
                }
                if($v == 'Super Admin') {
                    $isSuperAdmin = true;
                }
            }
        }  
        
        $refEnable = $this->getReferensiEnable();

        $data = DB::table('anggaran_tpbs as atpb')
            ->select('atpb.perusahaan_id', 'perusahaan_masters.nama_lengkap',
            DB::raw("sum(tt.anggaran_alokasi) as total"),
            DB::raw("count(case when tt.status_id = 1 then 1 end) completed"),
            DB::raw("count(case when tt.status_id = 2 then 1 end) inprogress"),
            DB::raw("count(case when tt.status_id = 4 then 1 end) verified"),
            DB::raw("(case when epp.id is not null then 1 else 0 end) enable_by_admin")
            // DB::raw("count(case when tt.is_enable_input_by_superadmin = true then 1 end) enable_by_admin"),
            // DB::raw("count(case when tt.is_enable_input_by_superadmin = false then 1 end) disable_by_admin")
            )
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('perusahaan_masters', 'perusahaan_masters.id', '=', 'atpb.perusahaan_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->leftJoin('target_tpbs as tt', 'tt.anggaran_tpb_id', '=', 'atpb.id')
            ->leftJoin('enable_input_by_superadmin as epp', function($join) use ($refEnable) {
                $join->on('epp.perusahaan_id', '=', 'atpb.perusahaan_id')
                    ->on('epp.tahun', '=', DB::raw("CAST(atpb.tahun AS INTEGER)"))
                    ->where('epp.referensi_id', '=', $refEnable?->id);
            })
            ->where('anggaran', '>=', 0);

        $rka = DB::table('anggaran_tpbs as atpb')
            ->select('perusahaan_id', 'perusahaan_masters.nama_lengkap', DB::raw("SUM(atpb.anggaran) as total_rka"))
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('perusahaan_masters', 'perusahaan_masters.id', '=', 'atpb.perusahaan_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->where('anggaran', '>=', 0);

        if($perusahaan_id) {
            $data = $data->where('atpb.perusahaan_id', $perusahaan_id);
            $rka = $rka->where('atpb.perusahaan_id', $perusahaan_id);
        }

        $tahun = $request->tahun ? $request->tahun : (int) date('Y');
        if($tahun) {
            $data = $data->where('atpb.tahun', $tahun);
            $rka = $rka->where('tahun', $tahun);
        }

        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        if($jenis_anggaran) {
            $data = $data->where('pp.jenis_anggaran', $jenis_anggaran);
            $rka = $rka->where('pp.jenis_anggaran', $jenis_anggaran);
        }

        if ($request->pilar_pembangunan) {
            $data = $data->where('pp.id', $request->pilar_pembangunan);
            $rka = $rka->where('pp.id', $request->pilar_pembangunan);
        }

        if ($request->tpb) {
            $data = $data->where('tpbs.id', $request->tpb);
            $rka = $rka->where('tpbs.id', $request->tpb);
        }

        $kriteria_program = explode(',', $request->kriteria_program);        
        if(count($kriteria_program)) {

            $data = $data->where(function($query) use ($kriteria_program) {
                if(in_array('prioritas', $kriteria_program)) {
                    $query->orWhere('kriteria_program_prioritas', true);
                }
    
                if(in_array('csv', $kriteria_program)) {
                    $query->orWhere('kriteria_program_csv', true);
                }
    
                if(in_array('umum', $kriteria_program)) {
                    $query->orWhere('kriteria_program_umum', true);
                }    
            });
            
        }


        $data = $data->groupBy('atpb.perusahaan_id', 'perusahaan_masters.nama_lengkap', 'epp.id')
            ->orderBy('atpb.perusahaan_id')
            ->get();

        $rka = $rka->groupBy('perusahaan_id', 'perusahaan_masters.nama_lengkap')
            ->orderBy('perusahaan_id')
            ->get();

        $joinData = $data->zip($rka);

        $countInprogress = $data->filter(function($row) {
            return $row->inprogress > 0;
        })->count();
        
        $countCompleted = $data->filter(function($row) {
            return $row->completed > 0;
        })->count();

        $countVerified = $data->filter(function($row) {
            return $row->verified > 0;
        })->count();

        $list_perusahaan = Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get();
        $currentNamaPerusahaan = $list_perusahaan->where('id', $perusahaan_id)->pluck('nama_lengkap');
        $currentNamaPerusahaan = count($currentNamaPerusahaan) ? $currentNamaPerusahaan[0] : 'ALL';

        // validasi availability untuk input data
        $isOkToInput = $this->checkRule();

        // cek enable input by superadmin
        $list_enable = DB::table('enable_input_by_superadmin')
            ->where('referensi_id', $refEnable?->id)
            ->where('tahun', $tahun)
            ->when($perusahaan_id, function($query) use ($perusahaan_id) {
                return $query->where('perusahaan_id', $perusahaan_id);
            })
            ->get();

        $isEnableInputBySuperadmin = false;
        if($list_enable->count()) $isEnableInputBySuperadmin = true;

        return view($this->__route . '.index2', [
            'joinData' => $joinData,
            'data' => $data,
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => $list_perusahaan,
            'pilar' => PilarPembangunan::get(),
            'tpb' => Tpb::get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => $tahun,
            'jenis_anggaran' => $jenis_anggaran,
            'kriteria_program' => $kriteria_program ?? [],
            'pilar_pembangunan_id' => $request->pilar_pembangunan,
            'tpb_id' => $request->tpb,
            'view_only' => $view_only,
            'countInprogress' => $countInprogress,
            'perusahaan_nama' => $currentNamaPerusahaan,
            'countCompleted' => $countCompleted,
            'isOkToInput' => $isOkToInput,
            'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
            'isSuperAdmin' => $isSuperAdmin,
            'countVerified' => $countVerified
        ]);
    }

    public function getDataPerusahaanTree(Request $request) {
        $perusahaan_id = $request->input('id');
        $tahun = $request->input('tahun');
        $jenis_anggaran = $request->input('jenis_anggaran') ?? 'CID';

        $result = DB::table('anggaran_tpbs as atpb')
            ->select('pp.order_pilar', 'pp.id as id_pilar', 'pp.nama as nama_pilar', 
                DB::raw("sum(tt.anggaran_alokasi) total"),
                DB::raw("count(case when tt.status_id = 1 then 1 end) completed"),
                DB::raw("count(case when tt.status_id = 2 then 1 end) inprogress"),
                DB::raw("count(case when tt.status_id = 4 then 1 end) verified"),
            )
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->leftJoin('target_tpbs as tt', 'tt.anggaran_tpb_id', '=', 'atpb.id')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('anggaran', '>=', 0)
            ->where('tahun', $tahun)
            ->where('pp.jenis_anggaran', $jenis_anggaran);

        $rka = DB::table('anggaran_tpbs as atpb')
            ->select('pp.order_pilar', 'pp.id as id_pilar', 'pp.nama as nama_pilar', DB::raw("SUM(atpb.anggaran) as total_rka"))
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->where('anggaran', '>=', 0)
            ->where('perusahaan_id', $perusahaan_id)
            ->where('tahun', $tahun)
            ->where('pp.jenis_anggaran', $jenis_anggaran);
            

        if ($request->input('pilar_pembangunan')) {
            $result = $result->where('pp.id', $request->pilar_pembangunan);
            $rka = $rka->where('pp.id', $request->pilar_pembangunan);
        }

        if($request->input('tpb')) {
            $result = $result->where('tpbs.id', $request->tpb);
            $rka = $rka->where('tpbs.id', $request->tpb);
        }

        $kriteria_program = explode(',', $request->kriteria_program);        
        if(count($kriteria_program)) {

            $result = $result->where(function($query) use ($kriteria_program) {
                if(in_array('prioritas', $kriteria_program)) {
                    $query->orWhere('kriteria_program_prioritas', true);
                }
    
                if(in_array('csv', $kriteria_program)) {
                    $query->orWhere('kriteria_program_csv', true);
                }
    
                if(in_array('umum', $kriteria_program)) {
                    $query->orWhere('kriteria_program_umum', true);
                }    
            });
            
        }

        $result = $result->groupBy('pp.id','pp.nama', 'pp.order_pilar')
            ->orderBy('pp.order_pilar')
            ->get();

        $rka = $rka->groupBy('pp.id', 'pp.nama', 'pp.order_pilar')
            ->orderBy('pp.order_pilar')
            ->get();

        $joinData = $result->zip($rka);

        echo json_encode(array('result' => $result, 'joinData' => $joinData));
    }

    public function getDataPerusahaanPilarTree(Request $request) {
        $perusahaan_id = $request->input('id');
        $tahun = $request->input('tahun');
        $pilar = $request->input('pilar');

        $result = DB::table('anggaran_tpbs as atpb')
            ->select('tpbs.id as id_tpb', 'tpbs.no_tpb', 'tpbs.nama as nama_tpb',
                DB::raw("sum(tt.anggaran_alokasi) total"),
                DB::raw("count(case when tt.status_id = 1 then 1 end) completed"),
                DB::raw("count(case when tt.status_id = 2 then 1 end) inprogress"),
                DB::raw("count(case when tt.status_id = 4 then 1 end) verified"),
            )
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->leftJoin('target_tpbs as tt', 'tt.anggaran_tpb_id', '=', 'atpb.id')
            ->where('perusahaan_id', $perusahaan_id)
            ->where('anggaran', '>=', 0)
            ->where('tahun', $tahun)
            ->where('pp.id', $pilar);

        $rka = DB::table('anggaran_tpbs as atpb')
            ->select('tpbs.id as id_tpb', 'tpbs.no_tpb', 'tpbs.nama as nama_tpb', DB::raw("SUM(atpb.anggaran) as total_rka"))
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->where('anggaran', '>=', 0)
            ->where('perusahaan_id', $perusahaan_id)
            ->where('tahun', $tahun)
            ->where('pp.id', $pilar);


        if($request->input('tpb')) {
            $result = $result->where('tpbs.id', $request->tpb);
            $rka = $rka->where('tpbs.id', $request->tpb);
        }

        $kriteria_program = explode(',', $request->kriteria_program);        
        if(count($kriteria_program)) {

            $result = $result->where(function($query) use ($kriteria_program) {
                if(in_array('prioritas', $kriteria_program)) {
                    $query->orWhere('kriteria_program_prioritas', true);
                }
    
                if(in_array('csv', $kriteria_program)) {
                    $query->orWhere('kriteria_program_csv', true);
                }
    
                if(in_array('umum', $kriteria_program)) {
                    $query->orWhere('kriteria_program_umum', true);
                }    
            });
            
        }

        $result = $result->groupBy('tpbs.id','tpbs.no_tpb', 'tpbs.nama')            
            ->orderBy('tpbs.id')
            ->get();

        $rka = $rka->groupBy('tpbs.id','tpbs.no_tpb', 'tpbs.nama')
            ->orderBy('tpbs.id')
            ->get();

        $joinData = $result->zip($rka);

        echo json_encode(array('result' => $result, 'joinData' => $joinData));
    }

    public function getDataPerusahaanPilarTpbTree(Request $request) {
        $perusahaan_id = $request->input('id');
        $tahun = $request->input('tahun');
        $pilar = $request->input('pilar');
        $tpb = $request->input('tpb');

        $refEnable = $this->getReferensiEnable();

        $result = DB::table('anggaran_tpbs as atpb')
            ->select('tt.id as id_target', 'tt.program', 'tt.kriteria_program_csv', 'tt.kriteria_program_umum', 'tt.kriteria_program_prioritas',
                DB::raw("sum(tt.anggaran_alokasi) total"),
                DB::raw("count(case when tt.status_id = 1 then 1 end) completed"),
                DB::raw("count(case when tt.status_id = 2 then 1 end) inprogress"),
                DB::raw("count(case when tt.status_id = 4 then 1 end) verified"),
                DB::raw("(case when epp.id is not null then 1 else 0 end) enable_by_admin")
                // DB::raw("count(case when tt.is_enable_input_by_superadmin = true then 1 end) enable_by_admin"),
                // DB::raw("count(case when tt.is_enable_input_by_superadmin = false then 1 end) disable_by_admin")
            )
            ->join('relasi_pilar_tpbs as rpt', 'rpt.id', '=', 'atpb.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans as pp', 'pp.id', '=', 'rpt.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'rpt.tpb_id')
            ->leftJoin('target_tpbs as tt', 'tt.anggaran_tpb_id', '=', 'atpb.id')
            ->leftJoin('enable_input_by_superadmin as epp', function($join) use ($refEnable) {
                $join->on('epp.perusahaan_id', '=', 'atpb.perusahaan_id')
                    ->on('epp.tahun', '=', DB::raw("CAST(atpb.tahun AS INTEGER)"))
                    ->where('epp.referensi_id', '=', $refEnable?->id);
            })
            ->where('atpb.perusahaan_id', $perusahaan_id)
            ->where('anggaran', '>=', 0)
            ->where('atpb.tahun', $tahun)
            ->where('pp.id', $pilar)
            ->where('tpbs.id', $tpb);

        $kriteria_program = explode(',', $request->kriteria_program);        
        if(count($kriteria_program)) {

            $result = $result->where(function($query) use ($kriteria_program) {
                if(in_array('prioritas', $kriteria_program)) {
                    $query->orWhere('kriteria_program_prioritas', true);
                }
    
                if(in_array('csv', $kriteria_program)) {
                    $query->orWhere('kriteria_program_csv', true);
                }
    
                if(in_array('umum', $kriteria_program)) {
                    $query->orWhere('kriteria_program_umum', true);
                }    
            });
            
        }
        
        $result = $result->groupBy('tt.id', 'tt.program', 'tt.kriteria_program_csv', 'tt.kriteria_program_umum', 'tt.kriteria_program_prioritas', 'epp.id')            
            ->orderBy('tt.id')
            ->get();

        echo json_encode(array('result' => $result));
    }

    public function edit2(Request $request)
    {
        try {

            $id_users = \Auth::user()->id;
            $users = User::where('id', $id_users)->first();
            $isSuperAdmin = false;
            if (!empty($users->getRoleNames())) {
                foreach ($users->getRoleNames() as $v) {
                    if($v == 'Super Admin') {
                        $isSuperAdmin = true;
                    }
                }
            }

            $data = TargetTpb::find((int)$request->input('program'));
            $anggaran_tpbs = AnggaranTpb::find($data->anggaran_tpb_id);
            $perusahaan_id = $anggaran_tpbs->perusahaan_id;
            $tahun = $anggaran_tpbs->tahun;
            $tpbs_temp = Tpb::find($data->tpb_id);

            // validasi availability untuk input data
            $isOkToInput = $this->checkRule();

            $refEnable = $this->getReferensiEnable();
            // cek enable input by superadmin
            $list_enable = DB::table('enable_input_by_superadmin')
                ->where('referensi_id', $refEnable?->id)
                ->where('tahun', $tahun)
                ->when($perusahaan_id, function($query) use ($perusahaan_id) {
                    return $query->where('perusahaan_id', $perusahaan_id);
                })
                ->get();

            $isEnableInputBySuperadmin = false;
            if($list_enable->count()) $isEnableInputBySuperadmin = true;

            return view($this->__route . '.edit2', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'tpb' => DB::table('tpbs')->select('*')->whereIn('id', function($query) use($perusahaan_id, $tahun) {
                    $query->select('relasi_pilar_tpbs.tpb_id as id')
                        ->from('anggaran_tpbs')
                        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id','=','anggaran_tpbs.relasi_pilar_tpb_id')
                        ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                        ->where('anggaran_tpbs.tahun', $tahun);
                })->where('tpbs.jenis_anggaran', $tpbs_temp->jenis_anggaran)->get(),
                'core_subject' => CoreSubject::get(),
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'data' => $data,
                'id_program' => $request->input('program'),
                'tahun' => $tahun,
                'perusahaan_id' => $perusahaan_id,
                'isOkToInput' => $isOkToInput,
                'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
                'isSuperAdmin' => $isSuperAdmin
            ]);
        } catch (Exception $e) {
        }
    }

    public function create2($perusahaan_id, $tahun, $jenis_anggaran)
    {
        //untuk View tabelnya
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();

        $isSuperAdmin = false;
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
                if($v == 'Super Admin') {
                    $isSuperAdmin = true;
                }
            }
        }

        $anggaran       = AnggaranTpb::select('relasi_pilar_tpbs.pilar_pembangunan_id', 'anggaran_tpbs.*', 'tpbs.nama as tpb_nama', 'tpbs.no_tpb as no_tpb'        
        )
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
            ->where('anggaran_tpbs.tahun', $tahun)
            ->where('anggaran_tpbs.anggaran', '>=', 0);

        $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
            ->where('anggaran_tpbs.tahun', $tahun)
            ->where('anggaran_tpbs.anggaran', '>=', 0);
                      

        $anggaran_program  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id')
            ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
            ->where('anggaran_tpbs.tahun', $tahun)
            ->where('anggaran_tpbs.anggaran', '>=', 0);   

        
        $jenis_anggaran = explode('-', $jenis_anggaran);
        $jenis_anggaran = implode(' ', $jenis_anggaran);        


        $anggaran_pilar = $anggaran_pilar->select(
            'pilar_pembangunans.order_pilar',
            'anggaran_tpbs.perusahaan_id',
            'anggaran_tpbs.tahun',            
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid'),
            'pilar_pembangunans.nama as pilar_nama',
        )
            ->groupBy(
                'anggaran_tpbs.perusahaan_id',
                'anggaran_tpbs.tahun',
                'pilar_pembangunans.nama',
                'pilar_pembangunans.order_pilar'
            )
            ->orderBy('pilar_pembangunans.order_pilar')
            ->get();

        $anggaran = $anggaran->select('*', 'tpbs.id as id_tpbs', 'anggaran_tpbs.id as id_anggaran',
        'pilar_pembangunans.nama as pilar_nama', 'tpbs.nama as tpb_nama', DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'))
                ->orderBy('pilar_pembangunans.nama')
                ->orderBy('tpbs.id')
                ->get();           
                
        $anggaran_program = $anggaran_program->select('target_tpbs.*', 'tpbs.*', 'target_tpbs.id as id_target_tpbs', 'pilar_pembangunans.nama as pilar_nama','tpbs.nama as tpb_nama', 
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'),
            DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_alokasi end) as anggaran_alokasi_noncid'), 
            DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran_alokasi end) as anggaran_alokasi_cid')
        )
        ->orderBy('pilar_pembangunans.nama')
        ->orderBy('no_tpb')
        ->get();  

        // validasi availability untuk input data
        $isOkToInput = $this->checkRule();
        $refEnable = $this->getReferensiEnable();

        // cek enable input by superadmin
        $list_enable = DB::table('enable_input_by_superadmin')
            ->where('referensi_id', $refEnable?->id)
            ->where('tahun', $tahun)
            ->when($perusahaan_id, function($query) use ($perusahaan_id) {
                return $query->where('perusahaan_id', $perusahaan_id);
            })
            ->get();

        $isEnableInputBySuperadmin = false;
        if($list_enable->count()) $isEnableInputBySuperadmin = true;

        $countStatus = $anggaran_program->groupBy('status_id')->map(function($data) {
            return $data->count();
        });

        $isFinish = isset($countStatus['1']) && !isset($countStatus['2']);
     
        return view(
            $this->__route . '.create2',
            [
                'pagetitle' => $this->pagetitle,
                'breadcrumb' => '',
                'perusahaan_id' => $perusahaan_id,
                'tahun' => $tahun,
                'actionform' => '-',
                'nama_perusahaan' => Perusahaan::find($perusahaan_id)->nama_lengkap,
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'admin_bumn' => $admin_bumn,
                'tpb' => DB::table('tpbs')->select('*')->whereIn('id', function($query) use($perusahaan_id, $tahun) {
                    $query->select('relasi_pilar_tpbs.tpb_id as id')
                        ->from('anggaran_tpbs')
                        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id','=','anggaran_tpbs.relasi_pilar_tpb_id')
                        ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                        ->where('anggaran_tpbs.tahun', $tahun)
                        ->where('anggaran_tpbs.anggaran', '>=', 0)
                        ->groupBy('tpb_id');
                })->get(),
                'tpb_id' => $request->tpb ?? '',
                'core_subject' => CoreSubject::get(),
                'jenis_anggaran' => $jenis_anggaran,
                'anggaran_pilar' => $anggaran_pilar,
                'anggaran' => $anggaran,
                'anggaran_program' => $anggaran_program,
                'view_only' => $view_only,
                'isOkToInput' => $isOkToInput,
                'isEnableInputBySuperadmin' => $isEnableInputBySuperadmin,
                'isFinish' => $isFinish,
                'isSuperAdmin' => $isSuperAdmin
            ]
        );
    }

    public function enableDisableInputData(Request $request) {
        DB::beginTransaction();
        try {
            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');
            $status = $request->input('status') === 'enable' ? 1 : 0;

            $targetTpbs = TargetTpb::select('target_tpbs.id')
            ->join('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')            
            ->where('anggaran_tpbs.tahun', $tahun)            
            ->when($id_bumn, function($query) use ($id_bumn) {
                return $query->where('anggaran_tpbs.perusahaan_id', $id_bumn);
            })
            ->get();

            $idTarget = $targetTpbs->pluck('id')->toArray();

            // update status enable/disable
            TargetTpb::whereIn('id', $idTarget)->update(['is_enable_input_by_superadmin' => $status]);

            // update log 
            $currentTime = date('Y-m-d H:i:s');
            if($id_bumn) {
                DB::table('log_enable_disable_input_datas')->insert([
                    'tipe' => 'RKA - Program',
                    'status' => $status ? 'enable' : 'disable',
                    'perusahaan_id' => $id_bumn,
                    'tahun' => $tahun,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime
                ]);
            } else {
                $getAllPerusahaanOK = DB::table('anggaran_tpbs')->select('perusahaan_id')
                    ->where('tahun', $tahun)
                    ->groupBy('perusahaan_id')
                    ->havingRaw('COUNT(CASE WHEN anggaran > 0 THEN 1 END) > 0')
                    ->orderBy('perusahaan_id')
                    ->get();
                
                foreach($getAllPerusahaanOK as $perusahaan) {
                    DB::table('log_enable_disable_input_datas')->insert([
                        'tipe' => 'RKA - Program',
                        'status' => $status ? 'enable' : 'disable',
                        'perusahaan_id' => $perusahaan->perusahaan_id,
                        'tahun' => $tahun,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime
                    ]);
                }
            }
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses '.$request->input('status').' input data',
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

    public function checkRule() {
        // validasi availability untuk input data
        $menuRKA = DB::table('menus')->where('route_name', $this->pageRouteName)->first();
        $start = null;
        $end = null;
        $isOkToInput = true;
        if($menuRKA) {
            $periodeHasJenis = DB::table('periode_has_jenis')->where('jenis_laporan_id', $menuRKA->id)->first();
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

        return $isOkToInput;        
    }

    public function getReferensiEnable() {
        $data = DB::table('referensi_enable_input_by_superadmin')
            ->where('route_name', $this->pageRouteName)
            ->first();

        return $data;
    }

    public function verifikasiDataFinal(Request $request) {
        DB::beginTransaction();
        try {

            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');

            $allDataUpdated = TargetTpb::select('target_tpbs.*')
                            ->join('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                            ->where('target_tpbs.status_id', '=', 1)
                            ->where('anggaran_tpbs.tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('anggaran_tpbs.perusahaan_id', $id_bumn);
                            })
                            ->get();
            
            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    TargetTpb::where('id', $data->id)->update(['status_id' => 4]);
                    ProgramController::store_log($data->id,4);
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

    public function batalVerifikasiDataFinal(Request $request) {
        DB::beginTransaction();
        try {

            $id_bumn = $request->input('bumn');
            $tahun = $request->input('tahun');

            $allDataUpdated = TargetTpb::select('target_tpbs.*')
                            ->join('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                            ->where('target_tpbs.status_id', '=', 4) // verified
                            ->where('anggaran_tpbs.tahun', $tahun)
                            ->when($id_bumn, function($query) use ($id_bumn) {
                                return $query->where('anggaran_tpbs.perusahaan_id', $id_bumn);
                            })
                            ->get();
            
            if($allDataUpdated->count()) {
                foreach($allDataUpdated as $data) {  
                    TargetTpb::where('id', $data->id)->update(['status_id' => 2]); // in progress
                    ProgramController::store_log($data->id,2);
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
}
