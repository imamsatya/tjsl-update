<?php
namespace App\Exports;

use App\Models\JenisProgram;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Models\JenisKegiatan;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class ReferensiJenisKegiatan implements FromView , WithTitle, ShouldAutoSize
{
     public function view(): View
    {
        return view('laporan_realisasi.bulanan.kegiatan.referensi_jenis_kegiatan', [
            'jenis_kegiatan' => JenisKegiatan::select(
                    'jenis_kegiatans.id  as jk_id', 'jenis_kegiatans.nama as jk_nama',
                    'sub_kegiatans.id as sk_id', 'sub_kegiatans.subkegiatan as sk_nama'
                )
                ->leftJoin('sub_kegiatans', 'sub_kegiatans.jenis_kegiatan_id', '=', 'jenis_kegiatans.id')
                ->orderBy('jenis_kegiatans.id')
                ->get()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Jenis Kegiatan' ;
    }
}
?>