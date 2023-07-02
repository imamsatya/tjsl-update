<?php
namespace App\Exports;

use App\Models\TargetTpb;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class LaporanRealisasiReferensiProgram implements FromView , WithTitle, ShouldAutoSize
{
    public function __construct($perusahaan,$tahun){
        $this->perusahaan = $perusahaan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $perusahaan_id = $this->perusahaan->id;
        $tahun = $this->tahun;

        $program = DB::table('target_tpbs')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
        ->join('tpbs', 'tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
        ->select(
            'target_tpbs.*',
            'anggaran_tpbs.id as anggaran_tpb_id',
            'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
            'tpbs.id as tpb_id',
            'tpbs.jenis_anggaran'
        )
        ->get();

        return view('laporan_realisasi.bulanan.kegiatan.referensi_program', [
            'program' => $program
        ]);
    }

    public function title(): string
    {
        return 'Referensi Program' ;
    }
}
?>