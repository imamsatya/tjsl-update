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
use App\Models\RealisasiUploadGagal;
use App\Http\Controllers\Realisasi\AdministrasiController;

class ImportKegiatan implements ToCollection, WithHeadingRow, WithMultipleSheets 
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
        $bulan = '';
        if($this->bulan == 1){
            $bulan = 'januari';
        }else if($this->bulan == 2){
            $bulan = 'februari';
        }else if($this->bulan == 3){
            $bulan = 'maret';
        }else if($this->bulan == 4){
            $bulan = 'april';
        }else if($this->bulan == 5){
            $bulan = 'mei';
        }else if($this->bulan == 6){
            $bulan = 'juni';
        }else if($this->bulan == 7){
            $bulan = 'juli';
        }else if($this->bulan == 8){
            $bulan = 'agustus';
        }else if($this->bulan == 9){
            $bulan = 'september';
        }else if($this->bulan == 10){
            $bulan = 'oktober';
        }else if($this->bulan == 11){
            $bulan = 'november';
        }else if($this->bulan == 12){
            $bulan = 'desember';
        }

        $perusahaan = Perusahaan::where('nama_lengkap', $this->perusahaan)->first();
        $berhasil = 0;
        $gagal = 0;
        $keterangan = '';
        foreach ($row as $ar) {
            $anggaran = false;
            $target = false;
            $is_gagal = false;
            $param_alokasi = 'alokasi_anggaran_tahun_'.$this->tahun.'_rp';
            $param_target= 'target_bulan_'.$bulan;
            $param_realisasi= 'realisasi_bulan_'.$bulan;
            $param_anggaran= 'realisasi_anggaran_bulan_'.$bulan;

            // cek target tpb/program
            try{
                $program = TargetTpb::find(rtrim($ar['id_program']));
                if(!$program){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Program tidak sesuai referensi<br>';
                }
            }catch(\Exception $e){
                DB::rollback();
                $is_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' Data Program tidak sesuai referensi<br>';
            }
            
            // cek input angka numeric
            if(!$is_gagal){
                if(!is_int($ar[$param_alokasi])){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Alokasi Anggaran harus angka<br>';
                }
                if(!is_int($ar[$param_anggaran])){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Realisasi Anggaran harus angka<br>';
                }
                if(!is_int($ar[$param_target])){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Target harus angka<br>';
                }
                if(!is_int($ar[$param_realisasi])){
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Realisasi harus angka<br>';
                }
            }

            // cek provinsi 
            if(!$is_gagal){
                try{
                    $provinsi = Provinsi::find(rtrim($ar['id_provinsi_kegiatan']));
                    if(!$provinsi){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Provinsi tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Provinsi tidak sesuai referensi<br>';
                }
            }

            // cek kota
            if(!$is_gagal){
                try{
                    $kota = Kota::find(rtrim($ar['id_kabupaten_kotamadya_kegiatan']));
                    if(!$kota){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota tidak sesuai referensi<br>';
                }
            }

            // cek relasi provinsi kota
            if(!$is_gagal){
                try{
                    $kota = Kota::where('id',rtrim($ar['id_kabupaten_kotamadya_kegiatan']))
                                ->where('provinsi_id',rtrim($ar['id_provinsi_kegiatan']))
                                ->first();
                    if(!$kota){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota tidak sesuai Provinsi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota tidak sesuai Provinsi<br>';
                }
            }

            // cek satuan ukur
            if(!$is_gagal){
                try{
                    $ukur = SatuanUkur::find(rtrim($ar['id_satuan_ukur']));
                    if(!$ukur){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Satuan Ukur tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Satuan Ukur tidak sesuai referensi<br>';
                }
            }

            // cek kegiatan
            if(!$is_gagal && is_numeric($ar['indikator_capaian_kegiatan'])){
                try{
                    $kegiatan = Kegiatan::where('target_tpb_id',rtrim($ar['id_program']))
                                        ->where('kegiatan',rtrim($ar['kegiatan']))
                                        ->where('provinsi_id',rtrim($ar['id_provinsi_kegiatan']))
                                        ->where('kota_id',rtrim($ar['id_kabupaten_kotamadya_kegiatan']));
                    
                    if($kegiatan->count()>0){
                        $kegiatan = $kegiatan->first();
                        $kegiatan->update([
                            'indikator' => rtrim($ar['indikator_capaian_kegiatan']) ,
                            'satuan_ukur_id' => rtrim($ar['id_satuan_ukur']) ,
                            'anggaran_alokasi' => rtrim($ar[$param_alokasi]) ,
                        ]);
                    }else{
                        $kegiatan = Kegiatan::create([
                            'target_tpb_id' => rtrim($ar['id_program']) ,
                            'kegiatan' => rtrim($ar['kegiatan']) ,
                            'provinsi_id' => rtrim($ar['id_provinsi_kegiatan']) ,
                            'kota_id' => rtrim($ar['id_kabupaten_kotamadya_kegiatan']) ,
                            'indikator' => rtrim($ar['indikator_capaian_kegiatan']) ,
                            'satuan_ukur_id' => rtrim($ar['id_satuan_ukur']) ,
                            'anggaran_alokasi' => rtrim($ar[$param_alokasi]) ,
                        ]);
                    }

                    $realisasi = KegiatanRealisasi::where('kegiatan_id',$kegiatan->id)
                                            ->where('bulan', $this->bulan)
                                            ->where('tahun', $this->tahun)
                                            ->get();

                    if($realisasi->count()==0){
                        $realisasi = KegiatanRealisasi::create([
                            'kegiatan_id' => $kegiatan->id,
                            'bulan' => $this->bulan,
                            'tahun' => $this->tahun,
                            'status_id' => 2,
                            'target' => rtrim($ar[$param_target]),
                            'realisasi' => rtrim($ar[$param_realisasi]),
                            'anggaran' => rtrim($ar[$param_anggaran]),
                            'file_name' => $this->nama_file,
                        ]);

                        $realisasi_total = KegiatanRealisasi::select(DB::Raw('sum(kegiatan_realisasis.anggaran) as total'))
                                                            ->where('kegiatan_id',$kegiatan->id)
                                                            ->where('bulan','<',$this->bulan)
                                                            ->where('tahun',$this->tahun)
                                                            ->first();
                        $paramr['anggaran_total'] = (int)$realisasi_total->total + $realisasi->anggaran;
                        $realisasi->update((array)$paramr);

                        AdministrasiController::store_log($realisasi->id,$realisasi->status_id);
                        $berhasil++;
                        DB::commit();
                    }else{
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' data realisasi bulan tersebut sudah ada<br>';
                    }

                }catch(\Exception $e){dd($e->getMessage());
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' isian tidak sesuai Referensi<br>';
                }
            }else{
                DB::rollback();
                $is_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' isian Indikator Capaian Kegiatan Harus Angka <br>';                
            }

            // simpan data gagal
            if($is_gagal){
                try{
                    $realisasi = RealisasiUploadGagal::create([
                        'realisasi_upload_id' => $this->realisasi_upload,
                        'target_tpb_id' => rtrim($ar['id_program']) ,
                        'kegiatan' => rtrim($ar['kegiatan']) ,
                        'provinsi_id' => rtrim($ar['id_provinsi_kegiatan']) ,
                        'kota_id' => rtrim($ar['id_kabupaten_kotamadya_kegiatan']) ,
                        'indikator' => rtrim($ar['indikator_capaian_kegiatan']) ,
                        'satuan_ukur_id' => rtrim($ar['id_satuan_ukur']) ,
                        'anggaran_alokasi' => rtrim($ar[$param_alokasi]) ,
                        'bulan' => $this->bulan,
                        'tahun' => $this->tahun,
                        'target' => rtrim($ar[$param_target]),
                        'realisasi' => rtrim($ar[$param_realisasi]),
                        'anggaran' => rtrim($ar[$param_anggaran]),
                    ]);
                    $gagal++;
                    DB::commit();
                }catch(\Exception $e){dd($e->getMessage());
                    DB::rollback();
                }
            } 
        }
        //update data realisasi upload
        $realisasi_upload = RealisasiUpload::find((int)$this->realisasi_upload);
        $param['perusahaan_id'] = $perusahaan->id;
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
