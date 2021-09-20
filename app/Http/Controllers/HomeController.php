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
        $perusahaan_id = ($request->perusahaan_id?$request->perusahaan_id:1);
        $tahun = ($request->tahun?$request->tahun:date('Y'));
        $admin_bumn = false;

        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
            }
        }

        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => '',
            'perusahaan_id' => $perusahaan_id,
            'tahun' => $tahun,
            'admin_bumn' => $admin_bumn,
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
        ]);
    }
    
    public function chartrealisasi(Request $request)
    {
        try{
            $json = [];
            $json['pilar1'] = rand(0,100);
            $json['pilar2'] = rand(0,100);
            $json['pilar3'] = rand(0,100);
            $json['pilar4'] = rand(0,100);

            return response()->json($json);
        }catch(\Exception $e){
            $json = [];
            return response()->json($json);
        }
    }
}
