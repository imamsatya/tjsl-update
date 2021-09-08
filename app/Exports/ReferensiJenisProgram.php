<?php
namespace App\Exports;

use App\Models\JenisProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiJenisProgram implements FromView , WithTitle
{
     public function view(): View
    {
        return view('target.administrasi.referensi_jenis_program', [
            'jenis_program' => JenisProgram::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kriteria Program' ;
    }
}
?>