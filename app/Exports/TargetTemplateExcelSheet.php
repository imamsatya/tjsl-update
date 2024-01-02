<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TargetTemplateExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($perusahaan,$filter_tahun){
        $this->perusahaan = $perusahaan ;
        $this->tahun = $filter_tahun ;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new TargetTemplateExport($this->perusahaan,$this->tahun);
        $sheets[] = new ReferensiJenisProgram();
        $sheets[] = new ReferensiCoreSubject();
        $sheets[] = new ReferensiTpb();
        $sheets[] = new ReferensiKodeTujuanTpb();
        $sheets[] = new ReferensiKodeIndikator();
        $sheets[] = new ReferensiCaraPenyaluran();
        $sheets[] = new ReferensiPerusahaan();
        $sheets[] = new ReferensiOwner();
        // $sheets[] = new ReferensiSatuanUkur();
        return $sheets;
    }
}
?>