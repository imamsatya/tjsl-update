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
use Symfony\Component\HttpFoundation\InputBag;

use App\Models\PeriodeLaporan;
use App\Models\PeriodeHasJenis;
use App\Models\JenisLaporan;
use App\Models\LaporanManajemen;
use App\Models\PeriodeLaporanTentatif;
use App\Models\PeriodeTentatifHasJenis;
use App\Models\Perusahaan;
use App\Models\Menu;

class PeriodeLaporanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'referensi.periode_laporan';
        $this->pagetitle = 'Periode Laporan';
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
            'breadcrumb' => 'Referensi - Periode Laporan'
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        $periode = PeriodeLaporan::orderBy('jenis_laporan_id')->orderBy('urutan')->where('jenis_periode', 'standar')->get();
        try {
            return datatables()->of($periode)
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->editColumn('tanggal_awal', function ($row) {
                    $tanggal_awal = $row->tanggal_awal;
                    if ($row->tanggal_awal) $tanggal_awal = date("d M Y", strtotime($row->tanggal_awal));
                    return $tanggal_awal;
                })
                ->editColumn('tanggal_akhir', function ($row) {
                    $tanggal_akhir = $row->tanggal_akhir;
                    if ($row->tanggal_akhir) $tanggal_akhir = date("d M Y", strtotime($row->tanggal_akhir));
                    return $tanggal_akhir;
                })
                ->editColumn('jenis_laporan', function ($row) {
                    $label = '<ul class="no-margin">';
                    if (!empty(@$row->has_jenis)) {
                        foreach ($row->has_jenis as $v) {
                            // $label .= '<li>' . @$v->jenis->nama . '</li>';
                            $label .= '<li>' . @$v->jenis->label . '</li>';
                        }
                    }
                    $label .= '</ul>';
                    return $label;
                })
                ->rawColumns(['nama', 'keterangan', 'action', 'jenis_laporan'])
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

    public function datatable_tentatif(Request $request)
    {
        $periode = PeriodeLaporan::orderBy('jenis_laporan_id')->orderBy('urutan')->where('jenis_periode', 'tentatif')->get();
        try {
            return datatables()->of($periode)
                ->addColumn('action', function ($row) {
                    $id = (int)$row->id;
                    $button = '<div align="center">';

                    $button .= '<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="' . $id . '" data-toggle="tooltip" title="Ubah data ' . $row->nama . '"><i class="bi bi-pencil fs-3"></i></button>';

                    $button .= '&nbsp;';

                    // $button .= '<button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" title="Hapus data '.$row->nama.'"><i class="bi bi-trash fs-3"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->editColumn('tanggal_awal', function ($row) {
                    $tanggal_awal = $row->tanggal_awal;
                    if ($row->tanggal_awal) $tanggal_awal = date("d M", strtotime($row->tanggal_awal));
                    return $tanggal_awal;
                })
                ->editColumn('tanggal_akhir', function ($row) {
                    $tanggal_akhir = $row->tanggal_akhir;
                    if ($row->tanggal_akhir) $tanggal_akhir = date("d M", strtotime($row->tanggal_akhir));
                    return $tanggal_akhir;
                })
                ->editColumn('jenis_laporan', function ($row) {
                    $label = '<ul class="no-margin">';
                    if (!empty(@$row->has_jenis)) {
                        foreach ($row->has_jenis as $v) {
                            // $label .= '<li>' . @$v->jenis->nama . '</li>';
                            $label .= '<li>' . @$v->jenis->label . '</li>';
                        }
                    }
                    $label .= '</ul>';
                    return $label;
                })
                ->rawColumns(['nama', 'keterangan', 'action', 'jenis_laporan'])
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
        $periode_laporan = PeriodeLaporan::get();

        return view($this->__route . '.form', [
            'pagetitle' => $this->pagetitle,
            'actionform' => 'insert',
            // 'jenis_laporan' => JenisLaporan::get(),
            'jenis_laporan' => Menu::where('status', 1)->where(DB::raw('TRIM(route_name)'), '!=', '')->get(),
            'data' => $periode_laporan
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
        $jenis_periode = $request->jenis_periode;
        $request->request->remove('jenis_periode');

        $validator = $this->validateform($request);
        if (!$validator->fails()) {
            $param = $request->except('actionform', 'id', 'tanggal_awal', 'tanggal_akhir', 'jenis_laporan');

            switch ($request->input('actionform')) {
                case 'insert':
                    DB::beginTransaction();
                    try {


                        $tanggal_awal = date_format(date_create($request->tanggal_awal), "Y-m-d");
                        $tanggal_akhir = date_format(date_create($request->tanggal_akhir), "Y-m-d");
                        $param['tanggal_awal'] = $tanggal_awal;
                        $param['tanggal_akhir'] = $tanggal_akhir;

                        //tambahan Imam
                        $param['jenis_periode'] = $jenis_periode;

                        $periode_laporan = PeriodeLaporan::create((array)$param);

                        #create new transaction
                        $create = [''];
                        if ($request->jenis_laporan) {
                            foreach ($request->jenis_laporan as $key => $data) {
                                $create['periode_laporan_id'] = $periode_laporan->id;
                                $create['jenis_laporan_id'] = $data;
                                PeriodeHasJenis::create($create);

                                // create data laporan manajemen all bumn
                                if ($data == 1) {
                                    $perusahaan = Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get();
                                    foreach ($perusahaan as $p) {
                                        $param_laporan['perusahaan_id'] = $p->id;
                                        $param_laporan['periode_laporan_id'] = $periode_laporan->id;
                                        $param_laporan['status_id'] = 3;
                                        $param_laporan['tahun'] = date('Y');
                                        $laporan_manajamen = LaporanManajemen::create((array)$param_laporan);
                                    }
                                }
                            }
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

                        $tanggal_awal = date_format(date_create($request->tanggal_awal), "Y-m-d");
                        $tanggal_akhir = date_format(date_create($request->tanggal_akhir), "Y-m-d");
                        $param['tanggal_awal'] = $tanggal_awal;
                        $param['tanggal_akhir'] = $tanggal_akhir;
                        //tambahan Imam ga perlu
                        // $param['jenis_periode'] = $jenis_periode;
                        $periode_laporan = PeriodeLaporan::find((int)$request->input('id'));

                        #delete transaction old
                        PeriodeHasJenis::where("periode_laporan_id", $request->input('id'))->delete();

                        #create new transaction
                        $create = [''];
                        if ($request->jenis_laporan) {
                            foreach ($request->jenis_laporan as $key => $data) {
                                $create['periode_laporan_id'] = $periode_laporan->id;
                                $create['jenis_laporan_id'] = $data;
                                PeriodeHasJenis::create($create);
                            }
                        }

                        $periode_laporan->update((array)$param);

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {

        try {

            $periode_laporan = PeriodeLaporan::find((int)$request->input('id'));
            $jenis_laporan_id = PeriodeHasJenis::where("periode_laporan_id", $request->input('id'))->pluck('jenis_laporan_id')->all();

            return view($this->__route . '.form', [
                'pagetitle' => $this->pagetitle,
                'actionform' => 'update',
                // 'jenis_laporan' => JenisLaporan::get(),
                'jenis_laporan' => Menu::where('status', 1)->where(DB::raw('TRIM(route_name)'), '!=', '')->get(),
                'jenis_laporan_id' => $jenis_laporan_id,
                'data' => $periode_laporan,
                'periode' => 'standar'

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
        DB::beginTransaction();
        try {
            $listId = $request->input('id');
            PeriodeLaporan::whereIn('id', $listId)->delete();

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
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }

    public function updateStatus(Request $request) {
        PeriodeLaporan::where('id', $request->id)->update(['is_active' => ($request->finalStatus === 'true')]);
        echo json_encode(['result' => true]);
    }

    public function updateVisibility(Request $request) {
        PeriodeLaporan::where('id', $request->id)->update(['is_visible' => ($request->finalVisibility === 'true')]);
        echo json_encode(['result' => true]);
    }
}
