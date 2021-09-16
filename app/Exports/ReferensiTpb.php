<?php
namespace App\Exports;

use App\Models\Tpb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiTpb implements FromView , WithTitle
{
    public function __construct($perusahaan){
        $this->perusahaan = $perusahaan ;
    }
    
    public function view(): View
    {
        $tpb = Tpb::Select('tpbs.*')
                    ->LeftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.tpb_id','tpbs.id')
                    ->LeftJoin('anggaran_tpbs','anggaran_tpbs.relasi_pilar_tpb_id','relasi_pilar_tpbs.id')
                    ->where('anggaran_tpbs.perusahaan_id', $this->perusahaan->id)
                    ->get();

        return view('target.administrasi.referensi_tpb', [
            'tpb' => $tpb
        ]);
    }

    public function title(): string
    {
        return 'Referensi TPB' ;
    }
}
?>