<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\KegiatanTemplateExport;

class KegiatanTemplateExcelSheet implements WithMultipleSheets
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
        $sheets[] = new KegiatanTemplateExport($this->perusahaan,$this->bulan,$this->tahun);
        $sheets[] = new ReferensiKegiatan($this->perusahaan);
        $sheets[] = new ReferensiProgram($this->perusahaan,$this->bulan,$this->tahun);
        $sheets[] = new ReferensiProvinsi();
        $sheets[] = new ReferensiKota();
        $sheets[] = new ReferensiSatuanUkur();
        return $sheets;
    }
}
?>