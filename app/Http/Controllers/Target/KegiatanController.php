<?php

namespace App\Http\Controllers\Target;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;

use App\Models\TargetTpb;
use App\Models\SatuanUkur;
use App\Models\Perusahaan;
use App\Models\Kegiatan;
use App\Models\Tpb;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\PilarPembangunan;

class KegiatanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'target.kegiatan';
        $this->pagetitle = 'Input Kegiatan';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // $perusahaan_id = $request->perusahaan_id;
        $perusahaan_id =  ($request->perusahaan_id?$request->perusahaan_id:1);
        $tahun = ($request->tahun?$request->tahun:date('Y'));
        $perusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        $kegiatan = Kegiatan::get();
        $pilar = PilarPembangunan::get();
        $tpb = Tpb::get();

        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Target - Administrasi - Input Data Kegiatan',
            'perusahaan_id' => $perusahaan_id,
            'perusahaan' => $perusahaan,
            'kegiatan' => $kegiatan,
            'tahun' => $tahun,
            'pilar' => $pilar,
            'tpb' => $tpb,
        ]);
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {   
        $target_tpb = TargetTpb::OrderBy('program')->get();
        try{
            return datatables()->of($target_tpb)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->addColumn('program', function ($row){
                return @$row->target_tpb->program;
            })
            ->addColumn('provinsi', function ($row){
                return @$row->provinsi->nama;
            })
            ->addColumn('kota', function ($row){
                return @$row->kota->nama;
            })
            ->addColumn('satuan_ukur', function ($row){
                return @$row->satuan_ukur->nama;
            })
            ->addColumn('target', function ($row){
                return '';
            })
            ->addColumn('realisasi', function ($row){
                return '';
            })
            ->rawColumns(['nama','keterangan','action'])
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
        $target_tpb = TargetTpb::get();
        $tpb = Tpb::get();
        $provinsi = Provinsi::where('is_luar_negeri', 'false')->get();
        $kota = Kota::where('is_luar_negeri', 'false')->get();
        $satuan_ukur = SatuanUkur::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $target_tpb,
            'target_tpb' => $target_tpb,
            'tpb' => $tpb,
            'provinsi' => $provinsi,
            'kota' => $kota,
            'satuan_ukur' => $satuan_ukur,
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

        $param = $request->except(['actionform','id','_token']);

        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                            try{
                                $target_tpb = Kegiatan::create((array)$param);

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
                                $target_tpb = Kegiatan::find((int)$request->input('id'));
                                $target_tpb->update((array)$param);

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

            $kegiatan = Kegiatan::find((int)$request->input('id'));
            $tpb = Tpb::get();
            $provinsi = Provinsi::where('is_luar_negeri', 'false')->get();
            $kota = Kota::where('is_luar_negeri', 'false')->get();
            $satuan_ukur = SatuanUkur::get();
            $target_tpb = TargetTpb::get();
    
                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $kegiatan,
                    'target_tpb' => $target_tpb,
                    'tpb' => $tpb,
                    'provinsi' => $provinsi,
                    'kota' => $kota,
                    'satuan_ukur' => $satuan_ukur,
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
            $data = TargetTpb::find((int)$request->input('id'));
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
