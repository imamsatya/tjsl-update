<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;

use App\Models\SatuanUkur;

class SatuanUkurController extends Controller
{
    public function __construct()
    {
        $this->__route = 'referensi.satuan_ukur';
        $this->pagetitle = 'Satuan Ukur';
    }

    public function index()
    {
        return view($this->__route.'.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Referensi - Satuan Ukur'
        ]);
    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(SatuanUkur::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','keterangan','is_active','action'])
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
        $satuanukur = SatuanUkur::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $satuanukur
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
            $param['nama'] = $request->input('nama');
            $param['keterangan'] = $request->input('keterangan');
            $param['is_active'] = $request->input('is_active');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $satuanukur = SatuanUkur::create((array)$param);

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
                                  $satuanukur = SatuanUkur::find((int)$request->input('id'));
                                  $satuanukur->update((array)$param);

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

    public function edit(Request $request)
    {

        try{

            $satuanukur = SatuanUkur::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $satuanukur

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SatuanUkur::find((int)$request->input('id'));
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

    protected function validateform($request)
    {
        $required['nama'] = 'required';
        $required['status'] = 'required';

        $message['nama.required'] = 'Nama wajib diinput';
        $message['status.required'] = 'Status wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

}