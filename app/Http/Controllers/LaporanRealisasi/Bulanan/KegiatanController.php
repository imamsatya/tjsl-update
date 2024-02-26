<?php

namespace App\Http\Controllers\LaporanRealisasi\Bulanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
use App\Models\Kegiatan;
use App\Models\KegiatanRealisasi;
use App\Models\LogKegiatan;
use App\Models\SubKegiatan;
use App\Models\LaporanRealisasiBulananUpload;
use Datatables;
use DB;
use Session;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Excel;
use App\Exports\LaporanRealisasiTemplateExcelSheet;
use App\Exports\LaporanRealisasiGagalUploadExcelSheet;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Imports\LaporanRealisasiBulananImport;
use DateTime;

// use Illuminate\Support\Facades\Storage;
// use Illuminate\Http\UploadedFile;
use App\Models\DownloadKegiatanExport;
use App\Models\DownloadKegiatanZip;
use App\Jobs\DownloadKegiatan;
use App\Exports\KegiatanBulanExport;
use App\Jobs\ZipKegiatanFiles;
use ZipArchive;
use Auth;
class KegiatanController extends Controller
{

    public function __construct()
    {

        $this->__route = 'laporan_realisasi.bulanan.kegiatan';
        $this->pagetitle = 'Laporan Realisasi Kegiatan';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd('halo');
        // dd($request->kriteria_program);
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();

        $admin_bumn = false;
        $view_only = false;
        // $perusahaan_id = $request->perusahaan_id ?? 'all';
        $perusahaan_id = $request->perusahaan_id ? (Crypt::decryptString($request->perusahaan_id)) : 'all' ;
        
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

      

        $tahun = $request->tahun ? $request->tahun : (int)date('Y');
        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        $jenis_kegiatan_id = $request->jenis_kegiatan ?? '';
        $jenis_kegiatan = DB::table('jenis_kegiatans')->where('is_active', true)->get();
        // dd($jenis_anggaran);
        
        //Kegiatan
        $program = DB::table('target_tpbs')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            if ($perusahaan_id != 'all' && $perusahaan_id != "") {
             
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }

            if ($perusahaan_id == 'all' || $perusahaan_id == "") {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                // ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }
            
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        ->select(
            'target_tpbs.*',

            'anggaran_tpbs.id as anggaran_tpb_id',
            'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
            'tpbs.id as tpb_id',
            'tpbs.jenis_anggaran'
        )
        ->get();
        // $currentMonth = (int) date('n');
       
        $bulan = $request->bulan_id;
        // $bulan = $request->bulan_id ??  'all';
        $tahun = $request->tahun ?? date('Y');
        
        // $perusahaan_id = $request->perusahaan_id ?? 'all';
     
        $tahun = $request->tahun ?? date('Y');
        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        // dd($perusahaan_id);

        // dd($bulan);
        // dd($program);
        
        $kegiatan = DB::table('kegiatans')
        ->join('kegiatan_realisasis', function($join) use ($bulan, $tahun) {
            $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->where(function($query) use ($bulan) {
                    if ($bulan !== null) {
                        $query->where('kegiatan_realisasis.bulan', $bulan);
                    }
                })
                ->where('kegiatan_realisasis.tahun', $tahun);
        })
        ->join('bulans', 'bulans.id', 'kegiatan_realisasis.bulan')
        ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            if ($perusahaan_id != 'all'  && $perusahaan_id != "") {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }

            if ($perusahaan_id == 'all' || $perusahaan_id == "") {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                // ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }
            
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
    
        ->join('tpbs', function($join) use ($jenis_anggaran) {
            
            $join->on('tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
                ->where('tpbs.jenis_anggaran', $jenis_anggaran);
        })
        ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
        ->leftJoin('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
        ->leftJoin('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
        ->leftJoin('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
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
            'bulans.nama as bulan_nama',
            'bulans.id as bulan_id'
        );

        $kegiatanDefault = $kegiatan;
       

        if ($request->pilar_pembangunan) {

            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan);
        }

        if ($request->tpb) {

            $kegiatan = $kegiatan->where('tpbs.id', $request->tpb);
        }

        if ($request->program_id) {

            $kegiatan = $kegiatan->where('target_tpbs.id', $request->program_id);
        }

        if ($request->jenis_kegiatan) {

            $kegiatan = $kegiatan->where('jenis_kegiatans.id', $request->jenis_kegiatan);
        }

        $totalAnggaranAlokasi = $kegiatan->get()->sum('anggaran_alokasi');
        $kegiatan = $kegiatan->get();
     
        // dd($kegiatan);
       
        // dd($request->perusahaan_id);
        // dd($kegiatan);
        // $pilar_pembangunan_id = $request->pilar_pembangunan ?? '';
        //     dd($pilar_pembangunan_id);
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
            'jenis_anggaran' => $jenis_anggaran,
            'kriteria_program' => $kriteria_program ?? [],
            'pilar_pembangunan_id' => $request->pilar_pembangunan ?? '',
            // 'tpb_id' => $request->tpb,
            // 'view_only' => $view_only,
            // 'pilar_pembangunan_id' => $request->pilar_pembangunan,
            'tpb_id' => $request->tpb ?? '',
            'view_only' => $view_only,
            'program' => $program,
            'jenis_kegiatan' => $jenis_kegiatan,
            'jenis_kegiatan_id' => $jenis_kegiatan_id,
            'bulan_id' =>  $bulan,
            'program_id' => $request->program_id ?? '',
            'jenis_kegiatan_id' => $request->jenis_kegiatan ?? '',
            'totalAnggaranAlokasi' => $totalAnggaranAlokasi

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($perusahaan_id, $tahun, $bulan)
    {
        $admin_bumn = false;
        $view_only = false;
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $perusahaan_id ? (Crypt::decryptString($perusahaan_id)) : null ;
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
        // dd($perusahaan_id);
        //untuk View tabelnya
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $perusahaan_id ?? 1;

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
        // Kegiatan
        $program = DB::table('target_tpbs')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        ->select(
            'target_tpbs.*',

            'anggaran_tpbs.id as anggaran_tpb_id',
            'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
            'tpbs.id as tpb_id',
            'tpbs.jenis_anggaran'
        )
        ->get();
        // dd($program);

        // $targetTpbs = DB::table('target_tpbs')
        //     ->leftJoin('anggaran_tpbs', 'target_tpbs.anggaran_tpb_id', '=', 'anggaran_tpbs.id')
        //     ->leftJoin('relasi_pilar_tpbs', 'anggaran_tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        //     ->leftJoin('tpbs', 'relasi_pilar_tpbs.tpb_id', '=', 'tpbs.id')
        //     ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
        //     ->where('anggaran_tpbs.tahun', $tahun)
        //     ->select('target_tpbs.*', 'tpbs.jenis_anggaran')
        //     ->get();
        // dd($targetTpbs);
    //    dd($bulan);
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
                'bulan' => Bulan::where('id', '<=', $bulan)->get(),
                // 'pilar' => PilarPembangunan::get(),
                // 'versi_pilar_id' => $versi_pilar_id,
                'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
                'admin_bumn' => $admin_bumn,
                'jenis_kegiatan' => JenisKegiatan::where('is_active', true)->get(),
                'provinsi' => Provinsi::all(),
                'kota_kabupaten' => Kota::all(),
                'satuan_ukur' => SatuanUkur::where('is_active', true)->get(),
                'program' => $program,
                'bulan_id' =>$bulan ?? 1,
                'subkegiatan' => SubKegiatan::where('is_active', true)->get(),
                
           
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

        // dd($request->data['nama_kegiatan']);
  
        //    dd($request);
       $cek_kegiatan = Kegiatan::join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id');
       
       $cek_kegiatan = $cek_kegiatan
       ->where('target_tpb_id',$request->data['program_id'] )
       ->where('kota_id',  $request->data['kota_kabupaten'])
       ->where('kegiatan', $request->data['nama_kegiatan'])
       ->where('bulan', $request->bulan)
       ->where('tahun', $request->tahun)
       ->first();
       if ($cek_kegiatan ) {
            Session::flash('error', "Data Kegiatan sudah ada. Data kegiatan tidak boleh memiliki program, kabupaten/kota, kegiatan, bulan dan tahun yang sama");
            $result = [
                        'flag'  => 'error',
                        'msg' => 'Gagal menambah data.Data Kegiatan sudah ada. Data kegiatan tidak boleh memiliki program, kabupaten/kota, kegiatan, bulan dan tahun yang sama',
                        'title' => 'Error'
            ];
            echo json_encode(['result' => $result]);
            return;
       }
    
        DB::beginTransaction();
        try {
            $kegiatan = new Kegiatan();
            $kegiatan->target_tpb_id = $request->data['program_id'];
            $kegiatan->kegiatan = $request->data['nama_kegiatan'];
            $kegiatan->provinsi_id = $request->data['provinsi'];
            $kegiatan->kota_id = $request->data['kota_kabupaten'];
            $kegiatan->indikator = $request->data['realisasi_indikator'];
            $kegiatan->satuan_ukur_id = $request->data['satuan_ukur'];
            $kegiatan->anggaran_alokasi = $request->data['realisasi_anggaran'];
            $kegiatan->jenis_kegiatan_id = $request->data['jenis_kegiatan'];
            $kegiatan->keterangan_kegiatan = $request->data['keterangan_kegiatan'];
            $kegiatan->save();
    
            $kegiatanGroup = Kegiatan::where('kegiatan', $request->data['nama_kegiatan'])
            ->where('target_tpb_id', $request->data['program_id'])
            ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
            ->orderBy('kegiatan_realisasis.bulan', 'desc')
            ->first();
            $kumulatif_anggaran = $request->data['realisasi_anggaran'];
            if ($kegiatanGroup) {
                $kumulatif_anggaran = $kumulatif_anggaran + $kegiatanGroup->anggaran_total;
            }
            $kegiatanRealisasi = new KegiatanRealisasi();
            $kegiatanRealisasi->kegiatan_id = $kegiatan->id;
            $kegiatanRealisasi->bulan = $request->bulan;
            $kegiatanRealisasi->tahun = $request->tahun;
            // target,realisasi -> null
            $kegiatanRealisasi->anggaran = $request->data['realisasi_anggaran'];
            $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran;
            $kegiatanRealisasi->status_id = 2;//in progress
            $kegiatanRealisasi->save();

            KegiatanController::store_log($kegiatanRealisasi->id,$kegiatanRealisasi->status_id);

            
            DB::commit();
            Session::flash('success', "Berhasil Menyimpan Data Kegiatan");
            $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses tambah data',
                        'title' => 'Sukses'
            ];
            echo json_encode(['result' => $result]);
        } catch (\Throwable $th) {
            //throw $th;
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
        
        $kegiatan = DB::table('kegiatans')
        ->leftjoin('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', 'kegiatans.id')
        ->leftjoin('sub_kegiatans', 'sub_kegiatans.id', '=', DB::raw('CAST(kegiatans.keterangan_kegiatan AS BIGINT)'))
        ->where('kegiatans.id', $request->id)
        ->select('kegiatans.*', )
        ->first();
        // dd($kegiatan);
        $perusahaan_id = $request->perusahaan_id;
        $tahun = $request->tahun;
        $jenis_anggaran = $request->jenis_anggaran;
        
        $program = DB::table('target_tpbs')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        ->select(
            'target_tpbs.*',

            'anggaran_tpbs.id as anggaran_tpb_id',
            'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
            'tpbs.id as tpb_id',
            'tpbs.jenis_anggaran'
        )
        ->get();

        $jenis_kegiatan = DB::table('jenis_kegiatans')->where('is_active', true)->get();
      
        // $kegiatan = DB::table('kegiatans')
        // ->join('kegiatan_realisasis', function($join) use ($bulan, $tahun) {
        //     $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
        //         ->where('kegiatan_realisasis.bulan', $bulan)
        //         ->where('kegiatan_realisasis.tahun', $tahun);
        // })
        // ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
        // ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
        //     $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
        //         ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
        //         ->where('anggaran_tpbs.tahun', $tahun);
        // })
        // ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        // ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        // ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
        // ->join('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
        // ->join('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
        // ->join('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
        // ->select(
        //     'kegiatans.*',
        //     'kegiatan_realisasis.bulan as kegiatan_realisasi_bulan',
        //     'kegiatan_realisasis.tahun as kegiatan_realisasi_tahun',
        //     'kegiatan_realisasis.anggaran as kegiatan_realisasi_anggaran',
        //     'kegiatan_realisasis.anggaran_total as kegiatan_realisasi_anggaran_total',
        //     'kegiatan_realisasis.status_id as kegiatan_realisasi_status_id',
        //     'target_tpbs.program as target_tpb_program',
        //     'jenis_kegiatans.nama as jenis_kegiatan_nama',
        //     'provinsis.nama as provinsi_nama',
        //     'kotas.nama as kota_nama',
        //     'anggaran_tpbs.id as anggaran_tpb_id',
        //     'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
        //     'tpbs.id as tpb_id',
        //     'tpbs.jenis_anggaran',
        //     'satuan_ukur.nama as satuan_ukur_nama'
        // )
        // ->get();

        // dd($kegiatan);
        try {
            // $data = TargetTpb::find((int)$request->input('program'));
            // $anggaran_tpbs = AnggaranTpb::find($data->anggaran_tpb_id);
            // $perusahaan_id = $anggaran_tpbs->perusahaan_id;
            // $tahun = $anggaran_tpbs->tahun;
            // $tpbs_temp = Tpb::find($data->tpb_id);
            return view($this->__route . '.edit', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
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
                'kegiatan' => $kegiatan,
                'program' => $program,
                'jenis_kegiatan' => $jenis_kegiatan,
                'jenis_anggaran' => $jenis_anggaran,
                'provinsi' => Provinsi::all(),
                'kota_kabupaten' => Kota::all(),
                'satuan_ukur' => SatuanUkur::where('is_active', true)->get(),
                'subkegiatan' => SubKegiatan::all(),

            ]);
        } catch (Exception $e) {
        }
    }

    public function editStore(Request $request) {

        // dd($request);
        // dd($request->data['kegiatan_data']['id']);
       
        // dd(Kegiatan::where('id', 84372)->first());
        // dd($request);
        DB::beginTransaction();
        try {
            $kegiatan = Kegiatan::where('id', $request->data['kegiatan_data']['id'])->first();
            $kegiatan->target_tpb_id = $request->data['program_id_edit'];
            $kegiatan->kegiatan = $request->data['nama_kegiatan_edit'];
            $kegiatan->provinsi_id = $request->data['provinsi_edit'];
            $kegiatan->kota_id = $request->data['kota_kabupaten_edit'];
            $kegiatan->indikator = $request->data['realisasi_indikator_edit'];
            $kegiatan->satuan_ukur_id = $request->data['satuan_ukur_edit'];
            $kegiatan->anggaran_alokasi = $request->data['realisasi_anggaran_edit'];
            $kegiatan->save();

            $kegiatanRealisasi = KegiatanRealisasi::where('kegiatan_id',$request->data['kegiatan_data']['id'] )->first();
            $kegiatanRealisasi->anggaran = $kegiatan->anggaran_alokasi;
            $kegiatanRealisasi->save();
            KegiatanController::store_log( $kegiatanRealisasi->id, 2);//in progress

            //cek ulang kumulatif anggaran versi sebelumnya
            $kegiatanGroupOld = Kegiatan::where('kegiatan', $request->data['kegiatan_data']['kegiatan'])
            ->where('target_tpb_id', $request->data['kegiatan_data']['target_tpb_id'])
            ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
            ->orderBy('kegiatan_realisasis.bulan')
            ->get();
            
            $kumulatif_anggaran_old = 0;
            foreach ($kegiatanGroupOld as $key => $kegiatan) {
           
                $kumulatif_anggaran_old = $kumulatif_anggaran_old + $kegiatan->anggaran;
                $kegiatanRealisasi = KegiatanRealisasi::where('id', $kegiatan->id )->first();
                $kegiatanRealisasi->anggaran = $kegiatan->anggaran_alokasi;
                $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran_old;
                $kegiatanRealisasi->save();
            }

            //cek ulang kumulatif anggaran versi baru
            $kegiatanGroupNew = Kegiatan::where('kegiatan',  $kegiatan->kegiatan)
            ->where('target_tpb_id', $kegiatan->target_tpb_id)
            ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
            ->orderBy('kegiatan_realisasis.bulan')
            ->get();

            $kumulatif_anggaran_new = 0;
            foreach ($kegiatanGroupNew as $key => $kegiatan) {
           
                $kumulatif_anggaran_new = $kumulatif_anggaran_new + $kegiatan->anggaran;
                $kegiatanRealisasi = KegiatanRealisasi::where('id', $kegiatan->id )->first();
                $kegiatanRealisasi->anggaran = $kegiatan->anggaran_alokasi;
                $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran_new;
                $kegiatanRealisasi->save();
            }
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

    public function datatable(Request $request)
    {
        // dd($request);
        
        // $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        // $laporan_manajemen = DB::table('laporan_manajemens')->selectRaw('laporan_manajemens.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap')
        // ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'laporan_manajemens.perusahaan_id')->where('periode_laporan_id', $periode_rka_id)->where('perusahaan_masters.induk', 0);
        if((int)preg_replace('/[^0-9]/','',ini_get('memory_limit')) < 512){
            ini_set('memory_limit','-1');
            ini_set('max_execution_limit','0');
        }

        
        $perusahaan_id = $request->perusahaan_id ?? 'all';
        // $perusahaan_id = $request->perusahaan_id ? (Crypt::decryptString($request->perusahaan_id)) : 'all' ;
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? date('Y');
        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        // dd($perusahaan_id);
        $kegiatan = DB::table('kegiatans')
        ->join('kegiatan_realisasis', function($join) use ($bulan, $tahun) {
            $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->where(function($query) use ($bulan) {
                    if ($bulan !== null) {
                        $query->where('kegiatan_realisasis.bulan', $bulan);
                    }
                })
                ->where('kegiatan_realisasis.tahun', $tahun);
        })
        ->join('bulans', 'bulans.id', 'kegiatan_realisasis.bulan')
        ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            if ($perusahaan_id != 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }

            if ($perusahaan_id == 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                // ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }
            
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
    
        ->join('tpbs', function($join) use ($jenis_anggaran) {
            $join->on('tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
                ->where('tpbs.jenis_anggaran', $jenis_anggaran);
        })
        ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
        ->leftJoin('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
        ->leftJoin('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
        ->leftJoin('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
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
        );

        if ($request->pilar_pembangunan_id) {

            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if ($request->tpb_id) {

            $kegiatan = $kegiatan->where('tpbs.id', $request->tpb_id);
        }

        if ($request->program_id) {

            $kegiatan = $kegiatan->where('target_tpbs.id', $request->program_id);
        }

        if ($request->jenis_kegiatan) {

            $kegiatan = $kegiatan->where('jenis_kegiatans.id', $request->jenis_kegiatan);
        }

        // $kegiatan = $kegiatan->get();
        // dd($kegiatan);
       
        try {
            return datatables()->of($kegiatan)
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
                ->rawColumns(['id', 'target_tpb_program', 'kegiatan', 'jenis_kegiatan_nama', 'provinsi_nama','kota_nama', 'anggaran_alokasi', 'indikator', 'kegiatan_realisasi_status_id', 'action'])
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

    public static function store_log($kegiatan_id, $status_id)
    {  
        $param['kegiatan_id'] = $kegiatan_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogKegiatan::create((array)$param);
    }

    public function log_status(Request $request)
    {
        $kegiatanRealisasi = KegiatanRealisasi::where('kegiatan_id', (int)$request->input('id'))->first();

        $log = LogKegiatan::select('log_kegiatans.*', 'users.name AS user', 'statuses.nama AS status')
            ->leftjoin('users', 'users.id', '=', 'log_kegiatans.user_id')
            ->leftjoin('statuses', 'statuses.id', '=', 'log_kegiatans.status_id')
            ->where('kegiatan_id', $kegiatanRealisasi->id)
            ->orderBy('created_at')
            ->get();

        return view($this->__route . '.log_status', [
            'pagetitle' => 'Log Status',
            'log' => $log
        ]);
    }

    public function delete(Request $request) {
        
      
         
         
       
        DB::beginTransaction();
        try {
            foreach ($request->kegiatan_deleted as $key => $kegiatan_id) {
                //kegiatan
                $kegiatan = Kegiatan::where('id', $kegiatan_id)->first();
                
                //save old kegiatan nama dan target_tpb_id
                $kegiatan_nama = $kegiatan->kegiatan;
                $target_tpb_id = $kegiatan->target_tpb_id;
    
                //delete kegiatan dan kegiatan realisasis
                $kegiatan_realisasis = KegiatanRealisasi::where('kegiatan_id', $kegiatan_id)->first();
                $kegiatan_realisasis->delete();
                $kegiatan->delete();
                //cek ulang kumulatif anggaran versi sebelumnya
                $kegiatanGroupOld = Kegiatan::where('kegiatan', $kegiatan_nama)
                ->where('target_tpb_id', $target_tpb_id)
                ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->orderBy('kegiatan_realisasis.bulan')
                ->get();
    
                $kumulatif_anggaran_old = 0;
                foreach ($kegiatanGroupOld as $key => $kegiatanOld) {
               
                    $kumulatif_anggaran_old = $kumulatif_anggaran_old + $kegiatanOld->anggaran;
                    $kegiatanRealisasi = KegiatanRealisasi::where('id', $kegiatanOld->id )->first();
                    $kegiatanRealisasi->anggaran = $kegiatanOld->anggaran_alokasi;
                    $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran_old;
                    $kegiatanRealisasi->save();
                }
                
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

    public function verifikasiData(Request $request) {

      
        DB::beginTransaction();
        try {
            foreach ($request->kegiatan_verifikasi as $key => $kegiatan_id) {
                $kegiatan = Kegiatan::where('id', $kegiatan_id)->first();
                
                //save old kegiatan nama dan target_tpb_id
                $kegiatan_nama = $kegiatan->kegiatan;
                $target_tpb_id = $kegiatan->target_tpb_id;
    
                //delete kegiatan dan kegiatan realisasis
                $kegiatan_realisasi = KegiatanRealisasi::where('kegiatan_id', $kegiatan_id)->first();
                if($kegiatan_realisasi && $kegiatan_realisasi->status_id !== 1) {
                    $kegiatan_realisasi->status_id = 1;
                    $kegiatan_realisasi->save();
                    KegiatanController::store_log($kegiatan_realisasi->id, 1);//finish


                }
                
        }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal verifikasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
    public function batalVerifikasiData(Request $request) {

      
        DB::beginTransaction();
        try {
            foreach ($request->kegiatan_verifikasi as $key => $kegiatan_id) {
                $kegiatan = Kegiatan::where('id', $kegiatan_id)->first();
                
                //save old kegiatan nama dan target_tpb_id
                $kegiatan_nama = $kegiatan->kegiatan;
                $target_tpb_id = $kegiatan->target_tpb_id;
    
                //delete kegiatan dan kegiatan realisasis
                $kegiatan_realisasi = KegiatanRealisasi::where('kegiatan_id', $kegiatan_id)->first();
                if($kegiatan_realisasi && $kegiatan_realisasi->status_id == 1) {
                    $kegiatan_realisasi->status_id = 2;
                    $kegiatan_realisasi->save();
                    KegiatanController::store_log($kegiatan_realisasi->id, 2);//in Progress


                }
                
        }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal verifikasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
    public function finalVerifikasiData(Request $request) {

      
        DB::beginTransaction();
        try {
            foreach ($request->kegiatan_verifikasi as $key => $kegiatan_id) {
                $kegiatan = Kegiatan::where('id', $kegiatan_id)->first();
                
                //save old kegiatan nama dan target_tpb_id
                $kegiatan_nama = $kegiatan->kegiatan;
                $target_tpb_id = $kegiatan->target_tpb_id;
    
                //delete kegiatan dan kegiatan realisasis
                $kegiatan_realisasi = KegiatanRealisasi::where('kegiatan_id', $kegiatan_id)->first();
                if($kegiatan_realisasi && $kegiatan_realisasi->status_id == 1) {
                    $kegiatan_realisasi->status_id = 4;
                    $kegiatan_realisasi->save();
                    KegiatanController::store_log($kegiatan_realisasi->id, 4);//Verified


                }
                
        }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal verifikasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function batalFinalVerifikasiData(Request $request) {

      
        DB::beginTransaction();
        try {
            foreach ($request->kegiatan_verifikasi as $key => $kegiatan_id) {
                $kegiatan = Kegiatan::where('id', $kegiatan_id)->first();
                
                //save old kegiatan nama dan target_tpb_id
                $kegiatan_nama = $kegiatan->kegiatan;
                $target_tpb_id = $kegiatan->target_tpb_id;
    
                //delete kegiatan dan kegiatan realisasis
                $kegiatan_realisasi = KegiatanRealisasi::where('kegiatan_id', $kegiatan_id)->first();
                if($kegiatan_realisasi && $kegiatan_realisasi->status_id == 4) {
                    $kegiatan_realisasi->status_id = 2;
                    $kegiatan_realisasi->save();
                    KegiatanController::store_log($kegiatan_realisasi->id, $kegiatan_realisasi->status_id);//In Progress


                }
                
        }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses Unverify data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal verifikasi data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function detail(Request $request)
    {
        // dd($request);
        // try{
          
            // $kegiatan  = Kegiatan::find((int)$request->input('id'));
            $kegiatan = DB::table('kegiatans')->where('kegiatans.id', (int)$request->input('id'))
            ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
            ->join('tpbs', 'tpbs.id', 'target_tpbs.tpb_id')
            ->join('anggaran_tpbs', 'anggaran_tpbs.id', 'target_tpbs.anggaran_tpb_id')
            ->join('perusahaan_masters', 'perusahaan_masters.id', 'anggaran_tpbs.perusahaan_id')
            ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
            ->join('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->leftJoin('provinsis', 'provinsis.id', 'kegiatans.provinsi_id')
            ->leftJoin('kotas', 'kotas.id', 'kegiatans.kota_id')
            ->leftJoin('satuan_ukur', 'satuan_ukur.id', 'kegiatans.satuan_ukur_id')
            ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', 'kegiatans.id')
            ->join('bulans', 'bulans.id', 'kegiatan_realisasis.bulan')
            ->select('kegiatans.*', 'perusahaan_masters.nama_lengkap as nama_perusahaan', 'pilar_pembangunans.nama as nama_pilar', 'provinsis.nama as provinsi', 'kotas.nama as kota'
            ,'satuan_ukur.nama as satuan_ukur','target_tpbs.program as program', 'tpbs.jenis_anggaran as jenis_anggaran', 'tpbs.no_tpb as no_tpb', 'tpbs.nama as nama_tpb', 'bulans.nama as nama_bulan')->first();
            
            $kumpulanKegiatan = DB::table('kegiatans')->where('kegiatan', $kegiatan->kegiatan)->where('target_tpb_id', $kegiatan->target_tpb_id)->where('provinsi_id', $kegiatan->provinsi_id)->where('kota_id', $kegiatan->kota_id)->get();
            $kumpulanKegiatanId = $kumpulanKegiatan->pluck('id');
            // dd($kumpulanKegiatan->pluck('id'));
        //   dd($kegiatan);
            $tahun     = KegiatanRealisasi::select('tahun')->where('kegiatan_id', $kegiatan->id)->groupBy('tahun')->orderBy('tahun')->get();
            
            $realisasi = KegiatanRealisasi::select('kegiatan_realisasis.*','kegiatans.target_tpb_id','kegiatans.kegiatan', 'bulans.nama as bulan_nama', 'jenis_kegiatans.nama as jenis_kegiatan_nama', 'sub_kegiatans.subkegiatan as sub_kegiatan_nama')
                        ->leftjoin('kegiatans','kegiatans.id','kegiatan_realisasis.kegiatan_id')
                        ->join('bulans', 'bulans.id', 'kegiatan_realisasis.bulan')->whereIn('kegiatan_realisasis.kegiatan_id', $kumpulanKegiatanId)
                        ->leftjoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
                        ->leftJoin('sub_kegiatans', function ($join) {
                            $join->on('sub_kegiatans.id', '=', DB::raw("CAST(kegiatans.keterangan_kegiatan AS bigint)"));
                        })
                        ->get();
                        
            $realisasi_total = KegiatanRealisasi::whereIn('kegiatan_id', $kumpulanKegiatanId)->select(DB::Raw('sum(anggaran) as total'))->first();
            // dd($realisasi);

            $realisasi_by_api = [];
            if($realisasi){
                if($realisasi[0]->sumber_data !== null){
                    //akumulasi nilai realisasi sebelumnya
                    $realisasi_by_api = Kegiatan::leftjoin('kegiatan_realisasis','kegiatan_realisasis.kegiatan_id','kegiatans.id')
                                    ->where('kegiatans.kegiatan',$realisasi[0]->kegiatan)
                                    ->where('kegiatans.target_tpb_id',$realisasi[0]->target_tpb_id)
                                    ->where('kegiatan_realisasis.bulan','<=',(int)$realisasi[0]->bulan)
                                    ->where('kegiatan_realisasis.tahun',(int)$realisasi[0]->tahun)
                                    ->where('kegiatan_realisasis.is_invalid_aplikasitjsl',false);  

                     $realisasi = $realisasi_by_api->get();
                     $realisasi_total['total'] = $realisasi_by_api->sum('kegiatan_realisasis.anggaran');
                }

            }
                // dd($kegiatan);
                return view($this->__route.'.detail',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $kegiatan,
                    'tahun' => $tahun,
                    'anggaran_total' => $realisasi_total->total,
                    'realisasi' => $realisasi,
                ]);
            }
        // }catch(Exception $e){}

    public function downloadTemplate(Request $request) {
        
        // $perusahaan_id = $perusahaan_id ? (Crypt::decryptString($perusahaan_id)) : null ;
        $perusahaan_id = ($request->perusahaan_id?Crypt::decryptString($request->perusahaan_id):1);
        
        $bulan = ($request->bulan ?? date('m'));
        $tahun = ($request->tahun ?? date('Y'));
        
        $perusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        $namaFile = "Template Laporan Realisasi.xlsx";

        return Excel::download(new LaporanRealisasiTemplateExcelSheet($perusahaan,$bulan,$tahun), $namaFile);
    }

    public function uploadExcel(Request $request) {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $param['file_name'] = $request->input('file_name');

        try {
            $realisasi = LaporanRealisasiBulananUpload::create((array)$param);

            $dataUpload = $this->uploadFile($request->file('file_name'), $realisasi->id);
            //Versi1
            Excel::import(new LaporanRealisasiBulananImport($dataUpload->fileRaw, $realisasi->id), public_path('file_upload/laporan_realisasi/kegiatan/bulanan/'.$dataUpload->fileRaw));

            //Versi 2
            // $file = 'file_upload/laporan_realisasi/kegiatan/bulanan/' . $dataUpload->fileRaw;
            // // Import data from Excel using Laravel Excel
            // Excel::import(new LaporanRealisasiBulananImport($dataUpload->fileRaw, $realisasi->id), Storage::path($file));
            
            $param2['file_name']  = $dataUpload->fileRaw;
            $param2['user_id']  = \Auth::user()->id;
            $realisasi->update((array)$param2);

            $result = LaporanRealisasiBulananUpload::find($realisasi->id);
            if(!$result) {
                DB::rollback();
                $result = [
                    'flag'  => 'warning',
                    'msg' => 'Gagal upload File',
                    'title' => 'Gagal'
                ];   
            } else {
                if($result->berhasil > 0) {
                    Session::flash('success', "Berhasil Upload Data");
                    DB::commit();
                    $message = 'Sukses tambah data: '.$result->berhasil.' BERHASIL & '.$result->gagal.' GAGAL.';
                    $result = [
                        'flag'  => 'success',
                        'msg' => $message,
                        'title' => 'Sukses'
                    ];                
                } else {
                    DB::rollback();
                    $result = [
                        'flag'  => 'warning',
                        'msg' => 'Gagal upload File',
                        'title' => 'Gagal'
                    ];
                }
                
            }
        }catch(\Exception $e){
            DB::rollback();            
            $result = [
                'flag'  => 'warning',
                'msg' => 'Something went wrong',
                'title' => 'Gagal',
                'err' => $e->getMessage()
            ];
        }

        return response()->json($result);        
    }    

    protected function uploadFile(UploadedFile $file, $id)
    {   
        //Versi 1
        $fileName = $file->getClientOriginalName();
        $fileRaw  =$fileName = $id.'_'.$fileName;
        $filePath = 'file_upload'.DIRECTORY_SEPARATOR.'laporan_realisasi'.DIRECTORY_SEPARATOR.'kegiatan'.DIRECTORY_SEPARATOR.'bulanan'.DIRECTORY_SEPARATOR.$fileName;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'file_upload'.DIRECTORY_SEPARATOR.'laporan_realisasi'.DIRECTORY_SEPARATOR.'kegiatan'.DIRECTORY_SEPARATOR.'bulanan'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;

        //Versi 2
        // $fileName = $file->getClientOriginalName();
        // $fileRaw = $id . '_' . $fileName;
        // $filePath = 'file_upload/laporan_realisasi/kegiatan/bulanan/' . $fileRaw;

        // // Store the file using Laravel Storage
        // Storage::put($filePath, file_get_contents($file));

        // $data = (object) [
        //     'fileName' => $fileName,
        //     'fileRaw' => $fileRaw,
        //     'filePath' => $filePath,
        // ];

        // return $data;
    }

    public function historyUpload(Request $request) {
        $id_users = \Auth::user()->id;
        $perusahaan_id = $request->perusahaan_id ? (Crypt::decryptString($request->perusahaan_id)) : 'all' ;
        $data = LaporanRealisasiBulananUpload::where('perusahaan_id', $perusahaan_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->orderBy('created_at','desc')->get();
        try{
            return datatables()->of($data)
            ->addColumn('tanggal', function ($row){
                $dateTime = new DateTime($row->created_at);
                $formattedDate = date_format($dateTime, 'j F Y H:i:s');
                return $formattedDate;
            })
            ->addColumn('keterangan_trim', function($row) {
                // $maxLength = 100;
                // $ellipsis = "...";
                // $truncatedText = mb_strimwidth($row->keterangan, 0, $maxLength, $ellipsis);

                $truncatedText = $row->keterangan;

                return $truncatedText;
            })
            ->addColumn('download_gagal', function($row) {                
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-warning cls-button-download" data-id="' . $id . '" data-toggle="tooltip" title="Download '  . '"><i class="bi bi-download fs-3"></i></button>';

                $button .= '&nbsp;';

                // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="' . $id . '" data-nama="' . $row->nama . '" data-toggle="tooltip" title="Hapus data ' . $row->nama . '"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                
                if($row->gagal === 0) {
                    $button = '';
                }
                return $button;
            })
            ->rawColumns(['tanggal', 'keterangan_trim', 'download_gagal'])
            ->toJson();
        }catch(Exception $e){
            return response([
                'draw'            => 0,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }   
    }

    public function downloadGagalUpload(Request $request) {
        $id_laporan = $request->id;
        $laporan = LaporanRealisasiBulananUpload::find($id_laporan);

        $perusahaan_id = $laporan->perusahaan_id;
        $bulan = $laporan->bulan;
        $tahun = $laporan->tahun;
        $perusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        $namaFile = "Laporan Realisasi (Gagal Upload).xlsx";

        return Excel::download(new LaporanRealisasiGagalUploadExcelSheet($perusahaan,$bulan,$tahun, $id_laporan), $namaFile);
    }

    public function export(Request $request){
        $perusahaan_id = $request->perusahaan_id ?? 'all';
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? date('Y');
        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        // dd($perusahaan_id);
        $kegiatan = DB::table('kegiatans')
        ->join('kegiatan_realisasis', function($join) use ($bulan, $tahun) {
            $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->where(function($query) use ($bulan) {
                    if ($bulan !== null) {
                        $query->where('kegiatan_realisasis.bulan', $bulan);
                    }
                })
                ->where('kegiatan_realisasis.tahun', $tahun);
        })
        ->join('bulans', 'bulans.id', 'kegiatan_realisasis.bulan')
        ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            if ($perusahaan_id != 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }

            if ($perusahaan_id == 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                // ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }
            
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
    
        ->join('tpbs', function($join) use ($jenis_anggaran) {
            $join->on('tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
                ->where('tpbs.jenis_anggaran', $jenis_anggaran);
        })
        ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
        ->leftjoin('sub_kegiatans', 'sub_kegiatans.id', '=', DB::raw('CAST(kegiatans.keterangan_kegiatan AS BIGINT)'))
        ->join('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
        ->join('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
        ->join('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
        ->join('statuses', 'statuses.id', '=', 'kegiatan_realisasis.status_id')
        ->join('perusahaan_masters', 'perusahaan_masters.id', '=', 'anggaran_tpbs.perusahaan_id')
        ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        ->select(
            'kegiatans.*',
            'kegiatan_realisasis.bulan as kegiatan_realisasi_bulan',
            'kegiatan_realisasis.tahun as kegiatan_realisasi_tahun',
            'kegiatan_realisasis.realisasi as kegiatan_realisasi_realisasi', 
            'kegiatan_realisasis.anggaran as kegiatan_realisasi_anggaran',
            'kegiatan_realisasis.anggaran_total as kegiatan_realisasi_anggaran_total',
            'kegiatan_realisasis.status_id as kegiatan_realisasi_status_id',
            'target_tpbs.id as target_tpb_id',
            'target_tpbs.program as target_tpb_program',
            'jenis_kegiatans.nama as jenis_kegiatan_nama',
            'sub_kegiatans.subkegiatan as sub_kegiatan_nama',
            'provinsis.nama as provinsi_nama',
            'kotas.nama as kota_nama',
            'anggaran_tpbs.id as anggaran_tpb_id',
            'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
            'tpbs.id as tpb_id',
            'tpbs.nama as tpb_nama',
            'tpbs.jenis_anggaran',
            'satuan_ukur.nama as satuan_ukur_nama',
            'bulans.nama as bulan_nama',
            'statuses.nama as nama_status',
            'perusahaan_masters.nama_lengkap as perusahaan_nama_lengkap',
            'pilar_pembangunans.nama as pilar_pembangunan_nama',
            

        );

        if ($request->pilar_pembangunan_id) {

            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if ($request->tpb_id) {

            $kegiatan = $kegiatan->where('tpbs.id', $request->tpb_id);
        }

        if ($request->program_id) {

            $kegiatan = $kegiatan->where('target_tpbs.id', $request->program_id);
        }

        if ($request->jenis_kegiatan) {

            $kegiatan = $kegiatan->where('jenis_kegiatans.id', $request->jenis_kegiatan);
        }

        $kegiatan = $kegiatan->get();


        $namaFile = "Kegiatan ".date('dmY').".xlsx";
        return Excel::download(new KegiatanBulanExport($kegiatan, $request->tahun), $namaFile);   
    }

    public function export_queue(Request $request) {
        $user = Auth::user();  
        $data = $request->all();
        $filter = '';

        $perusahaan_id = $request['perusahaan_id'] ?? 'all';
        $bulan = $request['bulan'];
        $tahun = $request['tahun'] ?? date('Y');
        $jenis_anggaran = $request['jenis_anggaran'] ?? 'CID';
        
        //isi
        // if($data['perusahaan_id']){
        //     $filter .= 'Perusahaan='.$data['perusahaan_id'].' & ';
        // }
        // if ($data['bulan']) {
        //     $filter .= 'Bulan='.$data['bulan'].' & ';
        // }
        // if ($data['tahun']) {
        //     $filter .= 'Tahun='.$data['tahun'].' & ';
        // }
        // if ($data['jenis_anggaran']) {
        //     $filter .= 'Jenis Anggaran='.$data['tahun'].' & ';
        // }
        // if($data['pilar_pembangunan_id']){
        //     $filter .= 'Pilar Pembangunan='.$data['pilar_pembangunan_id'].' & ';
        // }
        // if($data['tpb_id']){
        //     $filter .= 'TPB='.$data['tpb_id'].' & ';
        // }
        // if($data['program_id']){
        //     $filter .= 'Program='.$data['program_id'].' & ';
        // }
        // if($data['jenis_kegiatan']){
        //     $filter .= 'Jenis Kegiatan='.$data['jenis_kegiatan'].' & ';
        // }

        //V2
        if($request['perusahaan_id']){
            $filter .= 'Perusahaan='.Perusahaan::where('id',(int)$data['perusahaan_id'])->pluck('nama_lengkap')->first().' & ';
        }
        if ($request['bulan']) {
            $filter .= 'Bulan='.$request['bulan'].' & ';
        }
        if ($request['tahun']) {
            $filter .= 'Tahun='.$request['tahun'].' & ';
        }
        if ($request['jenis_anggaran']) {
            $filter .= 'Jenis Anggaran='.$jenis_anggaran.' & ';
        }
        if($request['pilar_pembangunan_id']){
            $filter .= 'Pilar Pembangunan='.$request['pilar_pembangunan_id'].' & ';
        }
        if($request['tpb_id']){
            $filter .= 'TPB='.$request['tpb_id'].' & ';
        }
        if($request['program_id']){
            $filter .= 'Program='.$request['program_id'].' & ';
        }
        if($request['jenis_kegiatan']){
            $filter .= 'Jenis Kegiatan='.$request['jenis_kegiatan'].' & ';
        }

        $kegiatan = DB::table('kegiatans')
        ->join('kegiatan_realisasis', function($join) use ($bulan, $tahun) {
            $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->where(function($query) use ($bulan) {
                    if ($bulan !== null) {
                        $query->where('kegiatan_realisasis.bulan', $bulan);
                    }
                })
                ->where('kegiatan_realisasis.tahun', $tahun);
        })
        ->join('bulans', 'bulans.id', 'kegiatan_realisasis.bulan')
        ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            if ($perusahaan_id != 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }

            if ($perusahaan_id == 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                // ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }
            
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
    
        ->join('tpbs', function($join) use ($jenis_anggaran) {
            $join->on('tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
                ->where('tpbs.jenis_anggaran', $jenis_anggaran);
        })
        ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
        ->leftjoin('sub_kegiatans', 'sub_kegiatans.id', '=', DB::raw('CAST(kegiatans.keterangan_kegiatan AS BIGINT)'))
        ->join('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
        ->join('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
        ->join('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
        ->join('statuses', 'statuses.id', '=', 'kegiatan_realisasis.status_id')
        ->join('perusahaan_masters', 'perusahaan_masters.id', '=', 'anggaran_tpbs.perusahaan_id')
        ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        ->select(
            'kegiatans.*',
            'kegiatan_realisasis.bulan as kegiatan_realisasi_bulan',
            'kegiatan_realisasis.tahun as kegiatan_realisasi_tahun',
            'kegiatan_realisasis.realisasi as kegiatan_realisasi_realisasi', 
            'kegiatan_realisasis.anggaran as kegiatan_realisasi_anggaran',
            'kegiatan_realisasis.anggaran_total as kegiatan_realisasi_anggaran_total',
            'kegiatan_realisasis.status_id as kegiatan_realisasi_status_id',
            'target_tpbs.id as target_tpb_id',
            'target_tpbs.program as target_tpb_program',
            'jenis_kegiatans.nama as jenis_kegiatan_nama',
            'sub_kegiatans.subkegiatan as sub_kegiatan_nama',
            'provinsis.nama as provinsi_nama',
            'kotas.nama as kota_nama',
            'anggaran_tpbs.id as anggaran_tpb_id',
            'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
            'tpbs.id as tpb_id',
            'tpbs.nama as tpb_nama',
            'tpbs.jenis_anggaran',
            'satuan_ukur.nama as satuan_ukur_nama',
            'bulans.nama as bulan_nama',
            'statuses.nama as nama_status',
            'perusahaan_masters.nama_lengkap as perusahaan_nama_lengkap',
            'pilar_pembangunans.nama as pilar_pembangunan_nama',
            

        );

        if ($request['pilar_pembangunan_id']) {

            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request['pilar_pembangunan_id']);
        }

        if ($request['tpb_id']) {

            $kegiatan = $kegiatan->where('tpbs.id', $request['tpb_id']);
        }

        if ($request['program_id']) {

            $kegiatan = $kegiatan->where('target_tpbs.id', $request['program_id']);
        }

        if ($request['jenis_kegiatan']) {

            $kegiatan = $kegiatan->where('jenis_kegiatans.id', $request['jenis_kegiatan']);
        }

        $kegiatan = $kegiatan->get();

        $chunkSize = 2000;
        $downloadKegiatanZip = DownloadKegiatanZip::create([
            'description' => 'Kegiatan Bulanan',
            'status' => 'on queue',
            'filter' => $filter,
            'created_at' => date('Y-m-d H:i:s'),
            'user_id' => $user->id,
        ]);    
        $downloadKegiatanZipId = $downloadKegiatanZip->id;
     
        $filesToZip = [];
        if (count($kegiatan) > $chunkSize) {
            $kegiatanChunks = $kegiatan->chunk($chunkSize);
             // Iterate over each chunk and dispatch a job
            $kegiatanChunks->each(function ($chunk, $index) use ($filter, $tahun, &$filesToZip)  {
                $data = $chunk;
                $part= 'Part '.($index+1);
                $download = DownloadKegiatanExport::create([
                    'description' => 'Kegiatan Bulanan',
                    'status' => 'on queue',
                    'filter' => $filter,
                    'created_at' => date('Y-m-d H:i:s')
                ]);    
                $downloadId = $download->id;
                //push
                array_push($filesToZip, $downloadId);   
                // Dispatch a job for the current chunk
                DownloadKegiatan::dispatch($data, $part, $downloadId, $tahun)->onQueue('kegiatan_queue');
            });
            ZipKegiatanFiles::dispatch($filesToZip, $downloadKegiatanZipId)->onQueue('kegiatan_queue');
            echo json_encode(array('result' => 'success', 'message' => 'Data sedang didownload...'));
        }else {
            $data = $kegiatan;
            $part= '';
            $download = DownloadKegiatanExport::create([
                'description' => 'Kegiatan Bulanan',
                'status' => 'on queue',
                'filter' => $filter,
                'created_at' => date('Y-m-d H:i:s')
            ]);    
            $downloadId = $download->id;   

            array_push($filesToZip, $downloadId);
            // Dispatch a job for the current chunk
            DownloadKegiatan::dispatch($data, $part, $downloadId, $tahun)->onQueue('kegiatan_queue');
            
            ZipKegiatanFiles::dispatch($filesToZip, $downloadKegiatanZipId)->onQueue('kegiatan_queue');
            echo json_encode(array('result' => 'success', 'message' => 'Data sedang didownload...'));
        }

        // $download = DownloadKegiatanExport::create([
        //     'description' => 'Kegiatan Bulanan',
        //     'status' => 'on queue',
        //     'filter' => $filter,
        //     'created_at' => date('Y-m-d H:i:s')
        //   ]);    
        //   $data['downloadId'] = $download->id;    
        //   DownloadKegiatan::dispatch($data);
        //   echo json_encode(array('result' => 'success', 'message' => 'Data sedang didownload...'));
    }

    public function datatable_download(Request $request)
    {
        
        try{
        $data = DownloadKegiatanZip::orderBy('id', 'desc');
        return datatables()->eloquent($data)      
        ->editColumn('created_at', function ($row){
            $value = $row->created_at;        
            return $value;
        })
        ->editColumn('updated_at', function ($row){
            $value = $row->updated_at;
            if($row->status != 'done'){
            $value = '-';
            }
            return $value;
        })
        ->addColumn('action', function ($row){
            $button = '-';
            if($row->status == 'done') {          
            $button = '<button data-filename="'.$row->file_path.'" type="button" class="btn btn-sm btn-success btn-icon cls-button-download-finish" data-toggle="tooltip" title="Download Excel"><i class="bi bi-download fs-3"></i></button>';
            }        
            return $button; 
        })
        ->rawColumns(['action'])
        ->toJson();
        }catch(Exception $e){
        return response([
            'draw'      => 0,
            'recordsTotal'  => 0,
            'recordsFiltered' => 0,
            'data'      => []
        ]);
        }
    }

    public function downloadExport(Request $request) 
    {
            $filename = $request->get('filename');
            if($filename) {
              $path = storage_path('app/public/zip_kegiatan/'.$filename);
              return response()->download($path);
            } 
            return;    
    }
}
