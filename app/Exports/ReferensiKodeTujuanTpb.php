<?php
namespace App\Exports;

use App\Models\KodeTujuanTpb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKodeTujuanTpb implements FromView , WithTitle
{   
    public function view(): View
    {
        $kode = KodeTujuanTpb::Select('kode_tujuan_tpbs.*','tpbs.no_tpb','tpbs.nama')
                    ->leftJoin('relasi_tpb_kode_tujuan_tpbs','relasi_tpb_kode_tujuan_tpbs.kode_tujuan_tpb_id','kode_tujuan_tpbs.id')
                    ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','relasi_tpb_kode_tujuan_tpbs.relasi_pilar_tpb_id')
                    ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                    ->orderBy('kode_tujuan_tpbs.kode')
                    ->get();

        return view('target.administrasi.referensi_kode_tujuan_tpb', [
            'kode_tujuan_tpb' => $kode
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kode Tujuan TPB' ;
    }
}
?>