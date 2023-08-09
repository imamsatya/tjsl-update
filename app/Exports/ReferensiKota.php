<?php
namespace App\Exports;

use App\Models\Kota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKota implements FromView , WithTitle
{
     public function view(): View
    { 
        return view('pumk.upload_data_mitra.referensi_kota', [
            'kota' => Kota::select('kotas.*','provinsis.nama as provinsi')
            ->leftjoin('provinsis','provinsis.id','=','kotas.provinsi_id')
            // ->where('kotas.is_luar_negeri',false)->orderby('kotas.nama','asc')->get() 
            ->whereNotNull('kotas.provinsi_id')->orderby('kotas.nama','asc')->get() 
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kota' ;
    }
}
?>