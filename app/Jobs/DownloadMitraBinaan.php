<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\PumkMitraBinaan;
use App\Models\Perusahaan;
use App\Models\BankAccount;
use App\Models\DownloadExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MitraBinaanExport;

class DownloadMitraBinaan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $data;
    protected $part;
    protected $downloadId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $part, $downloadId)
    {
    $this->data = $data;
    $this->part = $part;
    $this->downloadId = $downloadId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {  
    $request = $this->data;  
    
    // $downloadId = $request['downloadId']; 
    $downloadId = $this->downloadId;
    $latestDownload = DownloadExport::find($downloadId);
    $latestDownload->status = 'on process';
    $latestDownload->updated_at = date('Y-m-d H:i:s');
    $latestDownload->save();

    
    //fungsi handle limit memory
    if((int)preg_replace('/[^0-9]/','',ini_get('memory_limit')) < 512){
    ini_set('memory_limit','-1');
    ini_set('max_execution_limit','0');
    }
    // $data = PumkMitraBinaan::select('pumk_mitra_binaans.*','provinsis.nama AS provinsi','kotas.nama AS kota','sektor_usaha.nama AS sektor_usaha','kolekbilitas_pendanaan.nama AS kolektibilitas',
    // 'cara_penyalurans.nama AS cara_penyaluran','skala_usahas.name AS skala_usaha','kondisi_pinjaman.nama AS kondisi_pinjaman','jenis_pembayaran.nama AS jenis_pembayaran','perusahaans.nama_lengkap AS bumn')
    // ->leftjoin('provinsis','provinsis.id','=','pumk_mitra_binaans.provinsi_id')
    // ->leftjoin('kotas','kotas.id','=','pumk_mitra_binaans.kota_id')
    // ->leftjoin('cara_penyalurans','cara_penyalurans.id','=','pumk_mitra_binaans.cara_penyaluran_id')
    // ->leftjoin('skala_usahas','skala_usahas.id','=','pumk_mitra_binaans.skala_usaha_id')
    // ->leftjoin('kondisi_pinjaman','kondisi_pinjaman.id','=','pumk_mitra_binaans.kondisi_pinjaman_id')
    // ->leftjoin('jenis_pembayaran','jenis_pembayaran.id','=','pumk_mitra_binaans.jenis_pembayaran_id')
    // //->leftjoin('bank_account','bank_account.id','=','pumk_mitra_binaans.bank_account_id')
    // ->leftjoin('sektor_usaha','sektor_usaha.id','=','pumk_mitra_binaans.sektor_usaha_id')
    // ->leftjoin('perusahaans','perusahaans.id','=','pumk_mitra_binaans.perusahaan_id')
    // ->leftjoin('kolekbilitas_pendanaan','kolekbilitas_pendanaan.id','=','pumk_mitra_binaans.kolektibilitas_id');
    
    
    // if($request['perusahaan_id']){
    // $data = $data->where('pumk_mitra_binaans.perusahaan_id',$request['perusahaan_id']);
    // }

    // if($request['provinsi_id']){
    // $data = $data->where('pumk_mitra_binaans.provinsi_id',$request['provinsi_id']);
    // }

    // if($request['kota_id']){
    // $data = $data->where('pumk_mitra_binaans.kota_id',$request['kota_id']);
    // }

    // if($request['sektor_usaha_id']){
    // $data = $data->where('pumk_mitra_binaans.sektor_usaha_id',$request['sektor_usaha_id']);
    // }

    // if($request['cara_penyaluran_id']){
    // $data = $data->where('pumk_mitra_binaans.cara_penyaluran_id',$request['cara_penyaluran_id']);
    // }

    // if($request['skala_usaha_id']){
    // $data = $data->where('pumk_mitra_binaans.skala_usaha_id',$request['skala_usaha_id']);
    // }

    // if($request['kolektibilitas_id']){
    // $data = $data->where('pumk_mitra_binaans.kolektibilitas_id',$request['kolektibilitas_id']);
    // }

    // if($request['kondisi_pinjaman_id']){
    // $data = $data->where('pumk_mitra_binaans.kondisi_pinjaman_id',$request['kondisi_pinjaman_id']);
    // }

    // if($request['bank_account_id']){
    // $data = $data->where('pumk_mitra_binaans.bank_account_id',$request['bank_account_id']);
    // }

    // if($request['jenis_pembayaran_id']){
    // $data = $data->where('pumk_mitra_binaans.jenis_pembayaran_id',$request['jenis_pembayaran_id']);
    // }

    // if($request['identitas']){
    // $data = $data->where('pumk_mitra_binaans.no_identitas',$request['identitas']);
    // }

    // if($request['bulan_export']){
    // $data = $data->where('pumk_mitra_binaans.bulan',$request['bulan_export']);
    // }

    // if($request['tahun_export']){
    // $data = $data->where('pumk_mitra_binaans.tahun',$request['tahun_export']);   
    // }    

    // $mitra = $data->where('is_arsip',false)->get();
    $mitra = $this->data;

    foreach($mitra as $k=>$value){
    $sumber_bumn = [];
    $arr = explode(',', $value->sumber_dana); 
    foreach($arr as $val){
        if(is_numeric($val)){
        $sumber_bumn[] = ' '.Perusahaan::where('id',(int)$val)->pluck('nama_lengkap')->first().' ';
        }
        if(!is_numeric($val)){
        $sumber_bumn[] = " ".$val." ";
        }
    }

    $result_sumber = json_encode($sumber_bumn);
    $value->sumber_dana = str_replace(']','',str_replace('[','',(preg_replace('/"/',"",$result_sumber))));
    }

    $bank = BankAccount::get();
    $namaFile = "Data Mitra Binaan - ".time()." ".($this->part).".xlsx";
    Excel::store(new MitraBinaanExport($mitra,$bank), 'public/download/'.$namaFile);

    $latestDownload = DownloadExport::find($downloadId);
    $latestDownload->status = 'done';
    $latestDownload->file_path = $namaFile; // 'app/public/download/'
    $latestDownload->updated_at = date('Y-m-d H:i:s');
    $latestDownload->save();

    } 
}
