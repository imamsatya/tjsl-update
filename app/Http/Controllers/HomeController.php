<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Perusahaan;
use App\Models\User;
use App\Models\Tpb;
use App\Models\Kegiatan;
use App\Models\AnggaranTpb;
use App\Models\KolekbilitasPendanaan;
use App\Models\PumkMitraBinaan;
use App\Models\PeriodeLaporan;
use App\Models\Status;
use App\Models\Bulan;
use App\Models\PilarPembangunan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'home';
        $this->pagetitle = 'Dashboard';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;
        $tahun = ($request->tahun?$request->tahun:date('Y'));
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
            'users' =>$users,
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan_id' => $perusahaan_id,
            'tahun' => $tahun,
            'admin_bumn' => $admin_bumn,
            'tpb' => TPB::all(),
            'tpb_id' => $request->tpb_id,
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),                    
            'filter_bumn_id' => $perusahaan_id,
            'filter_periode_id' => $request->periode_id,
            'filter_status_id' => $request->status_id,
            'filter_tahun' => $request->tahun,
            'bulan' => Bulan::get(),
        ]);
    }

    public function chartmb(Request $request)
    {
        try{
            $json = [];
            $kolek = KolekbilitasPendanaan::select('nama')->pluck('nama');
            $perusahaan = $request->perusahaan_id_pumk;
            $bulan = $request->bulan_pumk? (int)$request->bulan_pumk : ((int)date('m') - 1);
            $tahun = $request->tahun_pumk;

            $id_lancar = KolekbilitasPendanaan::where('nama','ilike','%lancar%')->pluck('id')->first();
            $id_kurang_lancar = KolekbilitasPendanaan::where('nama','ilike','%kurang% %lancar%')->pluck('id')->first();
            $id_diragukan = KolekbilitasPendanaan::where('nama','ilike','%diragukan%')->pluck('id')->first();
            $id_macet = KolekbilitasPendanaan::where('nama','ilike','%macet%')->pluck('id')->first();

            $json['mitra_lancar'] =  PumkMitraBinaan::select('pumk_mitra_binaans.*','kolekbilitas_pendanaan.nama')
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_lancar)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->count();
                         
            $json['saldo_lancar'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total','kolekbilitas_pendanaan.nama'))
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_lancar)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->pluck('total')->first();
    
            $json['mitra_kurang_lancar'] = PumkMitraBinaan::select('pumk_mitra_binaans.*','kolekbilitas_pendanaan.nama')
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_kurang_lancar)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->count();                        
    
           $json['saldo_kurang_lancar'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total','kolekbilitas_pendanaan.nama'))
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_kurang_lancar)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->pluck('total')->first();
    
            $json['mitra_diragukan'] = PumkMitraBinaan::select('pumk_mitra_binaans.*','kolekbilitas_pendanaan.nama')
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_diragukan)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->count();                        
    
            $json['saldo_diragukan'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total','kolekbilitas_pendanaan.nama'))
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_diragukan)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->pluck('total')->first();
    
            $json['mitra_macet'] = PumkMitraBinaan::select('pumk_mitra_binaans.*','kolekbilitas_pendanaan.nama')
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_macet)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->count();                          
    
            $json['saldo_macet'] = PumkMitraBinaan::select(DB::raw('SUM(saldo_pokok_pendanaan) AS total','kolekbilitas_pendanaan.nama'))
                            ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','pumk_mitra_binaans.kolektibilitas_id')
                            ->where('kolekbilitas_pendanaan.id', $id_macet)
                            ->where('pumk_mitra_binaans.is_arsip',false)
                            ->where(function ($query) use ($perusahaan,$bulan,$tahun) {
                                if($perusahaan){
                                    $query->where('pumk_mitra_binaans.perusahaan_id', '=', $perusahaan);
                                }
                                if($bulan){
                                    $query->where('pumk_mitra_binaans.bulan', '=', $bulan);
                                }
                                if($tahun){
                                    $query->where('pumk_mitra_binaans.tahun', '=', $tahun);
                                }
                            })
                            ->pluck('total')->first();
            
            $json['bumn'] = '';
            $json['bulan'] = '';
            $json['tahun'] = '';
            
            if($perusahaan){
                $bumn = Perusahaan::find($perusahaan);
                $json['bumn'] = ' '.$bumn->nama_lengkap;
            }

            if($bulan){
                $bulan = Bulan::find($bulan);
                $json['bulan'] = 'Bulan '.$bulan->nama;
            }

            if($tahun){
                $json['tahun'] = 'Tahun '.$tahun;
            }
            
            return response()->json($json);
        }catch(\Exception $e){
            $json = [];
            return response()->json($json);
        }
    }    

    public function chartrealisasi(Request $request)
    {
        try{
            $pilar[0] = PilarPembangunan::where('nama','Pilar Pembangunan Sosial')->first();
            $pilar[1] = PilarPembangunan::where('nama','Pilar Pembangunan Ekonomi')->first();
            $pilar[2] = PilarPembangunan::where('nama','Pilar Pembangunan Lingkungan')->first();
            $pilar[3] = PilarPembangunan::where('nama','Pilar Pembangunan Hukum dan Tata Kelola')->first();
            
            for($i=0;$i<4;$i++){
                $kegiatan[$i] = Kegiatan::Select(DB::Raw('sum(kegiatan_realisasis.anggaran) as realisasi'))
                                        ->leftJoin('target_tpbs', 'target_tpbs.id', '=', 'kegiatans.target_tpb_id')
                                        ->leftJoin('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                                        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                                        ->leftJoin('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                                        ->where('relasi_pilar_tpbs.pilar_pembangunan_id',$pilar[$i]->id);
                                        
                $anggaran[$i] = AnggaranTpb::Select(DB::Raw('sum(anggaran_tpbs.anggaran) as anggaran'))
                                        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                                        ->where('relasi_pilar_tpbs.pilar_pembangunan_id',$pilar[$i]->id);

                if($request->perusahaan_id && $request->perusahaan_id!='all'){
                    $kegiatan[$i] = $kegiatan[$i]->where('anggaran_tpbs.perusahaan_id',$request->perusahaan_id);
                    $anggaran[$i] = $anggaran[$i]->where('anggaran_tpbs.perusahaan_id',$request->perusahaan_id);
                }
                if($request->tahun && $request->tahun!='all'){
                    $kegiatan[$i] = $kegiatan[$i]->where('anggaran_tpbs.tahun',$request->tahun);
                    $anggaran[$i] = $anggaran[$i]->where('anggaran_tpbs.tahun',$request->tahun);
                }

                $kegiatan[$i] = $kegiatan[$i]->first();
                $anggaran[$i] = $anggaran[$i]->first();

                $arr['realisasi'][$i] = 0;
                $arr['target'][$i] = 0;
                $arr['sisa'][$i] = 0;
                $arr['pilar'][$i] = 0;

                if($anggaran[$i]->anggaran>0){
                    $arr['realisasi'][$i] = number_format($kegiatan[$i]->realisasi,0,'.','.');
                    $arr['target'][$i] = number_format($anggaran[$i]->anggaran,0,'.','.');
                    $arr['sisa'][$i] = number_format(($anggaran[$i]->anggaran-$kegiatan[$i]->realisasi),0,'.','.');
                    $arr['pilar'][$i] = $kegiatan[$i]->realisasi/$anggaran[$i]->anggaran*100;
                }
            }
            
            $json['realisasi1'] = $arr['realisasi'][0];
            $json['realisasi2'] = $arr['realisasi'][1];
            $json['realisasi3'] = $arr['realisasi'][2];
            $json['realisasi4'] = $arr['realisasi'][3];
            $json['target1'] = $arr['target'][0];
            $json['target2'] = $arr['target'][1];
            $json['target3'] = $arr['target'][2];
            $json['target4'] = $arr['target'][3];
            $json['sisa1'] = $arr['sisa'][0];
            $json['sisa2'] = $arr['sisa'][1];
            $json['sisa3'] = $arr['sisa'][2];
            $json['sisa4'] = $arr['sisa'][3];
            $json['pilar1'] = $arr['pilar'][0];
            $json['pilar2'] = $arr['pilar'][1];
            $json['pilar3'] = $arr['pilar'][2];
            $json['pilar4'] = $arr['pilar'][3];

            return response()->json($json);
        }catch(\Exception $e){
            $json = [];
            return response()->json($json);
        }
    }
    
    public function charttpb(Request $request)
    {
        try{
            $kegiatan = Kegiatan::Select(DB::Raw('sum(kegiatan_realisasis.anggaran) as realisasi'))
                                    ->leftJoin('target_tpbs', 'target_tpbs.id', '=', 'kegiatans.target_tpb_id')
                                    ->leftJoin('anggaran_tpbs', 'anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                                    ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
                                    ->leftJoin('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id');
                                    
            $anggaran = AnggaranTpb::Select(DB::Raw('sum(anggaran_tpbs.anggaran) as anggaran'))
                                    ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id');

            if($request->tpb_id && $request->tpb_id!='all'){
                $kegiatan = $kegiatan->where('relasi_pilar_tpbs.tpb_id',$request->tpb_id);
                $anggaran = $anggaran->where('relasi_pilar_tpbs.tpb_id',$request->tpb_id);
            }
            if($request->perusahaan_id && $request->perusahaan_id!='all'){
                $kegiatan = $kegiatan->where('anggaran_tpbs.perusahaan_id',$request->perusahaan_id);
                $anggaran = $anggaran->where('anggaran_tpbs.perusahaan_id',$request->perusahaan_id);
            }
            if($request->tahun && $request->tahun!='all'){
                $kegiatan = $kegiatan->where('anggaran_tpbs.tahun',$request->tahun);
                $anggaran = $anggaran->where('anggaran_tpbs.tahun',$request->tahun);
            }

            $kegiatan = $kegiatan->first();
            $anggaran = $anggaran->first();

            $json['realisasi'] = 0;
            $json['target'] = 0;
            $json['sisa'] = 0;
            $json['tpb'] = 0;

            if($anggaran->anggaran>0){
                $json['realisasi'] = number_format($kegiatan->realisasi,0,'.','.');
                $json['target'] = number_format($anggaran->anggaran,0,'.','.');
                $json['sisa'] = number_format(($anggaran->anggaran-$kegiatan->realisasi),0,'.','.');
                $json['tpb'] = $kegiatan->realisasi/$anggaran->anggaran*100;
            }

            return response()->json($json);
        }catch(\Exception $e){
            $json = [];
            return response()->json($json);
        }
    }
}
