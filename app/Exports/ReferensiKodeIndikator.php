<?php
namespace App\Exports;

use App\Models\KodeIndikator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKodeIndikator implements FromView , WithTitle
{
    public function __construct($perusahaan){
        $this->perusahaan = $perusahaan ;
    }
    
    public function view(): View
    {
        $kode = KodeIndikator::Select('kode_indikators.*')
                    ->LeftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.tpb_id','kode_indikators.tpb_id')
                    ->LeftJoin('anggaran_tpbs','anggaran_tpbs.relasi_pilar_tpb_id','relasi_pilar_tpbs.id')
                    ->where('anggaran_tpbs.perusahaan_id', $this->perusahaan->id)
                    ->whereNotNull('kode_indikators.id')
                    ->orderBy('kode_indikators.id')
                    ->get();

        return view('target.administrasi.referensi_kode_indikator', [
            'kode_indikator' => $kode
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kode Indikator' ;
    }
}
?>