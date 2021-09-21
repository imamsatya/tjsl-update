<?php
namespace App\Exports;

use App\Models\KodeIndikator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKodeIndikator implements FromView , WithTitle
{   
    public function view(): View
    {
        $kode = KodeIndikator::Select('kode_indikators.*','tpbs.no_tpb','tpbs.nama')
                    ->leftJoin('relasi_tpb_kode_indikators','relasi_tpb_kode_indikators.kode_indikator_id','kode_indikators.id')
                    ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','relasi_tpb_kode_indikators.relasi_pilar_tpb_id')
                    ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                    ->orderBy('kode_indikators.kode')
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