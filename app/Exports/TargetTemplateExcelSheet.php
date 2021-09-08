<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\TargetTemplateExport;

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
        $sheets[] = new ReferensiTpb();
        $sheets[] = new ReferensiKodeIndikator();
        $sheets[] = new ReferensiCoreSubject();
        $sheets[] = new ReferensiPerusahaan();
        $sheets[] = new ReferensiCaraPenyaluran();
        // $sheets[] = new ReferensiSatuanUkur();
        return $sheets;
    }
}
?>