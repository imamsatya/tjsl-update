<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Perusahaan;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Tpb;
use App\Models\PilarPembangunan;
use App\Models\KodeTujuanTpb;
use App\Models\KodeIndikator;
use App\Models\SatuanUkur;
use App\Models\CoreSubject;
use App\Models\User;
use App\Models\CaraPenyaluran; // pelaksanaan program


class ApiController extends Controller
{
    public function getprovinsi(Request $request)
    {
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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

    public function getreferensibumnaktif(Request $request)
    {
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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


    public function getreferensipelaksanaanprogram(Request $request)
    {
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
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
        $ip = $request->getClientIp();
        $param = '127.0.0.1';

        if($ip !== $param){
            $result = "Forbidden Access!";
        
            return response()->json(['message' => $result]);            
        }else{
            $select = [
                "users.*","perusahaans.nama_lengkap AS perusahaan"
            ];
    
            $result = User::select($select)
                    ->leftjoin('perusahaans','perusahaans.id','=','users.id_bumn')
                    ->whereNotNull('id_bumn')
                    ->get();
    
            return response()->json(['status' => 1, 'message' => 'OK', 'data' => $result]);
        }
    }


}
