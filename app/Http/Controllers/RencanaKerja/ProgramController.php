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

class ProgramController extends Controller
{
    public function __construct()
    {

        $this->__route = 'rencana_kerja.program';
        $this->pagetitle = 'Data Program - TPB';
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
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id');            

            $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id');            
            
        $anggaran_bumn  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id');

        $anggaran_program  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id');

        $perusahaan_id = $request->perusahaan_id ?? 1;
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
            'perusahaans.nama_lengkap',
            'perusahaans.id',            
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid')
        )
            ->groupBy('anggaran_tpbs.perusahaan_id')
            ->groupBy('perusahaans.nama_lengkap')
            ->groupBy('perusahaans.id')
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

                // dd($jenis_anggaran);
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
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
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
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
            $target_tpb->anggaran_tpb_id = $anggaran_tpb    ->id;
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
            if ($request->data['program_multiyears'] === 'ya') {
                $target_tpb->multi_years = true;
            }
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
            if ($data['program_multiyears_edit'] === 'ya') {
                $target_tpb->multi_years = true;
            }
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

    public function delete(Request $request) {
        DB::beginTransaction();
        try {
            $list_id_program = $request->input('program_deleted');
            foreach($list_id_program as $id_program) {
                $data = TargetTpb::find((int) $id_program);
                $data->delete();
            }
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
}
