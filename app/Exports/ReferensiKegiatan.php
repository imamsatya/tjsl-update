<?php
namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKegiatan implements FromView , WithTitle
{
    public function view(): View
    {
        return view('realisasi.administrasi.referensi_kegiatan', [
            'kegiatan' => Kegiatan::get()
        ]);
    }

    public function title(): string
    {
        return 'Kegiatan Bulan Sebelumnya' ;
    }
}
?>