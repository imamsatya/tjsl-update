<?php

namespace App\Http\Controllers\PUMK;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\AnggaranTpb;
use App\Models\Perusahaan;
use App\Models\PilarPembangunan;
use App\Models\Tpb;
use App\Models\VersiPilar;
use App\Models\User;
use App\Models\LogAnggaranTpb;
use App\Models\PeriodeLaporan;
use App\Models\Status;
use App\Models\PumkAnggaran;
use App\Exports\AnggaranTpbExport;

class AnggaranController extends Controller
{
    public function __construct()
    {
        $this->__route = 'PUMK.anggaran';
        $this->pagetitle = 'Data Anggaran PUMK';
    }

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

        $anggaran_pumk = PumkAnggaran::select('pumk_anggarans.*','perusahaans.nama_singkat AS bumn_singkat','periode_laporans.nama AS periode','statuses.nama AS status')
                        ->leftJoin('perusahaans','perusahaans.id','pumk_anggarans.bumn_id')
                        ->leftJoin('periode_laporans', 'periode_laporans.id', 'pumk_anggarans.periode_id')
                        ->leftJoin('statuses', 'statuses.id', 'pumk_anggarans.status_id');
                                        
        if($perusahaan_id){
            $anggaran_pumk  = $anggaran_pumk->where('bumn_id', $perusahaan_id);
        }

        if($request->periode_id){
            $anggaran_pumk  = $anggaran_pumk->where('periode_id', $request->periode_id);
        }

        if($request->status_id){
            $anggaran_pumk  = $anggaran_pumk->where('status_id', $request->status_id);
        }

        if($request->tahun){
            $anggaran_pumk  = $anggaran_pumk->where('tahun', $request->tahun);
        }        
        
        $anggaran_pumk = $anggaran_pumk->orderBy('tahun','desc')->get();


        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'filter_bumn_id' => $perusahaan_id,
            'filter_periode_id' => $request->periode_id,
            'filter_status_id' => $request->status_id,
            'filter_tahun' => $request->tahun,
            'anggaran_pumk' => $anggaran_pumk,
            'periode' => PeriodeLaporan::get(),
            'status' => Status::get()
        ]);
    }

    public function create()
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = \Auth::user()->id_bumn;
        
        $admin_bumn = false;
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                }
            }
        }
        
        return view($this->__route.'.create',[
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'periode' => PeriodeLaporan::get()
        ]);

    }


    public function store(Request $request)
    {

        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                            try{
                                $validasi = true;
                                $param = $request->all();
                                $param = $request->except(['actionform','id','_token']);
                                $param['status_id'] = DB::table('statuses')->where('nama','INFILLED')->pluck('id')->first();
                                $data = PumkAnggaran::create($param);
                                if($validasi){
                                    DB::commit();
                                    $result = [
                                    'flag'  => 'success',
                                    'msg' => 'Sukses tambah data',
                                    'title' => 'Sukses'
                                    ];
                                }else{
                                    DB::rollback();
                                    $result = [
                                    'flag'  => 'warning',
                                    'msg' => 'Data Anggaran '.$validasi_msg.' sudah ada',
                                    'title' => 'Gagal'
                                    ];
                                }
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
                                $anggaran_tpb = AnggaranTpb::find((int)$request->input('id'));
                                $param['anggaran'] = str_replace(',', '', $request->input('anggaran'));
                                $anggaran_tpb->update((array)$param);
                                
                                AnggaranTpbController::store_log($anggaran_tpb->id,$anggaran_tpb->status_id,$param['anggaran']);

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

    public function delete(Request $request)
    {
        DB::beginTransaction();
       try{
            $data = PumkAnggaran::find((int)$request->input('id'));
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
        
       DB::beginTransaction();
       try{
            $data = PumkAnggaran::find((int)$request->input('id'));

            $status = Status::find((int)$data->status_id);

            if($status->nama == 'INFILLED'){
                $data = $data->update([
                    'status_id' => Status::where('nama','INPROGRESS')->pluck('id')->first(),
                ]);
            }else if($status->nama == 'INPROGRESS'){
                    $data = $data->update([
                        'status_id' => Status::where('nama','FINISH')->pluck('id')->first(),
                    ]);
            }else if($status->nama == 'FINISH'){
                    $data = $data->update([
                        'status_id' => Status::where('nama','INPROGRESS')->pluck('id')->first(),
                    ]);
            }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses ubah status',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal ubah status',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
}
