<?php
namespace App\Exports;

use App\Models\SkalaUsaha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiTambahanPendanaan implements FromView , WithTitle
{
     public function view(): View
    {
        $data = [[
            'id' => 1,
            'nama' => 'Ya'
        ],
        [
            'id' => 2,
            'nama' => 'Tidak'
        ]];

        return view('pumk.upload_data_mitra.referensi_tambahan_pendanaan', [
            'data' => $data
        ]);
    }

    public function title(): string
    {
        return 'Referensi Tambahan Pendanaan' ;
    }
}
?>