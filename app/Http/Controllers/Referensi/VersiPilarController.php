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

use App\Models\VersiPilar;
use App\Models\RelasiPilarTpb;
use App\Models\RelasiTpbKodeIndikator;
use App\Models\RelasiTpbKodeTujuanTpb;
use App\Models\PilarPembangunan;
use App\Models\KodeIndikator;
use App\Models\KodeTujuanTpb;
use App\Models\Tpb;

class VersiPilarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'referensi.versi_pilar';
        $this->pagetitle = 'Versi dan Relasi Pilar';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $versi = VersiPilar::orderBy('status')->orderBy('tanggal_akhir', 'desc')->get();
        $pilars = RelasiPilarTPB::select('pilar_pembangunans.nama', 'pilar_pembangunans.jenis_anggaran', 'relasi_pilar_tpbs.versi_pilar_id', 'pilar_pembangunans.id')
            ->leftJoin('pilar_pembangunans', 'pilar_pembangunans.id', 'relasi_pilar_tpbs.pilar_pembangunan_id')
            ->GroupBy('pilar_pembangunans.id')
            ->GroupBy('pilar_pembangunans.nama')
            ->GroupBy('relasi_pilar_tpbs.versi_pilar_id')
            ->orderBy('pilar_pembangunans.id')
            ->get();
        $tpbs = RelasiPilarTPB::select('tpbs.nama', 'tpbs.no_tpb', 'relasi_pilar_tpbs.*')
            ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
            ->orderBy('tpbs.id')
            ->get();

        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'versi' => $versi,
            'pilars' => $pilars,
            'tpbs' => $tpbs,
            'breadcrumb' => 'Referensi - Versi dan Relasi Pilar'
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
            return datatables()->of(VersiPilar::query())
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="' . $id . '" data-nama="' . $row->nama . '" data-toggle="tooltip" title="Hapus data ' . $row->nama . '"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['nama', 'keterangan', 'action'])
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
        $versi = VersiPilar::get();

        return view($this->__route . '.form', [
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            'data' => $versi
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateform($request);
        if (!$validator->fails()) {
            $param = $request->except('actionform', 'id', 'tanggal_awal', 'tanggal_akhir', 'status');

            $param['tanggal_awal'] = null;
            $param['tanggal_akhir'] = null;
            if ($request->tanggal_awal) {
                $param['tanggal_awal'] = date_format(date_create($request->tanggal_awal), "Y-m-d");
            }
            if ($request->tanggal_akhir) {
                $param['tanggal_akhir'] = date_format(date_create($request->tanggal_akhir), "Y-m-d");
            }

            $param['status'] = false;
            if ($request->status) {
                $param['status'] = true;
            }

            switch ($request->input('actionform')) {
                case 'insert':
                    DB::beginTransaction();
                    try {
                        $versi = VersiPilar::create((array)$param);

                        DB::commit();
                        $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses tambah data',
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

                    break;

                case 'update':
                    DB::beginTransaction();
                    try {
                        $versi = VersiPilar::find((int)$request->input('id'));
                        $versi->update((array)$param);

                        DB::commit();
                        $result = [
                            'flag'  => 'success',
                            'msg' => 'Sukses ubah data',
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

                    break;
            }
        } else {
            $messages = $validator->errors()->all('<li>:message</li>');
            $result = [
                'flag'  => 'warning',
                'msg' => '<ul>' . implode('', $messages) . '</ul>',
                'title' => 'Gagal proses data'
            ];
        }

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_pilar(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $param['versi_pilar_id'] = $request->input('id');
        $param['pilar_pembangunan_id'] = $request->input('pilar_pembangunan_id');
        $tpb = $request->input('tpb');

        switch ($request->input('actionform')) {
            case 'insert':
                DB::beginTransaction();
                try {
                    foreach ($tpb as $p) {
                        $param['tpb_id'] = $p;
                        RelasiPilarTpb::create((array)$param);
                    }

                    DB::commit();
                    $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses tambah data',
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

                break;

            case 'update':
                DB::beginTransaction();
                try {
                    $data = RelasiPilarTpb::where('versi_pilar_id', (int)$request->input('id'))
                        ->where('pilar_pembangunan_id', (int)$request->input('pilar_pembangunan_id'));
                    $data->delete();

                    foreach ($tpb as $p) {
                        $param['tpb_id'] = $p;
                        RelasiPilarTpb::create((array)$param);
                    }

                    DB::commit();
                    $result = [
                        'flag'  => 'success',
                        'msg' => 'Sukses ubah data',
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

                break;
        }
        return $result;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_tpb(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $kode_indikator = $request->input('kode_indikator');
        $kode_tujuan_tpb = $request->input('kode_tujuan_tpb');

        DB::beginTransaction();
        try {
            $relasi = RelasiPilarTpb::find($request->input('id'));
            $relasi->indikator()->sync($kode_indikator);
            $relasi->tujuan_tpb()->sync($kode_tujuan_tpb);

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses ubah data',
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

        return $result;
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

            $versi = VersiPilar::find((int)$request->input('id'));

            return view($this->__route . '.form', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $versi

            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit_pilar(Request $request)
    {
        try {
            $versi = VersiPilar::find((int)$request->input('versi'));
            $relasi = RelasiPilarTpb::where('versi_pilar_id', (int)$request->input('versi'))->where('pilar_pembangunan_id', (int)$request->input('id'));
            $pilar = PilarPembangunan::where('is_active', true)->orderBy('nama', 'asc')->get();
            $tpb = Tpb::get();
            $tpb_id = $relasi->pluck('tpb_id')->all();

            return view($this->__route . '.form_pilar', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $versi,
                'relasi' => $relasi,
                'pilar' => $pilar,
                'tpb' => $tpb,
                'tpb_id' => $tpb_id,
                'pilar_pembangunan_id' => (int)$request->input('id')

            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit_tpb(Request $request)
    {
        try {
            $relasi = RelasiPilarTpb::select('relasi_pilar_tpbs.id', 'tpbs.no_tpb', 'tpbs.nama')
                ->leftJoin('tpbs', 'tpbs.id', 'relasi_pilar_tpbs.tpb_id')
                ->where('relasi_pilar_tpbs.id', (int)$request->input('id'))
                ->first();
            $kode_indikator_id = RelasiTpbKodeIndikator::where('relasi_pilar_tpb_id', (int)$request->input('id'))->pluck('kode_indikator_id')->all();
            $kode_tujuan_tpb_id = RelasiTpbKodeTujuanTpb::where('relasi_pilar_tpb_id', (int)$request->input('id'))->pluck('kode_tujuan_tpb_id')->all();

            return view($this->__route . '.form_tpb', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                'data' => $relasi,
                'tpb_id' => (int)$request->input('tpb'),
                'kode_indikator' => KodeIndikator::get(),
                'kode_tujuan_tpb' => KodeTujuanTpb::get(),
                'kode_indikator_id' => $kode_indikator_id,
                'kode_tujuan_tpb_id' => $kode_tujuan_tpb_id,
            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function add_pilar(Request $request)
    {
        try {
            $versi = VersiPilar::find((int)$request->input('id'));
            // $pilar = PilarPembangunan::get();
            $pilar = PilarPembangunan::where('is_active', true)->orderBy('nama', 'asc')->orderBy('jenis_anggaran', 'asc')->get();
            $tpb = Tpb::get();

            return view($this->__route . '.form_pilar', [
                'pagetitle' => 'Relasi Pilar dan TPB',
                'actionform' => 'insert',
                'data' => $versi,
                'pilar' => $pilar,
                'tpb' => $tpb,
            ]);
        } catch (Exception $e) {
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_pilar(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = RelasiPilarTpb::where('versi_pilar_id', (int)$request->input('versi_pilar_id'))->where('pilar_pembangunan_id', (int)$request->input('pilar_pembangunan_id'));
            $data->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = VersiPilar::find((int)$request->input('id'));
            $data->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validateform($request)
    {
        $required['versi'] = 'required';

        $message['versi.required'] = 'Nama wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_status(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        try {
            $param['status'] = $request->input('status');
            $perusahaan = VersiPilar::find((int)$request->input('id'));
            $perusahaan->update((array)$param);

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses ubah data',
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
