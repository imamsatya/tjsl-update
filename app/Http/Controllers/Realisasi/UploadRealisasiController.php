<?php

namespace App\Http\Controllers\Realisasi;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KegiatanRowImport;

use App\Models\User;
use App\Models\TargetTpb;
use App\Models\RealisasiUpload;
use App\Models\Perusahaan;

class UploadRealisasiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'realisasi.upload_realisasi';
        $this->pagetitle = 'Upload Data Kegiatan';
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

        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Target - Upload',
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'tahun' => ($request->tahun?$request->tahun:date('Y')),
            'data' => null,
            'perusahaan_id' => $perusahaan_id,
        ]);
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $data = RealisasiUpload::where('user_id', $id_users)->orderBy('created_at','desc')->get();
        try{
            return datatables()->of($data)
            ->addColumn('download_berhasil', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                if($row->berhasil>0){
                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Download data berhasil"><i class="bi bi-download fs-3"></i></button>';
                }

                $button .= '</div>';
                return $button;
            })
            ->addColumn('download_gagal', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                if($row->gagal>0){
                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-danger cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Download data gagal"><i class="bi bi-download fs-3"></i></button>';
                }

                $button .= '</div>';
                return $button;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','keterangan','action','download_berhasil','download_gagal'])
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
        $realisasi = TargetTpbs::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $realisasi,
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

        $param['file_name'] = $request->input('file_name');

        try{
            $realisasi = RealisasiUpload::create((array)$param);

            $dataUpload = $this->uploadFile($request->file('file_name'), $realisasi->id);
            Excel::import(new KegiatanRowImport($dataUpload->fileRaw, $realisasi->id), public_path('file_upload/kegiatan/'.$dataUpload->fileRaw));

            $param2['file_name']  = $dataUpload->fileRaw;
            $param2['user_id']  = \Auth::user()->id;
            $param2['tanggal']  = date('Y-m-d H:i:s');
            $realisasi->update((array)$param2);

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

            $realisasi = TargetTpbs::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $realisasi,
                    'tpb' => Tpb::get()
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
            $data = TargetTpbs::find((int)$request->input('id'));
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

    protected function uploadFile(UploadedFile $file, $id)
    {
        $fileName = $file->getClientOriginalName();
        $fileRaw  =$fileName = $id.'_'.$fileName;
        $filePath = 'file_upload'.DIRECTORY_SEPARATOR.'kegiatan'.DIRECTORY_SEPARATOR.$fileName;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'file_upload'.DIRECTORY_SEPARATOR.'kegiatan'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }
}
