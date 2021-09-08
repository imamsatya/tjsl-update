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
use App\Models\User;
use App\Models\PeriodeLaporan;
use App\Models\Status;
use App\Models\PumkAnggaran;
use App\Exports\AnggaranPumkExport;

class AnggaranController extends Controller
{
    public function __construct()
    {
        $this->__route = 'pumk.anggaran';
        $this->pagetitle = 'Data Anggaran PUMK';
    }

    public function index(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;

        $admin_bumn = false;
        $super_admin = false;
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
                if($v == 'Super Admin') {
                    $super_admin = true;
                    $perusahaan_id;
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
            'super_admin' => $super_admin,
            'filter_bumn_id' => $perusahaan_id,
            'filter_periode_id' => $request->periode_id,
            'filter_status_id' => $request->status_id,
            'filter_tahun' => $request->tahun,
            'anggaran_pumk' => $anggaran_pumk,
            'periode' => PeriodeLaporan::orderby('urutan','asc')->get(),
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
            'periode' => PeriodeLaporan::orderby('urutan','asc')->get(),
        ]);

    }

    public function show(Request $request)
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
        
        $data = PumkAnggaran::select('pumk_anggarans.*','perusahaans.nama_lengkap AS bumn_lengkap','periode_laporans.nama AS periode','statuses.nama AS status')
                        ->leftJoin('perusahaans','perusahaans.id','pumk_anggarans.bumn_id')
                        ->leftJoin('periode_laporans', 'periode_laporans.id', 'pumk_anggarans.periode_id')
                        ->leftJoin('statuses', 'statuses.id', 'pumk_anggarans.status_id')
                        ->where('pumk_anggarans.id',$request->id)
                        ->first();
        return view($this->__route.'.show',[
            'pagetitle' => $this->pagetitle,
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'periode' => PeriodeLaporan::orderby('urutan','asc')->get(),
            'data' => $data
        ]);

    }

    public function edit(Request $request)
    {
        try{
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
            $data= PumkAnggaran::find((int)$request->input('id'));

                return view($this->__route.'.edit',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
                    'admin_bumn' => $admin_bumn,
                    'perusahaan_id' => $perusahaan_id,
                    'periode' => PeriodeLaporan::orderby('urutan','asc')->get(),
                    'data' => $data
                ]);
       }catch(Exception $e){}

    }

    public function store(Request $request)
    {

       // dd($request->all());
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
                                $param['saldo_awal'] = $request->saldo_awal == null? 0 : preg_replace('/[^0-9]/','',$request->saldo_awal);
                                $param['income_mitra_binaan'] = $request->income_mitra_binaan == null? 0 : preg_replace('/[^0-9]/','',$request->income_mitra_binaan);
                                $param['income_bumn_pembina_lain'] = $request->income_bumn_pembina_lain == null? 0 : preg_replace('/[^0-9]/','',$request->income_bumn_pembina_lain);
                                $param['income_jasa_adm_pumk'] = $request->income_jasa_adm_pumk == null? 0 : preg_replace('/[^0-9]/','',$request->income_jasa_adm_pumk);
                                $param['income_adm_bank'] = $request->income_adm_bank == null? 0 : preg_replace('/[^0-9]/','',$request->income_adm_bank);
                                $param['income_total'] = $request->income_total == null? 0 : preg_replace('/[^0-9]/','',$request->income_total);
                                $param['outcome_mandiri'] = $request->outcome_mandiri == null? 0 : preg_replace('/[^0-9]/','',$request->outcome_mandiri);
                                $param['outcome_kolaborasi_bumn'] = $request->outcome_kolaborasi_bumn == null? 0 : preg_replace('/[^0-9]/','',$request->outcome_kolaborasi_bumn);
                                $param['outcome_bumn_khusus'] = $request->outcome_bumn_khusus == null? 0 :preg_replace('/[^0-9]/','',$request->outcome_bumn_khusus);
                                $param['outcome_total'] = $request->outcome_total == null? 0 :preg_replace('/[^0-9]/','',$request->outcome_total);
                                $param['saldo_akhir'] = $request->saldo_akhir == null? 0 :preg_replace('/[^0-9]/','',$request->saldo_akhir);
                                $param['created_by'] = \Auth::user()->id;
                                $param['created_at'] = now(); 
                                $param['status_id'] = DB::table('statuses')->where('nama','Unfilled')->pluck('id')->first();
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
                                $param = $request->all();
                                $param = $request->except(['actionform','_token','bumn_id']);
                                $param['saldo_awal'] = $request->saldo_awal == null? 0 : preg_replace('/[^0-9]/','',$request->saldo_awal);
                                $param['income_mitra_binaan'] = $request->income_mitra_binaan == null? 0 : preg_replace('/[^0-9]/','',$request->income_mitra_binaan);
                                $param['income_bumn_pembina_lain'] = $request->income_bumn_pembina_lain == null? 0 : preg_replace('/[^0-9]/','',$request->income_bumn_pembina_lain);
                                $param['income_jasa_adm_pumk'] = $request->income_jasa_adm_pumk == null? 0 : preg_replace('/[^0-9]/','',$request->income_jasa_adm_pumk);
                                $param['income_adm_bank'] = $request->income_adm_bank == null? 0 : preg_replace('/[^0-9]/','',$request->income_adm_bank);
                                $param['income_total'] = $request->income_total == null? 0 : preg_replace('/[^0-9]/','',$request->income_total);
                                $param['outcome_mandiri'] = $request->outcome_mandiri == null? 0 : preg_replace('/[^0-9]/','',$request->outcome_mandiri);
                                $param['outcome_kolaborasi_bumn'] = $request->outcome_kolaborasi_bumn == null? 0 : preg_replace('/[^0-9]/','',$request->outcome_kolaborasi_bumn);
                                $param['outcome_bumn_khusus'] = $request->outcome_bumn_khusus == null? 0 :preg_replace('/[^0-9]/','',$request->outcome_bumn_khusus);
                                $param['outcome_total'] = $request->outcome_total == null? 0 :preg_replace('/[^0-9]/','',$request->outcome_total);
                                $param['saldo_akhir'] = $request->saldo_akhir == null? 0 :preg_replace('/[^0-9]/','',$request->saldo_akhir);
                                $param['updated_by'] = \Auth::user()->id; 
                                $param['updated_at'] = now(); 
                                $data = PumkAnggaran::find($param['id']);
                                $data->update((array)$param);

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

            if($status->nama == 'Unfilled'){
                $data = $data->update([
                    'status_id' => Status::where('nama','In Progress')->pluck('id')->first(),
                ]);
            }else if($status->nama == 'In Progress'){
                    $data = $data->update([
                        'status_id' => Status::where('nama','Finish')->pluck('id')->first(),
                    ]);
            }else if($status->nama == 'Finish'){
                    $data = $data->update([
                        'status_id' => Status::where('nama','In Progress')->pluck('id')->first(),
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


    public function export(Request $request)
    {

        $anggaran_pumk = PumkAnggaran::select('pumk_anggarans.*','perusahaans.nama_lengkap AS bumn_lengkap','periode_laporans.nama AS periode','statuses.nama AS status')
                        ->leftJoin('perusahaans','perusahaans.id','pumk_anggarans.bumn_id')
                        ->leftJoin('periode_laporans', 'periode_laporans.id', 'pumk_anggarans.periode_id')
                        ->leftJoin('statuses', 'statuses.id', 'pumk_anggarans.status_id');
        
                        if($request->perusahaan_id !== null){
                            $anggaran_pumk  = $anggaran_pumk->where('bumn_id', $perusahaan_id);
                        }
                
                        if($request->periode_id !== null){
                            $anggaran_pumk  = $anggaran_pumk->where('periode_id', $request->periode_id);
                        }
                
                        if($request->status_id !== null){
                            $anggaran_pumk  = $anggaran_pumk->where('status_id', $request->status_id);
                        }
                
                        if($request->tahun !== null){
                            $anggaran_pumk  = $anggaran_pumk->where('tahun', $request->tahun);
                        }        
                        
                        $anggaran_pumk = $anggaran_pumk->orderBy('tahun','desc');

        $anggaran_pumk = $anggaran_pumk->get();

        $namaFile = "Data Anggaran PUMK ".date('dmY').".xlsx";
        return Excel::download(new AnggaranPumkExport($anggaran_pumk,$request->tahun), $namaFile);
    }
}
