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

        $anggaran       = AnggaranTpb::select('tpbs.pilar_pembangunan_id','anggaran_tpbs.*')
                                        ->leftJoin('tpbs','tpbs.id','anggaran_tpbs.tpb_id');
        $anggaran_pilar = AnggaranTpb::leftJoin('tpbs','tpbs.id','anggaran_tpbs.tpb_id')
                                        ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'tpbs.pilar_pembangunan_id');
        
        if($perusahaan_id){
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.perusahaan_id', $perusahaan_id);
        }

        if($request->tahun){
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.tahun', $request->tahun);
        }

        if($request->pilar_pembangunan_id){
            $anggaran = $anggaran->where('tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
            $anggaran_pilar = $anggaran_pilar->where('tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if($request->tpb_id){
            $anggaran = $anggaran->where('anggaran_tpbs.tpb_id', $request->tpb_id);
            $anggaran_pilar = $anggaran_pilar->where('anggaran_tpbs.tpb_id', $request->tpb_id);
        }
        
        $anggaran_pilar = $anggaran_pilar->select('tpbs.pilar_pembangunan_id', DB::Raw('sum(anggaran_tpbs.anggaran) as sum_anggaran'), 'pilar_pembangunans.nama as pilar_nama', 'pilar_pembangunans.id as pilar_id')
                            ->groupBy('tpbs.pilar_pembangunan_id', 'pilar_pembangunans.nama', 'pilar_pembangunans.id')
                            ->orderBy('tpbs.pilar_pembangunan_id')
                            ->get();
        $anggaran = $anggaran->orderBy('tpbs.pilar_pembangunan_id')->get();
        
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::get(),
            'anggaran' => $anggaran,
            'anggaran_pilar' => $anggaran_pilar,
            'pilar' => PilarPembangunan::get(),
            'tpb' => Tpb::get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'tahun' => $request->tahun,
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
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
                                ->leftJoin('tpbs','tpbs.id','anggaran_tpbs.tpb_id');
        
        if($request->perusahaan_id){
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }

        if($request->pilar_pembangunan_id){
            $anggaran = $anggaran->where('tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if($request->tpb_id){
            $anggaran = $anggaran->where('anggaran_tpbs.tpb_id', $request->tpb_id);
        }
        
        try{
            return datatables()->of($anggaran)
            ->addColumn('perusahaan', function ($row){
                return @$row->perusahaan->nama_lengkap;
            })
            ->addColumn('pilar', function ($row){
                return @$row->tpb->nama . '-' .@$row->tpb->pilar->nama;
            })
            ->addColumn('tpb', function ($row){
                return @$row->tpb->pilar->nama .'-'.@$row->tpb->nama;
            })
            ->addColumn('status', function ($row){
                $class = 'primary';
                if($row->status_id == 1){
                    $class = 'success';
                }else if($row->status_id == 3){
                    $class = 'warning';
                }
                $status = '<span class="badge badge-light-'.$class.' fw-bolder me-auto px-4 py-3">'.@$row->status->nama.'</span>';
                // $status .= '<br>Fitri Hidayanti, '.$row->waktu;

                return $status;
            })
            ->addColumn('anggaran', function ($row){
                return number_format($row->anggaran,0,',',',');
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '';

                if($row->status_id!=1){
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button> ';

                    $button .= '&nbsp;';

                    $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                }
                return $button;
            })
            ->rawColumns(['nama','keterangan','action','status'])
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

        return view($this->__route.'.create',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'pilar' => PilarPembangunan::get(),
            'perusahaan' => Perusahaan::get(),
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
                                
                                if($request->perusahaan_id==''){
                                    $id_users = \Auth::user()->id;
                                    $users = User::where('id', $id_users)->first();
                                    $param['perusahaan_id'] = $users->id_bumn;
                                }

                                if($request->tpb_id){
                                    $tpb_id = $request->tpb_id;
                                    $anggaran = $request->anggaran;
                                    for($i=0; $i<count($tpb_id); $i++){
                                        $param['tpb_id'] = $tpb_id[$i];
                                        $param['anggaran'] = str_replace(',', '', $anggaran[$i]);
                                        $data = AnggaranTpb::create((array)$param);

                                        AnggaranTpbController::store_log($data->id,$param['status_id']);
                                    }
                                }

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
                                $anggaran_tpb = AnggaranTpb::find((int)$request->input('id'));
                                $param['anggaran'] = str_replace(',', '', $request->input('anggaran'));
                                $anggaran_tpb->update((array)$param);
                                
                                AnggaranTpbController::store_log($anggaran_tpb->id,$anggaran_tpb->status_id);

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
                    'perusahaan' => Perusahaan::get(),
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
            $data = AnggaranTpb::LeftJoin('tpbs','tpbs.id','anggaran_tpbs.tpb_id')
                                    ->where('tpbs.pilar_pembangunan_id', (int)$request->input('id'));
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
                                ->leftJoin('tpbs','tpbs.id','anggaran_tpbs.tpb_id');
        
        if($request->perusahaan_id){
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }

        if($request->pilar_pembangunan_id){
            $anggaran = $anggaran->where('tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if($request->tpb_id){
            $anggaran = $anggaran->where('anggaran_tpbs.tpb_id', $request->tpb_id);
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
                                ->leftJoin('tpbs','tpbs.id','anggaran_tpbs.tpb_id');
        
        if($request->perusahaan_id){
            $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $anggaran = $anggaran->where('anggaran_tpbs.tahun', $request->tahun);
        }

        DB::beginTransaction();
        try{
            $param['status_id'] = $request->status_id;

            $anggaran_tpb = $anggaran->get();
            foreach($anggaran_tpb as $a){
                AnggaranTpbController::store_log($a->id,$param['status_id']);
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
    public function getStatus(Request $request)
    {
        $anggaran = AnggaranTpb::Select('anggaran_tpbs.*')
                                ->leftJoin('tpbs','tpbs.id','anggaran_tpbs.tpb_id');
        
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
    

    public static function store_log($anggaran_tpb_id, $status_id)
    {  
        $param['anggaran_tpb_id'] = $anggaran_tpb_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = 1;
        LogAnggaranTpb::create((array)$param);
    }
}
