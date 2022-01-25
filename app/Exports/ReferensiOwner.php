<?php
namespace App\Exports;

use App\Models\OwnerProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiOwner implements FromView , WithTitle
{
     public function view(): View
    {
        return view('target.administrasi.referensi_owner', [
             'owner' => OwnerProgram::orderBy('id', 'asc')->get(),
        ]);
    }

    public function title(): string
    {
        return 'Referensi Owner Program' ;
    }
}
?>