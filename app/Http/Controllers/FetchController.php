<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\RelasiPilarTpb;
use App\Models\Tpb;
use App\Models\PumkAnggaran;
use App\Models\PeriodeLaporan;
use App\Models\Status;
use DB;


class FetchController extends Controller
{
    public function getTpbByPilar(Request $request)
    {
        $pilar_pembangunan_id = $request->id;
        $versi_pilar_id = $request->versi;
        $data = RelasiPilarTpb::select('relasi_pilar_tpbs.id','tpbs.no_tpb', 'tpbs.nama')
                                ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
                                ->where('relasi_pilar_tpbs.pilar_pembangunan_id',$pilar_pembangunan_id)
                                ->where('relasi_pilar_tpbs.versi_pilar_id',$versi_pilar_id)
                                ->get();
        
        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->no_tpb . ' - ' . $item->nama];
        }
        return response()->json($return);
    }

    public function getPumkAnggaranByPeriode(Request $request)
    {
            $RKA_id = PeriodeLaporan::where('nama','RKA')->pluck('id')->first();

            $data = PumkAnggaran::select('saldo_awal','status_id')
            ->where('bumn_id',$request->bumn_id)
            ->where('tahun',$request->tahun)
            ->where('periode_id',$RKA_id)
            ->orderby('id','desc')->first();

            if($data !== null){
                $statusRKA = Status::where('id',$data->status_id)->first();
                if($statusRKA->nama !== 'Finish'){
                    $return = 0;                    
                }else{
                    $return = number_format($data->saldo_awal);
                }
            }else{
                $return = 0;
            }


        return response()->json($return);
    }
}
