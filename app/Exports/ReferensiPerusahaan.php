<?php
namespace App\Exports;

use App\Models\Perusahaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiPerusahaan implements FromView , WithTitle
{
     public function view(): View
    {
        return view('target.administrasi.referensi_perusahaan', [
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
        ]);
    }

    public function title(): string
    {
        return 'Referensi BUMN' ;
    }
}
?>