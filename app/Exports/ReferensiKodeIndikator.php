<?php
namespace App\Exports;

use App\Models\KodeIndikator;
use App\Models\VersiPilar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReferensiKodeIndikator implements FromView , WithTitle, WithColumnFormatting
{   
    public function view(): View
    {
        $versi = VersiPilar::orderBy('status')->orderBy('tanggal_akhir','desc')->first();
        $kode = KodeIndikator::Select('kode_indikators.*','tpbs.no_tpb','tpbs.nama')
                    ->leftJoin('relasi_tpb_kode_indikators','relasi_tpb_kode_indikators.kode_indikator_id','kode_indikators.id')
                    ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','relasi_tpb_kode_indikators.relasi_pilar_tpb_id')
                    ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                    ->where('relasi_pilar_tpbs.versi_pilar_id',$versi->id)
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

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT
        ];
    }
}
?>