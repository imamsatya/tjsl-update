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
            $s_gagal = false;
            $param_alokasi = 'alokasi_anggaran_tahun_'.$this->tahun.'_rp';
            $param_target= 'target_bulan_'.$bulan;
            $param_realisasi= 'realisasi_bulan_'.$bulan;
            $param_anggaran= 'realisasi_anggaran_bulan_'.$bulan;

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
                }else{
                    DB::rollback();
                    $s_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' data realisasi bulan tersebut sudah ada<br>';
                }

            }catch(\Exception $e){dd($e->getMessage());
                DB::rollback();
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
                    
                    $s_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Mitra BUMN tidak ditemukan<br>';
                }
            }
            
            if($s_gagal){
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
        $realisasi_upload = RealisasiUpload::find((int)$this->realisasi_upload);
        $param['perusahaan_id'] = $perusahaan->id;
        $param['bulan'] = $this->bulan;
        $param['tahun'] = $this->tahun;
        $param['berhasil'] = $berhasil;
        $param['gagal'] = $gagal;
        $param['keterangan'] = $keterangan;
        $realisasi_upload->update($param);
    }

    public function headingRow(): int
    {
        return 4;
    }




}
