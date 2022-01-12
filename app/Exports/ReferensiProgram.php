<?php
namespace App\Exports;

use App\Models\TargetTpb;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use DB;

class ReferensiProgram implements FromView , WithTitle
{
    public function __construct($perusahaan,$bulan,$tahun){
        $this->perusahaan = $perusahaan;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $is_finish = Status::whereRaw("lower(replace(nama,' ','')) =?","finish")->pluck('id')->first();
        $target_tpb = TargetTpb::select('target_tpbs.*','anggaran_tpbs.tahun')
                                ->leftJoin('anggaran_tpbs','anggaran_tpbs.id','target_tpbs.anggaran_tpb_id')
                                ->where('anggaran_tpbs.perusahaan_id',$this->perusahaan->id)
                                ->where('target_tpbs.status_id',$is_finish);

        // if($this->bulan){
        //     $target_tpb = $target_tpb->where('extract(month from created_at)', '=',$this->bulan);
        // }                                
        if($this->tahun){
            $target_tpb = $target_tpb->where('anggaran_tpbs.tahun', '=',$this->tahun);
        }
        $target_tpb = $target_tpb->get();
                           
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