<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\MitraBinaanTemplateExport;

class MitraBinaanTemplateExcelSheet implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($perusahaan, $tahun, $periode){
        $this->perusahaan = $perusahaan ;
        $this->tahun = $tahun;
        $this->periode = $periode;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new MitraBinaanTemplateExport($this->perusahaan, $this->tahun, $this->periode);
        // $sheets[] = new ReferensiPerusahaan();
        $sheets[] = new ReferensiProvinsi();
        $sheets[] = new ReferensiKota();
        $sheets[] = new ReferensiSektorUsaha();
        // $sheets[] = new ReferensiSkalaUsaha();
        // $sheets[] = new ReferensiCaraPenyaluran();
        $sheets[] = new ReferensiKolektibilitasPendanaan();
        $sheets[] = new ReferensiKondisiPinjaman();
        // $sheets[] = new ReferensiJenisPembayaran();
        // $sheets[] = new ReferensiBankAccount();
        // $sheets[] = new ReferensiTambahanPendanaan();
        return $sheets;
    }
}
?>