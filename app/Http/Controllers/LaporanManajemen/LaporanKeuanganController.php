<?php

namespace App\Http\Controllers\LaporanManajemen;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;

use App\Models\VersiLaporanKeuangan;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\LaporanKeuangan;
use App\Models\LaporanKeuanganNilai;
use App\Models\PeriodeLaporan;
use App\Models\RelasiLaporanKeuangan;
use App\Models\LaporanKeuanganParent;
use App\Models\LaporanKeuanganChild;

class LaporanKeuanganController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'laporan_manajemen.laporan_keuangan';
        $this->pagetitle = 'Laporan Keuangan';
    }

    public function index(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;
        $periode_laporan_id = $request->periode_laporan_id;
        
        $admin_bumn = false;
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
            }
        }

        $periode_laporan = PeriodeLaporan::orderby('urutan','desc')->get();
        if(empty($periode_laporan_id)){
            $periode_first = PeriodeLaporan::orderby('urutan','desc')->first();
            $periode_laporan_id = $periode_first->id;
        }

        $laporan_bumn = LaporanKeuanganNilai::select('perusahaans.nama_lengkap','perusahaans.id as perusahaan_id')
                                ->leftJoin('relasi_laporan_keuangan', 'relasi_laporan_keuangan.id', 'laporan_keuangan_nilais.relasi_laporan_keuangan_id')
                                ->leftJoin('perusahaans', 'perusahaans.id', 'laporan_keuangan_nilais.perusahaan_id')
                                ->where('laporan_keuangan_nilais.periode_laporan_id',$periode_laporan_id)
                                ->GroupBy('perusahaans.id')
                                ->GroupBy('perusahaans.nama_lengkap')
                                ->orderBy('perusahaans.nama_lengkap');

        $laporan_jenis = LaporanKeuanganNilai::select('laporan_keuangans.nama','laporan_keuangans.id as laporan_keuangan_id', 'laporan_keuangan_nilais.perusahaan_id')
                                ->leftJoin('relasi_laporan_keuangan', 'relasi_laporan_keuangan.id', 'laporan_keuangan_nilais.relasi_laporan_keuangan_id')
                                ->leftJoin('laporan_keuangans', 'laporan_keuangans.id', 'relasi_laporan_keuangan.laporan_keuangan_id')
                                ->where('laporan_keuangan_nilais.periode_laporan_id',$periode_laporan_id)
                                ->GroupBy('laporan_keuangan_nilais.perusahaan_id')
                                ->GroupBy('laporan_keuangans.id')
                                ->GroupBy('laporan_keuangans.nama')
                                ->orderBy('laporan_keuangans.nama');

        $laporan_parent = LaporanKeuanganNilai::select('laporan_keuangan_nilais.id','laporan_keuangan_parent.label','laporan_keuangan_nilais.nilai','relasi_laporan_keuangan.parent_id','laporan_keuangan_nilais.laporan_keuangan_id', 'laporan_keuangan_nilais.perusahaan_id','relasi_laporan_keuangan.id as relasi_laporan_keuangan_id')
                                ->leftJoin('relasi_laporan_keuangan', 'relasi_laporan_keuangan.id', 'laporan_keuangan_nilais.relasi_laporan_keuangan_id')
                                ->leftJoin('laporan_keuangan_parent', 'laporan_keuangan_parent.id', 'relasi_laporan_keuangan.parent_id')
                                ->where('relasi_laporan_keuangan.parent_id','<>',null)
                                ->where('relasi_laporan_keuangan.child_id',null)
                                ->where('laporan_keuangan_nilais.periode_laporan_id',$periode_laporan_id)
                                ->GroupBy('relasi_laporan_keuangan.parent_id')
                                ->GroupBy('relasi_laporan_keuangan.id')
                                ->GroupBy('laporan_keuangan_parent.label')
                                ->GroupBy('laporan_keuangan_parent.kode')
                                ->GroupBy('laporan_keuangan_nilais.id')
                                ->GroupBy('laporan_keuangan_nilais.nilai')
                                ->GroupBy('laporan_keuangan_nilais.laporan_keuangan_id')
                                ->GroupBy('laporan_keuangan_nilais.perusahaan_id')
                                ->orderBy('laporan_keuangan_parent.kode');
                                
        $laporan_child = LaporanKeuanganNilai::select('laporan_keuangan_nilais.id','laporan_keuangan_child.is_pengurangan','laporan_keuangan_child.label','laporan_keuangan_nilais.nilai','relasi_laporan_keuangan.parent_id','relasi_laporan_keuangan.child_id','laporan_keuangan_nilais.laporan_keuangan_id', 'laporan_keuangan_nilais.perusahaan_id','relasi_laporan_keuangan.id as relasi_laporan_keuangan_id')
                                ->leftJoin('relasi_laporan_keuangan', 'relasi_laporan_keuangan.id', 'laporan_keuangan_nilais.relasi_laporan_keuangan_id')
                                ->leftJoin('laporan_keuangan_child', 'laporan_keuangan_child.id', 'relasi_laporan_keuangan.child_id')
                                ->where('relasi_laporan_keuangan.child_id','<>',null)
                                ->where('laporan_keuangan_nilais.periode_laporan_id',$periode_laporan_id)
                                ->GroupBy('relasi_laporan_keuangan.parent_id')
                                ->GroupBy('relasi_laporan_keuangan.child_id')
                                ->GroupBy('relasi_laporan_keuangan.id')
                                ->GroupBy('laporan_keuangan_child.is_pengurangan')
                                ->GroupBy('laporan_keuangan_child.label')
                                ->GroupBy('laporan_keuangan_child.kode')
                                ->GroupBy('laporan_keuangan_nilais.id')
                                ->GroupBy('laporan_keuangan_nilais.nilai')
                                ->GroupBy('laporan_keuangan_nilais.laporan_keuangan_id')
                                ->GroupBy('laporan_keuangan_nilais.perusahaan_id')
                                ->orderBy('laporan_keuangan_child.kode');
                            
        if($perusahaan_id){
            $laporan_bumn = $laporan_bumn->where('laporan_keuangan_nilais.perusahaan_id', $perusahaan_id);
            $laporan_jenis = $laporan_jenis->where('laporan_keuangan_nilais.perusahaan_id', $perusahaan_id);
            $laporan_parent = $laporan_parent->where('laporan_keuangan_nilais.perusahaan_id', $perusahaan_id);
            $laporan_child = $laporan_child->where('laporan_keuangan_nilais.perusahaan_id', $perusahaan_id);
        }   

        if($request->tahun){
            $laporan_bumn = $laporan_bumn->where('laporan_keuangan_nilais.tahun', (int)$request->tahun);
            $laporan_jenis = $laporan_jenis->where('laporan_keuangan_nilais.tahun', (int)$request->tahun);
            $laporan_parent = $laporan_parent->where('laporan_keuangan_nilais.tahun', (int)$request->tahun);
            $laporan_child = $laporan_child->where('laporan_keuangan_nilais.tahun', (int)$request->tahun);
        }

        if($request->periode_laporan_id){
            $laporan_bumn = $laporan_bumn->where('laporan_keuangan_nilais.periode_laporan_id', (int)$request->periode_laporan_id);
            $laporan_jenis = $laporan_jenis->where('laporan_keuangan_nilais.periode_laporan_id', (int)$request->periode_laporan_id);
            $laporan_parent = $laporan_parent->where('laporan_keuangan_nilais.periode_laporan_id', (int)$request->periode_laporan_id);
            $laporan_child = $laporan_child->where('laporan_keuangan_nilais.periode_laporan_id', (int)$request->periode_laporan_id);
        }

        if($request->laporan_keuangan_id){
            $laporan_bumn = $laporan_bumn->where('laporan_keuangan_nilais.laporan_keuangan_id', (int)$request->laporan_keuangan_id);
            $laporan_jenis = $laporan_jenis->where('laporan_keuangan_nilais.laporan_keuangan_id', (int)$request->laporan_keuangan_id);
            $laporan_parent = $laporan_parent->where('laporan_keuangan_nilais.laporan_keuangan_id', (int)$request->laporan_keuangan_id);
            $laporan_child = $laporan_child->where('laporan_keuangan_nilais.laporan_keuangan_id', (int)$request->laporan_keuangan_id);
        }
        
        return view($this->__route.'.index',[
            'pagetitle' => $this->pagetitle,
            'laporan_bumn' => $laporan_bumn->get(),
            'laporan_jenis' => $laporan_jenis->get(),
            'laporan_parent' => $laporan_parent->get(),
            'laporan_child' => $laporan_child->get(),
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'periode_laporan' => $periode_laporan,
            'jenis_laporan' => LaporanKeuangan::get(),
            'jenis_laporan_id' => $request->jenis_laporan_id,
            'periode_laporan_id' => $periode_laporan_id,
            'tahun' => ($request->tahun?$request->tahun:date('Y')),
            'breadcrumb' => 'Laporan Manajemen - Laporan Keuangan'
        ]);
    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(VersiLaporanKeuangan::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" title="Ubah data '.$row->nama.'"><i class="bi bi-pencil fs-3"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','keterangan','action'])
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

    public function create(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $perusahaan_id = $request->perusahaan_id;
        
        $admin_bumn = false;
        if(!empty($users->getRoleNames())){
            foreach ($users->getRoleNames() as $v) {
                if($v == 'Admin BUMN') {
                    $admin_bumn = true;
                    $perusahaan_id = \Auth::user()->id_bumn;
                }
            }
        }

        $versilaporankeuangan = VersiLaporanKeuangan::get();
        $periode_laporan = PeriodeLaporan::orderby('urutan','desc')->get();

        return view($this->__route.'.form',[
            'pagetitle' => 'Input '.$this->pagetitle,
            'breadcrumb' => 'Laporan Manajemen - Laporan Keuangan - Input Data',
            'actionform' => 'insert',
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'periode_laporan' => $periode_laporan,
            'jenis_laporan' => LaporanKeuangan::get(),
            'tahun' => $request->tahun,
            'data' => $versilaporankeuangan
        ]);

    }

    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $param = $request->except('actionform','id','relasi_id','nilai');

        DB::beginTransaction();
        try{
            $relasi_id = $request->relasi_id;
            $nilai = $request->nilai;
            for($i=0;$i<count($relasi_id);$i++){
                $param['nilai'] = str_replace(',', '', $nilai[$i]);
                $param['relasi_laporan_keuangan_id'] = $relasi_id[$i];

                $laporankeuangan = LaporanKeuanganNilai::create((array)$param);
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses tambah data',
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

    public function edit(Request $request)
    {

        try{
            $laporankeuangan = LaporanKeuanganNilai::find((int)$request->input('id'));
            if(@$laporankeuangan->relasi->child_id){
                $label = LaporanKeuanganChild::find($laporankeuangan->relasi->child_id);
            }else{
                $label = LaporanKeuanganChild::find($laporankeuangan->relasi->parent_id);
            }

            return view($this->__route.'.edit',[
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'label' => $label,
                'data' => $laporankeuangan

            ]);
        }catch(Exception $e){}

    }
    
    public function update(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        DB::beginTransaction();
        try{
            $param['nilai'] = str_replace(',', '', $request->input('nilai'));
            $laporankeuangan = LaporanKeuanganNilai::find((int)$request->input('id'));
            $laporankeuangan->update((array)$param);
            
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses tambah data',
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


    protected function validateform($request)
    {
        $required['perusahaan_id'] = 'required';

        $message['perusahaan_id.required'] = 'BUMN wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    public function delete(Request $request)
    {
      
        DB::beginTransaction();
        try{

            $data = RelasiLaporanKeuangan::where('versi_laporan_id',$request->id)->get();
            
            if($data){
                foreach($data as $v){
                    $v->delete();
                }
            }
            $data = VersiLaporanKeuangan::find($request->id);
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

    public function getLaporanKeuangan(Request $request)
    {
        $return['success']   = true;
        $laporankeuangan_first = LaporanKeuanganNilai::where('laporan_keuangan_id',$request->id)->where('perusahaan_id',$request->perusahaan_id)->where('tahun',$request->tahun)->where('periode_laporan_id',$request->periode_laporan_id)->first();
        if($laporankeuangan_first){
            $return['success']   = false;
        }

        $versilaporankeuangan = VersiLaporanKeuangan::orderBy('status')->orderBy('tanggal_akhir', 'desc')->first();

        $parent = RelasiLaporanKeuangan::select('laporan_keuangans.nama','relasi_laporan_keuangan.id as relasi_laporan_keuangan_id','relasi_laporan_keuangan.versi_laporan_id','laporan_keuangans.id AS laporan_id','relasi_laporan_keuangan.parent_id','laporan_keuangan_parent.kode','laporan_keuangan_parent.label')
                                ->leftJoin('laporan_keuangans', 'laporan_keuangans.id', 'relasi_laporan_keuangan.laporan_keuangan_id')
                                ->leftJoin('laporan_keuangan_parent', 'laporan_keuangan_parent.id', 'relasi_laporan_keuangan.parent_id')
                                ->where('relasi_laporan_keuangan.parent_id','<>',null)
                                ->where('relasi_laporan_keuangan.versi_laporan_id', $versilaporankeuangan->id)
                                ->where('laporan_keuangans.id', $request->id)
                                ->distinct('relasi_laporan_keuangan.parent_id')->get();

        $return['parent']   = $parent;
        foreach($parent as $p){
            $child = RelasiLaporanKeuangan::select('laporan_keuangans.nama','relasi_laporan_keuangan.id as relasi_laporan_keuangan_id','relasi_laporan_keuangan.versi_laporan_id','laporan_keuangans.id AS laporan_id','relasi_laporan_keuangan.parent_id','relasi_laporan_keuangan.child_id','laporan_keuangan_child.kode','laporan_keuangan_child.label','laporan_keuangan_child.is_pengurangan')
                                    ->leftJoin('laporan_keuangans', 'laporan_keuangans.id', 'relasi_laporan_keuangan.laporan_keuangan_id')
                                    ->leftJoin('laporan_keuangan_parent', 'laporan_keuangan_parent.id', 'relasi_laporan_keuangan.parent_id')
                                    ->leftJoin('laporan_keuangan_child', 'laporan_keuangan_child.id', 'relasi_laporan_keuangan.child_id')
                                    ->where('relasi_laporan_keuangan.child_id','<>',null)
                                    ->where('relasi_laporan_keuangan.versi_laporan_id', $versilaporankeuangan->id)
                                    ->where('laporan_keuangans.id', $request->id)
                                    ->where('parent_id',$p->parent_id)
                                    ->distinct('relasi_laporan_keuangan.child_id')->get();
            $return['child'][$p->parent_id] = $child;
        }
        return response()->json($return);
    }
}
