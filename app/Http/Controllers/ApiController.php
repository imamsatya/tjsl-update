<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Perusahaan;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Tpb;
use App\Models\TargetTpb;
use App\Models\PilarPembangunan;
use App\Models\KodeTujuanTpb;
use App\Models\KodeIndikator;
use App\Models\SatuanUkur;
use App\Models\CoreSubject;
use App\Models\User;
use App\Models\CaraPenyaluran; // pelaksanaan program
use App\Models\ApiWhitelist;

class ApiController extends Controller
{
    public function getprovinsi(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                'id','nama AS provinsi','tgl_sinkronisasi'
            ];
    
            $result = Provinsi::select($select)->where('is_luar_negeri','f')->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getkota(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                'kotas.id AS kota_id','kotas.nama AS kota', 'kotas.provinsi_id','provinsis.nama AS provinsi','kotas.tgl_sinkronisasi'
            ];
    
            $result = Kota::select($select)
                    ->leftjoin('provinsis','provinsis.id','=','kotas.provinsi_id')
                    ->where('kotas.is_luar_negeri','f')->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getkota_filter(Request $request, $id_provinsi)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                'kotas.id AS kota_id','kotas.nama AS kota', 'kotas.provinsi_id','provinsis.nama AS provinsi','kotas.tgl_sinkronisasi'
            ];
    
            $result = Kota::select($select)
                    ->leftjoin('provinsis','provinsis.id','=','kotas.provinsi_id')
                    ->where('kotas.is_luar_negeri','f')
                    ->where('provinsis.id',$id_provinsi)
                    ->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }    

    public function getreferensibumnaktif(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                '*'
            ];
    
            $result = Perusahaan::select($select)
                    ->where('is_active','t')->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }    

    public function getreferensitpb(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "no_tpb",
                "nama",
                "keterangan"
            ];
    
            $result = Tpb::select($select)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getreferensipilar(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "nama",
                "keterangan"
            ];
    
            $result = PilarPembangunan::select($select)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getreferensikodetujuantpb(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "kode",
                "keterangan"
            ];
    
            $result = KodeTujuanTpb::select($select)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }


    public function getreferensikodeindikator(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "tpb_id",
                "kode_tujuan_tpb",
                "kode AS kode_indikator",
                "keterangan_tujuan_tpb",
                "keterangan AS keterangan_kode_indikator"
            ];
    
            $result = KodeIndikator::select($select)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getreferensikodeindikator_filter(Request $request, $id_tpb)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "tpb_id",
                "kode_tujuan_tpb",
                "kode AS kode_indikator",
                "keterangan_tujuan_tpb",
                "keterangan AS keterangan_kode_indikator"
            ];
    
            $result = KodeIndikator::select($select)->where('tpb_id',$id_tpb)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getreferensipelaksanaanprogram(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "nama",
            ];
    
            $result = CaraPenyaluran::select($select)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getreferensisatuanukur(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "nama AS satuan_ukur",
                "keterangan"
            ];
    
            $result = SatuanUkur::select($select)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getreferensicoresubject(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "id",
                "nama AS core_subject",
                "keterangan"
            ];
    
            $result = CoreSubject::select($select)->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getuserbumn(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "users.*","perusahaan_masters.nama_lengkap AS perusahaan"
            ];
    
            $result = User::select($select)
                    ->leftjoin('perusahaan_masters','perusahaan_masters.id','=','users.id_bumn')
                    ->whereNotNull('id_bumn')
                    ->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getrelasipilartpb(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{   
            $result = DB::select('select "relasi_pilar_tpbs".
            "versi_pilar_id", "versi_pilars".
            "versi", "versi_pilars".
            "keterangan"
            AS keterangan_versi, "pilar_pembangunans".
            "id"
            AS pilar_id, "pilar_pembangunans".
            "nama"
            AS pilar, "relasi_pilar_tpbs".
            "tpb_id", "tpbs".
            "no_tpb", "tpbs".
            "nama"
            AS tpb
            from "relasi_pilar_tpbs"
            left join "pilar_pembangunans"
            on "pilar_pembangunans".
            "id" = "relasi_pilar_tpbs".
            "pilar_pembangunan_id"
            left join "tpbs"
            on "tpbs".
            "id" = "relasi_pilar_tpbs".
            "tpb_id"
            left join "versi_pilars"
            on "versi_pilars".
            "id" = "relasi_pilar_tpbs".
            "versi_pilar_id"
            where "versi_pilars".
            "versi"
            NOTNULL
            group by "pilar_pembangunans".
            "id", "pilar_pembangunans".
            "nama", "relasi_pilar_tpbs".
            "versi_pilar_id", "tpbs".
            "no_tpb", "tpbs".
            "nama", "versi_pilars".
            "versi", "versi_pilars".
            "keterangan", "relasi_pilar_tpbs".
            "tpb_id"
            order by "relasi_pilar_tpbs".
            "versi_pilar_id"
            asc');
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getrelasipilartpb_filter(Request $request, $id_pilar)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{   
            $result = DB::select('select "relasi_pilar_tpbs".
            "versi_pilar_id", "versi_pilars".
            "versi", "versi_pilars".
            "keterangan"
            AS keterangan_versi, "pilar_pembangunans".
            "id"
            AS pilar_id, "pilar_pembangunans".
            "nama"
            AS pilar, "relasi_pilar_tpbs".
            "tpb_id", "tpbs".
            "no_tpb", "tpbs".
            "nama"
            AS tpb
            from "relasi_pilar_tpbs"
            left join "pilar_pembangunans"
            on "pilar_pembangunans".
            "id" = "relasi_pilar_tpbs".
            "pilar_pembangunan_id"
            left join "tpbs"
            on "tpbs".
            "id" = "relasi_pilar_tpbs".
            "tpb_id"
            left join "versi_pilars"
            on "versi_pilars".
            "id" = "relasi_pilar_tpbs".
            "versi_pilar_id"
            where "versi_pilars".
            "versi"
            NOTNULL AND "pilar_pembangunans".
            "id" = '.$id_pilar.' 
            group by "pilar_pembangunans".
            "id", "pilar_pembangunans".
            "nama", "relasi_pilar_tpbs".
            "versi_pilar_id", "tpbs".
            "no_tpb", "tpbs".
            "nama", "versi_pilars".
            "versi", "versi_pilars".
            "keterangan", "relasi_pilar_tpbs".
            "tpb_id"
            order by "relasi_pilar_tpbs".
            "versi_pilar_id"
            asc');
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }

    public function getprogramapproved(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{   
            $result = DB::select('select "anggaran_tpbs"."perusahaan_id",
            "perusahaan_masters"."nama_lengkap" AS "perusahaan_text",
            "anggaran_tpbs"."tahun" AS "tahun_anggaran",
            "anggaran_tpbs"."anggaran" AS "nilai_anggaran",
            "target_tpbs"."jenis_program_id",
            "jenis_program"."nama" AS "jenis_program_text",
            "target_tpbs"."core_subject_id",
            "core_subject"."nama" AS "core_subject_text",
            "target_tpbs"."tpb_id",
            "tpbs"."nama" AS "tpb_text",
            "target_tpbs"."kode_indikator_id",
            "kode_indikators"."kode" AS "kode_indikator_text",
            "kode_indikators"."kode_tujuan_tpb" AS "kode_tujuan_tpb",
            "kode_indikators"."keterangan_tujuan_tpb" AS "keterangan_tujuan_tpb",
            "target_tpbs"."cara_penyaluran_id" AS "pelaksanaan_program_id",
            "cara_penyalurans"."nama" AS "pelaksanaan_program_text",
            "target_tpbs"."program" AS "nama_program",
            "target_tpbs"."jangka_waktu",
            "target_tpbs"."unit_owner",
            "target_tpbs"."anggaran_alokasi" AS "anggaran_alokasi_program",
            "statuses"."nama" AS "status_program"
            
            from "target_tpbs" 
            left join "anggaran_tpbs" on "anggaran_tpbs"."id" = "target_tpbs"."anggaran_tpb_id" 
            left join "perusahaan_masters" on "anggaran_tpbs"."perusahaan_id" = "perusahaan_masters"."id" 
            left join "jenis_program" on "target_tpbs"."jenis_program_id" = "jenis_program"."id" 
            left join "core_subject" on "target_tpbs"."core_subject_id" = "core_subject"."id" 
            left join "tpbs" on "target_tpbs"."tpb_id" = "tpbs"."id" 
            left join "kode_indikators" on "target_tpbs"."kode_indikator_id" = "kode_indikators"."id" 
            left join "cara_penyalurans" on "target_tpbs"."cara_penyaluran_id" = "cara_penyalurans"."id" 
            left join "statuses" on "target_tpbs"."status_id" = "statuses"."id" 
            where "target_tpbs"."status_id" = 1 AND "anggaran_tpbs"."status_id" = 1');
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }    

    public function getprogramowner(Request $request)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                'target_tpbs.id AS program_id',
                'target_tpbs.program',
                'target_tpbs.id_owner AS owner_id',
                'referensi_owners.nama AS owner_name',
                'target_tpbs.unit_owner',
                'target_tpbs.jenis_program_id',
                'jenis_program.nama AS jenis_program_text',
                'target_tpbs.core_subject_id',
                'core_subject.nama AS core_subject_text',
                'target_tpbs.tpb_id',
                'tpbs.no_tpb',
                'tpbs.nama AS tpb_text',
                'target_tpbs.kode_indikator_id',
                'kode_indikators.kode AS kode_indikator',
                'kode_indikators.keterangan AS keterangan_kode_indikator',
                'target_tpbs.cara_penyaluran_id AS pelaksanaan_program_id',
                'cara_penyalurans.nama AS pelaksanaan_program_text',
                'target_tpbs.jangka_waktu',
                'target_tpbs.anggaran_alokasi AS anggaran_alokasi_program',
                'target_tpbs.status_id AS program_status_id',
                'statuses.nama AS program_status_text',
                'target_tpbs.kode_tujuan_tpb_id',
                'kode_indikators.kode_tujuan_tpb',
                'kode_indikators.keterangan_tujuan_tpb',
                'anggaran_tpbs.perusahaan_id',
                'perusahaan_masters.nama_lengkap AS perusahaan_text',
                'anggaran_tpbs.anggaran',
                'anggaran_tpbs.status_id AS anggaran_status_id',
                'anggaran_tpbs.tahun AS anggaran_tahun'
            ];   
            $result = TargetTpb::select($select)
             ->leftjoin('anggaran_tpbs','anggaran_tpbs.id','=','target_tpbs.anggaran_tpb_id')
             ->leftjoin('statuses','statuses.id','=','target_tpbs.status_id')
             ->leftjoin('jenis_program','target_tpbs.jenis_program_id','=','jenis_program.id')
             ->leftjoin('core_subject','target_tpbs.core_subject_id','=','core_subject.id')
             ->leftjoin('tpbs','tpbs.id','=','target_tpbs.tpb_id')
             ->leftjoin('referensi_owners','referensi_owners.id','=','target_tpbs.id_owner')
             ->leftjoin('perusahaan_masters','perusahaan_masters.id','=','anggaran_tpbs.perusahaan_id')
             ->leftjoin('cara_penyalurans','cara_penyalurans.id','=','target_tpbs.cara_penyaluran_id')
             ->leftjoin('kode_indikators','kode_indikators.id','=','target_tpbs.kode_indikator_id')
             ->whereNotNull('anggaran_tpbs.anggaran')
             ->where('anggaran_tpbs.status_id',1) //status anggaran sudah approved
             ->where('target_tpbs.id_owner',1) // hanya program owner TJSL 
            ->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }        

    public function getprogramowner_filter(Request $request, $id_bumn)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                'target_tpbs.id AS program_id',
                'target_tpbs.program',
                'target_tpbs.id_owner AS owner_id',
                'referensi_owners.nama AS owner_name',
                'target_tpbs.unit_owner',
                'target_tpbs.jenis_program_id',
                'jenis_program.nama AS jenis_program_text',
                'target_tpbs.core_subject_id',
                'core_subject.nama AS core_subject_text',
                'target_tpbs.tpb_id',
                'tpbs.no_tpb',
                'tpbs.nama AS tpb_text',
                'target_tpbs.kode_indikator_id',
                'kode_indikators.kode AS kode_indikator',
                'kode_indikators.keterangan AS keterangan_kode_indikator',
                'target_tpbs.cara_penyaluran_id AS pelaksanaan_program_id',
                'cara_penyalurans.nama AS pelaksanaan_program_text',
                'target_tpbs.jangka_waktu',
                'target_tpbs.anggaran_alokasi AS anggaran_alokasi_program',
                'target_tpbs.status_id AS program_status_id',
                'statuses.nama AS program_status_text',
                'target_tpbs.kode_tujuan_tpb_id',
                'kode_indikators.kode_tujuan_tpb',
                'kode_indikators.keterangan_tujuan_tpb',
                'anggaran_tpbs.perusahaan_id',
                'perusahaan_masters.nama_lengkap AS perusahaan_text',
                'anggaran_tpbs.anggaran',
                'anggaran_tpbs.status_id AS anggaran_status_id',
                'anggaran_tpbs.tahun AS anggaran_tahun'
            ];   
            $result = TargetTpb::select($select)
             ->leftjoin('anggaran_tpbs','anggaran_tpbs.id','=','target_tpbs.anggaran_tpb_id')
             ->leftjoin('statuses','statuses.id','=','target_tpbs.status_id')
             ->leftjoin('jenis_program','target_tpbs.jenis_program_id','=','jenis_program.id')
             ->leftjoin('core_subject','target_tpbs.core_subject_id','=','core_subject.id')
             ->leftjoin('tpbs','tpbs.id','=','target_tpbs.tpb_id')
             ->leftjoin('referensi_owners','referensi_owners.id','=','target_tpbs.id_owner')
             ->leftjoin('perusahaan_masters','perusahaan_masters.id','=','anggaran_tpbs.perusahaan_id')
             ->leftjoin('cara_penyalurans','cara_penyalurans.id','=','target_tpbs.cara_penyaluran_id')
             ->leftjoin('kode_indikators','kode_indikators.id','=','target_tpbs.kode_indikator_id')
             ->whereNotNull('anggaran_tpbs.anggaran')
             ->where('anggaran_tpbs.status_id',1) //status anggaran sudah approved
             ->where('anggaran_tpbs.perusahaan_id',$id_bumn) 
             ->where('target_tpbs.id_owner',1) // hanya program owner TJSL 
            ->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }        

    public function getprogramowner_filter_bumn(Request $request, $id_bumn, $tahun)
    {
        $ip = str_replace(' ', '', $request->getClientIp());
        $whitelist = ApiWhitelist::whereIn('ip_user',['*',$ip])->where('status','t')->count();

        if($whitelist == 0){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                'target_tpbs.id AS program_id',
                'target_tpbs.program',
                'target_tpbs.id_owner AS owner_id',
                'referensi_owners.nama AS owner_name',
                'target_tpbs.unit_owner',
                'target_tpbs.jenis_program_id',
                'jenis_program.nama AS jenis_program_text',
                'target_tpbs.core_subject_id',
                'core_subject.nama AS core_subject_text',
                'target_tpbs.tpb_id',
                'tpbs.no_tpb',
                'tpbs.nama AS tpb_text',
                'target_tpbs.kode_indikator_id',
                'kode_indikators.kode AS kode_indikator',
                'kode_indikators.keterangan AS keterangan_kode_indikator',
                'target_tpbs.cara_penyaluran_id AS pelaksanaan_program_id',
                'cara_penyalurans.nama AS pelaksanaan_program_text',
                'target_tpbs.jangka_waktu',
                'target_tpbs.anggaran_alokasi AS anggaran_alokasi_program',
                'target_tpbs.status_id AS program_status_id',
                'statuses.nama AS program_status_text',
                'target_tpbs.kode_tujuan_tpb_id',
                'kode_indikators.kode_tujuan_tpb',
                'kode_indikators.keterangan_tujuan_tpb',
                'anggaran_tpbs.perusahaan_id',
                'perusahaan_masters.nama_lengkap AS perusahaan_text',
                'anggaran_tpbs.anggaran',
                'anggaran_tpbs.status_id AS anggaran_status_id',
                'anggaran_tpbs.tahun AS anggaran_tahun'
            ];   
            $result = TargetTpb::select($select)
             ->leftjoin('anggaran_tpbs','anggaran_tpbs.id','=','target_tpbs.anggaran_tpb_id')
             ->leftjoin('statuses','statuses.id','=','target_tpbs.status_id')
             ->leftjoin('jenis_program','target_tpbs.jenis_program_id','=','jenis_program.id')
             ->leftjoin('core_subject','target_tpbs.core_subject_id','=','core_subject.id')
             ->leftjoin('tpbs','tpbs.id','=','target_tpbs.tpb_id')
             ->leftjoin('referensi_owners','referensi_owners.id','=','target_tpbs.id_owner')
             ->leftjoin('perusahaan_masters','perusahaan_masters.id','=','anggaran_tpbs.perusahaan_id')
             ->leftjoin('cara_penyalurans','cara_penyalurans.id','=','target_tpbs.cara_penyaluran_id')
             ->leftjoin('kode_indikators','kode_indikators.id','=','target_tpbs.kode_indikator_id')
             ->whereNotNull('anggaran_tpbs.anggaran')
             ->where('anggaran_tpbs.status_id',1) //status anggaran sudah approved
             ->where('anggaran_tpbs.perusahaan_id',$id_bumn) 
             ->where('anggaran_tpbs.tahun',$tahun)
             ->where('target_tpbs.id_owner',1) // hanya program owner TJSL 
            ->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }   
}
