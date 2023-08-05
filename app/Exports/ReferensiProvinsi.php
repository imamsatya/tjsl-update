<?php
namespace App\Exports;

use App\Models\Provinsi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiProvinsi implements FromView , WithTitle
{
     public function view(): View
    {
        return view('pumk.upload_data_mitra.referensi_provinsi', [
            'provinsi' => Provinsi::orderby('nama','asc')->get()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Provinsi' ;
    }
}
?>