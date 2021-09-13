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
use PhpOffice\PhpSpreadsheet\Shared\Date;

use App\Models\PumkMitraBinaan;
use App\Models\Perusahaan;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\SektorUsaha;
use App\Models\CaraPenyaluran;
use App\Models\SkalaUsaha;
use App\Models\KolekbilitasPendanaan;
use App\Models\KondisiPinjaman;
use App\Models\JenisPembayaran;
use App\Models\BankAccount;
use App\Models\UploadPumkMitraBinaan;
use App\Models\UploadGagalPumkMitraBinaan;

class ImportMb implements ToCollection, WithHeadingRow, WithMultipleSheets 
{
    public function __construct($nama_file,$mb_upload,$perusahaan,$tahun){
        $this->nama_file = $nama_file;
        $this->mb_upload = $mb_upload;
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
        $update = 0;
        $gagal = 0;
        $keterangan = '';
       foreach ($row as $ar) {
       
            $cek_identitas = 0;
            $cek_kolektibilitas = 0;
            $s_gagal = false;

            $cek_identitas = PumkMitraBinaan::where('no_identitas',$ar['no_identitas'] )
                                 ->count();
            $cek_kolektibilitas = PumkMitraBinaan::where('kolektibilitas_id',(int)$ar['id_kolektibilitas_pendanaan'] )
                                 ->count();

            //buat data baru jika identitas & kolek belum ada
            if(($cek_identitas && $cek_kolektibilitas) == 0){
                try{
                    $mitra = PumkMitraBinaan::create([
                        'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                        'no_identitas' => rtrim($ar['no_identitas']),
                        'provinsi_id' => rtrim($ar['id_provinsi']),
                        'kota_id' => rtrim($ar['id_kota']),
                        'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                        'cara_penyaluran_id' => rtrim($ar['id_cara_penyaluran']),
                        'kolektibilitas_id' => rtrim($ar['id_kolektibilitas_pendanaan']),
                        'kondisi_pinjaman_id' => rtrim($ar['id_kondisi_pinjaman']),
                        'jenis_pembayaran_id' => rtrim($ar['id_jenis_pembayaran']),
                        'bank_account_id' => rtrim($ar['id_bank_account']),
                        'nilai_aset' => rtrim($ar['nilai_aset']),
                        'nilai_omset' => rtrim($ar['nilai_omset']),
                        'no_pinjaman' => rtrim($ar['no_pinjaman']),
                        'sumber_dana' => rtrim($ar['sumber_dana']),
                        'tgl_awal' => Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y'),
                        'tgl_jatuh_tempo' => Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y'),
                        'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                        'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                        'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                        'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                        'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                        'tgl_penerimaan_terakhir' => Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y'),
                        'jumlah_sdm' => rtrim($ar['sdm_di_mb']),
                        'kelebihan_angsuran' => rtrim($ar['kelebihan_angsuran']),
                        'subsektor' => rtrim($ar['subsektor']),
                        'hasil_produk_jasa' => rtrim($ar['produkjasa_yang_dihasilkan']),
                        'created_by_id' => \Auth::user()->id,
                        'perusahaan_id' => $perusahaan->id
                    ]);
                    $berhasil++;
                }catch(\Exception $e){
                    DB::rollback();

                    $gagal++;
                    $s_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' isian tidak sesuai Referensi<br>';
                }

                if($s_gagal){
                    try{
                        $mitra = UploadGagalPumkMitraBinaan::create([
                            'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                            'no_identitas' => rtrim($ar['no_identitas']),
                            'provinsi_id' => rtrim($ar['id_provinsi']),
                            'kota_id' => rtrim($ar['id_kota']),
                            'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                            'cara_penyaluran_id' => rtrim($ar['id_cara_penyaluran']),
                            'kolektibilitas_id' => rtrim($ar['id_kolektibilitas_pendanaan']),
                            'kondisi_pinjaman_id' => rtrim($ar['id_kondisi_pinjaman']),
                            'jenis_pembayaran_id' => rtrim($ar['id_jenis_pembayaran']),
                            'bank_account_id' => rtrim($ar['id_bank_account']),
                            'nilai_aset' => rtrim($ar['nilai_aset']),
                            'nilai_omset' => rtrim($ar['nilai_omset']),
                            'no_pinjaman' => rtrim($ar['no_pinjaman']),
                            'sumber_dana' => rtrim($ar['sumber_dana']),
                            'tgl_awal' => Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y'),
                            'tgl_jatuh_tempo' => Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y'),
                            'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                            'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                            'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                            'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                            'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                            'tgl_penerimaan_terakhir' => Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y'),
                            'jumlah_sdm' => rtrim($ar['sdm_di_mb']),
                            'kelebihan_angsuran' => rtrim($ar['kelebihan_angsuran']),
                            'subsektor' => rtrim($ar['subsektor']),
                            'hasil_produk_jasa' => rtrim($ar['produkjasa_yang_dihasilkan']),
                            'created_by_id' => \Auth::user()->id,
                            'perusahaan_id' => $perusahaan->id
                        ]);
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollback();
                    }
                }   
           }else{
               //update data
               try{
                    $mitra = PumkMitraBinaan::where('no_identitas',$ar['no_identitas'])
                        ->where('kolektibilitas_id',(int)$ar['kolektibilitas_pendanaan'])
                        ->update([
                        'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                        //'no_identitas' => rtrim($ar['no_identitas']), //no identitas primary 
                        'provinsi_id' => rtrim($ar['id_provinsi']),
                        'kota_id' => rtrim($ar['id_kota']),
                        'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                        'cara_penyaluran_id' => rtrim($ar['id_cara_penyaluran']),
                        'kolektibilitas_id' => rtrim($ar['id_kolektibilitas_pendanaan']),
                        'kondisi_pinjaman_id' => rtrim($ar['id_kondisi_pinjaman']),
                        'jenis_pembayaran_id' => rtrim($ar['id_jenis_pembayaran']),
                        'bank_account_id' => rtrim($ar['id_bank_account']),
                        'nilai_aset' => rtrim($ar['nilai_aset']),
                        'nilai_omset' => rtrim($ar['nilai_omset']),
                        'no_pinjaman' => rtrim($ar['no_pinjaman']),
                        'sumber_dana' => rtrim($ar['sumber_dana']),
                        'tgl_awal' => Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y'),
                        'tgl_jatuh_tempo' => Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y'),
                        'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                        'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                        'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                        'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                        'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                        'tgl_penerimaan_terakhir' => Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y'),
                        'jumlah_sdm' => rtrim($ar['sdm_di_mb']),
                        'kelebihan_angsuran' => rtrim($ar['kelebihan_angsuran']),
                        'subsektor' => rtrim($ar['subsektor']),
                        'hasil_produk_jasa' => rtrim($ar['produkjasa_yang_dihasilkan']),
                        'updated_by_id' => \Auth::user()->id,
                        'perusahaan_id' => $perusahaan->id
                    ]);

                    $update++;
                }catch(\Exception $e){
                    DB::rollback();

                    $gagal++;
                    $s_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' isian tidak sesuai Referensi<br>';
                }

