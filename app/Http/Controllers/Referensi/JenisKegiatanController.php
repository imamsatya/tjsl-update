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
}
