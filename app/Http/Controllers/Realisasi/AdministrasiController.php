<?php

namespace App\Http\Controllers\Realisasi;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\Status;
use App\Models\TargetTpb;
use App\Models\SatuanUkur;
use App\Models\Perusahaan;
use App\Models\Kegiatan;
use App\Models\Bulan;
use App\Models\LogKegiatan;
use App\Models\KegiatanRealisasi;
use App\Models\Tpb;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\PilarPembangunan;
use App\Models\OwnerProgram;
use App\Exports\KegiatanTemplateExcelSheet;
use App\Exports\KegiatanExport;

class AdministrasiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'realisasi.administrasi';
        $this->pagetitle = 'Data Kegiatan';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $this->api_sync_by_bumn();
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = ($request->perusahaan_id?$request->perusahaan_id:1);
        $target_tpb_id = $request->target_tpb_id;
       
        $default_perusahaan = Perusahaan::orderby('id','ASC')->pluck('id')->first();
        $perusahaan_id = $request->perusahaan_id? $request->perusahaan_id : $default_perusahaan;
        
        $admin_bumn = false;
        $view_only = false;        
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if($v == 'Admin Stakeholder') {
                    $view_only = true;
                }                
            }
        }

        $can_download_template = true;
        if($admin_bumn){
            $is_finish = Status::whereRaw("lower(replace(nama,' ','')) =?","finish")->pluck('id')->first();
            $cek_program = TargetTpb::select('anggaran_tpbs.perusahaan_id','target_tpbs.*')
                    ->leftjoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                    ->where('anggaran_tpbs.perusahaan_id',$perusahaan_id)
                    ->where('target_tpbs.status_id',$is_finish)
                    ->count();

            if($cek_program > 0){
                $can_download_template = true; 
            }else{
                $can_download_template = false;
            }
        }
        

        $tahun = ($request->tahun?$request->tahun:date('Y'));
        $pilar = PilarPembangunan::get();
        $tpb = Tpb::get();
        $target_tpb = $admin_bumn && $perusahaan_id? TargetTpb::select('anggaran_tpbs.perusahaan_id','anggaran_tpbs.tahun','perusahaans.nama_lengkap AS bumn','target_tpbs.*')
                    ->leftjoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                    ->leftjoin('perusahaans','perusahaans.id','anggaran_tpbs.perusahaan_id')
                    ->where('anggaran_tpbs.perusahaan_id',$perusahaan_id)
                    ->get() : TargetTpb::select('anggaran_tpbs.perusahaan_id','anggaran_tpbs.tahun','perusahaans.nama_lengkap AS bumn','target_tpbs.*')
                    ->leftjoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                    ->leftjoin('perusahaans','perusahaans.id','anggaran_tpbs.perusahaan_id')
                    ->get();

        $owner = OwnerProgram::get();

        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Kegiatan - Administrasi',
            'perusahaan_id' => $perusahaan_id,
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
            'admin_bumn' => $admin_bumn,
            'tahun' => $tahun,
            'pilar' => $pilar,
            'target_tpb' => $target_tpb,
            'target_tpb_id' => $target_tpb_id,
            'bulans' => Bulan::get(),
            'tpb' => $tpb,
            'can_download_template'  => $can_download_template,
            'owner' => $owner,
            'view_only' => $view_only              
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_status(Request $request)
    {
        $kegiatan = KegiatanRealisasi::Select('kegiatan_realisasis.*')
                                ->leftJoin('kegiatans','kegiatans.id','kegiatan_realisasis.kegiatan_id')
                                ->leftJoin('target_tpbs','target_tpbs.id','kegiatans.target_tpb_id')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id');
        
        if($request->perusahaan_id){
            $kegiatan = $kegiatan->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $kegiatan = $kegiatan->where('kegiatan_realisasis.tahun', $request->tahun);
        }

        if($request->bulan){
            $kegiatan = $kegiatan->where('kegiatan_realisasis.bulan', $request->bulan);
        }
        
        $kegiatan = $kegiatan->first();

        $result['status_id'] = @$kegiatan->status_id;

        return response()->json($result);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        $kegiatan = KegiatanRealisasi::select('kegiatans.*',
                                    'kegiatan_realisasis.*',
                                    'kegiatan_realisasis.id as kegiatan_realisasi_id',
                                    'satuan_ukur.nama as satuan_ukur',
                                    'target_tpbs.program',
                                    'target_tpbs.id_owner',
                                    'provinsis.nama as provinsi',
                                    'kotas.nama as kota',
                                    'bulans.nama as bulan')
                                ->leftJoin('kegiatans','kegiatans.id','kegiatan_realisasis.kegiatan_id')
                                ->leftJoin('provinsis','provinsis.id','kegiatans.provinsi_id')
                                ->leftJoin('kotas','kotas.id','kegiatans.kota_id')
                                ->leftJoin('satuan_ukur','satuan_ukur.id','kegiatans.satuan_ukur_id')
                                ->leftJoin('bulans','bulans.id','kegiatan_realisasis.bulan')
                                ->leftJoin('target_tpbs','target_tpbs.id','kegiatans.target_tpb_id')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                                ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id');

        if($request->bulan){
            $kegiatan = $kegiatan->where('kegiatan_realisasis.bulan', $request->bulan);
        }

        if($request->tahun){
            $kegiatan = $kegiatan->where('kegiatan_realisasis.tahun', $request->tahun);
        }
        
        if($request->perusahaan_id){
            $kegiatan = $kegiatan->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->target_tpb_id){
            $kegiatan = $kegiatan->where('kegiatans.target_tpb_id', $request->target_tpb_id);
        }

        if($request->pilar_pembangunan_id){
            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if($request->tpb_id){
            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
        }

        if($request->owner){
            $kegiatan = $kegiatan->where('target_tpbs.id_owner', (int)$request->owner);
        }
        //hanya menampilkan kegiatan valid
        $kegiatan = $kegiatan->where('kegiatans.is_invalid_aplikasitjsl',false)
                    ->where('kegiatan_realisasis.is_invalid_aplikasitjsl',false)
                    ->get();

        try{
            return datatables()->of($kegiatan)
            ->addColumn('action', function ($row){                
                $button = '<div align="center">';
                $id = (int)$row->kegiatan_realisasi_id;

                $id_users = \Auth::user()->id;
                $users = User::where('id', $id_users)->first();
                $view_only = false;        
                if(!empty($users->getRoleNames())){
                    foreach ($users->getRoleNames() as $v) {
                        if($v == 'Admin Stakeholder') {
                            $view_only = true;
                        }                
                    }
                }

            if(!$view_only){
                if($row->status_id!=1){
                    if(auth()->user()->can('edit-kegiatan')){
                        //jika id owner non tjsl
                        $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->kegiatan.'"><i class="bi bi-pencil fs-3"></i></button>';
                    }
                }
            }

                $button .= '&nbsp;';
                $button .= auth()->user()->can('view-kegiatan')?'<button type="button" class="btn btn-sm btn-light btn-icon btn-info cls-button-detail" data-id="'.$id.'" data-toggle="tooltip" title="Detail data '.$row->kegiatan.'"><i class="bi bi-info fs-3"></i></button>':'';

            if(!$view_only){                
                if($row->status_id!=1){ // jika status in progress
                    if(auth()->user()->can('delete-kegiatan')){
                        //jika id owner non tjsl
                        $button .= '&nbsp;';
                        $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->kegiatan.'" data-toggle="tooltip" title="Hapus data '.$row->kegiatan.'"><i class="bi bi-trash fs-3"></i></button>';
                    }
                }
            }
                $button .= '</div>';
                return $button;
            })
            ->addColumn('program', function ($row){
                return @$row->program;
            })
            ->addColumn('kota', function ($row){
                return @$row->provinsi .' - '. @$row->kota;
            })
            ->addColumn('satuan_ukur', function ($row){
                return @$row->satuan_ukur;
            })
            ->editColumn('anggaran', function ($row){
                $anggaran = '<i>Target '.$row->tahun.' : </i> Rp. '.number_format($row->anggaran_alokasi,0,',',',').'<br>';
                $anggaran .= '<i>Realisasi s.d '.$row->bulan.' : </i> Rp. '.number_format($row->anggaran_total,0,',',',').'<br>';
                return $anggaran;
            })
            ->editColumn('bulan', function ($row){
                return $row->bulan .'<br>'.$row->tahun;
            })
            ->editColumn('status', function ($row){
                $status_class = 'primary';
                if($row->status_id == 1){
                    $status_class = 'success';
                }else if($row->status_id == 3){
                    $status_class = 'warning';
                }
                $status = '<span class="btn cls-log badge badge-light-'.$status_class.' fw-bolder me-auto px-4 py-3" data-id='.$row->id.'>'.@$row->status->nama.'</span>';
                return $status;
            })
            ->editColumn('realisasi', function ($row){
                $realisasi = '<i>Target : </i>'.number_format($row->target,0,',',',').' '.$row->satuan_ukur.'<br>';
                $realisasi .= '<i>Realisasi : </i>'.number_format($row->realisasi,0,',',',').' '.$row->satuan_ukur.'<br>';
                $realisasi .= '<i>Anggaran Realisasi : </i>'.' Rp. '.number_format($row->anggaran,0,',',',').'<br>';
                return $realisasi;
            })
            ->rawColumns(['nama','keterangan','action','anggaran','realisasi','bulan','status'])
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $target_tpb = TargetTpb::get();
        $tpb = Tpb::get();
        $provinsi = Provinsi::where('is_luar_negeri', 'false')->get();
        $kota = Kota::where('is_luar_negeri', 'false')->get();
        $satuan_ukur = SatuanUkur::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $target_tpb,
            'target_tpb' => $target_tpb,
            'tpb' => $tpb,
            'provinsi' => $provinsi,
            'kota' => $kota,
            'bulans' => Bulan::get(),
            'satuan_ukur' => $satuan_ukur,
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        switch ($request->input('actionform')) {
            case 'update': DB::beginTransaction();
                            try{
                                $realisasi = KegiatanRealisasi::find((int)$request->input('id'));
                                $realisasi_total = KegiatanRealisasi::select(DB::Raw('sum(kegiatan_realisasis.anggaran) as total'))
                                                                    ->where('kegiatan_id',$realisasi->kegiatan_id)
                                                                    ->where('bulan','<',$request->input('bulan'))
                                                                    ->where('tahun',$request->input('tahun'))
                                                                    ->first();

                                $paramr['target'] = $request->input('target');
                                $paramr['realisasi'] = $request->input('realisasi');
                                $paramr['bulan'] = $request->input('bulan');
                                $paramr['tahun'] = $request->input('tahun');
                                $paramr['anggaran'] = str_replace(',', '', $request->anggaran);
                                $paramr['anggaran_total'] = (int)$realisasi_total->total + (int)str_replace(',', '', $request->anggaran);
                                $realisasi->update((array)$paramr);

                                $kegiatan = Kegiatan::find((int)$realisasi->kegiatan_id);
                                $param['target_tpb_id'] = $request->input('target_tpb_id');
                                $param['kegiatan'] = $request->input('kegiatan');
                                $param['provinsi_id'] = $request->input('provinsi_id');
                                $param['kota_id'] = $request->input('kota_id');
                                $param['indikator'] = $request->input('indikator');
                                $param['satuan_ukur_id'] = $request->input('satuan_ukur_id');
                                $param['anggaran_alokasi'] = str_replace(',', '', $request->anggaran_alokasi);
                                $kegiatan->update((array)$param);

                                AdministrasiController::store_log($realisasi->id,$realisasi->status_id);

                                DB::commit();
                                $result = [
                                'flag'  => 'success',
                                'msg' => 'Sukses ubah data',
                                'title' => 'Sukses'
                                ];
                            }catch(\Exception $e){
                                DB::rollback();
                                $result = [
                                'flag'  => 'warning',
                                'msg' => $e->getMessage(),
                                'title' => 'Gagal'
                                ];
                            }

            break;
        }

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {

        try{

            $kegiatan = KegiatanRealisasi::select('kegiatans.*','kegiatan_realisasis.*','kegiatan_realisasis.id as kegiatan_realisasi_id','kegiatans.id as kegiatan_id')
                                            ->LeftJoin('kegiatans','kegiatans.id','kegiatan_realisasis.kegiatan_id')->where('kegiatan_realisasis.id',(int)$request->input('id'))->first();
            $tpb = Tpb::get();
            $provinsi = Provinsi::where('is_luar_negeri', 'false')->get();
            $kota = Kota::where('is_luar_negeri', 'false')->get();
            $satuan_ukur = SatuanUkur::get();
            $target_tpb = TargetTpb::get();
    
            return view($this->__route.'.form',[
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $kegiatan,
                'target_tpb' => $target_tpb,
                'tpb' => $tpb,
                'provinsi' => $provinsi,
                'kota' => $kota,
                'bulans' => Bulan::get(),
                'satuan_ukur' => $satuan_ukur,
            ]);
        }catch(Exception $e){}

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = KegiatanRealisasi::find((int)$request->input('id'));
            $data->delete();

            $log = LogKegiatan::where('kegiatan_id', (int)$request->input('id'));
            $log->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function detail(Request $request)
    {

        try{
            $realisasi_detail = KegiatanRealisasi::where('id', (int)$request->input('id'))->first();
            $kegiatan  = Kegiatan::find($realisasi_detail->kegiatan_id);
            $tahun     = KegiatanRealisasi::select('tahun')->where('kegiatan_id', $kegiatan->id)->groupBy('tahun')->orderBy('tahun')->get();
            $realisasi = KegiatanRealisasi::where('kegiatan_id', $kegiatan->id)->get();
            $realisasi_total = KegiatanRealisasi::where('kegiatan_id', $kegiatan->id)->select(DB::Raw('sum(anggaran) as total'))->first();

                return view($this->__route.'.detail',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $kegiatan,
                    'tahun' => $tahun,
                    'anggaran_total' => $realisasi_total->total,
                    'realisasi' => $realisasi,
                ]);
        }catch(Exception $e){}

    }
    
    public function download_template(Request $request)
    {
        $perusahaan_id = ($request->perusahaan_id?$request->perusahaan_id:1);
        $bulan = ($request->bulan?$request->bulan:date('m'));
        $tahun = ($request->tahun?$request->tahun:date('Y'));
        $perusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        $namaFile = "Template Kegiatan.xlsx";
        
        return Excel::download(new KegiatanTemplateExcelSheet($perusahaan,$bulan,$tahun), $namaFile);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function upload()
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = \Auth::user()->id_bumn;
        
        $admin_bumn = false;
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                }
            }
        }

        return view('realisasi.upload_realisasi.upload',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id
        ]);

    }

    public function export(Request $request)
    {
        $kegiatan = KegiatanRealisasi::select('kegiatans.*',
                                    'kegiatan_realisasis.*',
                                    'satuan_ukur.nama as satuan_ukur',
                                    'target_tpbs.program',
                                    'pilar_pembangunans.nama as pilar_pembangunan',
                                    'tpbs.nama as tpb',
                                    'perusahaans.nama_lengkap as perusahaan',
                                    'provinsis.nama as provinsi',
                                    'kotas.nama as kota',
                                    'bulans.nama as bulan_nama')
                                ->leftJoin('kegiatans','kegiatans.id','kegiatan_realisasis.kegiatan_id')
                                ->leftJoin('provinsis','provinsis.id','kegiatans.provinsi_id')
                                ->leftJoin('kotas','kotas.id','kegiatans.kota_id')
                                ->leftJoin('satuan_ukur','satuan_ukur.id','kegiatans.satuan_ukur_id')
                                ->leftJoin('bulans','bulans.id','kegiatan_realisasis.bulan')
                                ->leftJoin('target_tpbs','target_tpbs.id','kegiatans.target_tpb_id')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                                ->leftJoin('perusahaans','perusahaans.id','anggaran_tpbs.perusahaan_id')
                                ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                ->leftJoin('pilar_pembangunans','pilar_pembangunans.id','relasi_pilar_tpbs.pilar_pembangunan_id')
                                ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                                ->orderBy('perusahaans.id','asc')
                                ->orderBy('pilar_pembangunans.nama','asc')
                                ->orderBy('tpbs.nama','asc')
                                ->orderBy('target_tpbs.program','asc')
                                ->orderBy('kegiatan_realisasis.tahun','desc')
                                ->orderBy('kegiatan_realisasis.bulan','asc');

        if($request->bulan){
            $kegiatan = $kegiatan->where('kegiatan_realisasis.bulan', $request->bulan);
        }

        if($request->tahun){
            $kegiatan = $kegiatan->where('kegiatan_realisasis.tahun', $request->tahun);
        }
        
        if($request->perusahaan_id){
            $kegiatan = $kegiatan->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->target_tpb_id){
            $kegiatan = $kegiatan->where('kegiatans.target_tpb_id', $request->target_tpb_id);
        }

        if($request->pilar_pembangunan_id){
            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if($request->tpb_id){
            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
        }

        if($request->owner_id){
            $kegiatan = $kegiatan->where('target_tpbs.id_owner', (int)$request->owner_id);
        }

        $kegiatan = $kegiatan->where('kegiatans.is_invalid_aplikasitjsl',false)
        ->where('kegiatan_realisasis.is_invalid_aplikasitjsl',false)->get();


        $namaFile = "Data Kegiatan ".date('dmY').".xlsx";
        return Excel::download(new KegiatanExport($kegiatan,$request->tahun), $namaFile);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validasi(Request $request)
    {
        $kegiatan = KegiatanRealisasi::Select('kegiatan_realisasis.*')
                                ->leftJoin('kegiatans','kegiatans.id','kegiatan_realisasis.kegiatan_id')
                                ->leftJoin('target_tpbs','target_tpbs.id','kegiatans.target_tpb_id')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                                ->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id)
                                ->where('kegiatan_realisasis.tahun', $request->tahun)
                                ->where('kegiatan_realisasis.bulan', $request->bulan);

        DB::beginTransaction();
        try{
            $param['status_id'] = $request->status_id;

            $realisasi = $kegiatan->get();
            foreach($realisasi as $a){
                AdministrasiController::store_log($a->id,$request->status_id);
            }

            $kegiatan->update($param);
            
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses validasi data',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function log_status(Request $request)
    {
        $kegiatan = KegiatanRealisasi::find((int)$request->input('id'));
        $log_kegiatan = LogKegiatan::where('kegiatan_id', (int)$request->input('id'))
                                    ->orderBy('created_at')
                                    ->get();

        return view($this->__route.'.log_status',[
            'pagetitle' => 'Log Status',
            'data' => $kegiatan,
            'log' => $log_kegiatan
        ]);

    }

    public static function store_log($kegiatan_id, $status_id)
    {  
        $param['kegiatan_id'] = $kegiatan_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogKegiatan::create((array)$param);
    }

    public function api_sync()
    {  
        try{
            $call = \Artisan::call('portalApp:KegiatanSync');
            $cek_log = DB::table('log_sinkronisasi_kegiatan')->orderby('id','desc')->first();
          
            if(!$cek_log || $cek_log->jumlah_data == 0){
                $result = [
                    'flag'  => 'warning',
                    'msg' => 'Belum ada data kegiatan terbaru dari Aplikasi TJSL',
                    'title' => 'Warning'
                ];
            }else{
                $jumlah =  $cek_log->jumlah_data? (string)$cek_log->jumlah_data : "";
                $result = [
                    'flag'  => 'success',
                    'msg' => 'Sukses Sinkronisasi '.$jumlah.' data',
                    'title' => 'Sukses'
                ];
            }

        }catch(\Exception $e){
            $result = [
                'flag'  => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }   
    
    public function api_sync_by_bumn()
    {  
        try{
            $id_bumn = auth()->user()->id_bumn;
            if($id_bumn){
                $call = \Artisan::call('portalApp:KegiatanBumnSync');    
            }

                $result = [
                    'flag'  => 'success',
                    'msg' => 'Sukses Sinkronisasi data by bumn',
                    'title' => 'Sukses'
                ];

        }catch(\Exception $e){
            $result = [
                'flag'  => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }       

}
