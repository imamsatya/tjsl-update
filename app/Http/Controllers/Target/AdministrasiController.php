<?php

namespace App\Http\Controllers\Target;

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
use App\Models\AnggaranTpb;
use App\Models\TargetTpb;
use App\Models\TargetMitra;
use App\Models\KodeIndikator;
use App\Models\KodeTujuanTpb;
use App\Models\CaraPenyaluran;
use App\Models\CoreSubject;
use App\Models\SatuanUkur;
use App\Models\JenisProgram;
use App\Models\Perusahaan;
use App\Models\PilarPembangunan;
use App\Models\Tpb;
use App\Models\Status;
use App\Models\OwnerProgram;
use App\Models\LogTargetTpb;
use App\Exports\TargetTemplateExport;
use App\Exports\TargetTemplateExcelSheet;
use App\Exports\TargetTpbExport;


class AdministrasiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'target.administrasi';
        $this->pagetitle = 'Data Program';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
       
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

        $anggaran       = AnggaranTpb::select('relasi_pilar_tpbs.pilar_pembangunan_id','anggaran_tpbs.*','tpbs.nama as tpb_nama', 'tpbs.no_tpb as no_tpb')
                                        ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                        ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id');
        $anggaran_pilar = AnggaranTpb::leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                        ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id');
        $anggaran_bumn  = AnggaranTpb::leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                        ->leftJoin('perusahaans','perusahaans.id','anggaran_tpbs.perusahaan_id');
        
        if($perusahaan_id){
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
        }

        $tahun = $request->tahun? $request->tahun : (int)date('Y'); 
        if($tahun){
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.tahun', $tahun);
            $anggaran_bumn = $anggaran_bumn->where('anggaran_tpbs.tahun', $tahun);
        }

        if($request->pilar_pembangunan_id){
            $anggaran = $anggaran->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
            $anggaran_pilar = $anggaran_pilar->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
            $anggaran_bumn = $anggaran_bumn->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if($request->tpb_id){
            $anggaran = $anggaran->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
            $anggaran_pilar = $anggaran_pilar->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
            $anggaran_bumn = $anggaran_bumn->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
        }
        
        //$is_finish = Status::whereRaw("lower(replace(nama,' ','')) =?","finish")->pluck('id')->first();
        
        $anggaran_pilar = $anggaran_pilar->select('anggaran_tpbs.perusahaan_id', 
                                                    'relasi_pilar_tpbs.pilar_pembangunan_id', 
                                                    DB::Raw('sum(anggaran_tpbs.anggaran) as sum_anggaran'), 
                                                    'pilar_pembangunans.nama as pilar_nama', 
                                                    'pilar_pembangunans.id as pilar_id')
                           // ->where('anggaran_tpbs.status_id',$is_finish)
                            ->groupBy('relasi_pilar_tpbs.pilar_pembangunan_id', 
                                        'anggaran_tpbs.perusahaan_id',
                                        'pilar_pembangunans.nama', 
                                        'pilar_pembangunans.id')
                            ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
                            ->get();
        $anggaran_bumn = $anggaran_bumn->select('anggaran_tpbs.perusahaan_id', 
                                                'perusahaans.nama_lengkap',
                                                'perusahaans.id',
                                                DB::Raw('sum(anggaran_tpbs.anggaran) as sum_anggaran'))
                           // ->where('anggaran_tpbs.status_id',$is_finish)
                            ->groupBy('anggaran_tpbs.perusahaan_id')
                            ->groupBy('perusahaans.nama_lengkap')
                            ->groupBy('perusahaans.id')
                            ->get();
        $anggaran = $anggaran->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
                            ->orderBy('relasi_pilar_tpbs.tpb_id')->get();

        $target = TargetTpb::get();
        if($request->status_id){
            $target = TargetTpb::where('target_tpbs.status_id', $request->status_id)
                            ->orderBy('target_tpbs.tpb_id')->get();
        }

        if($request->owner_id){
            $target = TargetTpb::where('target_tpbs.id_owner', $request->owner_id)
                            ->orderBy('target_tpbs.tpb_id')->get();
        }

        $can_download_template = true;
        if($admin_bumn){
            if($anggaran_bumn->count() > 0){
                $can_download_template = $anggaran_bumn[0]->perusahaan_id == $perusahaan_id? true :false; 
            }else{
                $can_download_template = false;
            }
        }


        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Program - Administrasi',
            'pilar' => PilarPembangunan::get(),
            'status' => Status::get(),
            'tpb' => Tpb::get(),
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => ($request->tahun?$request->tahun:date('Y')),
            'pilar_pembangunan_id' => $request->pilar_pembangunan_id,
            'tpb_id' => $request->tpb_id,
            'status_id' => $request->status_id,
            'anggaran' => $anggaran,
            'anggaran_pilar' => $anggaran_pilar,
            'anggaran_bumn' => $anggaran_bumn,
            'target' => $target,
            'owner' => OwnerProgram::get(),
            'owner_id' => $request->owner_id,
            'can_download_template'  => $can_download_template,
            'view_only' => $view_only 
        ]);
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {

        $kode = TargetTpb::orderBy('tpb_id')->get();
        try{
            return datatables()->of($kode)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->addColumn('tpb', function ($row){
                return @$row->tpb->no_tpb . ' - ' . @$row->tpb->nama;
            })
            ->rawColumns(['nama','keterangan','action'])
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
        $target = TargetTpb::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $target,
            'tpb' => Tpb::get()
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

        $param = $request->except('actionform','id','mitra_bumn');
        $param['anggaran_alokasi'] = str_replace(',', '', $param['anggaran_alokasi']);
        $mitra_bumn = $request->input('mitra_bumn');
        
        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                            try{
                                $target = TargetTpb::create((array)$param);
                                $target->mitra_bumn()->sync($mitra_bumn);

                                AdministrasiController::store_log($target->id,$target->status_id);


                                DB::commit();
                                $result = [
                                'flag'  => 'success',
                                'msg' => 'Sukses tambah data',
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

            case 'update': DB::beginTransaction();
                            try{
                                $target = TargetTpb::find((int)$request->input('id'));
                                $target->update((array)$param);
                                $target->mitra_bumn()->sync($mitra_bumn);
                                
                                AdministrasiController::store_log($target->id,$target->status_id);

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
            $target = TargetTpb::find((int)$request->input('id'));
            $mitra_bumn = TargetMitra::where('target_mitras.target_tpb_id',$target->id)->pluck('perusahaan_id','perusahaan_id')->all();
            $kode_indikator = KodeIndikator::LeftJoin('relasi_tpb_kode_indikators', 'relasi_tpb_kode_indikators.kode_indikator_id','kode_indikators.id')
                                            ->LeftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id','relasi_tpb_kode_indikators.relasi_pilar_tpb_id')
                                            ->where('relasi_pilar_tpbs.tpb_id',$target->tpb_id)->get();
            $kode_tujuan_tpb = KodeTujuanTpb::LeftJoin('relasi_tpb_kode_tujuan_tpbs', 'relasi_tpb_kode_tujuan_tpbs.kode_tujuan_tpb_id','kode_tujuan_tpbs.id')
                                            ->LeftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id','relasi_tpb_kode_tujuan_tpbs.relasi_pilar_tpb_id')
                                            ->where('relasi_pilar_tpbs.tpb_id',$target->tpb_id)->get();

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $target,
                    'kode_indikator' => $kode_indikator,
                    'kode_tujuan_tpb' => $kode_tujuan_tpb,
                    'satuan_ukur' => SatuanUkur::get(),
                    'cara_penyaluran' => CaraPenyaluran::get(),
                    'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
                    'core_subject' => CoreSubject::get(),
                    'jenis_program' => JenisProgram::get(),
                    'mitra' => $mitra_bumn,
                    'tpb' => Tpb::get()
                ]);
        }catch(Exception $e){}

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
            $target = TargetTpb::find((int)$request->input('id'));
            $mainOwner = [];
            if($target->id_owner){
                $mainOwner = OwnerProgram::find((int)$target->id_owner);  
            }

            //OwnerProgram
            $mitra_bumn = TargetMitra::where('target_mitras.target_tpb_id',$target->id)->get();

                return view($this->__route.'.detail',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $target,
                    'mainOwner' => $mainOwner?$mainOwner : [],
                    'mitra_bumn' => $mitra_bumn,
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
            $data = TargetTpb::find((int)$request->input('id'));
            $data->delete();

            $log = LogTargetTpb::where('target_tpb_id', (int)$request->input('id'));
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_status(Request $request)
    {
        $target = TargetTpb::Select('target_tpbs.*')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id');
        
        if($request->perusahaan_id){
            $target = $target->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $target = $target->where('anggaran_tpbs.tahun', $request->tahun);
        }
        
        $target = $target->first();

        $result['status_id'] = @$target->status_id;

        return response()->json($result);
    }

    public function download_template(Request $request)
    {
        $perusahaan_id =  ($request->perusahaan_id?$request->perusahaan_id:1);
        $perusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        $filter_tahun = $request->tahun;
        $namaFile = "Template Data Program.xlsx";
        
        return Excel::download(new TargetTemplateExcelSheet($perusahaan,$filter_tahun), $namaFile);
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

        return view('target.upload_target.upload',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id
        ]);

    }
    
    public function export(Request $request)
    {

        $target = TargetTpb::select('target_tpbs.*')
                            ->leftJoin('anggaran_tpbs', 'anggaran_tpbs.id', 'target_tpbs.anggaran_tpb_id');
        
        if($request->perusahaan_id){
            $target = $target->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $target = $target->where('anggaran_tpbs.tahun', $request->tahun);
        }

        if($request->owner_id){
            $target = $target->where('target_tpbs.id_owner', (int)$request->owner_id);
        }

        $target = $target->get();

        $namaFile = "Data Program ".date('dmY').".xlsx";
        return Excel::download(new TargetTpbExport($target,$request->tahun), $namaFile);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function log_status(Request $request)
    {
        $target_tpb = TargetTpb::find((int)$request->input('id'));
        $log_target_tpb = LogTargetTpb::where('target_tpb_id', (int)$request->input('id'))
                                    ->orderBy('created_at')
                                    ->get();

        return view($this->__route.'.log_status',[
            'pagetitle' => 'Log Status',
            'data' => $target_tpb,
            'log' => $log_target_tpb
        ]);

    }
    
    public static function store_log($target_tpb_id, $status_id)
    {  
        $param['target_tpb_id'] = $target_tpb_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = \Auth::user()->id;
        LogTargetTpb::create((array)$param);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validasi(Request $request)
    {
        $target = TargetTpb::Select('target_tpbs.*')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                                ->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id)
                                ->where('anggaran_tpbs.tahun', $request->tahun);

        DB::beginTransaction();
        try{
            $param['status_id'] = $request->status_id;

            $target_tpb = $target->get();
            foreach($target_tpb as $a){
                AdministrasiController::store_log($a->id,$request->status_id);
            }

            $target->update($param);
            
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
}
