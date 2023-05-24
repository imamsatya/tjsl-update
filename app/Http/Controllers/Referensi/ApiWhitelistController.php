<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiWhitelist;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use Carbon\Carbon;

class ApiWhitelistController extends Controller
{
    protected $__route;

    function __construct()
    {
         $this->__route = 'referensi.api_whitelist';
         $this->pagetitle = 'API Whitelist';
    }

    public function index(Request $request)
    {
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Referensi - Api Whitelist'
        ]);
    }

    public function datatable(Request $request)
    {
        $datas = ApiWhitelist::get();
       
        try{
            return datatables()->of($datas)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'"  data-toggle="tooltip" title="Hapus data "><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->editColumn('status', function ($row){
                $id = $row->status;
                $button = '<div align="center">';

                $checked = 'title="Ubah menjadi Aktif"';
                if($row->status == 't'){
                    $checked = 'title="Ubah menjadi Tidak Aktif" checked="checked"';
                }
                $button .= '<label class="form-check form-switch form-check-custom form-check-solid">
                                <input id="edit_active" class="form-check-input" data-id="'.$row->id.'" data-nama="'.$row->ip_user.'" type="checkbox" value="1" data-toggle="tooltip" '.$checked.' />
                            </label>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['status','action'])
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

    public function create()
    {
        $datas = ApiWhitelist::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $datas
        ]);

    }    

    public function store(Request $request)
    {

        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateform($request);
        if (!$validator->fails()) {
            $param['ip_user'] = $request->input('ip_user');
            $param['keterangan'] = $request->input('keterangan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $api = ApiWhitelist::create((array)$param);

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
                                  $api = ApiWhitelist::find((int)$request->input('id'));
                                  $api->update((array)$param);

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

    protected function validateform($request)
    {
        $required['ip_user'] = 'required';

        $message['ip_user.required'] = 'IP User wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    public function edit(Request $request)
    {

        try{

            $api = ApiWhitelist::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $api

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = ApiWhitelist::find((int)$request->input('id'));
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

    public function status(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = ApiWhitelist::find((int)$request->input('id'));
            if($data->status || $data->status == 't' || $data->status == 'true'){
                $data->update([
                    'status'=>'f'
                ]);
            }else{
                $data->update([
                    'status'=>'t'
                ]);
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses Ubah Status',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal Ubah Status',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }    

}
