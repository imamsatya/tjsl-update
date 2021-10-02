<?php

namespace App\Exports;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\View\View;
use DB;
use App\Models\PumkMitraBinaan;
use App\Models\Perusahaan;
use App\Models\Bulan;
class MitraBinaanSuksesUploadExport implements FromView , WithTitle
{
    public function __construct($kode){
        $this->kode = $kode ;
    }

    public function view(): View
    { 
        
      $data = [];
      
      if($this->kode){
        $data = PumkMitraBinaan::where('kode_upload',$this->kode)->get();
      }

      $bumn = Perusahaan::get();

      $bulan_static = (int)date('m', strtotime($data[0]->created_at))-1;
      $bulan = Bulan::where('id',$bulan_static)->pluck('nama')->first();

      return view('pumk.upload_data_mitra.template', [
          'periode' => "Periode : ".$bulan."-". date('Y'), 
          'perusahaan' => null, 
          'data' => $data,
          'bumn' => $bumn? $bumn : [] 
      ]);
    }

    public function title(): string
    {
        return 'Input Data Mitra Binaan' ;
    }
    
}
