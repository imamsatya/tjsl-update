<?php
namespace App\Exports;

use App\Models\TargetTpb;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiProgram implements FromView , WithTitle
{
    public function __construct($perusahaan){
        $this->perusahaan = $perusahaan;
    }

    public function view(): View
    {
        $target_tpb = TargetTpb::select('target_tpbs.*')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                                ->where('anggaran_tpbs.perusahaan_id',$this->perusahaan->id)
                                ->get();
                                
        return view('realisasi.administrasi.referensi_program', [
            'target_tpb' => $target_tpb
        ]);
    }

    public function title(): string
    {
        return 'Referensi Program' ;
    }
}
?>