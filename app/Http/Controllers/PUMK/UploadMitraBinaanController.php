<?php

namespace App\Http\Controllers\PUMK;

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
use App\Imports\RowImportmb;

use App\Models\User;
use App\Models\UploadPumkMitraBinaan;
use App\Models\UploadGagalPumkMitraBinaan;
use App\Models\PumkMitraBinaan;
use App\Models\Perusahaan;
use App\Exports\MitraBinaanTemplateExport;
use App\Exports\MitraBinaanSuksesUpload;
use App\Exports\MitraBinaanSuksesUploadExport;
use App\Exports\MitraBinaanGagalUpload;
use App\Exports\MitraBinaanGagalUploadExport;
use App\Exports\MitraBinaanTemplateExcelSheet;


class UploadMitraBinaanController extends Controller
{
    public function __construct()
    {
        $this->__route = 'pumk.upload_data_mitra';
        $this->pagetitle = 'Upload Data Mitra Binaan';
    }

    public function index(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;
        
        $admin_bumn = false;
        $super_admin = false;
        $admin_tjsl = false;

        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if($v == 'Super Admin') {
                    $super_admin = true;
                    $perusahaan_id = $request->perusahaan_id;
                }
                if($v == 'Admin TJSL') {
                    $admin_tjsl = true;
                    $perusahaan_id = $request->perusahaan_id;
                }
            }
        }

        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Mitra Binaan - Upload',
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'admin_tjsl' => $admin_tjsl,
            'super_admin' => $super_admin,
            'tahun' => ($request->tahun?$request->tahun:date('Y')),
            'data' => null,
            'perusahaan_id' => $perusahaan_id,
        ]);
    }

    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $data = UploadPumkMitraBinaan::where('upload_by_id', $id_users)->orderBy('created_at','desc')->get();
        try{
            return datatables()->of($data)
            ->editColumn('created_at', function ($row){
                $date = [];
                if($row->created_at){
                    $date = date_format($row->created_at, 'd M Y'); 
                }
                return $date;
            })
            ->addColumn('download_berhasil', function ($row){
                $kode = $row->kode_upload;
                $button = '<div align="center">';

                if($row->berhasil>0){
                    $count = PumkMitraBinaan::where('kode_upload',$kode)->count();
                    if($count > 0){
                        $button .= '<a href="download_upload_berhasil/'.$kode.'" target="_blank" class="btn btn-sm btn-light btn-icon btn-primary btn-download-berhasil" data-id="'.$kode.'" data-toggle="tooltip" title="Download data berhasil "><i class="bi bi-download fs-3"></i></a>';
                    }
                    else{
                        $button .= '<button class="btn btn-sm btn-light btn-icon btn-secondary " data-toggle="tooltip" title="Data Telah Diupdate " disabled><i class="bi bi-download fs-3"></i></button>';
                    }
                }

                $button .= '</div>';
                return $button;
            })
            ->addColumn('download_gagal', function ($row){
                $kode = $row->kode_upload;
                $button = '<div align="center">';

                if($row->gagal>0){
                    $count = UploadGagalPumkMitraBinaan::where('kode_upload',$kode)->count();
                    if($count > 0){
                        $button .= '<a href="download_upload_gagal/'.$kode.'" target="_blank" class="btn btn-sm btn-light btn-icon btn-danger cls-download-gagal" data-id="'.$kode.'" data-toggle="tooltip" title="Download data gagal"><i class="bi bi-download fs-3"></i></a>';
                    }
                    else{
                        $button .= '<button class="btn btn-sm btn-light btn-icon btn-secondary " data-toggle="tooltip" title="Data Telah Diupdate " disabled><i class="bi bi-download fs-3"></i></button>';
                    }
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
            ->rawColumns(['nama','keterangan','action','created_at','download_berhasil','download_gagal'])
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

    public function download_template()
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = null;
        $admin_bumn = false;
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }else{
                    $admin_bumn;
                    $perusahaan_id;
                }
            }
        }
        $perusahaan_id =  ($perusahaan_id? $perusahaan_id:0);
        if($perusahaan_id > 0){
            $perusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        }else{
            $perusahaan = [];
        }

        $namaFile = "Template Data Mitra Binaan.xlsx";
        
        return Excel::download(new MitraBinaanTemplateExcelSheet($perusahaan), $namaFile);
    }

    public function download_upload_berhasil($kode)
    {
        $namaFile = "Data Mitra Binaan Berhasil Upload.xlsx";

        return Excel::download(new MitraBinaanSuksesUpload($kode), $namaFile);
    }

    public function download_upload_gagal($kode)
    {

        $namaFile = "Data Mitra Binaan Gagal Upload.xlsx";

        return Excel::download(new MitraBinaanGagalUpload($kode), $namaFile);
    }

    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $param['file_name'] = $request->input('file_name');


       try{
            $mb = UploadPumkMitraBinaan::create((array)$param);

            $dataUpload = $this->uploadFile($request->file('file_name'), $mb->id);
           
          //fungsi 
           if((int)preg_replace('/[^0-9]/','',ini_get('memory_limit')) < 512){
                ini_set('memory_limit','-1');
                ini_set('max_execution_limit','0');
           }
           
            Excel::import(new RowImportmb($dataUpload->fileRaw, $mb->id), public_path('file_upload/upload_mitra_binaan/'.$dataUpload->fileRaw));

            $param2['file_name']  = $dataUpload->fileRaw;
            $param2['upload_by_id']  = \Auth::user()->id;
            $mb->update((array)$param2);

            DB::commit();
            $result = [
            'flag'  => 'success',
            'msg' => 'File Terupload',
            'title' => ''
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

    protected function uploadFile(UploadedFile $file, $id)
    {
        $fileName = $file->getClientOriginalName();
        $fileRaw  =$fileName = $id.'_'.$fileName;
        $filePath = 'file_upload'.DIRECTORY_SEPARATOR.'upload_mitra_binaan'.DIRECTORY_SEPARATOR.$fileName;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'file_upload'.DIRECTORY_SEPARATOR.'upload_mitra_binaan'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }
}
