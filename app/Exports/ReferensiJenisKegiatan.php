<?php
namespace App\Exports;

use App\Models\JenisProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\JenisKegiatan;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReferensiJenisKegiatan implements FromView , WithTitle, ShouldAutoSize
{
     public function view(): View
    {
        return view('laporan_realisasi.bulanan.kegiatan.referensi_jenis_kegiatan', [
            'jenis_kegiatan' => JenisKegiatan::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Jenis Kegiatan' ;
    }
}
?>