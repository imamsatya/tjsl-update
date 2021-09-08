<?php
namespace App\Exports;

use App\Models\KodeIndikator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKodeIndikator implements FromView , WithTitle
{
     public function view(): View
    {
        return view('target.administrasi.referensi_kode_indikator', [
            'kode_indikator' => KodeIndikator::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kode Indikator' ;
    }
}
?>