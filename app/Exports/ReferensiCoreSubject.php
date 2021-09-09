<?php
namespace App\Exports;

use App\Models\CoreSubject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiCoreSubject implements FromView , WithTitle
{
     public function view(): View
    {
        return view('target.administrasi.referensi_core_subject', [
            'core_subject' => CoreSubject::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Core Subject' ;
    }
}
?>