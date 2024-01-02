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

use App\Models\UserGuide;

class UserGuideController extends Controller
{
    public function __construct()
    {
        $this->__route = 'userguide';
        $this->pagetitle = 'User Guide';
    }

    public function index()
    {
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            // 'breadcrumb' => 'User Guide - Manual Book'
            'breadcrumb' => ' - Panduan Penggunaan'
        ]);
    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(UserGuide::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->name.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-name="'.$row->name.'" data-toggle="tooltip" title="Hapus data '.$row->name.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->addColumn('url', function ($data) {
                return "<a href='".$data->url."' target='_blank'>".$data->url."</a>";
            })
            ->rawColumns(['name', 'url', 'keterangan','action'])
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
        $userguide = UserGuide::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $userguide
        ]);
    }

    public function store(Request $request)
    {
        $result = [
            'flag'  => 'error',
            'msg'   => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateform($request);
        if (!$validator->fails()) {
            $param['name'] = $request->input('name');
            $param['url']  = $request->input('url');
            $param['keterangan'] = $request->input('keterangan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                                try{
                                    $userguide = UserGuide::create((array)$param);

                                    DB::commit();
                                    $result = [
                                        'flag' => 'success',
                                        'msg' => 'Sukses tambah data',
                                        'title' => 'Sukses'
                                    ];
                                }catch(\Exception $e){
                                    DB::rollback();
                                    $result = [
                                        'flag' => 'warning',
                                        'msg' => $e->getMessage(),
                                        'title' => 'Gagal'
                                    ];
                                }
                break;

                case 'update': DB::beginTransaction();
                                try{
                                    $userguide = UserGuide::find((int)$request->input('id'));
                                    $userguide->update((array)$param);

                                    DB::commit();
                                    $result = [
                                        'flag' => 'success',
                                        'msg' => 'Sukses ubah data',
                                        'title' => 'Sukses'
                                    ];
                                }catch(\Exception $e){
                                    DB::rollback();
                                    $result = [
                                        'flag' => 'warning',
                                        'msg' => $e->getMessage(),
                                        'title' => 'Gagal'
                                    ];
                                }
                break;
            }
        }else{
            $message = $validator->errors()->all('<li>:message</li>');
            $result = [
                'flag' => 'warning',
                'msg' => '<ul>'.implode('', $message).'</ul>',
                'title' => 'Gagal proses data'
            ];
        }

        return response()->json($result);
    }

    protected function validateform($request)
    {
        $required['name'] = 'required';

        $message['name.required'] = 'Nama wajib diisi';
        $message['url.required'] = 'Url wajib diisi';

        return Validator::make($request->all(), $required, $message);
    }

    public function edit(Request $request)
    {

        try{
            $userguide = UserGuide::find((int)$request->input('id'));
                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $userguide

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = UserGuide::find((int)$request->input('id'));
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

}
