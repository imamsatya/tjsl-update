<?php

namespace App\Http\Controllers\LaporanRealisasi\Triwulan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\LaporanManajemen;
use App\Models\LogLaporanManajemen;
use DB;
use Session;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class LaporanManajemenTriwulanController extends Controller
{

    public function __construct()
    {

        $this->__route = 'laporan_realisasi.triwulan.laporan_manajemen';
        $this->pagetitle = 'Laporan Manajemen - Triwulan';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
          //
        //   dd($request->perusahaan_id);
          $id_users = \Auth::user()->id;
          $users = User::where('id', $id_users)->first();
        //   $perusahaan_id = (int)$request->perusahaan_id;
          $perusahaan_id = $request->perusahaan_id ? (int)(Crypt::decryptString($request->perusahaan_id)) : null ;
  
          $admin_bumn = false;
          $view_only = false;
          if (!empty($users->getRoleNames())) {
              foreach ($users->getRoleNames() as $v) {
                  if ($v == 'Admin BUMN' || $v == 'Verifikator BUMN') {
                      $admin_bumn = true;
                      $perusahaan_id = \Auth::user()->id_bumn;
                  }
                  if ($v == 'Admin Stakeholder') {
                      $view_only = true;
                  }
              }
          }
          $status = DB::table('statuses')->get();
          $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->where('jenis_periode', 'standar')->where('is_visible', 'true')->orderBy('urutan')->get();
          //cek laporan
          $all_perusahaan_id =Perusahaan::where('is_active', true)->pluck('id');
          $currentYear = Carbon::now()->year;
         
            if ($perusahaan_id) {
                for ($year = 2020; $year <= $currentYear; $year++) {
                    //code untuk cek
                    // $cek_laporan_rka = DB::table('laporan_manajemens')->where('tahun', 2023)->where('perusahaan_id', 60)->where('periode_laporan_id', $periode_rka_id)->first();
                    // dd($cek_laporan_rka);
                    
                    foreach ($periode as $key => $periode_row) {
                     
                        $cek_laporan = DB::table('laporan_manajemens')->where('tahun', $year)->where('perusahaan_id', $perusahaan_id)->where('periode_laporan_id',  $periode_row->id)->first();
                       
                        if(!$cek_laporan){
                          
                            $latest_id = LaporanManajemen::max('id');
                            $laporan_manajemen_new = new LaporanManajemen();
                            $laporan_manajemen_new->id = $latest_id + 1;
                            $laporan_manajemen_new->perusahaan_id = $perusahaan_id;
                            $laporan_manajemen_new->periode_laporan_id = $periode_row->id;
                            $laporan_manajemen_new->status_id = 3; //unfilled
                            $laporan_manajemen_new->tahun = $year;
                            $laporan_manajemen_new->save();
                           
                        }
                    }
                    
                }
            }
            
          


        //   dd($laporan_manajemen);
          return view($this->__route . '.index', [
              'pagetitle' => $this->pagetitle,
              'breadcrumb' => 'Rencana Kerja - Tanda Bukti Lapor Elektronik - RKA',
              // 'tahun' => ($request->tahun ? $request->tahun : date('Y')),
              'tahun' => ($request->tahun ?? Carbon::now()->year),
              'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
              'admin_bumn' => $admin_bumn,
              'perusahaan_id' => $perusahaan_id ?? 1,
              'status' => $status,
              'status_id' => $request->status_laporan ?? '',
              'periode'=>$periode,
              'periode_id' => $request->periode_laporan ?? '',
          ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $perusahaan = Perusahaan::where('id', $request->perusahaan_id)->first();
        return view($this->__route.'.form',[
            'pagetitle' => $this->pagetitle,
            'actionform' => $request->actionform,
            'perusahaan_id' => $request->perusahaan_id,
            'tahun' => $request->tahun,
            'perusahaan' => $perusahaan,
            'laporan_id' => $request->laporan_id,
            'periode' => $request->periode
        
            // 'data' => $kode_indikator,
            // 'hastpb' => null,
            // 'tpb' => Tpb::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
       try {
            $perusahaan = Perusahaan::findOrFail($request->perusahaan_id);
            $periode_id = DB::table('periode_laporans')->where('nama', $request->periode)->first()->id;
            $validated = $request->validate([
                'file' => 'required|mimes:pdf|max:15360',
            ]);
            if (!$validated) {
                return redirect()->back()->withErrors($validated)->withInput();
            }
            if ($validated) {
                $file = $request->file('file');
                $filename = 'Laporan Manajemen '.$request->periode.' '.$perusahaan->nama_lengkap.' '.$request->tahun.'.'.$file->getClientOriginalExtension();
                $upload_path = 'laporan_manajemen/'.strtolower(str_replace(' ', '', $request->periode));
                $path = Storage::disk('public')->putFileAs($upload_path, $file, $filename);
                
                // dd('tes');
                // If you want to save the path to the file in the database, you can do it like this:
                $latest_id = LaporanManajemen::max('id');
                $laporan_manajemen_new = new LaporanManajemen();
                // $laporan_manajemen_new = $laporan_manajemen_new->where('perusahaan_id', $perusahaan->id)->where('tahun', $request->tahun)->where('periode_laporan_id', $periode_id)->first();
                $laporan_manajemen_new = $laporan_manajemen_new->where('id', $request->laporan_id)->first();
                $laporan_manajemen_new->perusahaan_id = $perusahaan->id;
                $laporan_manajemen_new->periode_laporan_id = $periode_id;
                $laporan_manajemen_new->status_id = 2; //in progress
                $laporan_manajemen_new->tahun = $request->tahun;
                $laporan_manajemen_new->file_name = $path;
                $laporan_manajemen_new->user_id = \Auth::user()->id;
                $laporan_manajemen_new->save();
                
                //save log
                $log = new LogLaporanManajemen();
                $log->laporan_manajemen_id = $laporan_manajemen_new->id;
                $log->status_id = 2;//in progress
                $log->user_id = \Auth::user()->id;
                $log->save();

                Session::flash('success', "Berhasil menyimpan File");
                $result = [
                    'flag'  => 'success',
                    'msg' => 'Sukses tambah data',
                    'title' => 'Sukses'
                ];
            
            }
        } catch (\Exception $e) {
        //throw $th;
        $result = [
            'flag'  => 'warning',
            'msg' => $e->getMessage(),
            'title' => 'Gagal'
        ];
       }
       
       return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function datatable(Request $request)
    {
        // dd($request);
        $periode = DB::table('periode_laporans')->whereNotIn('nama', ['RKA'])->get();
        if(Auth::user()->getRoleNames()->contains('Super Admin') || Auth::user()->getRoleNames()->contains('Admin TJSL')){
            $laporan_manajemen = DB::table('laporan_manajemens')
            ->selectRaw('laporan_manajemens.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap, periode_laporans.nama as periode_laporan_nama,
              TRUE AS isoktoinput')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'laporan_manajemens.perusahaan_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', '=', 'laporan_manajemens.periode_laporan_id')
            ->whereIn('periode_laporan_id', $periode->pluck('id')->toArray())
            ->where('perusahaan_masters.is_active', true);
        }
        else{
            $laporan_manajemen = DB::table('laporan_manajemens')
            ->selectRaw('laporan_manajemens.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap, periode_laporans.nama as periode_laporan_nama,
               CASE
                  WHEN CURRENT_DATE BETWEEN periode_laporans.tanggal_awal AND periode_laporans.tanggal_akhir
                  OR periode_laporans.is_active = FALSE
                  THEN TRUE
               ELSE FALSE
               END AS isoktoinput')
            ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'laporan_manajemens.perusahaan_id')
            ->leftJoin('periode_laporans', 'periode_laporans.id', '=', 'laporan_manajemens.periode_laporan_id')
            ->whereIn('periode_laporan_id', $periode->pluck('id')->toArray())
            ->where('perusahaan_masters.is_active', true);
        }
       
        $perusahaan_id = $request->perusahaan_id ?? 1; //default id 1
          if ($perusahaan_id) {

            $laporan_manajemen = $laporan_manajemen->where('perusahaan_id', $perusahaan_id);
        }


        if ($request->tahun) {

            $laporan_manajemen = $laporan_manajemen->where('tahun', $request->tahun);
        }

        if ($request->status_laporan) {

            $laporan_manajemen = $laporan_manajemen->where('status_id', $request->status_laporan);
        }
        if ($request->periode_laporan) {
         
            $laporan_manajemen = $laporan_manajemen->where('periode_laporan_id', $request->periode_laporan);
        }
        $laporan_manajemen = $laporan_manajemen->get();
        // $all_perusahaan_id =$laporan_manajemen->pluck('perusahaan_id');
        // dd();
        try {
            return datatables()->of($laporan_manajemen)
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    // $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';
                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data '  . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="' . $id . '" data-nama="' . $row->nama . '" data-toggle="tooltip" title="Hapus data ' . $row->nama . '"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['id',  'tahun', 'nama_lengkap',  'status_id', 'action'])
                ->toJson();
        } catch (Exception $e) {
            return response([
                'draw'            => 0,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }
    }

    public function log_status(Request $request)
    {

        $log = LogLaporanManajemen::select('log_laporan_manajemens.*', 'users.name AS user', 'statuses.nama AS status')
            ->leftjoin('users', 'users.id', '=', 'log_laporan_manajemens.user_id')
            ->leftjoin('statuses', 'statuses.id', '=', 'log_laporan_manajemens.status_id')
            ->where('laporan_manajemen_id', (int)$request->input('id'))
            ->orderBy('created_at')
            ->get();

        return view($this->__route . '.log_status', [
            'pagetitle' => 'Log Status',
            'log' => $log
        ]);
    }

    public function verifikasiData(Request $request) {
            
    
        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = LaporanManajemen::where('id', $selectedData)->first();
                if ($current->status_id == 2) {
                    $current->status_id = 1;
                    $current->save();

                    $log = new LogLaporanManajemen();
                    $log->laporan_manajemen_id = $current->id;
                    $log->status_id = 1;//completed
                    $log->user_id = \Auth::user()->id;
                    $log->save();    
                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function batalVerifikasiData(Request $request) {
        // dd($request->selectedData);

        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = LaporanManajemen::where('id', $selectedData)->first();
                if ($current->status_id == 1) {
                    $current->status_id = 2;
                    $current->save();

                    $log = new LogLaporanManajemen();
                    $log->laporan_manajemen_id = $current->id;
                    $log->status_id = 2;//in progress
                    $log->user_id = \Auth::user()->id;
                    $log->save();    

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses membatalkan verifikasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function finalVerifikasiData(Request $request) {
        // dd($request->selectedData);

        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = LaporanManajemen::where('id', $selectedData)->first();
                if ($current->status_id == 1) {
                    $current->status_id = 4;
                    $current->save();

                    $log = new LogLaporanManajemen();
                    $log->laporan_manajemen_id = $current->id;
                    $log->status_id =  $current->status_id;//verified
                    $log->user_id = \Auth::user()->id;
                    $log->save();    

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses memvalidasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function batalFinalVerifikasiData(Request $request) {
        // dd($request->selectedData);

        DB::beginTransaction();
        try {
            foreach ($request->selectedData as $selectedData) {
                $current = LaporanManajemen::where('id', $selectedData)->first();
                if ($current->status_id == 4) {
                    $current->status_id = 2;
                    $current->save();

                    $log = new LogLaporanManajemen();
                    $log->laporan_manajemen_id = $current->id;
                    $log->status_id =  $current->status_id;//verified
                    $log->user_id = \Auth::user()->id;
                    $log->save();    

                }
            }
           
                               
            
            DB::commit();

            $result = [
                'flag' => 'success',
                'msg' => 'Sukses membatalkan validasi data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    

}
