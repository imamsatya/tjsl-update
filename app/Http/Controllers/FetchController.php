<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Tpb;
use DB;


class FetchController extends Controller
{
    public function getTpbByPilar(Request $request)
    {
        $id = $request->id;
        $data = Tpb::where('pilar_pembangunan_id', $id)->get();
        
        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->no_tpb . ' - ' . $item->nama];
        }
        return response()->json($return);
    }
}
