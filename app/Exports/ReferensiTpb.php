<?php
namespace App\Exports;

use App\Models\Tpb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiTpb implements FromView , WithTitle
{   
    public function view(): View
    {
        $tpb = Tpb::get();

        return view('target.administrasi.referensi_tpb', [
            'tpb' => $tpb
        ]);
    }

    public function title(): string
    {
        return 'Referensi TPB' ;
    }
}
?>