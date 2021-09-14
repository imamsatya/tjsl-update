<?php
namespace App\Exports;

use App\Models\SkalaUsaha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiSkalaUsaha implements FromView , WithTitle
{
     public function view(): View
    {
        return view('pumk.upload_data_mitra.referensi_skala_usaha', [
            'skala_usaha' => SkalaUsaha::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Skala Usaha' ;
    }
}
?>