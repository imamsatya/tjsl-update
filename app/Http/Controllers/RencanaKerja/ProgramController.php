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
        $this->pagetitle = 'Program';
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

        $anggaran       = AnggaranTpb::select('relasi_pilar_tpbs.pilar_pembangunan_id', 'anggaran_tpbs.*', 'tpbs.nama as tpb_nama', 'tpbs.no_tpb as no_tpb', 
        'target_tpbs.kriteria_program_prioritas as kriteria_program_prioritas', 
        'target_tpbs.kriteria_program_umum as kriteria_program_umum', 
        'target_tpbs.kriteria_program_csv as kriteria_program_csv')
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id'); 
        $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id') ;
            
        $anggaran_bumn  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id');
            // ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id');           
        $anggaran_program  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
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

        
        if ($request->pilar_pembangunan) {
            $anggaran = $anggaran->where('pilar_pembangunans.nama', $request->pilar_pembangunan);
            $anggaran_pilar = $anggaran_pilar->where('pilar_pembangunans.nama', $request->pilar_pembangunan);
            $anggaran_bumn = $anggaran_bumn->where('pilar_pembangunans.nama', $request->pilar_pembangunan);
            $anggaran_program = $anggaran_program->where('pilar_pembangunans.nama', $request->pilar_pembangunan);
        }

        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        // dd($jenis_anggaran);
        $anggaran = $anggaran->where('tpbs.jenis_anggaran', $jenis_anggaran);
        $anggaran_pilar = $anggaran_pilar->where('tpbs.jenis_anggaran', $jenis_anggaran);
        $anggaran_bumn = $anggaran_bumn->where('tpbs.jenis_anggaran', $jenis_anggaran);
        $anggaran_program = $anggaran_program->where('tpbs.jenis_anggaran', $jenis_anggaran);
     

        if ($request->tpb) {
            $anggaran = $anggaran->where('tpbs.no_tpb', $request->tpb);
            $anggaran_pilar = $anggaran_pilar->where('tpbs.no_tpb', $request->tpb);
            $anggaran_bumn = $anggaran_bumn->where('tpbs.no_tpb', $request->tpb);
            $anggaran_program = $anggaran_program->where('tpbs.no_tpb', $request->tpb);
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
      

        

        $anggaran_pilar = $anggaran_pilar->select(
            'anggaran_tpbs.perusahaan_id',
            'anggaran_tpbs.tahun',            
            // 'relasi_pilar_tpbs.pilar_pembangunan_id',
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid'),
            'pilar_pembangunans.nama as pilar_nama',
            // 'pilar_pembangunans.id as pilar_id'
            // 'pilar_pembangunans.id as pilar_id'
        )
            ->groupBy(
                // 'relasi_pilar_tpbs.pilar_pembangunan_id',
                // 'relasi_pilar_tpbs.pilar_pembangunan_id',
                'anggaran_tpbs.perusahaan_id',
                'anggaran_tpbs.tahun',
                'pilar_pembangunans.nama',
                // 'pilar_pembangunans.id',
                // 'pilar_pembangunans.id',

            )
            // ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
            ->orderBy('pilar_pembangunans.nama')
            // ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
            ->orderBy('pilar_pembangunans.nama')
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

            $anggaran_program = $anggaran_program->select(
                'anggaran_tpbs.perusahaan_id',
                'perusahaans.nama_lengkap',
                'perusahaans.id',            
                DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then target_tpbs.anggaran_alokasi else 0 end) as sum_anggaran_kegiatan_cid'),
                DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then target_tpbs.anggaran_alokasi else 0 end) as sum_anggaran_kegiatan_noncid')
            )
                ->groupBy('anggaran_tpbs.perusahaan_id')
                ->groupBy('perusahaans.nama_lengkap')
                ->groupBy('perusahaans.id')
                
                ->get();
        $anggaran = $anggaran->select('*', 'anggaran_tpbs.id as id_anggaran',
        'pilar_pembangunans.nama as pilar_nama', 'tpbs.nama as tpb_nama', DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'))
                ->orderBy('pilar_pembangunans.nama')
                ->orderBy('no_tpb')
                // ->where('anggaran_tpbs', '!=', null)
                ->get();        
    //    dd($anggaran);

        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'anggaran' => $anggaran,
            'anggaran_pilar' => $anggaran_pilar,
            'anggaran_bumn' => $anggaran_bumn,
            'pilar' => PilarPembangunan::select(DB::raw('DISTINCT ON (nama) *'))->where('is_active', true)->orderBy('nama')->orderBy('id')->get(),
            'tpb' => Tpb::select(DB::raw('DISTINCT ON (no_tpb) *'))->orderBy('no_tpb')->orderBy('id')->get(),
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
        $admin_bumn = false;
        $view_only = false;
        // if (!empty($users->getRoleNames())) {
        //     foreach ($users->getRoleNames() as $v) {
        //         if ($v == 'Admin BUMN') {
        //             $admin_bumn = true;
        //             $perusahaan_id = \Auth::user()->id_bumn;
        //         }
        //         if ($v == 'Admin Stakeholder') {
        //             $view_only = true;
        //         }
        //     }
        // }
        $versi = VersiPilar::whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', date('Y-m-d'))->first();
        $versi_pilar_id = $versi->id;
        // $pilars = DB::table('relasi_pilar_tpbs')
        //     ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        //     ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->where('versi_pilar_id', $versi->id)
        //     ->get(['relasi_pilar_tpbs.id', 'pilar_pembangunans.nama as pilar_name', 'pilar_pembangunans.jenis_anggaran as pilar_jenis_anggaran', 'tpbs.nama as tpb_name', 'tpbs.jenis_anggaran as tpb_jenis_anggaran']);


        // $current = AnggaranTpb::join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        //     ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        //     ->where('perusahaan_id', $perusahaan_id)
        //     ->where('tahun', $tahun)
        //     ->get();

        $pilars = DB::table('relasi_pilar_tpbs')
            ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun){
                $join->on('anggaran_tpbs.relasi_pilar_tpb_id', '=', 'relasi_pilar_tpbs.id')
                    ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                    ->where('anggaran_tpbs.tahun', $tahun);
            })
            ->where('versi_pilar_id', $versi->id)            
            ->get(['relasi_pilar_tpbs.id', 'pilar_pembangunans.nama as pilar_name', 'pilar_pembangunans.jenis_anggaran as pilar_jenis_anggaran', 'tpbs.nama as tpb_name', 'tpbs.jenis_anggaran as tpb_jenis_anggaran', 'anggaran_tpbs.anggaran', 'tpbs.no_tpb as tpb_no_tpb']);


        // if (count($current) > 0) {
        //     $actionform = 'update';
        // } else {
        //     $actionform = 'insert';
        // }

        // foreach ($pilars as $key => $pilar) {
        //     foreach ($current as $key => $current2) {

        //         if ($pilar->id == $current2->relasi_pilar_tpb_id) {

        //             $pilarArray = (array) $pilar; // convert object to array
        //             $pilarArray['anggaran'] = $current2->anggaran; // add new key
        //             $pilars[$key] = (object) $pilarArray; // convert array back to object and assign it to $pilars
        //         }
        //     }
        // }
        $pilars = $pilars->groupBy([
            'pilar_name',
            function ($item) {
                return $item->tpb_name;
            }
        ])->sortByDesc(null);
        
        // dd($pilars);

        //untuk View tabelnya
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

        $anggaran       = AnggaranTpb::select('relasi_pilar_tpbs.pilar_pembangunan_id', 'anggaran_tpbs.*', 'tpbs.nama as tpb_nama', 'tpbs.no_tpb as no_tpb', 
        'target_tpbs.kriteria_program_prioritas as kriteria_program_prioritas', 
        'target_tpbs.kriteria_program_umum as kriteria_program_umum', 
        'target_tpbs.kriteria_program_csv as kriteria_program_csv')
            ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id'); 
        $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id') ;
            
        $anggaran_bumn  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id');
            // ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id');           
        $anggaran_program  = AnggaranTpb::leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->leftJoin('perusahaans', 'perusahaans.id', 'anggaran_tpbs.perusahaan_id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
            ->leftJoin('target_tpbs', 'target_tpbs.anggaran_tpb_id', 'anggaran_tpbs.id');         

        if ($perusahaan_id) {
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_program = $anggaran_program->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
        }

        
        if ($tahun) {
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_program = $anggaran_program->where('anggaran_tpbs.tahun', $tahun);
        }

        
       

        
        // dd($jenis_anggaran);
        $anggaran = $anggaran->where('tpbs.jenis_anggaran', $jenis_anggaran);
        $anggaran_pilar = $anggaran_pilar->where('tpbs.jenis_anggaran', $jenis_anggaran);
        $anggaran_bumn = $anggaran_bumn->where('tpbs.jenis_anggaran', $jenis_anggaran);
        $anggaran_program = $anggaran_program->where('tpbs.jenis_anggaran', $jenis_anggaran);
     

       

        
      

        

        $anggaran_pilar = $anggaran_pilar->select(
            'anggaran_tpbs.perusahaan_id',
            'anggaran_tpbs.tahun',            
            // 'relasi_pilar_tpbs.pilar_pembangunan_id',
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_cid'),
            DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then anggaran_tpbs.anggaran else 0 end) as sum_anggaran_noncid'),
            'pilar_pembangunans.nama as pilar_nama',
            // 'pilar_pembangunans.id as pilar_id'
            // 'pilar_pembangunans.id as pilar_id'
        )
            ->groupBy(
                // 'relasi_pilar_tpbs.pilar_pembangunan_id',
                // 'relasi_pilar_tpbs.pilar_pembangunan_id',
                'anggaran_tpbs.perusahaan_id',
                'anggaran_tpbs.tahun',
                'pilar_pembangunans.nama',
                // 'pilar_pembangunans.id',
                // 'pilar_pembangunans.id',

            )
            // ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
            ->orderBy('pilar_pembangunans.nama')
            // ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
            ->orderBy('pilar_pembangunans.nama')
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

            $anggaran_program = $anggaran_program->select(
                'anggaran_tpbs.perusahaan_id',
                'perusahaans.nama_lengkap',
                'perusahaans.id',            
                DB::Raw('sum(case when tpbs.jenis_anggaran = \'CID\' then target_tpbs.anggaran_alokasi else 0 end) as sum_anggaran_kegiatan_cid'),
                DB::Raw('sum(case when tpbs.jenis_anggaran = \'non CID\' then target_tpbs.anggaran_alokasi else 0 end) as sum_anggaran_kegiatan_noncid')
            )
                ->groupBy('anggaran_tpbs.perusahaan_id')
                ->groupBy('perusahaans.nama_lengkap')
                ->groupBy('perusahaans.id')
                
                ->get();
        $anggaran = $anggaran->select('*', 'anggaran_tpbs.id as id_anggaran',
        'pilar_pembangunans.nama as pilar_nama', 'tpbs.nama as tpb_nama', DB::Raw('(case when tpbs.jenis_anggaran = \'non CID\' then anggaran end) as anggaran_noncid'), DB::Raw('(case when tpbs.jenis_anggaran = \'CID\' then anggaran end) as anggaran_cid'))
                ->orderBy('pilar_pembangunans.nama')
                ->orderBy('no_tpb')
                // ->where('anggaran_tpbs', '!=', null)
                ->get();        
        return view(
            $this->__route . '.create',
            [
                'pagetitle' => $this->pagetitle,
                'breadcrumb' => '',
                'pilars' => $pilars,
                'perusahaan_id' => $perusahaan_id,
                'tahun' => $tahun,
                'actionform' => '-',
                'nama_perusahaan' => Perusahaan::find($perusahaan_id)->nama_lengkap,
                // 'pilar' => PilarPembangunan::get(),
                // 'versi_pilar_id' => $versi_pilar_id,
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'admin_bumn' => $admin_bumn,
                'tpb' => Tpb::get(),
                'tpb_id' => $request->tpb ?? '',
                'core_subject' => CoreSubject::get(),
                // 'perusahaan_id' => $perusahaan_id,
                // 'data' => $anggaran_tpb
                //untuk View
                'jenis_anggaran' => $jenis_anggaran,
                'anggaran_bumn' => $anggaran_bumn,
                'anggaran_pilar' => $anggaran_pilar,
                'anggaran' => $anggaran,
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
        //
        $anggaran_tpb = DB::table('anggaran_tpbs')
        ->selectRaw('anggaran_tpbs.*, relasi_pilar_tpbs.id as relasi_pilar_tpb_id, relasi_pilar_tpbs.versi_pilar_id as versi_pilar_id,  relasi_pilar_tpbs.pilar_pembangunan_id as pilar_pembangunan_id,  relasi_pilar_tpbs.tpb_id as tpb_id ')
        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        ->where('tahun', $request->tahun)
        ->where('perusahaan_id', $request->perusahaan_id)
        ->where('tpb_id', $request->data['tpb_id'])
        ->get( );
       
        // dd($anggaran_tpb[0]->id);
        DB::beginTransaction();
        try {
           
            //code...
            $target_tpb = new TargetTpb();
            $target_tpb->anggaran_tpb_id = $anggaran_tpb[0]->id;
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
            // dd('harusnya berhasil');
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

    public static function store_log($target_tpb_id, $status_id)
    {  
        $param['target_tpb_id'] = $target_tpb_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogTargetTpb::create((array)$param);
    }
}
