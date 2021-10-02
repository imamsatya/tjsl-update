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
        return [];
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
        $is_gagal = false;

        //validasi data
            // cek nama mitra
            try{
                $nama = $ar['nama_mitra_binaan'] == null? true : false;

                if($nama){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Nama Mitra Kosong<br>';
                }
            }catch(\Exception $e){
                DB::rollback();
                $is_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' Nama Mitra Kosong<br>';
            }

            // cek no ktp
            try{
                $no_id = strlen($ar['no_identitas']) == 16? true : false;
                if(!$no_id){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Nomor Identitas Harus 16 Digit. <br>';
                }
            }catch(\Exception $e){
                DB::rollback();
                $is_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' Nomor Identitas Harus 16 Digit.<br>';
            }

            // cek no pinjaman
            if(!$is_gagal){
                try{
                    $nopim = $ar['no_pinjaman'] !== null? true : false;
                    if(!$nopim){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Nomor Pinjaman Kosong.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Nomor Pinjaman Kosong.<br>';
                }
            }

            // cek tgl awal
            if(!$is_gagal){
                try{
                    $tglawal = $ar['tgl_awal_pendanaan'] !== null? true : false;
                    if(!$tglawal){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Awal Pendanaan Kosong.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Awal Pendanaan Kosong.<br>';
                }
            }

            // cek Cara Penyaluran / Pelaksanaan Program
            if(!$is_gagal){
                try{
                    $p = rtrim($ar['id_pelaksanaan_program'])? true : false;

                    if($p){
                        $pp = CaraPenyaluran::find(rtrim($ar['id_pelaksanaan_program']));
                    }else{
                        $ar['id_pelaksanaan_program'] = 0;
                    }

                    if(!$pp){
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

            // cek Sumber dana
            $program = CaraPenyaluran::get();
            $mandiri = $program->where('nama','Mandiri')->pluck('id')->first();
            $kolaborasi = $program->where('nama','Kolaborasi')->pluck('id')->first();
            
            // sumber dana jika pelaksanaan program mandiri
            if(!$is_gagal && rtrim($ar['id_pelaksanaan_program']) == $mandiri){
                $ar['sumber_dana'] = $perusahaan->nama_lengkap;
            }
            // sumber dana jika pelaksanaan program kolaborasi
            if(!$is_gagal && rtrim($ar['id_pelaksanaan_program']) == $kolaborasi){
                try{
                    $p = $ar['sumber_dana'] !== null? true : false;
                    if(!$p){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Jika pelaksanaan program kolaborasi, maka Sumber Dana Wajib Diisi.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Jika pelaksanaan program kolaborasi, maka Sumber Dana Wajib Diisi.<br>';
                }
            } 

            // cek tgl tempo
            if(!$is_gagal){
                try{
                    $p = $ar['tgl_jatuh_tempo'] !== null? true : false;
                    if(!$p){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Jatuh Tempo Kosong.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).'Tanggal Jatuh Tempo Kosong.<br>';
                }
            } 

            // cek tgl_penerimaan_terakhir
            if(!$is_gagal){
                try{
                    $p = $ar['tgl_penerimaan_terakhir'] !== null? true : false;
                    if(!$p){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).'Tanggal Penerimaan Terakhir Kosong.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).'Tanggal Penerimaan Terakhir Kosong.<br>';
                }
            } 

            // cek provinsi 
            if(!$is_gagal){
                try{
                    $prov = rtrim($ar['id_provinsi'])? true : false;

                    if($prov){
                        $provinsi = Provinsi::find(rtrim($ar['id_provinsi']));
                    }else{
                        $ar['id_provinsi'] = 0;
                    }

                    if(!$provinsi){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Provinsi Tidak sesuai Referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Provinsi Kosong<br>';
                }
            }

            // cek kota
            if(!$is_gagal){
                try{
                    $kotas = rtrim($ar['id_kota'])? true : false;

                    if($kotas){
                        $kota = Kota::find(rtrim($ar['id_kota']));
                    }else{
                        $ar['id_kota'] = 0;
                    }                    
                    
                    if(!$kota){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota Tidak Sesuai referensi.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota Kosong.<br>';
                }
            }

            // cek relasi provinsi kota
            if(!$is_gagal){
                try{
                    $kota = Kota::where('id',rtrim($ar['id_kota']))
                                ->where('provinsi_id',rtrim($ar['id_provinsi']))
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

            // cek sektor
            if(!$is_gagal){
                try{

                    $sektors = rtrim($ar['id_sektor_usaha'])? true : false;

                    if($sektors){
                        $sektor = SektorUsaha::find(rtrim($ar['id_sektor_usaha']));
                    }else{
                        $ar['id_sektor_usaha'] = 0;
                    }   

                    if(!$sektor){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Sektor Usaha tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Sektor Usaha Kosong.<br>';
                }
            }

            // cek Skala
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['id_skala_usaha'])? true : false;

                    if($params){
                        $skala = SkalaUsaha::find(rtrim($ar['id_skala_usaha']));
                    }else{
                        $ar['id_skala_usaha'] = 0;
                    }   
                    
                    if(!$skala){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Skala Usaha tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Skala Usaha Kosong.<br>';
                }
            }
         
            // cek Kolektibilitas
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['id_kolektibilitas_pendanaan'])? true : false;

                    if($params){
                        $kolek = KolekbilitasPendanaan::find(rtrim($ar['id_kolektibilitas_pendanaan']));
                    }else{
                        $ar['id_kolektibilitas_pendanaan'] = 0;
                    }   
                    
                    if(!$kolek){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kolektibilitas tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kolektibilitas Kosong.<br>';
                }
            }

            // cek KondisiPinjaman
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['id_kondisi_pinjaman'])? true : false;

                    if($params){
                        $kondisi = KondisiPinjaman::find(rtrim($ar['id_kondisi_pinjaman']));
                    }else{
                        $ar['id_kondisi_pinjaman'] = 0;
                    } 
                    
                    if(!$kondisi){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kondisi Pinjaman tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kondisi Pinjaman Kosong.<br>';
                }
            } 

            // cek nilai asset
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['nilai_aset'])? true : false;
                    if($params){
                        $ar['nilai_aset'];
                        $aset = true;
                    }else{
                        $ar['nilai_aset'] = 0;
                        $aset = false;
                    }
                }catch(\Exception $e){
                }
            }

            // cek nilai omset
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['nilai_omset'])? true : false;
                    if($params){
                        $ar['nilai_omset'];
                        $cek = true;
                    }else{
                        $ar['nilai_omset'] = 0;
                        $cek = false;
                    }
                }catch(\Exception $e){
                }
            }  

            // cek nominal Pendanaan
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['nominal_pendanaan'])? true : false;
                    if($params){
                        $ar['nominal_pendanaan'];
                        $cek = true;
                    }else{
                        $ar['nominal_pendanaan'] = 0;
                        $cek = false;
                    }
                }catch(\Exception $e){
                }
            }              

            // cek saldo pokok Pendanaan
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['saldo_pokok_pendanaan'])? true : false;
                    if($params){
                        $ar['saldo_pokok_pendanaan'];
                        $cek = true;
                    }else{
                        $ar['saldo_pokok_pendanaan'] = 0;
                        $cek = false;
                    }
                }catch(\Exception $e){
                }
            }   

            // cek saldo jasa adm Pendanaan
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['saldo_jasa_admin_pendanaan'])? true : false;
                    if($params){
                        $ar['saldo_jasa_admin_pendanaan'];
                        $cek = true;
                    }else{
                        $ar['saldo_jasa_admin_pendanaan'] = 0;
                        $cek = false;
                    }
                }catch(\Exception $e){
                }
            }            

            // cek saldo jasa adm Pendanaan
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['penerimaan_pokok_bulan_berjalan'])? true : false;
                    if($params){
                        $ar['penerimaan_pokok_bulan_berjalan'];
                        $cek = true;
                    }else{
                        $ar['penerimaan_pokok_bulan_berjalan'] = 0;
                        $cek = false;
                    }
                }catch(\Exception $e){
                }
            } 

            // cek saldo jasa adm Pendanaan
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['penerimaan_jasa_admin_bulan_berjalan'])? true : false;
                    if($params){
                        $ar['penerimaan_jasa_admin_bulan_berjalan'];
                        $cek = true;
                    }else{
                        $ar['penerimaan_jasa_admin_bulan_berjalan'] = 0;
                        $cek = false;
                    }
                }catch(\Exception $e){
                }
            }             

            // cek Jenis Pembayaran
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['id_jenis_pembayaran'])? true : false;

                    if($params){
                        $jenis = JenisPembayaran::find(rtrim($ar['id_jenis_pembayaran']));
                    }else{
                        $ar['id_jenis_pembayaran'] = 0;
                    } 

                    if(!$jenis){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Jenis Pembayaran tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Jenis Pembayaran Kosong.<br>';
                }
            }
            
            //cek bank account
            $jenisP = JenisPembayaran::where('nama','Manual')->pluck('id')->first();

            if(!$is_gagal && rtrim($ar['id_jenis_pembayaran']) > $jenisP){
                $cek = rtrim($ar['id_bank_account']) == "" ? 0 : rtrim($ar['id_bank_account']);
                if($cek > 0){
                    try{
                        $bank = BankAccount::find(rtrim($ar['id_bank_account']));
                        if(!$bank){
                            DB::rollback();
                            $is_gagal = true;
                            $keterangan .= 'Baris '.rtrim($ar['no']).' Bank Account tidak sesuai referensi<br>';
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Bank Account tidak sesuai referensi<br>';
                    }
                }else{
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Jika Jenis Pembayaran Virtual Account, maka Bank Account wajib diisi.<br>';                    
                }

            }

            // cek tambah pendanaan
            if(!$is_gagal){
                try{
                    $params = rtrim($ar['id_tambahan_pendanaan'])? true : false;
                    if(rtrim($ar['id_tambahan_pendanaan']) == 1 || rtrim($ar['id_tambahan_pendanaan']) == 2){
                        $tambah = true;
                    }else{
                        $tambah = false;
                        $ar['id_tambahan_pendanaan'] = 2;
                    }
                    if(!$tambah){
                        DB::rollback();
                        $is_gagal = false;
                        // $keterangan .= 'Baris '.rtrim($ar['no']).' Data Tambahan Pendanaan Tidak Sesuai/Kosong diubah default sistem.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = false;
                    // $keterangan .= 'Baris '.rtrim($ar['no']).' Data Tambahan Pendanaan Tidak Sesuai/Kosong diubah default sistem.<br>';
                }
            }   
            
        //proses data
            // cek kegiatan
            if(!$is_gagal){
                try{
                    $cek_identitas = 0;
                    $cek_kolektibilitas = 0;
                    $s_gagal = false;
    
                    //cek apakah no identitas belum ada dan kolek belum lunas? jika ya create
                    $cek_identitas = PumkMitraBinaan::where('no_identitas',$ar['no_identitas'] )
                                     ->count();
                    $cek_kolektibilitas = PumkMitraBinaan::where('kolektibilitas_id',(int)$ar['id_kolektibilitas_pendanaan'] )
                                     ->count();

                    //buat data baru jika identitas & kolek belum ada
                    if(($cek_identitas && $cek_kolektibilitas) == 0 ){
                        $mitra = PumkMitraBinaan::create([
                            'bulan' => (int)date('m')-1,
                            'tahun' => (int)date('Y'),
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
                            'tgl_awal' => $ar['tgl_awal_pendanaan']? (string)$ar['tgl_awal_pendanaan'] : null,
                            'tgl_jatuh_tempo' => $ar['tgl_jatuh_tempo'] ? (string) $ar['tgl_jatuh_tempo'] : null,
                            'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                            'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                            'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                            'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                            'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                            'tgl_penerimaan_terakhir' => $ar['tgl_penerimaan_terakhir'] ? (string)$ar['tgl_penerimaan_terakhir'] : null,
                            'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                            'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                            'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                            'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                            'created_by_id' => \Auth::user()->id,
                            'perusahaan_id' => $perusahaan->id,
                            'kode_upload' => $kode,
                            'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):2
                        ]);
                        $berhasil++;
                    }else{
                        // jika no ktp sudah ada
                        $Tambah_ya = 1;
                        $Tambah_tidak = 2;

                        if($ar['id_tambahan_pendanaan'] == $Tambah_ya){
                            $mitra = PumkMitraBinaan::create([
                                'bulan' => (int)date('m')-1,
                                'tahun' => (int)date('Y'),
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
                                'tgl_awal' => $ar['tgl_awal_pendanaan']? (string)$ar['tgl_awal_pendanaan'] : null,
                                'tgl_jatuh_tempo' => $ar['tgl_jatuh_tempo'] ? (string) $ar['tgl_jatuh_tempo'] : null,
                                'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                                'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                                'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                                'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                                'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                                'tgl_penerimaan_terakhir' => $ar['tgl_penerimaan_terakhir'] ? (string)$ar['tgl_penerimaan_terakhir'] : null,
                                'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                                'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                                'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                                'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                                'created_by_id' => \Auth::user()->id,
                                'perusahaan_id' => $perusahaan->id,
                                'kode_upload' => $kode,
                                'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):2
                            ]);
                            $berhasil++;
                        }
                        else{
                            //update data jika no ktp sudah ada
                            // $mitra = PumkMitraBinaan::where('no_identitas',$ar['no_identitas'])
                            // ->where('kolektibilitas_id',(int)$ar['id_kolektibilitas_pendanaan'])
                            // ->update([
                            $last_data = PumkMitraBinaan::select('no_identitas','is_arsip')
                                ->where('no_identitas',(int)$ar['no_identitas'])
                                ->where('no_pinjaman',$ar['no_pinjaman'])
                                ->where('bulan',(int) date('m')-1)
                                ->where('tahun',(int) date('Y'))
                                ->update([
                                    'is_arsip'=> true
                                ]);
                            $mitra = PumkMitraBinaan::create([                             
                            'bulan' => (int)date('m')-1,
                            'tahun' => (int)date('Y'),
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
                            'tgl_awal' => $ar['tgl_awal_pendanaan']? (string)$ar['tgl_awal_pendanaan'] : null,
                            'tgl_jatuh_tempo' => $ar['tgl_jatuh_tempo'] ? (string) $ar['tgl_jatuh_tempo'] : null,
                            'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                            'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                            'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                            'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                            'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                            'tgl_penerimaan_terakhir' => $ar['tgl_penerimaan_terakhir'] ? (string)$ar['tgl_penerimaan_terakhir'] : null,
                            'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                            'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                            'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                            'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                            'updated_by_id' => \Auth::user()->id,
                            'perusahaan_id' => $perusahaan->id,
                            'kode_upload' => $kode,
                            'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):2
                            ]);
                            $berhasil++;
                        }

                    }
                }catch(\Exception $e){dd($e->getMessage());
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' isian tidak sesuai Referensi<br>';
                }
            }

            // simpan data gagal
            if($is_gagal){
                try{
                        // record data invalid identitas ke gagal upload
                        //$keterangan_gagal = 'no.identitas tidak valid/lebih dari 16 angka';
                        $mitra = UploadGagalPumkMitraBinaan::create([
                            'bulan' => (int)date('m'),
                            'tahun' => (int)date('Y'),
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
                            'tgl_awal' => $ar['tgl_awal_pendanaan']? (string)$ar['tgl_awal_pendanaan'] : null,
                            'tgl_jatuh_tempo' => $ar['tgl_jatuh_tempo'] ? (string) $ar['tgl_jatuh_tempo'] : null,
                            'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                            'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                            'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                            'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                            'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                            'tgl_penerimaan_terakhir' => $ar['tgl_penerimaan_terakhir'] ? (string)$ar['tgl_penerimaan_terakhir'] : null,
                            'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                            'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                            'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                            'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                            'created_by_id' => \Auth::user()->id,
                            'perusahaan_id' => $perusahaan->id,
                            'kode_upload' => $kode,
                            'keterangan_gagal' => $keterangan,
                            'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):2
                        ]);
                    $gagal++;
                }catch(\Exception $e){dd($e->getMessage());
                    DB::rollback();
                }
            } 
        }
        $mb_upload = UploadPumkMitraBinaan::find((int)$this->mb_upload);
        $param['perusahaan_id'] = $perusahaan->id;
        $param['tahun'] = $this->tahun;
        $param['berhasil'] = $berhasil;
        $param['kode_upload'] = $kode;
        $param['gagal'] = $gagal;
        $param['keterangan'] = $keterangan;
        $mb_upload->update($param); 
        DB::commit();            
    }

    public function headingRow(): int
    {
        return 5;
    }

}
