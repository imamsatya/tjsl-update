<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\MitraBinaanTemplateExport;

class MitraBinaanTemplateExcelSheet implements WithMultipleSheets
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
        $sheets[] = new MitraBinaanTemplateExport($this->perusahaan);
        $sheets[] = new ReferensiPerusahaan();
        $sheets[] = new ReferensiProvinsi();
        $sheets[] = new ReferensiKota();
        $sheets[] = new ReferensiSektorUsaha();
        $sheets[] = new ReferensiSkalaUsaha();
        $sheets[] = new ReferensiCaraPenyaluran();
        $sheets[] = new ReferensiKolektibilitasPendanaan();
        $sheets[] = new ReferensiKondisiPinjaman();
        $sheets[] = new ReferensiJenisPembayaran();
        $sheets[] = new ReferensiBankAccount();
        return $sheets;
    }
}
?>