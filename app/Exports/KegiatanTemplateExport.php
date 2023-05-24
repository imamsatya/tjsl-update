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

class KegiatanTemplateExport implements FromView , WithTitle
{
    public function __construct($perusahaan,$bulan,$tahun){
        $this->perusahaan = $perusahaan;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {   
        if($this->bulan == 1){
            $bulan = 'Januari';
        }else if($this->bulan == 2){
            $bulan = 'Februari';
        }else if($this->bulan == 3){
            $bulan = 'Maret';
        }else if($this->bulan == 4){
            $bulan = 'April';
        }else if($this->bulan == 5){
            $bulan = 'Mei';
        }else if($this->bulan == 6){
            $bulan = 'Juni';
        }else if($this->bulan == 7){
            $bulan = 'Juli';
        }else if($this->bulan == 8){
            $bulan = 'Agustus';
        }else if($this->bulan == 9){
            $bulan = 'September';
        }else if($this->bulan == 10){
            $bulan = 'Oktober';
        }else if($this->bulan == 11){
            $bulan = 'November';
        }else if($this->bulan == 12){
            $bulan = 'Desember';
        }
      return view('realisasi.administrasi.template', [
          'bulan' => $this->bulan, 
          'tahun' => $this->tahun, 
          'bulan_string' => $bulan, 
          'perusahaan' => $this->perusahaan, 
          'tanggal' => date('d-m-Y'),
      ]);
    }

    public function title(): string
    {
        return 'Input Kegiatan' ;
    }
    
}
