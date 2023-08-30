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
use App\Models\LaporanRealisasiBulananUploadGagal;
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
        foreach ($row as $ar) {
         
            $is_gagal = false;
            
            $no = (int) rtrim($ar['no']);
            $val_program = (int) rtrim($ar['id_program_sheet_referensi_program']);
            $val_nama_kegiatan = rtrim($ar['nama_kegiatan']);
            $val_jenis_kegiatan = $ar['id_jenis_kegiatan_sheet_referensi_jenis_kegiatan'] ? (int) rtrim($ar['id_jenis_kegiatan_sheet_referensi_jenis_kegiatan']) : null;
            $val_sub_kegiatan =$ar['id_sub_kegiatan_sheet_referensi_sub_kegiatan'] ?  (int) rtrim($ar['id_sub_kegiatan_sheet_referensi_sub_kegiatan']) : null;
            // $val_keterangan_kegiatan = rtrim($ar['keterangan_kegiatan']);
            $val_provinsi = (int) rtrim($ar['id_provinsi_sheet_referensi_provinsi']);
            $val_kabupaten = (int) rtrim($ar['id_kabupatenkota_sheet_referensi_kota']);
            $val_realisasi_anggaran = rtrim($ar['realisasi_anggaran']);
            $val_satuan_ukur = (int) rtrim($ar['id_satuan_ukur_sheet_referensi_satuan_ukur']);
            $val_realisasi_indikator = rtrim($ar['realisasi_indikator']);  
               

            // eksekusi data kalau kolom nomornya terisi angka
         
            if($no > 0) {

                // cek target tpb/program
                $program = TargetTpb::find($val_program);
                if(!$program){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Program tidak sesuai referensi<br>';
                }

                // cek jenis kegiatan
                $jenis_kegiatan = null;
                if ($val_jenis_kegiatan) {
                    $jenis_kegiatan = JenisKegiatan::find($val_jenis_kegiatan);
                    if(!$jenis_kegiatan) {
                        $is_gagal = true;
                        $keterangan .= 'Baris '.$no.' Data Jenis Kegiatan tidak sesuai referensi<br>';
                    }
                }
               

                // cek provinsi 
                $provinsi = Provinsi::find($val_provinsi);
                if(!$provinsi){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Provinsi tidak sesuai referensi<br>';
                }

                // cek kota
                $kota = Kota::find($val_kabupaten);
                if(!$kota){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Kota tidak sesuai referensi<br>';
                }

                // cek relasi provinsi kota
                $kotaProvinsi = Kota::where('id',$val_kabupaten)
                                    ->where('provinsi_id',$val_provinsi)
                                    ->first();
                if(!$kotaProvinsi){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Kota tidak sesuai Provinsi<br>';
                }
                
                // cek input angka numeric
                if(preg_match('/^[0-9]+$/', $val_realisasi_anggaran) !== 1) {
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Realisasi Anggaran harus angka<br>';
                }

                // cek realisasi anggaran tidak boleh kurang dari 0
                if((int) $val_realisasi_anggaran < 0) {
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Realisasi Anggaran tidak boleh negatif<br>';
                }

                // cek satuan ukur
                $ukur = SatuanUkur::find($val_satuan_ukur);
                if(!$ukur){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.$no.' Data Satuan Ukur tidak sesuai referensi<br>';
                }

                // simpan data gagal
                if($is_gagal){
                    try{
                        $realisasiGagal = LaporanRealisasiBulananUploadGagal::create([
                            'realisasi_upload_id' => $this->realisasi_upload,
                            'id_program' => $val_program,
                            'nama_kegiatan' => $val_nama_kegiatan,
                            'id_jenis_kegiatan' => $val_jenis_kegiatan,
                            'id_sub_kegiatan' => $val_sub_kegiatan,
                            'id_provinsi' => $val_provinsi,
                            'id_kabupaten' => $val_kabupaten,
                            'realisasi_anggaran' => $val_realisasi_anggaran,
                            'id_satuan_ukur' => $val_satuan_ukur,
                            'realisasi_indikator' => $val_realisasi_indikator
                        ]);
                        $gagal++;
                        DB::commit();
                    }catch(\Exception $e){
                        // dd($e->getMessage());
                        DB::rollback();
                    }
                } 

                // save data

                if(!$is_gagal) {
                    try{
                        $kegiatan = new Kegiatan();
                        $kegiatan->target_tpb_id = $program->id;
                        $kegiatan->kegiatan = $val_nama_kegiatan;
                        $kegiatan->provinsi_id = $provinsi->id;
                        $kegiatan->kota_id = $kota->id;
                        $kegiatan->indikator = $val_realisasi_indikator;
                        $kegiatan->satuan_ukur_id = $ukur->id;
                        $kegiatan->anggaran_alokasi = (int) $val_realisasi_anggaran;
                        $kegiatan->jenis_kegiatan_id = $jenis_kegiatan?->id;
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

                        $kegiatanRealisasi = new KegiatanRealisasi();
                        $kegiatanRealisasi->kegiatan_id = $kegiatan->id;
                        $kegiatanRealisasi->bulan = $this->bulan;
                        $kegiatanRealisasi->tahun = $this->tahun;
                        $kegiatanRealisasi->anggaran = $val_realisasi_anggaran;
                        $kegiatanRealisasi->anggaran_total = $kumulatif_anggaran;
                        $kegiatanRealisasi->status_id = 2;//in progress
                        $kegiatanRealisasi->save();

                        

                        KegiatanController::store_log($kegiatanRealisasi->id,$kegiatanRealisasi->status_id);
                        $berhasil++;
                        DB::commit();  

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
