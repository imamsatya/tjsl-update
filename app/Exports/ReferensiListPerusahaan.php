<?php
namespace App\Exports;

use App\Models\Perusahaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiListPerusahaan implements FromView , WithTitle
{
     public function view(): View
    {
        return view('pumk.upload_data_mitra.referensi_list_perusahaan', [
            // 'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get(),
             'listPerusahaan' => Perusahaan::where('is_active', true)->orderBy('id', 'asc')->get(),
        ]);
    }

    public function title(): string
    {
        return 'Referensi BUMN' ;
    }
}
?>