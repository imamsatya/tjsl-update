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

use App\Models\PilarPembangunan;
use Session;

class PilarPembangunanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'referensi.pilar_pembangunan';
        $this->pagetitle = 'Pilar Pembangunan';
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
            'breadcrumb' => 'Referensi - Pilar Pembangunan'
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        try {
            return datatables()->of(PilarPembangunan::query())
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

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
        $pilar = PilarPembangunan::get();

        return view($this->__route . '.form', [
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $pilar
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // $result = [
        //     'flag' => 'error',
        //     'msg' => 'Error System',
        //     'title' => 'Error'
        // ];

        // $validator = $this->validateform($request);
        // if (!$validator->fails()) {
        //     $param['nama'] = $request->input('nama');
        //     $param['keterangan'] = $request->input('keterangan');

        //     switch ($request->input('actionform')) {
        //         case 'insert':
        //             DB::beginTransaction();
        //             try {
        //                 $pilar = PilarPembangunan::create((array)$param);

        //                 DB::commit();
        //                 $result = [
        //                     'flag'  => 'success',
        //                     'msg' => 'Sukses tambah data',
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

        //         case 'update':
        //             DB::beginTransaction();
        //             try {
        //                 $pilar = PilarPembangunan::find((int)$request->input('id'));
        //                 $pilar->update((array)$param);

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

            'nama_pilar' => 'required',
            'jenis_anggaran' => 'required',
        ]);

        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        if ($validated) {

            foreach ($request->jenis_anggaran as $key => $value) {
                # code...

                $pilar_pembangunan = new PilarPembangunan();
                $pilar_pembangunan->nama = $request->nama_pilar;
                $pilar_pembangunan->keterangan = $request->keterangan;
                $pilar_pembangunan->jenis_anggaran = $value;
                $pilar_pembangunan->save();
            }


            return redirect()->back()->with('success', 'Berhasil menyimpan Pilar Pembangunan');
        }
    }

    public function update(Request $request)
    {

        $validated = $request->validate([
            // 'id_tpb' => 'required',
            'nama_pilar' => 'required',
            // 'jenis_anggaran' => 'required',
        ]);
        if (!$validated) {
            return redirect()->back()->withErrors($validated)->withInput();
        }
        if ($validated) {
            $pilar_pembangunan = new PilarPembangunan();
            $pilar_pembangunan = PilarPembangunan::where('id', $request->id)->first();
            $pilar_pembangunan->nama = $request->nama_pilar;
            // $pilar_pembangunan->jenis_anggaran = $request->jenis_anggaran;
            $pilar_pembangunan->keterangan = $request->keterangan;
            $pilar_pembangunan->save();

            echo json_encode(['result' => true]);
        }
    }

    public function update_status(Request $request)
    {

        PilarPembangunan::where('id', $request->id)
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

            $pilar = PilarPembangunan::find((int)$request->input('id'));

            return view($this->__route . '.form', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $pilar

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

            $pilar_pembangunan = new PilarPembangunan();
            $pilar_pembangunan = $pilar_pembangunan->where('id', $value)->delete();
        }
        Session::flash('success', "Berhasil menghapus Pilar Pembangunan yang dipilih");
        // DB::beginTransaction();
        // try {
        //     $data = PilarPembangunan::find((int)$request->input('id'));
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

        $message['nama.required'] = 'Nama wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    public function order(Request $request) {
        try {

            $pilar = PilarPembangunan::select('nama', 'order_pilar')->where('is_active', '1')->groupBy('nama', 'order_pilar')->orderBy('order_pilar')->get();

            $pilar = $pilar->map(function($item) {
                return $item->nama;
            });

            return view($this->__route . '.order', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $pilar

            ]);
        } catch (Exception $e) {
        }
    }

    public function orderSubmit(Request $request) {
        try {
            DB::beginTransaction();
            $data = $request->input('data');
            foreach($data as $index => $pilar) {
                DB::table('pilar_pembangunans')->where('nama', $pilar)->update([
                    'order_pilar' => $index + 1
                ]);
            }
            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Berhasil memperbarui urutan pilar!',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
}
