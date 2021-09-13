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


class KegiatanRowImport implements ToCollection, WithMultipleSheets, WithMappedCells
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct($nama_file = "", $realisasi_upload = ""){
       $this->nama_file = $nama_file ;
       $this->realisasi_upload = $realisasi_upload ;
    }

    public function collection(Collection $row)
    {
            $perusahaan =  rtrim($row['perusahaan']);
            $tahun      = rtrim($row['tahun']);
            $bulan      = rtrim($row['bulan']);
            $ImportKegiatan = new ImportKegiatan($this->nama_file,$this->realisasi_upload,$perusahaan,$tahun,$bulan);
            $ImportKegiatan->sheets();
            Excel::import($ImportKegiatan, public_path('file_upload/kegiatan/'.$this->nama_file));
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
            'perusahaan' => 'A2',
            'bulan'  => 'L3',
            'tahun'  => 'M3',
        ];
    }




}
