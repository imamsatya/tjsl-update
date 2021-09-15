<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RealisasiGagalExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($kegiatan,$perusahaan,$bulan,$tahun){
        $this->kegiatan = $kegiatan ;
        $this->perusahaan = $perusahaan ;
        $this->bulan = $bulan ;
        $this->tahun = $tahun ;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new RealisasiGagalExport($this->kegiatan,$this->perusahaan,$this->bulan,$this->tahun);
        $sheets[] = new ReferensiKegiatan();
        $sheets[] = new ReferensiProgram();
        $sheets[] = new ReferensiProvinsi();
        $sheets[] = new ReferensiKota();
        $sheets[] = new ReferensiSatuanUkur();
        return $sheets;
    }
}
?>