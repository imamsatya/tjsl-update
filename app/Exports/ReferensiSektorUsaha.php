<?php
namespace App\Exports;

use App\Models\SektorUsaha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiSektorUsaha implements FromView , WithTitle
{
     public function view(): View
    {
        return view('pumk.upload_data_mitra.referensi_sektor_usaha', [
            'sektor_usaha' => SektorUsaha::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Sektor Usaha' ;
    }
}
?>