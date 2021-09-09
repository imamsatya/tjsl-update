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

class AnggaranPumkExport implements FromView , WithTitle, WithColumnFormatting
{
    const FORMAT_NUMBER_CUSTOM = '#,##0';
    public function __construct($anggaran_pumk,$tahun){
        $this->anggaran_pumk = $anggaran_pumk ;
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

      return view('pumk.anggaran.export', [
          'anggaran_pumk' => $this->anggaran_pumk, 
          'tahun' => $tahun, 
          'tanggal' => date('d-m-Y'),
        //   'user' => $users->username,
      ]);
    }

    public function title(): string
    {
        return 'Data Anggaran PUMK' ;
    }
    
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}
