<?php
namespace App\Exports;

use App\Models\KondisiPinjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKondisiPinjaman implements FromView , WithTitle
{
     public function view(): View
    { 
        return view('pumk.upload_data_mitra.referensi_kondisi_pinjaman', [
            'kondisi' => KondisiPinjaman::all() 
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kondisi Pinjaman' ;
    }
}
?>