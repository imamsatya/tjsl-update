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

class ImportMb implements ToCollection, WithHeadingRow, WithMultipleSheets , WithValidation
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
    public function rules(): array
    {
        return [
            'nama_mitra_binaan' => ['required'],
            'no_identitas' => ['required'],
            'id_provinsi' => ['required'],
            'id_kota' => ['required'],
            'id_sektor_usaha' => ['required'],
            'id_skala_usaha' => ['required'],
            'id_pelaksanaan_program' => ['required'],
            'id_kolektibilitas_pendanaan' => ['required'],
            'id_kondisi_pinjaman' => ['required'],
            'id_jenis_pembayaran' => ['required'],
            'id_bank_account' => ['required'],
            'no_pinjaman' => ['required'],
            'tgl_awal_pendanaan' => ['required'],
            'sumber_dana' => ['required'],
            'tgl_jatuh_tempo' => ['required'],
            'saldo_pokok_pendanaan' => ['required'],
            'saldo_jasa_admin_pendanaan' => ['required'],
            'penerimaan_pokok_bulan_berjalan' => ['required'],
            'penerimaan_jasa_admin_bulan_berjalan' => ['required'],
            'tgl_penerimaan_terakhir' => ['required'],
            'penerimaan_jasa_admin_bulan_berjalan' => ['required'],
            'penerimaan_jasa_admin_bulan_berjalan' => ['required']
        ];
    }
    public function collection(Collection $row)
    {
        $perusahaan = Perusahaan::where('nama_lengkap', $this->perusahaan)->first();
        $berhasil = 0;
        $update = 0;
        $gagal = 0;
        $keterangan = '';
        $keterangan_gagal = '';
        $kode = uniqid();

       foreach ($row as $ar) {
            //jika no ktp lebih dari 16 angka/invalid
            if(strlen($ar['no_identitas']) == 16){
                $cek_identitas = 0;
                $cek_kolektibilitas = 0;
                $s_gagal = false;

                //cek apakah no identitas belum ada dan kolek belum lunas? jika ya create
                $cek_identitas = PumkMitraBinaan::where('no_identitas',$ar['no_identitas'] )
                                 ->count();
                $cek_kolektibilitas = PumkMitraBinaan::where('kolektibilitas_id',(int)$ar['id_kolektibilitas_pendanaan'] )
                                 ->count();

                //buat data baru jika identitas & kolek belum ada
                if(($cek_identitas && $cek_kolektibilitas) == 0){
                    $mitra = PumkMitraBinaan::create([
                        'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                        'no_identitas' => rtrim($ar['no_identitas']),
                        'provinsi_id' => rtrim($ar['id_provinsi']),
                        'kota_id' => rtrim($ar['id_kota']),
                        'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                        'skala_usaha_id' => rtrim($ar['id_skala_usaha']),
                        'cara_penyaluran_id' => rtrim($ar['id_pelaksanaan_program']),
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
                        'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                        'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                        'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                        'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                        'created_by_id' => \Auth::user()->id,
                        'perusahaan_id' => $perusahaan->id,
                        'kode_upload' => $kode
                    ]);
                    $berhasil++;
                }else{
                    $mitra = PumkMitraBinaan::where('no_identitas',$ar['no_identitas'])
                        ->where('kolektibilitas_id',(int)$ar['id_kolektibilitas_pendanaan'])
                        ->update([
                        'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                        //'no_identitas' => rtrim($ar['no_identitas']),
                        'provinsi_id' => rtrim($ar['id_provinsi']),
                        'kota_id' => rtrim($ar['id_kota']),
                        'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                        'skala_usaha_id' => rtrim($ar['id_skala_usaha']),
                        'cara_penyaluran_id' => rtrim($ar['id_pelaksanaan_program']),
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
                        'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                        'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                        'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                        'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                        'created_by_id' => \Auth::user()->id,
                        'perusahaan_id' => $perusahaan->id,
                        'kode_upload' => $kode
                    ]);
                    $berhasil++;
                }

            }else{ 
                // record data invalid identitas ke gagal upload
                $keterangan_gagal = 'no.identitas tidak valid/lebih dari 16 angka';
                $mitra = UploadGagalPumkMitraBinaan::create([
                    'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                    'no_identitas' => rtrim($ar['no_identitas']),
                    'provinsi_id' => rtrim($ar['id_provinsi']),
                    'kota_id' => rtrim($ar['id_kota']),
                    'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                    'skala_usaha_id' => rtrim($ar['id_skala_usaha']),
                    'cara_penyaluran_id' => rtrim($ar['id_pelaksanaan_program']),
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
                    'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                    'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                    'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                    'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                    'created_by_id' => \Auth::user()->id,
                    'perusahaan_id' => $perusahaan->id,
                    'kode_upload' => $kode,
                    'keterangan_gagal' => $keterangan_gagal
                ]);

                $gagal++;               
            }
            $mb_upload = UploadPumkMitraBinaan::find((int)$this->mb_upload);
            $param['perusahaan_id'] = $perusahaan->id;
            $param['tahun'] = $this->tahun;
            $param['berhasil'] = $berhasil;
            $param['kode_upload'] = $kode;
            $param['gagal'] = $gagal;
            $param['keterangan'] = $keterangan;
            $mb_upload->update($param);  
        }

    }

    public function headingRow(): int
    {
        return 5;
    }

}
