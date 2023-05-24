<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Datatables;
use App\Http\Controllers\Controller;

use App\Models\Tpb;
use Session;

class TpbController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'referensi.tpb';
        $this->pagetitle = 'TPB';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Referensi - TPB'
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        $tpb = Tpb::orderBy('no_tpb')->get();
        try {
            return datatables()->of($tpb)
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="' . $id . '" data-nama="' . $row->nama . '" data-toggle="tooltip" title="Hapus data ' . $row->nama . '"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['nama', 'jenis_anggaran', 'action', 'is_active'])
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
        $tpb = Tpb::get();

        return view($this->__route . '.form', [
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $tpb
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // versi BUMN
        // $result = [
        //     'flag' => 'error',
        //     'msg' => 'Error System',
        //     'title' => 'Error'
        // ];

        // $validator = $this->validateform($request);
        // if (!$validator->fails()) {
        //     $param = $request->except('actionform', 'id', 'no_tpb');

        //     switch ($request->input('actionform')) {
        //         case 'insert':
        //             DB::beginTransaction();
        //             try {
        //                 $param['no_tpb'] = 'TPB ' . $request->no_tpb;
        //                 $exist = Tpb::where('no_tpb', $param['no_tpb'])->first();
        //                 if ($exist) {
        //                     DB::rollback();
        //                     $result = [
        //                         'flag'  => 'warning',
        //                         'msg' => 'No TPB sudah ada',
        //                         'title' => 'Gagal'
        //                     ];
        //                 } else {
        //                     $tpb = Tpb::create((array)$param);
        //                     DB::commit();
        //                     $result = [
        //                         'flag'  => 'success',
        //                         'msg' => 'Sukses tambah data',
        //                         'title' => 'Sukses'
        //                     ];
        //                 }
        //             } catch (\Exception $e) {
        //                 DB::rollback();
        //                 $result = [
        //                     'flag'  => 'warning',
        //                     'msg' => $e->getMessage(),
        //                     'title' => 'Gagal'
        //                 ];
        //             }

        //             break;

        //         case 'update':
        //             DB::beginTransaction();
        //             try {
        //                 $tpb = Tpb::find((int)$request->input('id'));
        //                 $tpb->update((array)$param);

        //                 DB::commit();
        //                 $result = [
        //                     'flag'  => 'success',
        //                     'msg' => 'Sukses ubah data',
        //                     'title' => 'Sukses'
        //                 ];
        //             } catch (\Exception $e) {
        //                 DB::rollback();
        //                 $result = [
        //                     'flag'  => 'warning',
        //                     'msg' => $e->getMessage(),
        //                     'title' => 'Gagal'
        //                 ];
        //             }

        //             break;
        //     }
        // } else {
        //     $messages = $validator->errors()->all('<li>:message</li>');
        //     $result = [
        //         'flag'  => 'warning',
        //         'msg' => '<ul>' . implode('', $messages) . '</ul>',
        //         'title' => 'Gagal proses data'
        //     ];
        // }



        // return response()->json($result);

        //versi Imam
        $validated = $request->validate([
            'id_tpb' => 'required',
            'nama_tpb' => 'required',
            'jenis_anggaran' => 'required',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        if ($validated) {

            foreach ($request->jenis_anggaran as $key => $value) {
                # code...

                $tpb = new Tpb();
                $tpb->no_tpb = $request->id_tpb;
                $tpb->nama = $request->nama_tpb;
                $tpb->keterangan = $request->keterangan;
                $tpb->jenis_anggaran = $value;
                $tpb->save();
            }


            return redirect()->back()->with('success', 'Berhasil menyimpan TPB');
        }
    }

    public function update(Request $request)
    {

        $validated = $request->validate([
            // 'id_tpb' => 'required',
            'nama_tpb' => 'required',
            // 'jenis_anggaran' => 'required',
        ]);
        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        if ($validated) {
            $tpb = new Tpb();
            $tpb = Tpb::where('id', $request->id)->first();
            $tpb->nama = $request->nama_tpb;
            // $tpb->jenis_anggaran = $request->jenis_anggaran;
            $tpb->keterangan = $request->keterangan;
            $tpb->save();

            echo json_encode(['result' => true]);
        }
    }

    public function update_status(Request $request)
    {

        Tpb::where('id', $request->id)
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

            $tpb = Tpb::find((int)$request->input('id'));

            return view($this->__route . '.form', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $tpb
            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {

        foreach ($request->selectedData as $key => $value) {

            $tpb = new Tpb();
            $tpb = $tpb->where('id', $value)->delete();
        }
        Session::flash('success', "Berhasil menghapus TPB yang dipilih");

        // DB::beginTransaction();
        // try {
        //     $data = Tpb::find((int)$request->input('id'));
        //     $data->delete();

        //     DB::commit();
        //     $result = [
        //         'flag'  => 'success',
        //         'msg' => 'Sukses hapus data',
        //         'title' => 'Sukses'
        //     ];
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     $result = [
        //         'flag'  => 'warning',
        //         'msg' => 'Gagal hapus data',
        //         'title' => 'Gagal'
        //     ];
        // }
        // return response()->json($result);
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateform($request)
    {
        $required['nama'] = 'required';
        // $required['jenis_anggaran'] = 'required';
        $message['nama.required'] = 'Nama wajib diinput';
        // $message['jenis_anggaran.required'] = 'jenis_anggaran wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }
}
