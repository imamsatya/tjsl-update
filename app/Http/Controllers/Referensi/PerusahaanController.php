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

use App\Models\Perusahaan;

class PerusahaanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'referensi.perusahaan';
        $this->pagetitle = 'BUMN';
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
            'breadcrumb' => 'Referensi - BUMN'
        ]);
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        try{
            $perusahaan = Perusahaan::orderBy('id', 'asc')->get();

            return datatables()->of($perusahaan)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button> ';

                // $button .= '&nbsp;';

                // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->editColumn('is_active', function ($row){
                $id = $row->is_active;
                $button = '<div align="center">';

                $checked = 'title="Ubah menjadi Aktif"';
                if($row->is_active){
                    $checked = 'title="Ubah menjadi Tidak Aktif" checked="checked"';
                }
                $button .= '<label class="form-check form-switch form-check-custom form-check-solid">
                                <input id="edit_active" class="form-check-input" data-id="'.$row->id.'" data-nama="'.$row->nama_lengkap.'" type="checkbox" value="1" data-toggle="tooltip" '.$checked.' />
                            </label>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','keterangan','action','is_active'])
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
        $perusahaan = Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $perusahaan
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
                                  $perusahaan = Perusahaan::create((array)$param);

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
                                  $perusahaan = Perusahaan::find((int)$request->input('id'));
                                  $perusahaan->update((array)$param);

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_active(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        try{
            $param['is_active'] = $request->input('is_active');
            $perusahaan = Perusahaan::find((int)$request->input('id'));
            $perusahaan->update((array)$param);

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

            $perusahaan = Perusahaan::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $perusahaan
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
            $data = Perusahaan::find((int)$request->input('id'));
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

    public function inactiveAll()
    {
        DB::beginTransaction();
        try{
            $data = Perusahaan::get();

            foreach($data as $val){
                $val->update([
                    'is_active'=>false
                ]);
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses Inactive Semua data',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal Inactive Semua data',
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
        $required['nama_lengkap'] = 'required';

        $message['nama_lengkap.required'] = 'Nama wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }
}