                if($s_gagal){
                    try{
                        $mitra = UploadGagalPumkMitraBinaan::create([
                            'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                            'no_identitas' => rtrim($ar['no_identitas']),
                            'provinsi_id' => rtrim($ar['id_provinsi']),
                            'kota_id' => rtrim($ar['id_kota']),
                            'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                            'cara_penyaluran_id' => rtrim($ar['id_cara_penyaluran']),
                            'kolektibilitas_id' => rtrim($ar['id_kolektibilitas_pendanaan']),
                            'kondisi_pinjaman_id' => rtrim($ar['id_kondisi_pinjaman']),
                            'jenis_pembayaran_id' => rtrim($ar['id_jenis_pembayaran']),
                            'bank_account_id' => rtrim($ar['id_bank_account']),
                            'nilai_aset' => rtrim($ar['nilai_aset']),
                            'nilai_omset' => rtrim($ar['nilai_omset']),
                            'no_pinjaman' => rtrim($ar['no_pinjaman']),
                            'sumber_dana' => rtrim($ar['sumber_dana']),
                            'tgl_awal' => Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y'),
                            'tgl_jatuh_tempo' => Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y'),
                            'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                            'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                            'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                            'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                            'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                            'tgl_penerimaan_terakhir' => Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y'),
                            'jumlah_sdm' => rtrim($ar['sdm_di_mb']),
                            'kelebihan_angsuran' => rtrim($ar['kelebihan_angsuran']),
                            'subsektor' => rtrim($ar['subsektor']),
                            'hasil_produk_jasa' => rtrim($ar['produkjasa_yang_dihasilkan']),
                            'created_by_id' => \Auth::user()->id,
                            'perusahaan_id' => $perusahaan->id
                        ]);
                        DB::commit();
                    }catch(\Exception $e){
                        DB::rollback();
                    }
                }   
           }
        }

        $mb_upload = UploadPumkMitraBinaan::find((int)$this->mb_upload);
        $param['perusahaan_id'] = $perusahaan->id;
        $param['tahun'] = $this->tahun;
        $param['berhasil'] = $berhasil;
        $param['update'] = $update;
        $param['gagal'] = $gagal;
        $param['keterangan'] = $keterangan;
        $mb_upload->update($param);
    }

    public function headingRow(): int
    {
        return 5;
    }

}
