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

class LaporanRealisasiGagalUploadExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($perusahaan,$bulan,$tahun, $id_laporan){
        $this->perusahaan = $perusahaan ;
        $this->bulan = $bulan ;
        $this->tahun = $tahun ;
        $this->id_laporan = $id_laporan;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new LaporanRealisasiGagalUploadExport($this->perusahaan,$this->bulan,$this->tahun, $this->id_laporan);
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