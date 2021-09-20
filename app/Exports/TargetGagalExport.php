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

class TargetGagalExport implements FromView , WithTitle
{
    public function __construct($target,$perusahaan,$tahun){
        $this->target = $target ;
        $this->perusahaan = $perusahaan ;
        $this->tahun = $tahun ;
    }

    public function view(): View
    {  
      return view('target.upload_target.target_gagal', [
          'target' => $this->target, 
          'perusahaan' => $this->perusahaan, 
          'tahun' => $this->tahun, 
          'tanggal' => date('d-m-Y'),
      ]);
    }

    public function title(): string
    {
        return 'Input Target';
    }
    
}
