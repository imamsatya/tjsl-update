<?php
namespace App\Exports;

use App\Models\JenisPembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiJenisPembayaran implements FromView , WithTitle
{
     public function view(): View
    {
        return view('pumk.upload_data_mitra.referensi_jenis_pembayaran', [
            'jenis' => JenisPembayaran::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Jenis Pembayaran' ;
    }
}
?>