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
use App\Models\JenisProgram;
use App\Models\CoreSubject;
use App\Models\KodeIndikator;
use App\Models\KodeTujuanTpb;
use App\Models\CaraPenyaluran;
use App\Models\OwnerProgram;
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
            $is_gagal = false;
            $param_alokasi = 'alokasi_anggaran_tahun_'.$this->tahun.'_dalam_rupiah';

            //cek kriteria program 
            try{
                $jenis_program = JenisProgram::find(rtrim($ar['id_kriteria_program']));
                if(!$jenis_program){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kriteria Program tidak sesuai referensi<br>';
                }
            }catch(\Exception $e){
                DB::rollback();
                $is_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kriteria Program tidak sesuai referensi<br>';
            }

            // cek input angka numeric
            if(!$is_gagal){
                if(!is_numeric($ar['jangka_waktu_penerapan_dalam_tahun'])){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Jangka Waktu harus angka<br>';
                }
                if(!is_numeric($ar[$param_alokasi])){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Alokasi Anggaran harus angka<br>';
                }
            }

            //cek id owner
            if(!$is_gagal && rtrim($ar['id_owner'])!=''){
                $own_ref = OwnerProgram::where('nama','TJSL')->orWhere('nama','tjsl')->pluck('id')->first();
                    if(rtrim($ar['id_owner']) >  $own_ref){
                       $unit = rtrim($ar['unit_owner']) == ''? true : false;
                       if($unit){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Jika ID Owner Non-TJSL, maka Unit Owner Wajib Diisi.<br>';                        
                       }
                    }
            }

            //cek core subject 
            if(!$is_gagal && rtrim($ar['id_core_subject_iso_26000'])!=''){
                try{
                    $core_subject = CoreSubject::find(rtrim($ar['id_core_subject_iso_26000']));
                    if(!$core_subject){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Core Subject tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Core Subject tidak sesuai referensi<br>';
                }
            }

            //cek kode indikator
            if(!$is_gagal && rtrim($ar['id_kode_indikator'])!=''){
                try{
                    $kode = KodeIndikator::leftJoin('relasi_tpb_kode_indikators','relasi_tpb_kode_indikators.kode_indikator_id','kode_indikators.id')
                                        ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','relasi_tpb_kode_indikators.relasi_pilar_tpb_id')
                                        ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                                        ->where('kode_indikators.id',rtrim($ar['id_kode_indikator']))
                                        ->where('tpbs.id', rtrim($ar['id_tpb']))
                                        ->first();
                    if(!$kode){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kode Indikator tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kode Indikator tidak sesuai referensi<br>';
                }
            }

            //cek kode tujuan TPB
            if(!$is_gagal){
                try{
                    $kode = KodeTujuanTpb::leftJoin('relasi_tpb_kode_tujuan_tpbs','relasi_tpb_kode_tujuan_tpbs.kode_tujuan_tpb_id','kode_tujuan_tpbs.id')
                                        ->leftJoin('relasi_pilar_tpbs','relasi_pilar_tpbs.id','relasi_tpb_kode_tujuan_tpbs.relasi_pilar_tpb_id')
                                        ->leftJoin('tpbs','tpbs.id','relasi_pilar_tpbs.tpb_id')
                                        ->where('kode_tujuan_tpbs.id',rtrim($ar['id_kode_tujuan_tpb']))
                                        ->where('tpbs.id', rtrim($ar['id_tpb']))
                                        ->first();
                    if(!$kode){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kode Tujuan TPB tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kode Tujuan TPB tidak sesuai referensi<br>';
                }
            }

            //cek pelaksanaan program
            if(!$is_gagal){
                try{
                    $cara = CaraPenyaluran::find(rtrim($ar['id_pelaksanaan_program']));
                    if(!$cara){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Pelaksanaan Program tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Pelaksanaan Program tidak sesuai referensi<br>';
                }
            }

            //cek tpb anggaran
            if(!$is_gagal){
                try{
                    $anggaran = AnggaranTpb::select('anggaran_tpbs.id','relasi_pilar_tpbs.tpb_id')
                                        ->leftJoin('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', 'anggaran_tpbs.relasi_pilar_tpb_id')
                                        ->where('relasi_pilar_tpbs.tpb_id', rtrim($ar['id_tpb']))
                                        ->where('anggaran_tpbs.perusahaan_id', $perusahaan->id)
                                        ->where('anggaran_tpbs.tahun', $this->tahun)
                                        ->first();
                    if(!$anggaran){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Tidak tersedia anggaran pada TPB yang anda pilih.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data TPB tidak sesuai referensi<br>';
                }
            }
            
            //simpan data target tpb
            if(!$is_gagal){
                try{
                    $target = TargetTpb::create([
                        'anggaran_tpb_id' => $anggaran->id ,
                        'status_id' => 2,
                        'program' => rtrim($ar['program']) ,
                        'id_owner' => rtrim($ar['id_owner']) ,
                        'unit_owner' => rtrim($ar['unit_owner']) ,
                        'file_name' => $this->nama_file,
                        'jenis_program_id' => rtrim($ar['id_kriteria_program']) ,
                        'core_subject_id' => (rtrim($ar['id_core_subject_iso_26000'])?rtrim($ar['id_core_subject_iso_26000']):null),
                        'tpb_id' => rtrim($ar['id_tpb']) ,
                        'kode_indikator_id' => (rtrim($ar['id_kode_indikator'])?rtrim($ar['id_kode_indikator']):null),
                        'kode_tujuan_tpb_id' => rtrim($ar['id_kode_tujuan_tpb']) ,
                        'cara_penyaluran_id' => rtrim($ar['id_pelaksanaan_program']) ,
                        'jangka_waktu' => rtrim($ar['jangka_waktu_penerapan_dalam_tahun']) ,
                        'anggaran_alokasi' => rtrim($ar[$param_alokasi]) ,
                    ]);
                    
                    AdministrasiController::store_log($target->id,$target->status_id);
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' isian tidak sesuai Referensi<br>';
                }

                //cek mitra bumn
                if($target && !$is_gagal){
                    try{
                        if($ar['id_mitra_bumn'] != ''){
                            $mitra = explode(",", str_replace('.',',',$ar['id_mitra_bumn']));
                            $mitra = array_map('trim',$mitra);
                            
                            $mitra_count = Perusahaan::whereIn('id',$mitra)->count();
                            if($mitra_count == count($mitra)){
                                $target->mitra_bumn()->sync($mitra);
                            }else{
                                DB::rollback();
                                $is_gagal = true;
                                $keterangan .= 'Baris '.rtrim($ar['no']).' Data Mitra BUMN tidak sesuai referensi<br>';
                            }
                        }
                        DB::commit();
                        $berhasil++;
                    }catch(\Exception $e){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Mitra BUMN tidak sesuai referensi<br>';
                    }
                }
            }
                
            // simpan data gagal
            if($is_gagal){
                try{
                    $target = TargetUploadGagal::create([
                        'target_upload_id' => $this->target_upload,
                        'program' => rtrim($ar['program']) ,
                        'id_owner' => rtrim($ar['id_owner']),
                        'unit_owner' => rtrim($ar['unit_owner']),
                        'jenis_program_id' => rtrim($ar['id_kriteria_program']) ,
                        'core_subject_id'   => rtrim($ar['id_core_subject_iso_26000']) ,
                        'tpb_id' => rtrim($ar['id_tpb']) ,
                        'kode_indikator_id' => rtrim($ar['id_kode_indikator']) ,
                        'kode_tujuan_tpb_id' => rtrim($ar['id_kode_tujuan_tpb']) ,
                        'cara_penyaluran_id' => rtrim($ar['id_pelaksanaan_program']) ,
                        'mitra_bumn_id' => rtrim($ar['id_mitra_bumn']) ,
                        'jangka_waktu' => rtrim($ar['jangka_waktu_penerapan_dalam_tahun']) ,
                        'anggaran_alokasi' => rtrim($ar[$param_alokasi]) ,
                    ]);
                    $gagal++;
                    DB::commit();
                }catch(\Exception $e){dd($e->getMessage());
                    DB::rollback();
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
