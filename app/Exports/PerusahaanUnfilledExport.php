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
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use DB;

class PerusahaanUnfilledExport implements FromView , WithTitle, WithColumnFormatting, ShouldAutoSize
{
    const FORMAT_NUMBER_CUSTOM = '#,##0';
    public function __construct($perusahaanUnfilled, $tahun){
        $this->perusahaanUnfilled = $perusahaanUnfilled ;
        $this->tahun = $tahun ;
    }

    public function view(): View
    {  
    //   $id_users = \Auth::user()->id;
    //   $users = User::where('id', $id_users)->first();
      $tahun = '';
      if($this->tahun){
        $tahun = 'Tahun '.$this->tahun;
      }

      return view('home.export_unfilled', [
          'perusahaanUnfilled' => $this->perusahaanUnfilled, 
          'tahun' => $tahun, 
          'tanggal' => date('d-m-Y'),
        //   'user' => $users->username,
      ]);
    }

    public function title(): string
    {
        return 'Perusahaan Unfilled' ;
    }
    
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}
