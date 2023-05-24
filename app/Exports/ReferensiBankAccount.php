<?php
namespace App\Exports;

use App\Models\BankAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiBankAccount implements FromView , WithTitle
{
     public function view(): View
    { 
        return view('pumk.upload_data_mitra.referensi_bank_account', [
            'bank' => BankAccount::all() 
        ]);
    }

    public function title(): string
    {
        return 'Referensi Bank Account' ;
    }
}
?>