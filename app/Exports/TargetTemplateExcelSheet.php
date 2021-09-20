<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TargetTemplateExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($perusahaan){
        $this->perusahaan = $perusahaan ;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new TargetTemplateExport($this->perusahaan);
        $sheets[] = new ReferensiJenisProgram();
        $sheets[] = new ReferensiCoreSubject();
        $sheets[] = new ReferensiTpb();
        $sheets[] = new ReferensiKodeTujuanTpb();
        $sheets[] = new ReferensiKodeIndikator();
        $sheets[] = new ReferensiCaraPenyaluran();
        $sheets[] = new ReferensiPerusahaan();
        // $sheets[] = new ReferensiSatuanUkur();
        return $sheets;
    }
}
?>