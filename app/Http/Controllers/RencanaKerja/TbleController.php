<?php

namespace App\Http\Controllers\RencanaKerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Perusahaan;
use App\Models\Menu;
use DB;
use Session;
use Datatables;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;
use Carbon\Carbon;
use Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class TbleController extends Controller
{

    public function __construct()
    {

        $this->__route = 'rencana_kerja.tble';
        $this->pagetitle = 'Tanda Bukti Lapor Elektronik - RKA';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        // $perusahaan_id = $request->perusahaan_id;
        $perusahaan_id = $request->perusahaan_id ? (Crypt::decryptString($request->perusahaan_id)) : null ;

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
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        $laporan_manajemen = DB::table('laporan_manajemens')->selectRaw('laporan_manajemens.*, perusahaan_masters.id as perusahaan_id, perusahaan_masters.nama_lengkap as nama_lengkap')
        ->leftJoin('perusahaan_masters', 'perusahaan_masters.id', '=', 'laporan_manajemens.perusahaan_id')->where('periode_laporan_id', $periode_rka_id);
        if ($perusahaan_id) {

            $laporan_manajemen = $laporan_manajemen->where('perusahaan_id', $perusahaan_id);
        }


        if ($request->tahun) {

            $laporan_manajemen = $laporan_manajemen->where('tahun', $request->tahun);
        }

        if ($request->status_laporan) {

            $laporan_manajemen = $laporan_manajemen->where('status_id', $request->status_laporan);
        }

        $laporan_manajemen = $laporan_manajemen->get();
        // dd($laporan_manajemen);
        return view($this->__route . '.index', [
            'pagetitle' => $this->pagetitle,
            'breadcrumb' => 'Rencana Kerja - Tanda Bukti Lapor Elektronik - RKA',
            // 'tahun' => ($request->tahun ? $request->tahun : date('Y')),
            'tahun' => ($request->tahun ?? Carbon::now()->year),
            'perusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
            'admin_bumn' => $admin_bumn,
            'perusahaan_id' => $perusahaan_id,
            'status' => $status,
            'status_id' => $request->status_laporan ?? ''
        ]);
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
      
        $currentYear = date('Y');
        $perusahaan = Perusahaan::select('id', 'nama_lengkap')
            ->get();
           $newarray = [];
           foreach ($perusahaan as $key => $perusahaan_row) {
            for ($i = 2020; $i <= $currentYear; $i++) {    
                $item = $perusahaan_row;  
                $item['tahun'] = 'Rencana Kerja '.$i;
                array_push($newarray, $item);
            }
        }
        
            
        // dd($perusahaan->slice(0, 4));
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
       
        try {
            return datatables()->of($newarray)
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
                ->rawColumns(['id',  'nama_lengkap', 'tahun', 'action'])
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

    public function cetakDataById( $id, $tahun) {
        
        $perusahaan = Perusahaan::where('id', $id)->first();
        $menu_anggaran = Menu::where('route_name', 'anggaran_tpb.rka')->first()->label;
        $menu_program = Menu::where('route_name', 'rencana_kerja.program.index2')->first()->label;
        $menu_spdpumk = Menu::where('route_name', 'rencana_kerja.spdpumk_rka.index')->first()->label;
        $menu_laporan_manajemen = Menu::where('route_name', 'rencana_kerja.laporan_manajemen.index')->first()->label;
        // dd($menu_program);
        $data =  [
            [
                'jenis_laporan' => $menu_anggaran,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => 'Unfilled',
                'status' => 'Unfilled',
            ],
            [
                'jenis_laporan' => $menu_program,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => 'Unfilled',
                'status' => 'Unfilled',
            ],
            [
                'jenis_laporan' => $menu_spdpumk,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => 'Unfilled',
                'status' => 'Unfilled',
            ],
            [
                'jenis_laporan' => $menu_laporan_manajemen,
                'periode' => 'RKA '.$tahun,
                'tanggal_update' => 'Unfilled',
                'status' => 'Unfilled',
            ],
        ];
    
        //cek angaran
        $anggaran = DB::table('anggaran_tpbs')->where('perusahaan_id', $id)->where('tahun', $tahun)->orderBy('updated_at', 'desc')->get();
        

        $totalAnggaran = count($anggaran);
        $totalVerifiedAnggaran = count($anggaran->where('status_id', 1));
        $totalValidatedAnggaran = count($anggaran->where('status_id', 4));
    
        // dd($totalAnggaran . ' ' . $totalVerifiedAnggaran. ' '.$totalValidatedAnggaran);
        //cek ada atau tidak
        if($totalAnggaran == $totalVerifiedAnggaran && $totalAnggaran != 0){
            $data[0]['tanggal_update'] = $anggaran->first()->updated_at;
            $data[0]['status'] = "Verified";
        }

        if($totalAnggaran == $totalValidatedAnggaran && $totalAnggaran != 0){
            $data[0]['tanggal_update'] = $anggaran->first()->updated_at;
            $data[0]['status'] = "Validated";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($anggaran?->where('status_id', 2)->first()) {
            $data[0]['tanggal_update'] = $anggaran->where('status_id', 2)->first()->updated_at;
            $data[0]['status'] = "In Progress";
        }
      
        //cek program
        $anggaran = DB::table('anggaran_tpbs')->where('perusahaan_id', $id)->where('tahun', $tahun)->orderBy('target_tpbs.updated_at', 'desc')->join('target_tpbs', 'target_tpbs.anggaran_tpb_id', '=', 'anggaran_tpbs.id')->get();
        $totalAnggaranProgram = count($anggaran);
        $totalVerifiedAnggaranProgram = count($anggaran->where('status_id', 1));
        $totalValidatedAnggaranProgram = count($anggaran->where('status_id', 4));
        
         //cek ada atau tidak, Verifiedd
         if($totalAnggaranProgram == $totalVerifiedAnggaran && $totalAnggaranProgram != 0){
            $data[1]['tanggal_update'] = $anggaran->first()->updated_at;
            $data[1]['status'] = "Verified";
        }

        if($totalAnggaranProgram == $totalValidatedAnggaran && $totalAnggaranProgram != 0){
            $data[1]['tanggal_update'] = $anggaran->first()->updated_at;
            $data[1]['status'] = "Validated";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($anggaran?->where('status_id', 2)->first()) {
            $data[1]['tanggal_update'] = $anggaran->where('status_id', 2)->first()->updated_at;
            $data[1]['status'] = "In Progress";
        }

        //cek spd pumk
        $periode_rka_id = DB::table('periode_laporans')->where('nama', 'RKA')->first()->id;
        $spd_pumk = DB::table('pumk_anggarans')->where('bumn_id', $id)->where('tahun', $tahun)->where('periode_id', $periode_rka_id)->get();
        
        $totalSPDPUMK = count($spd_pumk);
        $totalVerifiedSPDPUMK = count($spd_pumk->where('status_id', 1));
        $totalValidatedSPDPUMK = count($spd_pumk->where('status_id', 4));
      
        if($totalSPDPUMK == $totalVerifiedSPDPUMK && $totalSPDPUMK!= 0){
            $data[2]['tanggal_update'] = $spd_pumk->first()->updated_at;
            $data[2]['status'] = "Verified";
        }

        if($totalSPDPUMK == $totalValidatedSPDPUMK && $totalSPDPUMK!= 0){
            $data[2]['tanggal_update'] = $spd_pumk->first()->updated_at;
            $data[2]['status'] = "Validated";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress
        if ($spd_pumk?->where('status_id', 2)->first()) {
            $data[2]['tanggal_update'] = $spd_pumk->where('status_id', 2)->first()->updated_at;
            $data[2]['status'] = "In Progress";
        }
        //cek laporan manajemen rka
        $laporan_manajemen = DB::table('laporan_manajemens')->where('perusahaan_id', $id)->where('tahun', $tahun)->where('periode_laporan_id', $periode_rka_id)->get();

        $totalLaporanManajemen = count($laporan_manajemen);
        $totalVerifiedLaporanManajemen = count($laporan_manajemen->where('status_id', 1));
        $totalValidatedLaporanManajemen = count($laporan_manajemen->where('status_id', 4));
        if($totalLaporanManajemen == $totalVerifiedLaporanManajemen && $totalLaporanManajemen != 0 ){
            $data[3]['tanggal_update'] = $laporan_manajemen->first()->updated_at;
            $data[3]['status'] = "Verified";
        }
        if($totalLaporanManajemen == $totalValidatedLaporanManajemen && $totalLaporanManajemen != 0 ){
            $data[3]['tanggal_update'] = $laporan_manajemen->first()->updated_at;
            $data[3]['status'] = "Validated";
        }
        //kalau ada yg inprogress walaupun 1 sudah pasti in progress/unfilled
        if ($laporan_manajemen?->whereIn('status_id', [2, 3])->first()) {
            $data[3]['tanggal_update'] =$laporan_manajemen->whereIn('status_id', [2, 3])->first()->status_id === 2 ? $laporan_manajemen->whereIn('status_id', [2, 3])->first()->updated_at : 'Unfilled';
            $data[3]['status'] = $laporan_manajemen->whereIn('status_id', [2, 3])->first()->status_id === 2 ? 'In Progress' : 'Unfilled';
        }
        $tanggal_cetak = Carbon::now()->locale('id_ID')->isoFormat('D MMMM YYYY');
        $user = Auth::user();
        
       // Generate the QR code as an Intervention Image instance
    //    $qrCode = QrCode::format('png')
    //    ->size(400)
    //    ->margin(20)
    //    ->generate('www.google.com');

        //    // Load the custom image as an Intervention Image instance
        //    $customImage = Image::make(public_path('logo_only.png'));
        //         // dd($customImage);
        //    // Calculate the position to place the custom image in the middle of the QR code
        //    $imageWidth = $qrCode->width();
        //    $imageHeight = $qrCode->height();
        //    $customImageWidth = 100;
        //    $customImageHeight = 100;
        //    $x = ($imageWidth - $customImageWidth) / 2;
        //    $y = ($imageHeight - $customImageHeight) / 2;

        //    // Insert the custom image into the QR code image
        //    $qrCode->insert($customImage, 'center', $x, $y);

        //    // Encode the combined image to base64
        //    $base64Image = base64_encode($qrCode->encode('png')->encoded);

        //V1
        // Save the QR code to a file
        // Storage::disk('local')->put('qr_code.png', $qrCode);

        // // Load your custom image
        // $customImage = Image::make('logo_only.png');

        // // Open the QR code image
        // $qrCodeImage = Image::make(Storage::disk('local')->path('qr_code.png'));

        // // Overlay the custom image onto the QR code
        // $qrCodeImage->insert($customImage, 'center');

        // // Save or display the merged image
        // $qrCodeImage->save('merged_qr_code.png');
        // $qrCodeImagePath = asset('merged_qr_code.png');

        // // Optionally, you can delete the temporary QR code image
        // Storage::disk('local')->delete('qr_code.png');

        //V2
        $encryptedId = Crypt::encryptString($id);
        $encryptedTanggalCetak = Crypt::encryptString($tanggal_cetak);
        $redirectRoute = route('verifikasi.index', ['id' => $encryptedId, 'tahun' => $tahun, 'tanggal_cetak' => $encryptedTanggalCetak]);
        
        // $qrCode = QrCode::format('png')
        // ->size(300)
        // ->margin(20)
        // ->generate($redirectRoute);

        // Storage::disk('local')->put('qr_code.png', $qrCode);

        // // Load your custom image
        // $customImage = Image::make('logo_only.png');

        // // Calculate the new size for the custom image (e.g., 100x100 pixels)
        // $newCustomWidth = 50;
        // $newCustomHeight = 50;

        // // Resize the custom image
        // $customImage->resize($newCustomWidth, $newCustomHeight);

        // // Calculate the position to overlay the custom image in the center of the QR code
        // $qrCodeImage = Image::make(Storage::disk('local')->path('qr_code.png'));
        // $qrCodeWidth = $qrCodeImage->getWidth();
        // $qrCodeHeight = $qrCodeImage->getHeight();
        // $customWidth = $customImage->width();
        // $customHeight = $customImage->height();
        // $overlayX = ($qrCodeWidth - $customWidth) / 2;
        // $overlayY = ($qrCodeHeight - $customHeight) / 2;

        // // Overlay the custom image onto the QR code
        // $qrCodeImage->insert($customImage, 'top-left', $overlayX, $overlayY);

        // // Save or display the merged image
        // $qrCodeImage->save('merged_qr_code.png');
        // $qrCodeImagePath = asset('merged_qr_code.png');

        // // Optionally, you can delete the temporary QR code image
        // Storage::disk('local')->delete('qr_code.png');
        // //put the qrcode in rencana_kerja.tble.detailtemplate

        
        // Generate the QR code
        // $data = route('verifikasi.index', ['id' => $encryptedId, 'tahun' => $tahun, 'tanggal_cetak' => $encryptedTanggalCetak]);
        // $type = 'QRCODE';
        // $qrCodePath = 'qr_code.png';
        // $barcode = new DNS2D();
        // $barcode->setStorPath(storage_path('app'));
        // $width = 200; // Image width
        // $height = 200; // Image height

        // // Generate the QR code image and save it
        // $barcode->getBarcodePNG($data, $type, $width, $height, $qrCodePath);
        
        // // Load your custom image
        // $customImage = Image::make('logo_only.png');

        // // Calculate the position to overlay the custom image in the center of the QR code
        // $qrCodeImage = Image::make(storage_path('app/' . $qrCodePath));
        // $qrCodeWidth = $qrCodeImage->getWidth();
        // $qrCodeHeight = $qrCodeImage->getHeight();
        // $customWidth = $customImage->width();
        // $customHeight = $customImage->height();
        // $overlayX = ($qrCodeWidth - $customWidth) / 2;
        // $overlayY = ($qrCodeHeight - $customHeight) / 2;

        // // Overlay the custom image onto the QR code
        // $qrCodeImage->insert($customImage, 'top-left', $overlayX, $overlayY);

        // // Save or display the merged image
        // $mergedQrCodePath = 'merged_qr_code.png';
        // $qrCodeImage->save(storage_path('app/' . $mergedQrCodePath));
        // $qrCodeImagePath = asset('storage/' . $mergedQrCodePath);

        // // Optionally, you can delete the temporary QR code image
        // unlink(storage_path('app/' . $qrCodePath));
        $barcode = new DNS2D();
        
        // Generate the QR code
        $qrCodeImage = $barcode->getBarcodePNG($redirectRoute, 'QRCODE,H', 1, 1);

        // Create an image object from the QR code
        $img = Image::make($qrCodeImage);

        // Load the logo image
        $logo = Image::make('logo_only.png');

        // Resize the logo image to 30% of its original size
        $logo->resize($logo->width() * 0.03, $logo->height() * 0.03);

        // Insert the logo into the center of the QR code image
        $img->insert($logo, 'center');

        // Encode the image as a data URL
        $dataUrl = $img->encode('data-url')->encoded;
        

        $pdf = PDF::loadView('rencana_kerja.tble.detailtemplate', 
        ['data' => $data,
         'perusahaan' => $perusahaan, 
         'tanggal_cetak' => $tanggal_cetak,
         'user' => $user,
         'qrCodeImage' =>  $dataUrl])->setPaper('a4', 'portrait');
        return  $pdf->download($perusahaan->nama_lengkap.'-rka-'.$tahun.'.pdf');
    }

    public function generateQRCode($content)
    {
        if ($content) {
            $id = $this->encryption->encode($content->id);
            $baseUrl = env('APP_URL');

            $img = Image::make(DNS2D::getBarcodePNG($baseUrl . $id . '/verifikasi', 'QRCODE,H', 3, 3));
            $logo = Image::make(asset('/assets/img/logo-lite.png'));

            return $img->insert($logo, 'center')->encode('data-url')->encoded;
        }
    }

    public function verifikasiIndex($encryptedId, $tahun, Request $request){
        try {
            $periode = $request->periode ?? 'RKA';
            $tanggal_cetak = Crypt::decryptString($request->tanggal_cetak);
            $id = Crypt::decryptString($encryptedId);
            
            // Check if decryption fails for $tanggal_cetak or $id
            if (!$tanggal_cetak || !$id) {
                return view('false.blade.php');
            }
    
            $perusahaan = Perusahaan::where('id', $id)->first();
            
            return view($this->__route . '.scan', [
                'periode' => $periode,
                'perusahaan' => $perusahaan, 
                'tahun' => $tahun,
                'tanggal_cetak' => $tanggal_cetak
            ]);
        } catch (\Exception $e) {
            // Handle any decryption errors or exceptions here
            return view($this->__route . '.false');
        }
    }

}
