<?php
namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKegiatan implements FromView , WithTitle
{
    public function __construct($perusahaan){
        $this->perusahaan = $perusahaan;
    }

    public function view(): View
    {
        $kegiatan = Kegiatan::select('kegiatans.*')
                                ->leftJoin('target_tpbs','target_tpbs.id','kegiatans.target_tpb_id')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                                ->where('anggaran_tpbs.perusahaan_id',$this->perusahaan->id)
                                ->get();
                                
        return view('realisasi.administrasi.referensi_kegiatan', [
            'kegiatan' => $kegiatan
        ]);
    }

    public function title(): string
    {
        return 'Kegiatan Bulan Sebelumnya' ;
    }
}
?>