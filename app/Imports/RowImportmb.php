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
use App\Models\PumkMitraBinaan;
use App\Models\UploadPumkMitraBinaan;
use DB;

class RowImportmb implements ToCollection, WithMultipleSheets, WithMappedCells
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct($nama_file = "", $mb_upload = ""){
        $this->nama_file = $nama_file ;
        $this->mb_upload = $mb_upload ;
     }
 
     public function collection(Collection $row)
     {          

           // bulan berfungsi sebagai semester, possible value : 1 , 2
            $perusahaan =  rtrim($row['perusahaan']);
            $tahun      =  substr(rtrim($row['tahun']),10);
            
            $periode = substr($tahun, 9, 1);
            $position = strpos($tahun , "-");
            $tahunCut = substr($tahun, $position + 1);

            try {
                $data_perusahaan = DB::table('perusahaan_masters')->where('nama_lengkap', $perusahaan)->first();
                if($data_perusahaan) {
                    $cek_ketersediaan_data = PumkMitraBinaan::where('tahun', $tahunCut)->where('bulan', $periode)
                    ->where('perusahaan_id', $data_perusahaan->id)
                    ->first();

                    if($cek_ketersediaan_data) { // batalkan proses upload data
                        $data_upload = UploadPumkMitraBinaan::find($this->mb_upload);
                        $data_upload->delete();
                        throw new \Exception("Data sudah tersedia, tidak bisa upload data!");   
                    }
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400)->header('Content-Type', 'text/plain');
            }

            $Importmb = new ImportMb($this->nama_file,$this->mb_upload,$perusahaan,$tahun,$tahunCut, $periode);
            $Importmb->sheets();
            Excel::import($Importmb, public_path('file_upload/upload_mitra_binaan/'.$this->nama_file));
 
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
             'tahun'  => 'A3',
         ];
     }
 
 
}
