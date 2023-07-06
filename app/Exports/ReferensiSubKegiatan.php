<?php
namespace App\Exports;

use App\Models\JenisProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\SubKegiatan;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReferensiSubKegiatan implements FromView , WithTitle, ShouldAutoSize
{
     public function view(): View
    {
        return view('laporan_realisasi.bulanan.kegiatan.referensi_sub_kegiatan', [
            'sub_kegiatan' => SubKegiatan::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Sub Kegiatan' ;
    }
}
?>