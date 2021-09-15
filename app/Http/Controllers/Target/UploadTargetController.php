<?php

namespace App\Http\Controllers\Target;

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
use App\Imports\RowImport;

use App\Models\User;
use App\Models\TargetTpb;
use App\Models\TargetUpload;
use App\Models\TargetUploadGagal;
use App\Models\Perusahaan;
use App\Exports\TargetBerhasilExport;
use App\Exports\TargetGagalExcelSheet;

class UploadTargetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'target.upload_target';
        $this->pagetitle = 'Upload Data Program';
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
            'breadcrumb' => 'Program - Upload',
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
        $data = TargetUpload::where('user_id', $id_users)->orderBy('created_at','desc')->get();
        try{
            return datatables()->of($data)
            ->addColumn('download_berhasil', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                if($row->berhasil>0){
                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-berhasil" data-id="'.$id.'" data-toggle="tooltip" title="Download data berhasil"><i class="bi bi-download fs-3"></i></button>';
                }

                $button .= '</div>';
                return $button;
            })
            ->addColumn('download_gagal', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                if($row->gagal>0){
                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-danger cls-button-gagal" data-id="'.$id.'" data-toggle="tooltip" title="Download data gagal"><i class="bi bi-download fs-3"></i></button>';
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
        $target = TargetTpbs::get();

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

        $param['file_name'] = $request->input('file_name');

        try{
            $target = TargetUpload::create((array)$param);

            $dataUpload = $this->uploadFile($request->file('file_name'), $target->id);
            Excel::import(new RowImport($dataUpload->fileRaw, $target->id), public_path('file_upload/target_tpb/'.$dataUpload->fileRaw));

            $param2['file_name']  = $dataUpload->fileRaw;
            $param2['user_id']  = \Auth::user()->id;
            $param2['tanggal']  = date('Y-m-d H:i:s');
            $target->update((array)$param2);

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

            $target = TargetTpbs::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $target,
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
        $filePath = 'file_upload'.DIRECTORY_SEPARATOR.'target_tpb'.DIRECTORY_SEPARATOR.$fileName;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'file_upload'.DIRECTORY_SEPARATOR.'target_tpb'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }
    
    public function export_berhasil(Request $request)
    {
        $upload = TargetUpload::find($request->id);
        $target = TargetTpb::where('file_name', $upload->file_name)->get();
        $perusahaan = @$upload->perusahaan->nama_lengkap;
        $tahun = @$upload->tahun;

        $namaFile = "Data Target Berhasil Upload.xlsx";
        return Excel::download(new TargetBerhasilExport($target,$perusahaan,$tahun), $namaFile);
    }

    public function export_gagal(Request $request)
    {
        $upload = TargetUpload::find($request->id);
        $target = TargetUploadGagal::where('target_upload_id', $upload->id)->get();
        $perusahaan = @$upload->perusahaan->nama_lengkap;
        $tahun = @$upload->tahun;

        $namaFile = "Data Target Gagal Upload.xlsx";
        return Excel::download(new TargetGagalExcelSheet($target,$perusahaan,$tahun), $namaFile);
    }
}
