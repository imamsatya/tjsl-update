<?php
namespace App\Exports;

use App\Models\SatuanUkur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiSatuanUkur implements FromView , WithTitle
{
     public function view(): View
    {
        return view('target.administrasi.referensi_satuan_ukur', [
            'satuan_ukur' => SatuanUkur::where('is_active', true)->get()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Satuan Ukur' ;
    }
}
?>