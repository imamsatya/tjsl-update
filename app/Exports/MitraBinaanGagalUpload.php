<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\MitraBinaanGagalUploadExport;

class MitraBinaanGagalUpload implements WithMultipleSheets
{
    use Exportable;
    
     public function __construct($kode){
        $this->kode = $kode ;
     }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new MitraBinaanGagalUploadExport($this->kode);
        return $sheets;
    }
}
?>