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
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\KodeIndikator;
use App\Models\SatuanUkur;
use App\Models\Perusahaan;
use App\Models\Kegiatan;
use App\Models\KegiatanRealisasi;
use App\Models\TargetMitra;
use App\Models\RealisasiUpload;
use App\Models\JenisKegiatan;
use App\Models\SubKegiatan;
use App\Models\RealisasiUploadGagal;
use App\Models\LaporanRealisasiBulananUpload;
use App\Http\Controllers\LaporanRealisasi\Bulanan\KegiatanController;
use App\Http\Controllers\Realisasi\AdministrasiController;

class ImportLaporanRealisasiKegiatanBulanan implements ToCollection, WithHeadingRow, WithMultipleSheets 
{

    public function __construct($nama_file,$realisasi_upload,$perusahaan,$tahun,$bulan){
        $this->nama_file = $nama_file;
        $this->realisasi_upload = $realisasi_upload;
        $this->perusahaan = $perusahaan;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }
    
    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $row)
    {
        $berhasil = 0;
        $gagal = 0;
        $keterangan = '';
        $range_jenis_anggaran = ['1', '2', 'CID', 'non CID'];
        foreach ($row as $ar) {
         
            $is_gagal = false;
            
            $no = (int) rtrim($ar['no']);
            $val_jenis_anggaran = rtrim($ar['jenis_anggaran_1_cid_2_non_cid']);            
            $val_program = (int) rtrim($ar['id_program_sheet_referensi_program']);
            $val_nama_kegiatan = rtrim($ar['nama_kegiatan']);
            $val_jenis_kegiatan = $ar['id_jenis_kegiatan_sheet_referensi_jenis_kegiatan'] ? (int) rtrim($ar['id_jenis_kegiatan_sheet_referensi_jenis_kegiatan']) : null;
            $val_sub_kegiatan =$ar['id_sub_kegiatan_sheet_referensi_sub_kegiatan'] ?  (int) rtrim($ar['id_sub_kegiatan_sheet_referensi_sub_kegiatan']) : null;
            // $val_keterangan_kegiatan = rtrim($ar['keterangan_kegiatan']);
            $val_provinsi = (int) rtrim($ar['id_provinsi_sheet_referensi_provinsi']);
            $val_kabupaten = (int) rtrim($ar['id_kabupatenkota_sheet_referensi_kota']);
            $val_realisasi_anggaran = (int) rtrim($ar['realisasi_anggaran']);
            $val_satuan_ukur = (int) rtrim($ar['id_satuan_ukur_sheet_referensi_satuan_ukur']);
            $val_realisasi_indikator = rtrim($ar['realisasi_indikator']);  
               

            // eksekusi data kalau kolom nomornya terisi angka
            
            if( $no > 0) {
                // cek jenis anggaran
                try {
                    if(!in_array($val_jenis_anggaran, $range_jenis_anggaran)) {
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Jenis Anggaran tidak sesuai referensi<br/>';
                    }

                } catch (\Exception $e) {
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Jenis Anggaran tidak sesuai referensi<br/>';
                }

                // cek target tpb/program
                try{
                    $program = TargetTpb::find($val_program);
                    if(!$program){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Program tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Program tidak sesuai referensi<br>';
                }

                // cek jenis kegiatan
                $jenis_kegiatan = null;
                if ($val_jenis_kegiatan != null) {
                    try {
                        $jenis_kegiatan = JenisKegiatan::find($val_jenis_kegiatan);
                        if(!$jenis_kegiatan) {
                            DB::rollback();
                            $is_gagal = true;
                            $keterangan .= 'Baris '.$no.' Data Jenis Kegiatan tidak sesuai referensi<br>';
                        }
                    } catch (\Exception $e) {
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Jenis Kegiatan tidak sesuai referensi<br>';
                    }
                }
               

                // cek provinsi 
                if(!$is_gagal){
                    try{
                        $provinsi = Provinsi::find($val_provinsi);
                        if(!$provinsi){
                            DB::rollback();
                            $is_gagal = true;
                            $keterangan .= 'Baris '.$no.' Data Provinsi tidak sesuai referensi<br>';
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Provinsi tidak sesuai referensi<br>';
                    }
                }

                // cek kota
                if(!$is_gagal){
                    try{
                        $kota = Kota::find($val_kabupaten);
                        if(!$kota){
                            DB::rollback();
                            $is_gagal = true;
                            $keterangan .= 'Baris '.$no.' Data Kota tidak sesuai referensi<br>';
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Kota tidak sesuai referensi<br>';
                    }
                }

                // cek relasi provinsi kota
                if(!$is_gagal){
                    try{
                        $kota = Kota::where('id',$val_kabupaten)
                                    ->where('provinsi_id',$val_provinsi)
                                    ->first();
                        if(!$kota){
                            DB::rollback();
                            $is_gagal = true;
                            $keterangan .= 'Baris '.$no.' Data Kota tidak sesuai Provinsi<br>';
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Kota tidak sesuai Provinsi<br>';
                    }
                }
                
                // cek input angka numeric
                if(!$is_gagal){
                    if(!is_int($val_realisasi_anggaran)){
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Realisasi Anggaran harus angka<br>';
                    }
                }

                // cek satuan ukur
                if(!$is_gagal){
                    try{
                        $ukur = SatuanUkur::find($val_satuan_ukur);
                        if(!$ukur){
                            DB::rollback();
                            $is_gagal = true;
                            $keterangan .= 'Baris '.$no.' Data Satuan Ukur tidak sesuai referensi<br>';
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Satuan Ukur tidak sesuai referensi<br>';
                    }
                }

                // simpan data gagal
                // if($is_gagal){
                //     try{
                //         $realisasi = RealisasiUploadGagal::create([
                //             'realisasi_upload_id' => $this->realisasi_upload,
                //             'target_tpb_id' => rtrim($ar['id_program']) ,
                //             'kegiatan' => rtrim($ar['kegiatan']) ,
                //             'provinsi_id' => rtrim($ar['id_provinsi_kegiatan']) ,
                //             'kota_id' => rtrim($ar['id_kabupaten_kotamadya_kegiatan']) ,
                //             'indikator' => rtrim($ar['indikator_capaian_kegiatan']) ,
                //             'satuan_ukur_id' => rtrim($ar['id_satuan_ukur']) ,
                //             'anggaran_alokasi' => rtrim($ar[$param_alokasi]) ,
                //             'bulan' => $this->bulan,
                //             'tahun' => $this->tahun,
                //             'target' => rtrim($ar[$param_target]),
                //             'realisasi' => rtrim($ar[$param_realisasi]),
                //             'anggaran' => rtrim($ar[$param_anggaran]),
                //         ]);
                //         $gagal++;
                //         DB::commit();
                //     }catch(\Exception $e){dd($e->getMessage());
                //         DB::rollback();
                //     }
                // } 

                // save data
               
                if(!$is_gagal){
                    try{
                        
                        $kegiatan = new Kegiatan();
                        $kegiatan->target_tpb_id = $program->id;
                        $kegiatan->kegiatan = $val_nama_kegiatan;
                        $kegiatan->provinsi_id = $provinsi->id;
                        $kegiatan->kota_id = $kota->id;
                        $kegiatan->indikator = $val_realisasi_indikator;
                        $kegiatan->satuan_ukur_id = $ukur->id;
                        $kegiatan->anggaran_alokasi = $val_realisasi_anggaran;
                        $kegiatan->jenis_kegiatan_id = $jenis_kegiatan != null ? $jenis_kegiatan->id : null;
                        $kegiatan->keterangan_kegiatan = $val_sub_kegiatan;
                        $kegiatan->save();

                        $kegiatanGroup = Kegiatan::where('kegiatan', $val_nama_kegiatan)
                        ->where('target_tpb_id', $program->id)
                        ->join('kegiatan_realisasis', 'kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                        ->orderBy('kegiatan_realisasis.bulan', 'desc')
                        ->first();

                        $kumulatif_anggaran = $val_realisasi_anggaran;
                        if ($kegiatanGroup) {
                            $kumulatif_anggaran = $kumulatif_anggaran + $kegiatanGroup->anggaran_total;
                        }

                        // $tes = Kegiatan::find($kegiatan->id);
                        // dd($tes);

                        $kegiatanRealisasi = new KegiatanRealisasi();
                        $kegiatanRealisasi->kegiatan_id = $kegiatan->id;
                        $kegiatanRealisasi->bulan = $this->bulan;
                        $kegiatanRealisasi->tahun = $this->tahun;
                        // target,realisasi -> null
                        $kegiatanRealisasi->anggaran = $val_realisasi_anggaran;
                        $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran;
                        $kegiatanRealisasi->status_id = 2;//in progress
                        $kegiatanRealisasi->save();

                        

                        KegiatanController::store_log($kegiatanRealisasi->id,$kegiatanRealisasi->status_id);
                        $berhasil++;
                        DB::commit();  
                        // dd($kegiatanRealisasi);                  

                    } catch(\Exception $e){
                        dd($e->getMessage());
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Gagal insert data pada baris '.$no.'<br>';
                    }
                }

            } 
        }
        //update data realisasi upload
        $realisasi_upload = LaporanRealisasiBulananUpload::find((int)$this->realisasi_upload);
        $param['perusahaan_id'] = $this->perusahaan;
        $param['bulan'] = $this->bulan;
        $param['tahun'] = $this->tahun;
        $param['berhasil'] = $berhasil;
        $param['gagal'] = $gagal;
        $param['keterangan'] = $keterangan;
        $realisasi_upload->update($param);
        DB::commit();
    }

    public function headingRow(): int
    {
        return 4;
    }
}
