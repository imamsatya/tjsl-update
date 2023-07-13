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
use App\Models\Bulan;

class MitraBinaanTemplateExport implements FromView , WithTitle
{
    public function __construct($perusahaan, $tahun, $periode){
        $this->perusahaan = $perusahaan ;
        $this->tahun = $tahun ;
        $this->periode = $periode;
    }

    public function view(): View
    { 
      $data = [];
    //   if(!empty($this->perusahaan)){
    //      $data = PumkMitraBinaan::where('perusahaan_id',$this->perusahaan->id)->get();
    //   } 
      $perusahaan = empty($this->perusahaan)? 'PT/PERUM ... ' : $this->perusahaan->nama_lengkap; 
      
      // $bulan_static = (int)date('m') == 1? 12 : (int)date('m');
      // $tahun_static = (int)date('m') == 1? (int)date('Y')-1 : (int)date('Y');
      // $bulan = Bulan::where('id',$bulan_static)->pluck('nama')->first();

      return view('pumk.upload_data_mitra.template', [
          // 'periode' => "Periode : ".$bulan."-".$tahun_static, 
          'periode' => "Periode : Semester ".$this->periode."-".$this->tahun, 
          'perusahaan' => $perusahaan, 
          'data' => $data, 
          'tahun' => $this->tahun,
      ]);
    }

    public function title(): string
    {
        return 'Input Data Mitra Binaan' ;
    }
    
}
