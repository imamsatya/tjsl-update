<?php
namespace App\Exports;

use App\Models\KodeTujuanTpb;
use App\Models\VersiPilar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReferensiKodeTujuanTpb implements FromView , WithTitle, WithColumnFormatting
{   
    public function view(): View
    {

        $versi = VersiPilar::orderBy('status')->orderBy('tanggal_akhir','desc')->first();
        $kode = KodeTujuanTpb::Select('kode_tujuan_tpbs.*','tpbs.no_tpb','tpbs.nama')
                    ->leftJoin('relasi_tpb_kode_tujuan_tpbs','relasi_tpb_kode_tujuan_tpbs.kode_tujuan_tpb_id','kode_tujuan_tpbs.id')
                    ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','relasi_tpb_kode_tujuan_tpbs.relasi_pilar_tpb_id')
                    ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                    ->where('relasi_pilar_tpbs.versi_pilar_id',$versi->id)
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

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT
        ];
    }
}
?>