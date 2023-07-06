<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\LaporanRealisasiTemplateExport;
use App\Exports\ReferensiKegiatan;
use App\Exports\ReferensiSubKegiatan;
use App\Exports\ReferensiProgram;
use App\Exports\ReferensiProvinsi;
use App\Exports\ReferensiKota;
use App\Exports\ReferensiSatuanUkur;
use App\Exports\ReferensiJenisKegiatan;

class LaporanRealisasiTemplateExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($perusahaan,$bulan,$tahun){
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
        $sheets[] = new LaporanRealisasiTemplateExport($this->perusahaan,$this->bulan,$this->tahun);
        $sheets[] = new LaporanRealisasiReferensiProgram($this->perusahaan,$this->tahun);
        $sheets[] = new ReferensiJenisKegiatan();
        $sheets[] = new ReferensiSubKegiatan();
        $sheets[] = new ReferensiProvinsi();
        $sheets[] = new ReferensiKota();
        $sheets[] = new ReferensiSatuanUkur();
        return $sheets;
    }
}
?>