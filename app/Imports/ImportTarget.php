<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Models\AnggaranTpb;
use App\Models\TargetTpb;
use App\Models\Perusahaan;
use App\Models\TargetMitra;
use App\Models\TargetUpload;
use App\Models\TargetUploadGagal;
use App\Http\Controllers\Target\AdministrasiController;

class ImportTarget implements ToCollection, WithHeadingRow, WithMultipleSheets 
{

    public function __construct($nama_file,$target_upload,$perusahaan,$tahun){
        $this->nama_file = $nama_file;
        $this->target_upload = $target_upload;
        $this->perusahaan = $perusahaan;
        $this->tahun = $tahun;
    }
      public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $row)
    {
        $perusahaan = Perusahaan::where('nama_lengkap', $this->perusahaan)->first();
        $berhasil = 0;
        $gagal = 0;
        $keterangan = '';
        foreach ($row as $ar) {
            $anggaran = false;
            $target = false;
            $s_gagal = false;

            try{
                $anggaran = AnggaranTpb::select('anggaran_tpbs.id','relasi_pilar_tpbs.tpb_id')
                                    ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
                                    ->where('relasi_pilar_tpbs.tpb_id', rtrim($ar['id_tpb']))
                                    ->where('anggaran_tpbs.perusahaan_id', $perusahaan->id)
                                    ->where('anggaran_tpbs.tahun', $this->tahun)
                                    ->first();
            }catch(\Exception $e){
                DB::rollback();

                $gagal++;
                $s_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' Data TPB tidak ditemukan<br>';
            }

            if($anggaran){
                try{
                    $target = TargetTpb::create([
                        'anggaran_tpb_id' => $anggaran->id ,
                        'status_id' => 2,
                        'program' => rtrim($ar['program']) ,
                        'unit_owner' => rtrim($ar['unit_owner']) ,
                        'file_name' => $this->nama_file,
                        'jenis_program_id' => rtrim($ar['id_kriteria_program']) ,
                        'core_subject_id' => rtrim($ar['id_core_subject_iso_26000']) ,
                        'tpb_id' => rtrim($ar['id_tpb']) ,
                        'kode_indikator_id' => rtrim($ar['id_kode_indikator']) ,
                        'cara_penyaluran_id' => rtrim($ar['id_cara_penyaluran']) ,
                        'jangka_waktu' => rtrim($ar['jangka_waktu_penerapan_dalam_tahun']) ,
                        'anggaran_alokasi' => rtrim($ar['alokasi_anggaran_tahun_2021_dalam_rupiah']) ,
                    ]);
                    
                    AdministrasiController::store_log($target->id,$target->status_id);
                }catch(\Exception $e){
                    DB::rollback();

                    $gagal++;
                    $s_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' isian tidak sesuai Referensi<br>';
                }

                if($target){
                    try{
                        if($ar['id_mitra_bumn'] != ''){
                            $mitra = explode(",", $ar['id_mitra_bumn']);
                            $mitra = array_map('trim',$mitra);
                            $target->mitra_bumn()->sync($mitra);
                        }
                        DB::commit();
                        $berhasil++;
                    }catch(\Exception $e){
                        DB::rollback();
                        
                        $gagal++;
                        $s_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Mitra BUMN tidak ditemukan<br>';
                    }
                }
                
                if($s_gagal){
                    try{
                        $target = TargetUploadGagal::create([
                            'target_upload_id' => $this->target_upload,
                            'program' => rtrim($ar['program']) ,
                            'unit_owner' => rtrim($ar['unit_owner']),
                            'jenis_program_id' => rtrim($ar['id_kriteria_program']) ,
                            'core_subject_id'   => rtrim($ar['id_core_subject_iso_26000']) ,
                            'tpb_id' => rtrim($ar['id_tpb']) ,
                            'kode_indikator_id' => rtrim($ar['id_kode_indikator']) ,
                            'cara_penyaluran_id' => rtrim($ar['id_cara_penyaluran']) ,
                            'jangka_waktu' => rtrim($ar['jangka_waktu_penerapan_dalam_tahun']) ,
                            'anggaran_alokasi' => rtrim($ar['alokasi_anggaran_tahun_2021_dalam_rupiah']) ,
                        ]);
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollback();
                    }
                }   
            }
        }
        
        $target_upload = TargetUpload::find((int)$this->target_upload);
        $param['perusahaan_id'] = $perusahaan->id;
        $param['tahun'] = $this->tahun;
        $param['berhasil'] = $berhasil;
        $param['gagal'] = $gagal;
        $param['keterangan'] = $keterangan;
        $target_upload->update($param);
    }

    public function headingRow(): int
    {
        return 4;
    }




}
