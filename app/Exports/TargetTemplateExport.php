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

class TargetTemplateExport implements FromView , WithTitle
{
    public function __construct($perusahaan,$filter_tahun ){
        $this->perusahaan = $perusahaan ;
        $this->tahun = $filter_tahun ;
    }

    public function view(): View
    {  
      return view('target.administrasi.template', [
          'tahun' => $this->tahun? $this->tahun : date('Y'), 
          'perusahaan' => $this->perusahaan, 
          'tanggal' => date('d-m-Y'),
      ]);
    }

    public function title(): string
    {
        return 'Input Program' ;
    }
    
}
