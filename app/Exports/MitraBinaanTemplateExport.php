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

class MitraBinaanTemplateExport implements FromView , WithTitle
{
    public function __construct($perusahaan){
        $this->perusahaan = $perusahaan ;
    }

    public function view(): View
    { 
      $data = [];
    //   if(!empty($this->perusahaan)){
    //      $data = PumkMitraBinaan::where('perusahaan_id',$this->perusahaan->id)->get();
    //   } 
      $perusahaan = empty($this->perusahaan)? 'PT/PERUM ... ' : $this->perusahaan->nama_lengkap; 
      
      return view('pumk.upload_data_mitra.template', [
          'periode' => "Periode : ".date('M')."-". date('Y'), 
          'perusahaan' => $perusahaan, 
          'data' => $data, 
      ]);
    }

    public function title(): string
    {
        return 'Input Data Mitra Binaan' ;
    }
    
}
