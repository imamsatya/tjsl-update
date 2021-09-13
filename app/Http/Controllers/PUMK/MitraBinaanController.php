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
use App\Models\PumkAnggaran;
use App\Models\LogPumkAnggaran;
use App\Exports\AnggaranPumkExport;

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
                            '<button type="button" class="btn btn-sm btn-success btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Edit data"><i class="bi bi-pencil fs-3"></i></button>

                            <button type="button" class="btn btn-sm btn-info btn-icon cls-button-show" data-id="'.$id.'"  data-toggle="tooltip" title="Lihat detail"><i class="bi bi-eye fs-3"></i></button>
                            
                            <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete-mitra" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>
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
}
