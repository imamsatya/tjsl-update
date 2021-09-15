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

use App\Models\Tpb;
use App\Models\KodeIndikator;

class KodeIndikatorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'referensi.kode_indikator';
        $this->pagetitle = 'Kode Indikator';
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
            'breadcrumb' => 'Referensi - Kode Indikator'
        ]);
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        $kode = KodeIndikator::orderBy('kode')->get();
        try{
            return datatables()->of($kode)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->kode_tujuan_tpb.'" data-toggle="tooltip" title="Hapus data"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->addColumn('tpb', function ($row){
                $tpb = @$row->tpb->no_tpb . ' - ' . @$row->tpb->nama;
                return $tpb;
            })
            ->rawColumns(['keterangan','action','tpb'])
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
        $kode_indikator = KodeIndikator::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $kode_indikator,
            'hastpb' => null,
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
    
        $param = $request->except('actionform','id');

        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                            try{
                                $kode_indikator = KodeIndikator::create((array)$param);

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
                                $kode_indikator = KodeIndikator::find((int)$request->input('id'));
                                $kode_indikator->update((array)$param);

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

            $kode_indikator = KodeIndikator::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $kode_indikator,
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
            $data = KodeIndikator::find((int)$request->input('id'));
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
}
