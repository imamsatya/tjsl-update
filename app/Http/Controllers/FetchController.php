<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\RelasiPilarTpb;
use App\Models\Tpb;
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
}
