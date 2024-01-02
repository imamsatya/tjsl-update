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

class KegiatanExport implements FromView , WithTitle, WithColumnFormatting
{
    const FORMAT_NUMBER_CUSTOM = '#,##0';
    public function __construct($kegiatan,$tahun){
        $this->kegiatan = $kegiatan ;
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

      return view('realisasi.administrasi.export', [
          'kegiatan' => $this->kegiatan, 
          'tahun' => $tahun, 
          'tanggal' => date('d-m-Y'),
        //   'user' => $users->username,
      ]);
    }

    public function title(): string
    {
        return 'Data Kegiatan' ;
    }
    
    public function columnFormats(): array
    {
        return [
            //'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }
}
