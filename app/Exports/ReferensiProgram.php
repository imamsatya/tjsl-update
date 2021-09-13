<?php
namespace App\Exports;

use App\Models\TargetTpb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiProgram implements FromView , WithTitle
{
     public function view(): View
    {
        return view('realisasi.administrasi.referensi_program', [
            'target_tpb' => TargetTpb::get()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Program' ;
    }
}
?>