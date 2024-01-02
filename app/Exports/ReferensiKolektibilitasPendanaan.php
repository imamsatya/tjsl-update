<?php
namespace App\Exports;

use App\Models\KolekbilitasPendanaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKolektibilitasPendanaan implements FromView , WithTitle
{
     public function view(): View
    { 
        return view('pumk.upload_data_mitra.referensi_kolektibilitas', [
            'kolektibilitas' => KolekbilitasPendanaan::all() 
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kolektibilitas' ;
    }
}
?>