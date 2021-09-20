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

class MitraBinaanExport implements FromView , WithTitle, WithColumnFormatting
{
    const FORMAT_NUMBER_CUSTOM = '#,';
    public function __construct($mitra){
        $this->mitra = $mitra ;
    }

    public function view(): View
    {  
      return view('pumk.data_mitra.export', [
          'data' => $this->mitra, 
          'tanggal' => date('d-m-Y'),

      ]);
    }

    public function title(): string
    {
        return 'Data Mitra Binaan' ;
    }
    
    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'O' => NumberFormat::FORMAT_NUMBER,
            'P' => NumberFormat::FORMAT_NUMBER,
            'Q' => NumberFormat::FORMAT_NUMBER,
            'R' => NumberFormat::FORMAT_NUMBER,
            'S' => NumberFormat::FORMAT_NUMBER,
            'Z' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
