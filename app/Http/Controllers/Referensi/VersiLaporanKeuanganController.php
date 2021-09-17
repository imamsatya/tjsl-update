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

use App\Models\VersiLaporanKeuangan;
use App\Models\LaporanKeuangan;
use App\Models\RelasiLaporanKeuangan;
use App\Models\LaporanKeuanganParent;

class VersiLaporanKeuanganController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'referensi.versi_laporan_keuangan';
        $this->pagetitle = 'Versi Laporan Keuangan';
    }

    public function index()
    {
        $versilaporankeuangan = VersiLaporanKeuangan::orderBy('status')->orderBy('tanggal_akhir', 'desc')->get();
        $laporankeuangan = RelasiLaporanKeuangan::select('laporan_keuangans.nama','relasi_laporan_keuangan.versi_laporan_id','laporan_keuangans.id')
                                ->leftJoin('laporan_keuangans', 'laporan_keuangans.id', 'relasi_laporan_keuangan.laporan_keuangan_id')
                                ->GroupBy('laporan_keuangans.id')
                                ->GroupBy('laporan_keuangans.nama')
                                ->GroupBy('relasi_laporan_keuangan.versi_laporan_id')
                                ->orderBy('laporan_keuangans.nama')
                                ->get();
        $parent = LaporanKeuanganParent::leftjoin('relasi_laporan_keuangan','parent_id','=','laporan_keuangan_parent.id')
                    ->get();
        

        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'versilaporankeuangan' => $versilaporankeuangan,
            'laporankeuangan' => $laporankeuangan,
            'parent' => $parent,
            'breadcrumb' => 'Referensi - Versi Laporan Keuangan'
        ]);
    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(VersiLaporanKeuangan::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
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

    public function create()
    {
        $versilaporankeuangan = VersiLaporanKeuangan::get();

        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $versilaporankeuangan
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
            $param = $request->except('actionform','id','tanggal_awal','tanggal_akhir','status');
            
            $param['tanggal_awal'] = null;
            $param['tanggal_akhir'] = null;
            if($request->tanggal_awal){
                $param['tanggal_awal'] = date_format(date_create($request->tanggal_awal),"Y-m-d");
            }
            if($request->tanggal_akhir){
                $param['tanggal_akhir'] = date_format(date_create($request->tanggal_akhir),"Y-m-d");
            }
            
            $param['status'] = false;
            if($request->status){
                $param['status'] = true;
            }

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $versilaporankeuangan = VersiLaporanKeuangan::create((array)$param);

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
                                  $versilaporankeuangan = VersiLaporanKeuangan::find((int)$request->input('id'));
                                  $versilaporankeuangan->update((array)$param);

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

            $versilaporankeuangan = VersiLaporanKeuangan::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $versilaporankeuangan

                ]);
        }catch(Exception $e){}

    }

    protected function validateform($request)
    {
        $required['versi'] = 'required';

        $message['versi.required'] = 'Nama wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = VersiLaporanKeuangan::find((int)$request->input('id'));
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

    public function update_status(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        try{
            $param['status'] = $request->input('status');
            $versilaporankeuangan = VersiLaporanKeuangan::find((int)$request->input('id'));
            $versilaporankeuangan->update((array)$param);

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

    public function add_laporan(Request $request)
    {
        try{
            $versilaporankeuangan = VersiLaporanKeuangan::find((int)$request->input('id'));
            $laporankeuangan = LaporanKeuangan::get();
            // $tpb = Tpb::get();

                return view($this->__route.'.form_laporan',[
                    'pagetitle' => 'Jenis Laporan',
                    'actionform' => 'insert',
                    'data' => $versilaporankeuangan,
                    'laporankeuangan' => $laporankeuangan,
                    // 'tpb' => $tpb,
                ]);
        }catch(Exception $e){}

    }

    public function add_parent(Request $request)
    {
        // try{
        //     $versilaporankeuangan = VersiLaporanKeuangan::find((int)$request->input('id'));
        //     $laporankeuangan = LaporanKeuangan::get();
            // $tpb = Tpb::get();

                return view($this->__route.'.form_parent',[
                    'pagetitle' => 'Parent Laporan',
                    'actionform' => 'insert',
                    'versi_id' => $request->versi_id,
                    'lapor_id' => $request->lapor_id,
                    // 'data' => $versilaporankeuangan,
                    // 'laporankeuangan' => $laporankeuangan,
                    // 'tpb' => $tpb,
                ]);
        // }catch(Exception $e){}

    }

    public function store_parent(Request $request)
    {
      
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        // $validator = $this->validateform($request);
        // if (!$validator->fails()) {
            $param = $request->except('actionform','_token','versi_laporan_id','laporan_keuangan_id');
           //dd($param);
            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  //$params = $param->except();
                                  //dd();
                                  $lapkeu_parent = LaporanKeuanganParent::create((array)$param);

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
                            //    try{
                            //       $versilaporankeuangan = VersiLaporanKeuangan::find((int)$request->input('id'));
                            //       $versilaporankeuangan->update((array)$param);

                            //       DB::commit();
                            //       $result = [
                            //         'flag'  => 'success',
                            //         'msg' => 'Sukses ubah data',
                            //         'title' => 'Sukses'
                            //       ];
                            //    }catch(\Exception $e){
                            //       DB::rollback();
                            //       $result = [
                            //         'flag'  => 'warning',
                            //         'msg' => $e->getMessage(),
                            //         'title' => 'Gagal'
                            //       ];
                            //    }

                break;
            }
        // }else{
        //     $messages = $validator->errors()->all('<li>:message</li>');
        //     $result = [
        //         'flag'  => 'warning',
        //         'msg' => '<ul>'.implode('', $messages).'</ul>',
        //         'title' => 'Gagal proses data'
        //     ];
        // }

        return response()->json($result);

    }

    public function edit_laporan(Request $request)
    {
        try{
            $versilaporankeuangan = VersiLaporanKeuangan::find((int)$request->input('versi'));
            $relasi = RelasiLaporanKeuangan::where('versi_laporan_id',(int)$request->input('versi'))->where('laporan_keuangan_id',(int)$request->input('id'));
            $laporankeuangan = LaporanKeuangan::get();
            // $tpb = Tpb::get();
            // $tpb_id = $relasi->pluck('tpb_id')->all();

                return view($this->__route.'.form_laporan',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'data' => $versilaporankeuangan,
                    'relasi' => $relasi,
                    'laporankeuangan' => $laporankeuangan,
                    // 'tpb' => $tpb,
                    // 'tpb_id' => $tpb_id,
                    'laporan_keuangan_id' => (int)$request->input('id')

                ]);
        }catch(Exception $e){}

    }

    public function store_laporan(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $param['versi_laporan_id'] = $request->input('id');
        $param['laporan_keuangan_id'] = $request->input('laporan_keuangan_id');
        // $tpb = $request->input('tpb');
        // $laporankeuangan = $request->input('laporan_keuangan_id');

        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                            try{
                                
                                // foreach($laporankeuangan as $p){
                                //     $param['laporan_keuangan_id'] = $p;
                                //     RelasiLaporanKeuangan::create((array)$param);
                                // }

                                $relasilaporankeuangan = RelasiLaporanKeuangan::create((array)$param);

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
                                // $data = RelasiLaporanKeuangan::where('versi_laporan_id',(int)$request->input('id'))
                                //                         ->where('laporan_keuangan_id',(int)$request->input('laporan_keuangan_id'));
                                // $data->delete();
                                
                                // foreach($tpb as $p){
                                //     $param['tpb_id'] = $p;
                                //     RelasiPilarTpb::create((array)$param);
                                // }

                                // foreach($laporankeuangan as $p){
                                //     $param['laporan_keuangan_id'] = $p;
                                //     RelasiLaporanKeuangan::create((array)$param);
                                // }

                                $relasilaporankeuangan = RelasiLaporanKeuangan::find((int)$request->input('id'));
                                $relasilaporankeuangan->update((array)$param);

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
        return $result;
    }

}
