<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TargetGagalExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($target,$perusahaan,$tahun){
        $this->target = $target ;
        $this->perusahaan = $perusahaan ;
        $this->tahun = $tahun ;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new TargetGagalExport($this->target,$this->perusahaan,$this->tahun);
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