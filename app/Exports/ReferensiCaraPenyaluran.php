<?php
namespace App\Exports;

use App\Models\CaraPenyaluran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiCaraPenyaluran implements FromView , WithTitle
{
     public function view(): View
    {
        return view('target.administrasi.referensi_cara_penyaluran', [
            'cara_penyaluran' => CaraPenyaluran::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Pelaksanaan Program' ;
    }
}
?>