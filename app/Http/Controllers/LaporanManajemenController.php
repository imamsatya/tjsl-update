<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;

use App\Models\LaporanManajemen;
use App\Models\LogLaporanManajemen;
use App\Models\PeriodeLaporan;
use App\Models\Perusahaan;
use App\Models\Status;

class LaporanManajemenController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'laporan_manajemen';
        $this->pagetitle = 'Laporan Manajemen';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::get(),
            'periode' => PeriodeLaporan::get(),
            'status' => Status::get(),
        ]);
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        $laporan = LaporanManajemen::Select('laporan_manajemens.*')
                                    ->leftJoin('periode_laporans','periode_laporans.id', 'laporan_manajemens.periode_laporan_id')
                                    ->where('periode_laporans.jenis_laporan', 'Manajemen')
                                    ->orderBy('laporan_manajemens.tahun')
                                    ->orderBy('periode_laporans.urutan')
                                    ->orderBy('laporan_manajemens.perusahaan_id');
        
        if($request->perusahaan_id){
            $laporan = $laporan->where('laporan_manajemens.perusahaan_id', $request->perusahaan_id);
        }

        if($request->tahun){
            $laporan = $laporan->where('laporan_manajemens.tahun', $request->tahun);
        }

        if($request->periode_laporan_id){
            $laporan = $laporan->where('laporan_manajemens.periode_laporan_id', $request->periode_laporan_id);
        }

        if($request->status_id){
            $laporan = $laporan->where('laporan_manajemens.status_id', $request->status_id);
        }
        
        try{
            return datatables()->of($laporan)
            ->addColumn('status', function ($row){
                $waktu = $row->waktu;
                if($row->waktu) $waktu = date("d-m-Y", strtotime($row->waktu));
                
                $class = 'primary';
                if($row->status_id == 1){
                    $class = 'success';
                }else if($row->status_id == 3){
                    $class = 'warning';
                }
                $status = '<span class="btn cls-log badge badge-light-'.$class.' fw-bolder me-auto px-4 py-3" data-id="'.$row->id.'" >'.@$row->status->nama.'</span>';
                // $status .= '<br>Fitri Hidayanti, '.$row->waktu;

                return $status;
            })
            ->addColumn('perusahaan', function ($row){
                return @$row->perusahaan->nama_lengkap;
            })
            ->addColumn('periode', function ($row){
                return @$row->tahun .' - '. @$row->periode->nama;
            })
            ->addColumn('user', function ($row){
                $waktu = $row->waktu;
                if($row->waktu) $waktu = date("d-m-Y", strtotime($row->waktu));
                $user = 'Fitri Hidayanti<br>'.$row->waktu;
                return $user;
            })
            ->editColumn('waktu', function ($row){
                $waktu = $row->waktu;
                if($row->waktu) $waktu = date("d-m-Y", strtotime($row->waktu));
                return $waktu;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';
                if($row->file_name){
                    $button .= '<a class="tooltips btn btn-sm btn-light btn-icon btn-warning" title="Download File '.@$row->periode->nama.'" href="'.\URL::to('file_upload/laporan_manajemen/'.$row->file_name).'" download="Download File '.@$row->periode->nama.'" ><i class="bi bi-download fs-3"></i></a> ';
                }
                
                if($row->status_id != 1){
                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Upload File '.@$row->periode->nama.'"><i class="bi bi-upload fs-3"></i></button> ';
                }
                
                if($row->status_id != 1){
                    $button .= '<button type="button" data-periode="'.@$row->periode->nama.'" class="btn btn-sm btn-success btn-icon cls-validasi" data-id="'.$id.'" data-toggle="tooltip" title="Validasi '.@$row->periode->nama.'"><i class="bi bi-check fs-3"></i></button>';
                }else{
                    $button .= '<button type="button" data-periode="'.@$row->periode->nama.'"  class="btn btn-sm btn-danger btn-icon cls-cancel-validasi" data-id="'.$id.'" data-toggle="tooltip" title="Batalkan Validasi '.@$row->periode->nama.'"><i class="bi bi-check fs-3"></i></button>';
                }

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','keterangan','action','status','user'])
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

    public function log_status(Request $request)
    {
        $laporan_manajemen = LaporanManajemen::find((int)$request->input('id'));
        $log_laporan_manajemen = LogLaporanManajemen::where('laporan_manajemen_id', (int)$request->input('id'))
                                    ->orderBy('created_at')
                                    ->get();

        return view($this->__route.'.log_status',[
            'pagetitle' => 'Log Status',
            'data' => $laporan_manajemen,
            'log' => $log_laporan_manajemen
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $laporan_manajemen = LaporanManajemen::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $laporan_manajemen
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

        $validator = $this->validateform($request);
        if (!$validator->fails()) {
            $param = $request->except('actionform','id');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

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
                                  $laporan_manajemen = LaporanManajemen::find((int)$request->input('id'));
                                  $dataUpload = $this->uploadFile($request->file('file_name'), (int)$request->input('id'), @$laporan_manajemen->perusahaan->nama_lengkap, @$laporan_manajemen->periode->nama);
                                  $param2['file_name']  = $dataUpload->fileRaw;
                                  $param2['status_id']  = 2;
                                  $param2['waktu']  = date('Y-m-d H:i:s');
                                  $laporan_manajemen->update((array)$param2);
                                  
                                  LaporanManajemenController::store_log($laporan_manajemen->id,$param2['status_id']);

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
        }else{
            $messages = $validator->errors()->all('<li>:message</li>');
            $result = [
                'flag'  => 'warning',
                'msg' => '<ul>'.implode('', $messages).'</ul>',
                'title' => 'Gagal proses data'
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

    public function edit(Request $request)
    {
        try{

            $laporan_manajemen = LaporanManajemen::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $laporan_manajemen
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
            $data = LaporanManajemen::find((int)$request->input('id'));
            $data->delete();

            $log = LogLaporanManajemen::where('laporan_manajemen_id', (int)$request->input('id'));
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
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateform($request)
    {
        $required['file_name'] = 'required';

        $message['file_name.required'] = 'File wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }
    
    protected function uploadFile(UploadedFile $file, $id, $perusahaan, $periode)
    {
        $fileName = $file->getClientOriginalName();
        $ext = substr($file->getClientOriginalName(),strripos($file->getClientOriginalName(),'.'));
        $fileRaw  = $fileName = 'Laporan_'.$perusahaan.'_'.$periode.$ext;
        $filePath = 'file_upload'.DIRECTORY_SEPARATOR.'laporan_manajemen'.DIRECTORY_SEPARATOR.$fileName;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'file_upload'.DIRECTORY_SEPARATOR.'laporan_manajemen'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileName);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileName, 'filePath' => $filePath);
        return $data;
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validasi(Request $request)
    {
        $laporan_manajemen = LaporanManajemen::find($request->input('id'));
        $msg = 'validasi data';

        if($request->input('status_id') == 2) $msg = 'batalkan validasi data';

        DB::beginTransaction();
        try{
            $param['status_id'] = $request->input('status_id');
            $laporan_manajemen->update($param);

            LaporanManajemenController::store_log($laporan_manajemen->id,$param['status_id']);

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses '.$msg,
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal '.$msg,
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
    
    public static function store_log($laporan_manajemen_id, $status_id)
    {  
        $param['laporan_manajemen_id'] = $laporan_manajemen_id;
        $param['status_id'] = $status_id;
        $param['user_id'] = 1;
        LogLaporanManajemen::create((array)$param);
    }
}
