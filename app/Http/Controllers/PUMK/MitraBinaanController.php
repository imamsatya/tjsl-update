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

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\SektorUsaha;
use App\Models\CaraPenyaluran;
use App\Models\SkalaUsaha;
use App\Models\KolekbilitasPendanaan;
use App\Models\KondisiPinjaman;
use App\Models\JenisPembayaran;
use App\Models\BankAccount;
use App\Models\PumkMitraBinaan;

use App\Models\PeriodeLaporan;
use App\Models\Status;
use App\Models\Bulan;
use App\Models\PumkAnggaran;
use App\Models\LogPumkAnggaran;
use App\Exports\MitraBinaanExport;

class MitraBinaanController extends Controller
{
    public function __construct()
    {
        $this->__route = 'pumk.data_mitra';
        $this->pagetitle = 'Mitra Binaan PUMK';
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
            'breadcrumb' => '',
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'provinsi' => Provinsi::where('is_luar_negeri',false)->get(),
            'kota' => Kota::where('is_luar_negeri',false)->get(),
            'sektor_usaha' => SektorUsaha::get(),
            'cara_penyaluran' => CaraPenyaluran::get(),
            'skala_usaha' => SkalaUsaha::get(),
            'kolektibilitas_pendanaan' => KolekbilitasPendanaan::get(),
            'kondisi_pinjaman' => KondisiPinjaman::get(),
            'jenis_pembayaran' => JenisPembayaran::get(),
            'bank_account' => BankAccount::get(),
            'admin_bumn' => $admin_bumn,
            'admin_tjsl' => $admin_tjsl,
            'super_admin' => $super_admin,
            'filter_bumn_id' => $perusahaan_id,
        ]);
    }

    public function datatable(Request $request)
    {
        try{
            $data = PumkMitraBinaan::select('pumk_mitra_binaans.*','provinsis.nama AS provinsi','kotas.nama AS kota','sektor_usaha.nama AS sektor_usaha','kolekbilitas_pendanaan.nama AS kolektibilitas')
                    ->leftjoin('provinsis','provinsis.id','=','pumk_mitra_binaans.provinsi_id')
                    ->leftjoin('kotas','kotas.id','=','pumk_mitra_binaans.kota_id')
                    ->leftjoin('sektor_usaha','sektor_usaha.id','=','pumk_mitra_binaans.sektor_usaha_id')
                    ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','=','pumk_mitra_binaans.kolektibilitas_id');

            if($request->perusahaan_id){
                $data = $data->where('pumk_mitra_binaans.perusahaan_id',$request->perusahaan_id);
            }

            if($request->provinsi_id){
                $data = $data->where('pumk_mitra_binaans.provinsi_id',$request->provinsi_id);
            }

            if($request->kota_id){
                $data = $data->where('pumk_mitra_binaans.kota_id',$request->kota_id);
            }

            if($request->sektor_usaha_id){
                $data = $data->where('pumk_mitra_binaans.sektor_usaha_id',$request->sektor_usaha_id);
            }

            if($request->cara_penyaluran_id){
                $data = $data->where('pumk_mitra_binaans.cara_penyaluran_id',$request->cara_penyaluran_id);
            }

            if($request->skala_usaha_id){
                $data = $data->where('pumk_mitra_binaans.skala_usaha_id',$request->skala_usaha_id);
            }

            if($request->kolektibilitas_id){
                $data = $data->where('pumk_mitra_binaans.kolektibilitas_id',$request->kolektibilitas_id);
            }

            if($request->kondisi_pinjaman_id){
                $data = $data->where('pumk_mitra_binaans.kondisi_pinjaman_id',$request->kondisi_pinjaman_id);
            }

            if($request->bank_account_id){
                $data = $data->where('pumk_mitra_binaans.bank_account_id',$request->bank_account_id);
            }

            if($request->jenis_pembayaran_id){
                $data = $data->where('pumk_mitra_binaans.jenis_pembayaran_id',$request->jenis_pembayaran_id);
            }

            if($request->identitas){
                $data = $data->where('pumk_mitra_binaans.no_identitas',$request->identitas);
            }

            return datatables()->of($data->get())
            ->editColumn('nominal_pendanaan', function ($row){
                $nominal = 0;
                if($row->nominal_pendanaan){
                    $nominal = number_format($row->nominal_pendanaan,0,',',',');
                }else{
                    $nominal;
                }
                return $nominal;
            })
            ->editColumn('saldo_pokok_pendanaan', function ($row){
                $saldo = 0;
                if($row->saldo_pokok_pendanaan){
                    $saldo = number_format($row->saldo_pokok_pendanaan,0,',',',');
                }else{
                    $saldo;
                }
                return $saldo;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = 
                            '<div style="width:120px;text-align:center;"><span><button type="button" class="btn btn-sm btn-success btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Edit data"><i class="bi bi-pencil fs-3"></i></button>&nbsp;
                            <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show-mitra" data-id="'.$id.'"  data-toggle="tooltip" title="Lihat detail"><i class="bi bi-info fs-3"></i></button>&nbsp;
                            <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete-mitra" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button></span><div>
                            ';
                return $button;
            })
            ->rawColumns(['action'])
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

    public function delete(Request $request)
    {
       DB::beginTransaction();
       try{
            $data = PumkMitraBinaan::find((int)$request->input('id'));
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

            $data = PumkMitraBinaan::find($request->id);
            $data->tgl_penerimaan_terakhir = date('Y-m-d',strtotime($data->tgl_penerimaan_terakhir));

                return view($this->__route.'.edit',[
                    'pagetitle' => $this->pagetitle,
                    'actionform' => 'update',
                    'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
                    'provinsi' => Provinsi::where('is_luar_negeri',false)->get(),
                    'kota' => Kota::where('is_luar_negeri',false)->get(),
                    'sektor_usaha' => SektorUsaha::get(),
                    'cara_penyaluran' => CaraPenyaluran::get(),
                    'skala_usaha' => SkalaUsaha::get(),
                    'kolektibilitas_pendanaan' => KolekbilitasPendanaan::get(),
                    'kondisi_pinjaman' => KondisiPinjaman::get(),
                    'jenis_pembayaran' => JenisPembayaran::get(),
                    'bank_account' => BankAccount::get(),
                    'admin_bumn' => $admin_bumn,
                    'perusahaan_id' => $perusahaan_id,
                    'data' => $data
                ]);
       }catch(Exception $e){}

    }

    public function show(Request $request)
    {
        
       try{
            $select = ['bulans.nama AS bulan_text','pumk_mitra_binaans.*','perusahaans.nama_lengkap AS perusahaan_text','provinsis.nama AS prov_text','kotas.nama AS kota_text','sektor_usaha.nama AS sektor_usaha_text','cara_penyalurans.nama AS cara_penyaluran_text','skala_usahas.name AS skala_usaha_text','kolekbilitas_pendanaan.nama AS kolektibilitas_text','kondisi_pinjaman.nama AS kondisi_pinjaman_text','jenis_pembayaran.nama AS jenis_pembayaran_text','bank_account.nama AS bank_account_text','users.name AS user_create_text'];

            $data = PumkMitraBinaan::select($select)
                    ->leftjoin('perusahaans','perusahaans.id','=','pumk_mitra_binaans.perusahaan_id')
                    ->leftjoin('provinsis','provinsis.id','=','pumk_mitra_binaans.provinsi_id')
                    ->leftjoin('kotas','kotas.id','=','pumk_mitra_binaans.kota_id')
                    ->leftjoin('bulans','bulans.id','=','pumk_mitra_binaans.bulan')
                    ->leftjoin('sektor_usaha','sektor_usaha.id','=','pumk_mitra_binaans.sektor_usaha_id')
                    ->leftjoin('cara_penyalurans','cara_penyalurans.id','=','pumk_mitra_binaans.cara_penyaluran_id')
                    ->leftjoin('skala_usahas','skala_usahas.id','=','pumk_mitra_binaans.skala_usaha_id')
                    ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','=','pumk_mitra_binaans.kolektibilitas_id')
                    ->leftjoin('kondisi_pinjaman','kondisi_pinjaman.id','=','pumk_mitra_binaans.kondisi_pinjaman_id')
                    ->leftjoin('jenis_pembayaran','jenis_pembayaran.id','=','pumk_mitra_binaans.jenis_pembayaran_id')
                    ->leftjoin('bank_account','bank_account.id','=','pumk_mitra_binaans.bank_account_id')
                    ->leftjoin('users','users.id','=','pumk_mitra_binaans.created_by_id')
                    ->where('pumk_mitra_binaans.id',(int)$request->id)
                    ->first();
           
            return view($this->__route.'.show',[
                    'pagetitle' => $this->pagetitle,
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
      DB::beginTransaction();
      try{
           $param = $request->all();
           $param = $request->except(['actionform','_token']);
           $param['nilai_aset'] = (int)preg_replace('/[^0-9]/','',$request->nilai_aset);
           $param['nilai_omset'] = (int)preg_replace('/[^0-9]/','',$request->nilai_omset);
           $param['saldo_pokok_pendanaan'] = (int)preg_replace('/[^0-9]/','',$request->saldo_pokok_pendanaan);
           $param['saldo_jasa_adm_pendanaan'] =(int) preg_replace('/[^0-9]/','',$request->saldo_jasa_adm_pendanaan);
           $param['penerimaan_pokok_bulan_berjalan'] = (int)preg_replace('/[^0-9]/','',$request->penerimaan_pokok_bulan_berjalan);
           $param['penerimaan_jasa_bulan_berjalan'] = (int)preg_replace('/[^0-9]/','',$request->penerimaan_jasa_bulan_berjalan);
           $param['kelebihan_angsuran'] = (int)preg_replace('/[^0-9]/','',$request->kelebihan_angsuran);
           $data = PumkMitraBinaan::find((int)$param['id']);
           $data->update($param);

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

    public function export(Request $request)
    {
        
        $data = PumkMitraBinaan::select('pumk_mitra_binaans.*','provinsis.nama AS provinsi','kotas.nama AS kota','sektor_usaha.nama AS sektor_usaha','kolekbilitas_pendanaan.nama AS kolektibilitas',
        'cara_penyalurans.nama AS cara_penyaluran','skala_usahas.name AS skala_usaha','kondisi_pinjaman.nama AS kondisi_pinjaman','jenis_pembayaran.nama AS jenis_pembayaran','bank_account.nama AS bank','perusahaans.nama_lengkap AS bumn')
        ->leftjoin('provinsis','provinsis.id','=','pumk_mitra_binaans.provinsi_id')
        ->leftjoin('kotas','kotas.id','=','pumk_mitra_binaans.kota_id')
        ->leftjoin('cara_penyalurans','cara_penyalurans.id','=','pumk_mitra_binaans.cara_penyaluran_id')
        ->leftjoin('skala_usahas','skala_usahas.id','=','pumk_mitra_binaans.skala_usaha_id')
        ->leftjoin('kondisi_pinjaman','kondisi_pinjaman.id','=','pumk_mitra_binaans.kondisi_pinjaman_id')
        ->leftjoin('jenis_pembayaran','jenis_pembayaran.id','=','pumk_mitra_binaans.jenis_pembayaran_id')
        ->leftjoin('bank_account','bank_account.id','=','pumk_mitra_binaans.bank_account_id')
        ->leftjoin('sektor_usaha','sektor_usaha.id','=','pumk_mitra_binaans.sektor_usaha_id')
        ->leftjoin('perusahaans','perusahaans.id','=','pumk_mitra_binaans.perusahaan_id')
        ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','=','pumk_mitra_binaans.kolektibilitas_id');
       
        
        if($request->perusahaan_id){
            $data = $data->where('pumk_mitra_binaans.perusahaan_id',$request->perusahaan_id);
        }

        if($request->provinsi_id){
            $data = $data->where('pumk_mitra_binaans.provinsi_id',$request->provinsi_id);
        }

        if($request->kota_id){
            $data = $data->where('pumk_mitra_binaans.kota_id',$request->kota_id);
        }

        if($request->sektor_usaha_id){
            $data = $data->where('pumk_mitra_binaans.sektor_usaha_id',$request->sektor_usaha_id);
        }

        if($request->cara_penyaluran_id){
            $data = $data->where('pumk_mitra_binaans.cara_penyaluran_id',$request->cara_penyaluran_id);
        }

        if($request->skala_usaha_id){
            $data = $data->where('pumk_mitra_binaans.skala_usaha_id',$request->skala_usaha_id);
        }

        if($request->kolektibilitas_id){
            $data = $data->where('pumk_mitra_binaans.kolektibilitas_id',$request->kolektibilitas_id);
        }

        if($request->kondisi_pinjaman_id){
            $data = $data->where('pumk_mitra_binaans.kondisi_pinjaman_id',$request->kondisi_pinjaman_id);
        }

        if($request->bank_account_id){
            $data = $data->where('pumk_mitra_binaans.bank_account_id',$request->bank_account_id);
        }

        if($request->jenis_pembayaran_id){
            $data = $data->where('pumk_mitra_binaans.jenis_pembayaran_id',$request->jenis_pembayaran_id);
        }

        if($request->identitas){
            $data = $data->where('pumk_mitra_binaans.no_identitas',$request->identitas);
        }
     
        $mitra = $data->get();

        $namaFile = "Data Mitra Binaan ".date('dmY').".xlsx";
        return Excel::download(new MitraBinaanExport($mitra), $namaFile);
    }
}
