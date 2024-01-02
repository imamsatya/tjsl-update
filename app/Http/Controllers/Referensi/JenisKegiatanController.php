<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;

use App\Models\JenisKegiatan;
use App\Models\SatuanUkur;
use App\Models\SubKegiatan;
use Session;

class JenisKegiatanController extends Controller
{

    public function __construct()
    {
        $this->__route = 'referensi.jenis_kegiatan';
        $this->pagetitle = 'Jenis Kegiatan';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Referensi - Jenis Kegiatan'
        ]);
    }

    public function create_subkegiatan(Request $request)
    {
        
        $main_kegiatan = JenisKegiatan::get();
        if ($request->id) {
            $subkegiatan = SubKegiatan::where('id', $request->id)->first();
        }
      

        return view($this->__route . '.create_subkegiatan', [
            'pagetitle' => $this->pagetitle,
            'actionform' => $request->actionform ?? 'insert',
            'main_kegiatan' => $main_kegiatan,
            'satuan_ukur' => SatuanUkur::where('is_active', true)->get(),
            'subkegiatan' => $request->id ? $subkegiatan : null
            // 'jenis_laporan' => JenisLaporan::get(),
            // 'jenis_laporan' => Menu::where('status', 1)->where(DB::raw('TRIM(route_name)'), '!=', '')->get(),
            // 'data' => $periode_laporan
        ]);
    }

    public function datatable(Request $request)
    {
        $jenis_kegiatan = JenisKegiatan::orderBy('id')->get();
        try {
            return datatables()->of($jenis_kegiatan)
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="' . $id . '" data-nama="' . $row->nama . '" data-toggle="tooltip" title="Hapus data ' . $row->nama . '"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['nama', 'keterangan', 'action', 'is_active'])
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

    public function datatable_subkegiatan(Request $request)
    {
        $subkegiatan = SubKegiatan::orderBy('jenis_kegiatan_id')
        ->join('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'sub_kegiatans.jenis_kegiatan_id')
        ->join('satuan_ukur', 'satuan_ukur.id', '=', 'sub_kegiatans.satuan_ukur_id')
        ->select(
            'sub_kegiatans.*',
            'jenis_kegiatans.nama as jenis_kegiatan_nama',
            'satuan_ukur.nama as satuan_ukur_nama'
           
        )
        ->get();
        try {
            return datatables()->of($subkegiatan)
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit2" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="' . $id . '" data-nama="' . $row->nama . '" data-toggle="tooltip" title="Hapus data ' . $row->nama . '"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['jenis_kegiatan_nama', 'subkegiatan', 'satuan_ukur_nama', 'action', 'is_active'])
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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        //versi Imam
        $validated = $request->validate([
            'nama_kegiatan' => 'required',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        if ($validated) {
            $jenis_kegiatan = new JenisKegiatan();
            $jenis_kegiatan->nama = $request->nama_kegiatan;
            $jenis_kegiatan->keterangan = $request->keterangan;
            $jenis_kegiatan->save();
            return redirect()->back()->with('success', 'Berhasil menyimpan Jenis Kegiatan');
        }
    }

    public function store_subkegiatan(Request $request)
    {
        //
        
        if ($request->actionform === "insert") {
            $validated = $request->validate([
                'kegiatan_utama' => 'required',
                'subkegiatan' => 'required',
                'satuan_ukur' => 'required'
            ]);
            if (!$validated) {
                return redirect()->back()->withErrors($validated)->withInput();
            }
            DB::beginTransaction();
            try {
                $subkegiatan = new SubKegiatan();
                $subkegiatan->jenis_kegiatan_id = $request->kegiatan_utama;
                $subkegiatan->subkegiatan = $request->subkegiatan;
                $subkegiatan->satuan_ukur_id = $request->satuan_ukur;
                $subkegiatan->save();
                DB::commit();

                Session::flash('success', "Berhasil Menyimpan Data Sub Kegiatan");
                $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses tambah data',
                            'title' => 'Sukses'
                ];
                echo json_encode(['result' => true, 'data' => $result]);
               
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                throw $th;
            }
        }

        if ($request->actionform === "update") {
            $validated = $request->validate([
                'kegiatan_utama' => 'required',
                'subkegiatan' => 'required',
                'satuan_ukur' => 'required'
            ]);
            if (!$validated) {
                return redirect()->back()->withErrors($validated)->withInput();
            }
            DB::beginTransaction();
            try {
                $subkegiatan = SubKegiatan::where('id', $request->id)->first();
                $subkegiatan->jenis_kegiatan_id = $request->kegiatan_utama;
                $subkegiatan->subkegiatan = $request->subkegiatan;
                $subkegiatan->satuan_ukur_id = $request->satuan_ukur;
                $subkegiatan->save();
                DB::commit();

                Session::flash('success', "Berhasil Mengubah Data Sub Kegiatan");
                $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses mengubah data',
                            'title' => 'Sukses'
                ];
                echo json_encode(['result' => true, 'data' => $result]);
               
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollback();
                throw $th;
            }
        }
       
    }

    public function update(Request $request)
    {

        $validated = $request->validate([
            'nama_kegiatan' => 'required',
        ]);
        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        if ($validated) {
            $jenis_kegiatan = new JenisKegiatan();
            $jenis_kegiatan = JenisKegiatan::where('id', $request->id)->first();
            $jenis_kegiatan->nama = $request->nama_kegiatan;
            $jenis_kegiatan->keterangan = $request->keterangan;
            $jenis_kegiatan->save();

            echo json_encode(['result' => true]);
        }
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

    public function update_status(Request $request)
    {

        JenisKegiatan::where('id', $request->id)
            ->update(['is_active' => ($request->finalStatus === 'true')]);

        echo json_encode(['result' => true]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        try {

            $jenis_kegiatan = JenisKegiatan::find((int)$request->input('id'));

            return view($this->__route . '.form', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $jenis_kegiatan
            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

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

    public function delete(Request $request)
    {

        foreach ($request->selectedData as $key => $value) {

            $jenis_kegiatan = new JenisKegiatan();
            $jenis_kegiatan = $jenis_kegiatan->where('id', $value)->delete();
        }
        Session::flash('success', "Berhasil menghapus Jenis Kegiatan yang dipilih");
    }

    public function delete_subkegiatan(Request $request)
    {

        foreach ($request->selectedData as $key => $value) {

            $subkegiatan = new SubKegiatan();
            $subkegiatan = $subkegiatan->where('id', $value)->delete();
        }
        Session::flash('success', "Berhasil menghapus Sub Kegiatan yang dipilih");
    }
}
