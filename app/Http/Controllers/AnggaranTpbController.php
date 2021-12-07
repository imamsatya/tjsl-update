<?php

namespace App\Http\Controllers;

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

use App\Models\AnggaranTpb;
use App\Models\Perusahaan;
use App\Models\PilarPembangunan;
use App\Models\Tpb;
use App\Models\VersiPilar;
use App\Models\User;
use App\Models\LogAnggaranTpb;
use App\Exports\AnggaranTpbExport;

class AnggaranTpbController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'anggaran_tpb';
        $this->pagetitle = 'Anggaran TPB';
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
        $perusahaan_id = $request->perusahaan_id;
        
        $admin_bumn = false;
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
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
        
        $anggaran_pilar = $anggaran_pilar->select('anggaran_tpbs.perusahaan_id','anggaran_tpbs.tahun', 
                                                    'relasi_pilar_tpbs.pilar_pembangunan_id', 
                                                    DB::Raw('sum(anggaran_tpbs.anggaran) as sum_anggaran'), 
                                                    'pilar_pembangunans.nama as pilar_nama', 
                                                    'pilar_pembangunans.id as pilar_id')
                                        ->groupBy('relasi_pilar_tpbs.pilar_pembangunan_id', 
                                                    'anggaran_tpbs.perusahaan_id',
                                                    'anggaran_tpbs.tahun',
                                                    'pilar_pembangunans.nama', 
                                                    'pilar_pembangunans.id')
                                        ->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')
                                        ->get();

        $anggaran_bumn = $anggaran_bumn->select('anggaran_tpbs.perusahaan_id', 
                                                'perusahaans.nama_lengkap',
                                                'perusahaans.id',
                                                DB::Raw('sum(anggaran_tpbs.anggaran) as sum_anggaran'))
                                        ->groupBy('anggaran_tpbs.perusahaan_id')
                                        ->groupBy('perusahaans.nama_lengkap')
                                        ->groupBy('perusahaans.id')
                                        ->get();
        $anggaran = $anggaran->orderBy('relasi_pilar_tpbs.pilar_pembangunan_id')->orderBy('no_tpb')->get();
        
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
            'anggaran' => $anggaran,
            'anggaran_pilar' => $anggaran_pilar,
            'anggaran_bumn' => $anggaran_bumn,
            'pilar' => PilarPembangunan::get(),
            'tpb' => Tpb::get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => ($request->tahun?$request->tahun:date('Y')),
            'pilar_pembangunan_id' => $request->pilar_pembangunan_id,
            'tpb_id' => $request->tpb_id,
        ]);
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function log_status(Request $request)
    {
        $anggaran = AnggaranTpb::find((int)$request->input('id'));
        $log_anggaran_tpb = LogAnggaranTpb::where('anggaran_tpb_id', (int)$request->input('id'))
                                    ->orderBy('created_at')
                                    ->get();

        return view($this->__route.'.log_status',[
            'pagetitle' => 'Log Status',
            'data' => $anggaran,
            'log' => $log_anggaran_tpb
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
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
        
        $anggaran_tpb = AnggaranTpb::get();
        $versi = VersiPilar::whereNull('tanggal_akhir')->orWhere('tanggal_akhir','>=',date('Y-m-d'))->first();
        $versi_pilar_id = $versi->id;

        return view($this->__route.'.create',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'pilar' => PilarPembangunan::get(),
            'versi_pilar_id' => $versi_pilar_id,
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'data' => $anggaran_tpb
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
            case 'insert': DB::beginTransaction();
                            try{
                                $param['perusahaan_id'] = $request->perusahaan_id;
                                $param['tahun'] = $request->tahun;
                                $param['status_id'] = 2;
                                $param['user_id']  = \Auth::user()->id;
                                
                                if($request->perusahaan_id==''){
                                    $id_users = \Auth::user()->id;
                                    $users = User::where('id', $id_users)->first();
                                    $param['perusahaan_id'] = $users->id_bumn;
                                }

                                $validasi = true;
                                if($request->tpb_id){
                                    $tpb_id = $request->tpb_id;
                                    $anggaran = $request->anggaran;
                                    for($i=0; $i<count($tpb_id); $i++){
                                        $param['relasi_pilar_tpb_id'] = $tpb_id[$i];
                                        $param['anggaran'] = str_replace(',', '', $anggaran[$i]);

                                        $checkdata = AnggaranTpb::where('perusahaan_id', $param['perusahaan_id'])
                                                                ->where('tahun', $param['tahun'])
                                                                ->where('relasi_pilar_tpb_id', $param['relasi_pilar_tpb_id'])
                                                                ->first();

                                        if($checkdata != null) {
                                            $validasi = false;
                                            $validasi_msg = @$checkdata->relasi->tpb->no_tpb . ' - ' .@$checkdata->relasi->tpb->nama;
                                        }else{
                                            $data = AnggaranTpb::create((array)$param);
                                            AnggaranTpbController::store_log($data->id,$param['status_id'],$param['anggaran'],'RKA');
                                        }
                                    }
                                }

                                if($validasi){
                                    DB::commit();
                                    $result = [
                                    'flag'  => 'success',
                                    'msg' => 'Sukses tambah data',
                                    'title' => 'Sukses'
                                    ];
                                }else{
                                    DB::rollback();
                                    $result = [
                                    'flag'  => 'warning',
                                    'msg' => 'Data Anggaran '.$validasi_msg.' sudah ada',
                                    'title' => 'Gagal'
                                    ];
                                }
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
                                $anggaran_tpb = AnggaranTpb::find((int)$request->input('id'));
                                $param['anggaran'] = str_replace(',', '', $request->input('anggaran'));
                                $anggaran_tpb->update((array)$param);
                                
                                AnggaranTpbController::store_log($anggaran_tpb->id,$anggaran_tpb->status_id,$param['anggaran'],'RKA Revisi');

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

            $anggaran_tpb = AnggaranTpb::find((int)$request->input('id'));

                return view($this->__route.'.edit',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'tpb' => Tpb::get(),
                    'pilar' => PilarPembangunan::get(),
                    'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
                    'data' => $anggaran_tpb
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
            $data = AnggaranTpb::find((int)$request->input('id'));
            $data->delete();

            $log = LogAnggaranTpb::where('anggaran_tpb_id', (int)$request->input('id'));
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
    public function delete_by_pilar(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = AnggaranTpb::LeftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                    ->where('anggaran_tpbs.perusahaan_id', (int)$request->input('perusahaan_id'))
                                    ->where('anggaran_tpbs.tahun', (int)$request->input('tahun'))
                                    ->where('relasi_pilar_tpbs.pilar_pembangunan_id', (int)$request->input('id'));
            foreach($data as $a){
                $log = LogAnggaranTpb::where('anggaran_tpb_id', $a->id);
                $log->delete();
            }
            $data->delete();

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
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateform($request)
    {
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    public function export(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
                                ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id');
        
        if($request->perusahaan_id){
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }

        if($request->pilar_pembangunan_id){
            $anggaran = $anggaran->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if($request->tpb_id){
            $anggaran = $anggaran->where('relasi_pilar_tpbs.tpb_id', $request->tpb_id);
        }

        $anggaran = $anggaran->get();
        $namaFile = "Data Anggaran TPB ".date('dmY').".xlsx";
        return Excel::download(new AnggaranTpbExport($anggaran,$request->tahun), $namaFile);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validasi(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
                                ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                                ->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id)
                                ->where('anggaran_tpbs.tahun', $request->tahun);

        DB::beginTransaction();
        try{
            $param['status_id'] = $request->status_id;

            $anggaran_tpb = $anggaran->get();
            foreach($anggaran_tpb as $a){
                AnggaranTpbController::store_log($a->id,$param['status_id'],$a->anggaran,'');
            }
            
            $anggaran->update($param);

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_status(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
                                ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','anggaran_tpbs.relasi_pilar_tpb_id')
                                ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id');
        
        if($request->perusahaan_id){
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }
        
        $anggaran = $anggaran->first();

        $result['status_id'] = @$anggaran->status_id;

        return response()->json($result);
    }
    

    public static function store_log($anggaran_tpb_id, $status_id, $anggaran, $keterangan)
    {  
        $param['anggaran'] = $anggaran;
        $param['anggaran_tpb_id'] = $anggaran_tpb_id;
        $param['status_id'] = $status_id;
        $param['keterangan'] = $keterangan;
        $param['user_id'] = \Auth::user()->id;
        LogAnggaranTpb::create((array)$param);
    }
}
