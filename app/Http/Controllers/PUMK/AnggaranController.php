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
use PDF;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Menu;
use App\Models\PeriodeLaporan;
use App\Models\Status;
use App\Models\PumkAnggaran;
use App\Models\LogPumkAnggaran;
use App\Exports\AnggaranPumkExport;

class AnggaranController extends Controller
{
    public function __construct()
    {
        $this->__route = 'pumk.anggaran';
        $this->pagetitle = 'Sumber dan Penggunaan Dana PUMK';
        $this->breadcumb = Menu::where('route_name',$this->__route.'.index')->pluck('label')->first();
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
            'breadcrumb' => $this->breadcumb,
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'admin_tjsl' => $admin_tjsl,
            'super_admin' => $super_admin,
            'filter_bumn_id' => $perusahaan_id,
            'filter_periode_id' => $request->periode_id,
            'filter_status_id' => $request->status_id,
            'filter_tahun' => $request->tahun,
            'periode' => PeriodeLaporan::orderby('urutan','asc')->get(),
            'status' => Status::get()
        ]);
    }

    public function datatable(Request $request)
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
        try{
            $anggaran_pumk = PumkAnggaran::select('pumk_anggarans.*','perusahaans.nama_singkat AS bumn_singkat','periode_laporans.nama AS periode','statuses.nama AS status')
            ->leftJoin('perusahaans','perusahaans.id','pumk_anggarans.bumn_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', 'pumk_anggarans.periode_id')
            ->leftJoin('statuses', 'statuses.id', 'pumk_anggarans.status_id');
                            
            if($perusahaan_id){
            $anggaran_pumk  = $anggaran_pumk->where('bumn_id', (int)$perusahaan_id);
            }

            if($request->periode_id){
            $anggaran_pumk  = $anggaran_pumk->where('periode_id', (int)$request->periode_id);
            }

            if($request->status_id){
            $anggaran_pumk  = $anggaran_pumk->where('status_id', (int)$request->status_id);
            }

            if($request->tahun){
            $anggaran_pumk  = $anggaran_pumk->where('tahun', $request->tahun);
            }        

            $data = $anggaran_pumk->orderBy('perusahaans.nama_singkat','asc')->orderBy('periode_laporans.nama','asc');
            
            return datatables()->of($data->get())
            ->editColumn('outcome_total', function ($row){
                $nominal = 0;
                if($row->outcome_total){
                    $nominal = number_format($row->outcome_total,0,',',',');
                }else{
                    $nominal;
                }
                return $nominal;
            })
            ->editColumn('income_total', function ($row){
                $saldo = 0;
                if($row->income_total){
                    $saldo = number_format($row->income_total,0,',',',');
                }else{
                    $saldo;
                }
                return $saldo;
            })
            ->editColumn('saldo_akhir', function ($row){
                $saldo = 0;
                if($row->saldo_akhir){
                    $saldo = number_format($row->saldo_akhir,0,',',',');
                }else{
                    $saldo;
                }
                return $saldo;
            })
            ->editColumn('status', function ($p){
                $log = '<div style="width:120px;text-align:center;"><span><button type="button" class="btn btn-sm cls-button-log" data-id="'.$p->id.'" data-nama="Log '.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Log data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'">
                '.$p->status.'</button></span><div>';
                return $log;
            })
            ->addColumn('action', function ($p){
                $id = (int)$p->id;
                if($p->status !== 'Finish'){
                    if($p->periode !== 'RKA'){
                        //jika status belum finish dan peride bukan RKA
                        if(\Auth::user()->getRoleNames()->first() == 'Admin BUMN'){
                            $btn = '<div style="width:120px;text-align:center;"><span>
                            <button type="button" class="btn btn-sm btn-success btn-icon cls-button-edit" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Edit data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-pencil fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete-pumkanggaran" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Hapus data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-trash fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                            </span><div>
                            ';
                        }else{
                            $btn = '<div style="width:120px;text-align:center;"><span>
                            <button type="button" class="btn btn-sm btn-success btn-icon cls-button-edit" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Edit data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-pencil fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-warning btn-icon cls-button-update-status" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="update status '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-check fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete-pumkanggaran" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Hapus data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-trash fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                            </span><div>
                            ';
                        }

                    }else{
                      //jika status belum finish dan peride RKA
                        if(\Auth::user()->getRoleNames()->first() == 'Admin BUMN'){
                        $btn = '<div style="width:120px;text-align:center;"><span>
                        <button type="button" class="btn btn-sm btn-success btn-icon cls-button-edit" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Edit data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-pencil fs-3"></i></button>

                        <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete-pumkanggaran" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Hapus data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-trash fs-3"></i></button>

                        <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                        </span><div>
                        ';
                        }else{
                            $btn = '<div style="width:120px;text-align:center;"><span>
                            <button type="button" class="btn btn-sm btn-success btn-icon cls-button-edit" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Edit data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-pencil fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-warning btn-icon cls-button-update-status" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="update status '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-check fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete-pumkanggaran" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Hapus data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-trash fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                            </span><div>
                            ';                            
                        }
                    }
                   
                }else{
                    //jika status finish dan peride bukan RKA
                    if($p->periode !== 'RKA'){
                        if(\Auth::user()->getRoleNames()->first() == 'Admin BUMN'){
                        $btn = '
                        <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                        ';
                        }else{
                            $btn = '
                            <button type="button" class="btn btn-sm btn-secondary btn-icon cls-button-aktivasi-status" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'"" data-toggle="tooltip" title="Aktivasi kembali status '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-layer-backward fs-3"></i></button>
                            <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                            ';                            
                        }
                    }else{
                      //jika status finish dan peride RKA
                        $btn = '';
                        if(\Auth::user()->getRoleNames()->first() !== 'Admin BUMN'){
                        $btn = '
                        <button type="button" class="btn btn-sm btn-secondary btn-icon cls-button-aktivasi-status" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'"" data-toggle="tooltip" title="Aktivasi kembali status '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-layer-backward fs-3"></i></button>
                        <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                        ';
                        }else{
                            $btn = '
                            <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'" data-nama="'.$p->bumn_singkat.' periode '.$p->periode.' Tahun '.$p->tahun.'" data-toggle="tooltip" title="Lihat detail data '.$p->bumn_singkat.' Tahun '.$p->tahun.'" Periode '.$p->periode.'"><i class="bi bi-info fs-3"></i></button>
                            ';                            
                        }                    
                    }
                }
                return $btn;

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
        
        $data_rka = PumkAnggaran::select('pumk_anggarans.*','perusahaans.nama_lengkap AS bumn_lengkap','periode_laporans.nama AS periode','statuses.nama AS status')
                        ->leftJoin('perusahaans','perusahaans.id','pumk_anggarans.bumn_id')
                        ->leftJoin('periode_laporans', 'periode_laporans.id', 'pumk_anggarans.periode_id')
                        ->leftJoin('statuses', 'statuses.id', 'pumk_anggarans.status_id')
                        ->where('pumk_anggarans.bumn_id',$data->bumn_id)
                        ->where('periode_laporans.nama','ilike','%RKA%')
                        //->where('statuses.nama','ilike','%Finish%')
                        ->first();
        $status_rka = $data_rka->status == 'Finish'? true : false;

        //hitung persentase
        $const = 100;
            //dana tersedia
            $p_saldo_awal = 0;
            if($data->saldo_awal == null || $data_rka->saldo_awal == null){
                $p_saldo_awal;
            }else{
                $p_saldo_awal = $data->saldo_awal/$data_rka->saldo_awal * $const;
            }
            $p_income_mitra_binaan = 0;
            if($data->income_mitra_binaan == null || $data_rka->income_mitra_binaan == null){
                $p_income_mitra_binaan;
            }else{
                $p_income_mitra_binaan = $data->income_mitra_binaan/$data_rka->income_mitra_binaan * $const;
            }
            $p_income_bumn_pembina_lain = 0;
            if($data->income_bumn_pembina_lain == null || $data_rka->income_bumn_pembina_lain == null){
                $p_income_bumn_pembina_lain;
            }else{
                $p_income_bumn_pembina_lain = $data->income_bumn_pembina_lain/$data_rka->income_bumn_pembina_lain * $const;
            }
            $p_income_jasa_adm_pumk = 0;
            if($data->income_jasa_adm_pumk == null || $data_rka->income_jasa_adm_pumk == null){
                $p_income_jasa_adm_pumk;
            }else{
                $p_income_jasa_adm_pumk = $data->income_jasa_adm_pumk/$data_rka->income_jasa_adm_pumk * $const;
            }
            $p_income_adm_bank = 0;
            if($data->income_adm_bank == null || $data_rka->income_adm_bank == null){
                $p_income_adm_bank;
            }else{
                $p_income_adm_bank = $data->income_adm_bank/$data_rka->income_adm_bank * $const;
            }
            $p_income_total = 0;
            if($data->income_total == null || $data_rka->income_total == null){
                $p_income_total;
            }else{
                $p_income_total = $data->income_total/$data_rka->income_total * $const;
            }

            //dana disalurkan
            $p_outcome_mandiri = 0;
            if($data->outcome_mandiri == null || $data_rka->outcome_mandiri == null){
                $p_outcome_mandiri;
            }else{
                $p_outcome_mandiri = $data->outcome_mandiri/$data_rka->outcome_mandiri * $const;
            }
            $p_outcome_kolaborasi_bumn = 0;
            if($data->outcome_kolaborasi_bumn == null || $data_rka->outcome_kolaborasi_bumn == null){
                $p_outcome_kolaborasi_bumn;
            }else{
                $p_outcome_kolaborasi_bumn = $data->outcome_kolaborasi_bumn/$data_rka->outcome_kolaborasi_bumn * $const;
            }
            $p_outcome_bumn_khusus = 0;
            if($data->outcome_bumn_khusus == null || $data_rka->outcome_bumn_khusus == null){
                $p_outcome_bumn_khusus;
            }else{
                $p_outcome_bumn_khusus = $data->outcome_bumn_khusus/$data_rka->outcome_bumn_khusus * $const;
            }
            $p_outcome_total = 0;
            if($data->outcome_total == null || $data_rka->outcome_total == null){
                $p_outcome_total;
            }else{
                $p_outcome_total = $data->outcome_total/$data_rka->outcome_total * $const;
            }
            
         
        return view($this->__route.'.show',[
            'pagetitle' => $this->pagetitle,
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'periode' => PeriodeLaporan::orderby('urutan','asc')->get(),
            'data' => $data,
            'data_rka' => $data_rka,
            'p_saldo_awal' => $p_saldo_awal,
            'p_income_mitra_binaan' => $p_income_mitra_binaan,
            'p_income_bumn_pembina_lain' => $p_income_bumn_pembina_lain,
            'p_income_jasa_adm_pumk' => $p_income_jasa_adm_pumk,
            'p_income_adm_bank' => $p_income_adm_bank,
            'p_income_total' => $p_income_total,
            'p_outcome_mandiri' => $p_outcome_mandiri,
            'p_outcome_kolaborasi_bumn' => $p_outcome_kolaborasi_bumn,
            'p_outcome_bumn_khusus' => $p_outcome_bumn_khusus,
            'p_outcome_total' => $p_outcome_total,
            'status_rka' => $status_rka
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
                    'periode_text' => PeriodeLaporan::where('id',$data->periode_id)->first(),
                    'data' => $data
                ]);
       }catch(Exception $e){}

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
                                $perusahaan_id = \Auth::user()->id_bumn;
                                $param = $request->all();
                                $param = $request->except(['actionform','id','_token']);
                                if($request->bumn_id == null){
                                    $param['bumn_id'] = $perusahaan_id;
                                }
                                $param['saldo_awal'] = $request->saldo_awal == null? 0 : preg_replace('/[^-0-9]/','',$request->saldo_awal);
                                $param['income_mitra_binaan'] = $request->income_mitra_binaan == null? 0 : preg_replace('/[^-0-9]/','',$request->income_mitra_binaan);
                                $param['income_bumn_pembina_lain'] = $request->income_bumn_pembina_lain == null? 0 : preg_replace('/[^-0-9]/','',$request->income_bumn_pembina_lain);
                                $param['income_jasa_adm_pumk'] = $request->income_jasa_adm_pumk == null? 0 : preg_replace('/[^-0-9]/','',$request->income_jasa_adm_pumk);
                                $param['income_adm_bank'] = $request->income_adm_bank == null? 0 : preg_replace('/[^-0-9]/','',$request->income_adm_bank);
                                $param['income_total'] = $request->income_total == null? 0 : preg_replace('/[^-0-9]/','',$request->income_total);
                                $param['outcome_mandiri'] = $request->outcome_mandiri == null? 0 : preg_replace('/[^-0-9]/','',$request->outcome_mandiri);
                                $param['outcome_kolaborasi_bumn'] = $request->outcome_kolaborasi_bumn == null? 0 : preg_replace('/[^-0-9]/','',$request->outcome_kolaborasi_bumn);
                                $param['outcome_bumn_khusus'] = $request->outcome_bumn_khusus == null? 0 :preg_replace('/[^-0-9]/','',$request->outcome_bumn_khusus);
                                $param['outcome_total'] = $request->outcome_total == null? 0 :preg_replace('/[^-0-9]/','',$request->outcome_total);
                                $param['saldo_akhir'] = $request->saldo_akhir == null? 0 :preg_replace('/[^-0-9]/','',$request->saldo_akhir);
                                $param['created_by'] = \Auth::user()->id;
                                $param['created_at'] = now();
                                if($param['saldo_awal'] == 0 || $param['saldo_awal'] == null || $param['saldo_awal'] == ""){
                                    $param['status_id'] = DB::table('statuses')->where('nama','Unfilled')->pluck('id')->first();
                                }else{
                                    $param['status_id'] = DB::table('statuses')->where('nama','ilike','%In Progress%')->pluck('id')->first();
                                } 

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
                                
                                $param = $request->except(['actionform','_token']);
                                $param['saldo_awal'] = $request->saldo_awal == null? 0 : preg_replace('/[^-0-9]/','',$request->saldo_awal);
                                if((int)$param['saldo_awal'] !== 0){
                                    $status_ids = (int)$param['status_id'];
                                    $status = Status::find((int)$status_ids);

                                    if($status->nama == 'Unfilled'){
                                        $data_status = Status::where('nama','In Progress')->pluck('id')->first();
                                        $param['status_id'] = $data_status; 
                                    }else if($status->nama == 'In Progress'){
                                        $data_status = Status::where('nama','In Progress')->pluck('id')->first();
                                        $param['status_id'] = $data_status; 
                                    }else if($status->nama == 'Finish'){
                                        $data_status = Status::where('nama','In Progress')->pluck('id')->first();
                                        $param['status_id'] = $data_status; 
                                    }
                                }else{
                                    $data_status = Status::where('nama','Unfilled')->pluck('id')->first();
                                    $param['status_id'] = $data_status;
                                }
                                
                                $param['income_mitra_binaan'] = $request->income_mitra_binaan == null? 0 : preg_replace('/[^-0-9]/','',$request->income_mitra_binaan);
                                $param['income_bumn_pembina_lain'] = $request->income_bumn_pembina_lain == null? 0 : preg_replace('/[^-0-9]/','',$request->income_bumn_pembina_lain);
                                $param['income_jasa_adm_pumk'] = $request->income_jasa_adm_pumk == null? 0 : preg_replace('/[^-0-9]/','',$request->income_jasa_adm_pumk);
                                $param['income_adm_bank'] = $request->income_adm_bank == null? 0 : preg_replace('/[^-0-9]/','',$request->income_adm_bank);
                                $param['income_total'] = $request->income_total == null? 0 : preg_replace('/[^-0-9]/','',$request->income_total);
                                $param['outcome_mandiri'] = $request->outcome_mandiri == null? 0 : preg_replace('/[^-0-9]/','',$request->outcome_mandiri);
                                $param['outcome_kolaborasi_bumn'] = $request->outcome_kolaborasi_bumn == null? 0 : preg_replace('/[^-0-9]/','',$request->outcome_kolaborasi_bumn);
                                $param['outcome_bumn_khusus'] = $request->outcome_bumn_khusus == null? 0 :preg_replace('/[^-0-9]/','',$request->outcome_bumn_khusus);
                                $param['outcome_total'] = $request->outcome_total == null? 0 :preg_replace('/[^-0-9]/','',$request->outcome_total);
                                $param['saldo_akhir'] = $request->saldo_akhir == null? 0 :preg_replace('/[^-0-9]/','',$request->saldo_akhir);
                                $param['updated_by'] = \Auth::user()->id; 
                                $param['updated_at'] = now(); 
                                $data = PumkAnggaran::find($param['id']);
                                $cekPeriode = PeriodeLaporan::where('id',$data['periode_id'])->first();
                                $data->update((array)$param);

                                $log['pumk_anggaran_id'] = (int)$param['id'];
                                $log['status_id'] = (int)$param['status_id'];
                                if($cekPeriode->nama == 'RKA'){
                                    $log['nilai_rka'] = (int)$param['saldo_awal'];
                                }else{
                                    $log['nilai_rka'] = null;
                                }
                                $log['created_by_id'] = (int)$param['updated_by'];
                                $log['created_at'] = now();

                                AnggaranController::store_log($log);

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
                            $anggaran_pumk  = $anggaran_pumk->where('bumn_id',$request->perusahaan_id);
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

    public static function store_log($log)
    {  
        LogPumkAnggaran::insert($log);
    }

    public function log_status(Request $request)
    {
        $log = LogPumkAnggaran::select('log_pumk_anggarans.*','users.name AS user','statuses.nama AS status')
                                    ->leftjoin('users','users.id','=','log_pumk_anggarans.created_by_id')
                                    ->leftjoin('statuses','statuses.id','=','log_pumk_anggarans.status_id')
                                    ->where('pumk_anggaran_id', (int)$request->input('id'))
                                    ->orderBy('created_at')
                                    ->get();

        return view($this->__route.'.log_status',[
            'pagetitle' => 'Log Status',
            'log' => $log
        ]);

    }

    public function exportPDF($id) {
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
                        ->where('pumk_anggarans.id',$id)
                        ->first();

        $data_rka = PumkAnggaran::select('pumk_anggarans.*','perusahaans.nama_lengkap AS bumn_lengkap','periode_laporans.nama AS periode','statuses.nama AS status')
                        ->leftJoin('perusahaans','perusahaans.id','pumk_anggarans.bumn_id')
                        ->leftJoin('periode_laporans', 'periode_laporans.id', 'pumk_anggarans.periode_id')
                        ->leftJoin('statuses', 'statuses.id', 'pumk_anggarans.status_id')
                        ->where('pumk_anggarans.bumn_id',$data->bumn_id)
                        ->where('periode_laporans.nama','ilike','%RKA%')
                        //->where('statuses.nama','ilike','%Finish%')
                        ->first();
        //hitung persentase
        $const = 100;
            //dana tersedia
            $p_saldo_awal = 0;
            if($data->saldo_awal == null || $data_rka->saldo_awal == null){
                $p_saldo_awal;
            }else{
                $p_saldo_awal = $data->saldo_awal/$data_rka->saldo_awal * $const;
            }
            $p_income_mitra_binaan = 0;
            if($data->income_mitra_binaan == null || $data_rka->income_mitra_binaan == null){
                $p_income_mitra_binaan;
            }else{
                $p_income_mitra_binaan = $data->income_mitra_binaan/$data_rka->income_mitra_binaan * $const;
            }
            $p_income_bumn_pembina_lain = 0;
            if($data->income_bumn_pembina_lain == null || $data_rka->income_bumn_pembina_lain == null){
                $p_income_bumn_pembina_lain;
            }else{
                $p_income_bumn_pembina_lain = $data->income_bumn_pembina_lain/$data_rka->income_bumn_pembina_lain * $const;
            }
            $p_income_jasa_adm_pumk = 0;
            if($data->income_jasa_adm_pumk == null || $data_rka->income_jasa_adm_pumk == null){
                $p_income_jasa_adm_pumk;
            }else{
                $p_income_jasa_adm_pumk = $data->income_jasa_adm_pumk/$data_rka->income_jasa_adm_pumk * $const;
            }
            $p_income_adm_bank = 0;
            if($data->income_adm_bank == null || $data_rka->income_adm_bank == null){
                $p_income_adm_bank;
            }else{
                $p_income_adm_bank = $data->income_adm_bank/$data_rka->income_adm_bank * $const;
            }
            $p_income_total = 0;
            if($data->income_total == null || $data_rka->income_total == null){
                $p_income_total;
            }else{
                $p_income_total = $data->income_total/$data_rka->income_total * $const;
            }

            //dana disalurkan
            $p_outcome_mandiri = 0;
            if($data->outcome_mandiri == null || $data_rka->outcome_mandiri == null){
                $p_outcome_mandiri;
            }else{
                $p_outcome_mandiri = $data->outcome_mandiri/$data_rka->outcome_mandiri * $const;
            }
            $p_outcome_kolaborasi_bumn = 0;
            if($data->outcome_kolaborasi_bumn == null || $data_rka->outcome_kolaborasi_bumn == null){
                $p_outcome_kolaborasi_bumn;
            }else{
                $p_outcome_kolaborasi_bumn = $data->outcome_kolaborasi_bumn/$data_rka->outcome_kolaborasi_bumn * $const;
            }
            $p_outcome_bumn_khusus = 0;
            if($data->outcome_bumn_khusus == null || $data_rka->outcome_bumn_khusus == null){
                $p_outcome_bumn_khusus;
            }else{
                $p_outcome_bumn_khusus = $data->outcome_bumn_khusus/$data_rka->outcome_bumn_khusus * $const;
            }
            $p_outcome_total = 0;
            if($data->outcome_total == null || $data_rka->outcome_total == null){
                $p_outcome_total;
            }else{
                $p_outcome_total = $data->outcome_total/$data_rka->outcome_total * $const;
            }

        $pdf_doc = PDF::loadView($this->__route.'.export_pdf',[
        // return view($this->__route.'.export_pdf',[ //test by html
            'data' => $data,
            'data_rka' => $data_rka,
            'p_saldo_awal' => $p_saldo_awal,
            'p_income_mitra_binaan' => $p_income_mitra_binaan,
            'p_income_bumn_pembina_lain' => $p_income_bumn_pembina_lain,
            'p_income_jasa_adm_pumk' => $p_income_jasa_adm_pumk,
            'p_income_adm_bank' => $p_income_adm_bank,
            'p_income_total' => $p_income_total,
            'p_outcome_mandiri' => $p_outcome_mandiri,
            'p_outcome_kolaborasi_bumn' => $p_outcome_kolaborasi_bumn,
            'p_outcome_bumn_khusus' => $p_outcome_bumn_khusus,
            'p_outcome_total' => $p_outcome_total,
        ]);

        return $pdf_doc->download('Data_PUMK_'.date('d_m_Y').'.pdf');
    }
    
    public static function sync()
    {  

        $bumn = Perusahaan::select('id','nama_lengkap')->where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get();
        $pumk = PumkAnggaran::select('pumk_anggarans.*','periode_laporans.nama AS periode')
                ->leftjoin('periode_laporans','periode_laporans.id','pumk_anggarans.periode_id')
                ->where('periode_laporans.nama','RKA')
                ->get();

        if($pumk->isEmpty()){
            foreach($bumn as $k=>$b){
                        try{
                                $data = PumkAnggaran::create([
                                    'tahun' => date('Y'),
                                    'bumn_id' => $b->id,
                                    'periode_id' => DB::table('periode_laporans')->where('nama','RKA')->pluck('id')->first(),
                                    'status_id' => DB::table('statuses')->where('nama','Unfilled')->pluck('id')->first()
                                ]);
    
                             if($data){
                                 DB::commit();
                                 $result = [
                                 'flag'  => 'success',
                                 'msg' => 'Sukses tambah data',
                                 'title' => 'Sukses'
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
            }                              
        }else{

            $compare = ($bumn->count()) == ($pumk->count())? true : false;

            if(!$compare){
                foreach($bumn as $k=>$b){
                    foreach($pumk as $key=>$p){
                        if($b->id !== $p->bumn_id){
                            try{
                                    $data = PumkAnggaran::create([
                                        'tahun' => date('Y'),
                                        'bumn_id' => $b->id,
                                        'periode_id' => DB::table('periode_laporans')->where('nama','RKA')->pluck('id')->first(),
                                        'status_id' => DB::table('statuses')->where('nama','Unfilled')->pluck('id')->first()
                                    ]);

                                if($data){
                                     DB::commit();
                                     $result = [
                                     'flag'  => 'success',
                                     'msg' => 'Sukses tambah data',
                                     'title' => 'Sukses'
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
                        }

                    }
                }
            }
            $result = [
                'flag'  => 'warning',
                'msg' => 'Data Sudah Lengkap.',
                'title' => 'Tidak menyinkronkan!'
            ];

        }                              
       
      return response()->json($result);

    }
}
