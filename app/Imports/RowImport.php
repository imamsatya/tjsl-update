<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;


class RowImport implements ToCollection, WithMultipleSheets, WithMappedCells
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct($nama_file = "", $target_upload = ""){
       $this->nama_file = $nama_file ;
       $this->target_upload = $target_upload ;
    }

    public function collection(Collection $row)
    {
            $perusahaan =  rtrim($row['perusahaan']);
            $tahun      =  substr(rtrim($row['tahun']),6);
            $ImportTarget = new ImportTarget($this->nama_file,$this->target_upload,$perusahaan,$tahun);
            $ImportTarget->sheets();
            Excel::import($ImportTarget, public_path('file_upload/target_tpb/'.$this->nama_file));

    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function mapping(): array
    {
        return [
            'perusahaan' => 'A2'
        ];
    }




}
