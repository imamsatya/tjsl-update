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
use App\Models\Bulan;
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

        if($row->count() == 0){
            trigger_error('Data Kosong.');
        }

        $bumn_cek = $this->perusahaan? true : false;
        if($bumn_cek){
            $count = Perusahaan::where('nama_lengkap', $this->perusahaan)->count();
            if($count > 0){
                $perusahaan = Perusahaan::where('nama_lengkap', $this->perusahaan)->first();
            }else{
                trigger_error('Header Nama Perusahaan Tidak Sesuai Referensi.');
            }
        }else{
            trigger_error('Header Nama Perusahaan Kosong.');
        }
        
        $berhasil = 0;
        $update = 0;
        $gagal = 0;
        
        $keterangan = '';
        $kode = uniqid();
        $no = 1;

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

            // cek provinsi 
                try{
                    $prov = rtrim($ar['id_provinsi'])? true : false;
                    $is_angka = is_numeric($ar['id_provinsi'])? true : false;

                    if($prov && $is_angka){
                        $provinsi = Provinsi::find(rtrim($ar['id_provinsi']));
                    }else{
                        $ar['id_provinsi'];
                    }

                    if(!$provinsi){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Provinsi Tidak sesuai Referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Provinsi Wajib Diisi Angka ID sesuai Referensi Provinsi.<br>';
                }

            // cek kota
                try{
                    $kotas = rtrim($ar['id_kota'])? true : false;
                    $is_angka = is_numeric($ar['id_kota'])? true : false;

                    if($kotas && $is_angka){
                        $kota = Kota::find(rtrim($ar['id_kota']));
                    }else{
                        $ar['id_kota'];
                    }                    
                    
                    if(!$kota){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota Tidak Sesuai referensi.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kota Wajib Diisi Angka ID sesuai Referensi Kota.<br>';
                }

            // cek relasi provinsi kota
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

            // cek sektor
                try{
                    $sektors = rtrim($ar['id_sektor_usaha'])? true : false;
                    $is_angka = is_numeric($ar['id_kota'])? true : false;

                    if($sektors && $is_angka){
                        $sektor = SektorUsaha::find(rtrim($ar['id_sektor_usaha']));
                    }else{
                        $ar['id_sektor_usaha'];
                    }   

                    if(!$sektor){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Sektor Usaha tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Sektor Usaha Wajib Diisi Angka ID sesuai Referensi Sektor Usaha.<br>';
                }

            // cek Skala
                try{
                    $params = rtrim($ar['id_skala_usaha'])? true : false;
                    $is_angka = is_numeric($ar['id_kota'])? true : false;

                    if($params && $is_angka){
                        $skala = SkalaUsaha::find(rtrim($ar['id_skala_usaha']));
                    }else{
                        $ar['id_skala_usaha'];
                    }   
                    
                    if(!$skala){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Skala Usaha tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Skala Usaha Wajib Diisi Angka ID sesuai Referensi Skala Usaha.<br>';
                }

            // cek no ktp
                try{
                    $no_id = strlen(preg_replace('/[^0-9]/','',$ar['no_identitas'])) == 16? substr($ar['no_identitas'],-4) == 0000? false : true : false;

                    if(!$no_id){
                        //jika no ktp tidak 16 digit dan angka terakhir bukan 0
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= "Baris ".rtrim($ar['no'])." Nomor Identitas Wajib 16 Digit dan 4 digit terakhir bukan nol. <br>";
                    }

                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Nomor Identitas Harus 16 Digit dan angka terakhir bukan nol.<br>';
                }

            // cek nilai asset
                try{
                    $params = rtrim($ar['nilai_aset'])? true : false;
                    $is_angka = is_numeric($ar['nilai_aset'])? true : false;

                    if($params && $is_angka){
                        $ar['nilai_aset'];
                    }else if($params && !$is_angka){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Nilai aset harus angka / boleh kosong.<br>';
                    }else{
                        $ar['nilai_aset'] = 0;
                    }
                }catch(\Exception $e){}

            // cek nilai omset
                try{
                    $params = rtrim($ar['nilai_omset'])? true : false;
                    $is_angka = is_numeric($ar['nilai_omset'])? true : false;

                    if($params && $is_angka){
                        $ar['nilai_omset'];
                    }else if($params && !$is_angka){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Nilai omset harus angka / boleh kosong.<br>';
                    }else{
                        $ar['nilai_omset'] = 0;
                    }
                }catch(\Exception $e){
                }

            // cek no pinjaman
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

            // cek Cara Penyaluran / Pelaksanaan Program
                try{
                    $p = rtrim($ar['id_pelaksanaan_program'])? true : false;
                    $is_angka = is_numeric($ar['id_pelaksanaan_program'])? true : false;

                    if($p && $is_angka){
                        $pp = CaraPenyaluran::find(rtrim($ar['id_pelaksanaan_program']));
                    }else{
                        $ar['id_pelaksanaan_program'];
                    }
                    
                    if(!$pp){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Pelaksanaan Program tidak sesuai referensi<br>';
                    }

                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Pelaksanaan Program Wajib Diisi Angka ID sesuai Referensi Pelaksanaan Program.<br>';
                }


            // cek Sumber dana
            $program = CaraPenyaluran::get();
            $mandiri = $program->where('nama','Mandiri')->pluck('id')->first();
            $kolaborasi = $program->where('nama','Kolaborasi')->pluck('id')->first();
            
            // sumber dana jika pelaksanaan program mandiri
            if(rtrim($ar['id_pelaksanaan_program']) == $mandiri){
                $ar['sumber_dana'] = $perusahaan->id;
            }
            // sumber dana jika pelaksanaan program kolaborasi
            if(rtrim($ar['id_pelaksanaan_program']) == $kolaborasi){
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

            // cek tgl awal
                try{
                    $tglawal = $ar['tgl_awal_pendanaan'] !== null? true : false;
                    
                    if(!$tglawal){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Awal Pendanaan Kosong.<br>';
                    }else if(!is_numeric($ar['tgl_awal_pendanaan'])){
                        DB::rollback();
                        $ar['tgl_awal_pendanaan'];
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Kesalahan format Tanggal Awal Pendanaan.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Awal Pendanaan Kosong.<br>';
                }

            // cek tgl tempo
                try{
                    $p = $ar['tgl_jatuh_tempo'] !== null? true : false;
                    if(!$p){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Jatuh Tempo Kosong.<br>';
                    }else if(!is_numeric($ar['tgl_jatuh_tempo'])){
                        DB::rollback();
                        $ar['tgl_jatuh_tempo'];
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Kesalahan format Tanggal Jatuh Tempo.<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).'Tanggal Jatuh Tempo Kosong.<br>';
                }

            // cek nominal Pendanaan
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

            // cek saldo pokok Pendanaan
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

            // cek saldo jasa adm Pendanaan
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

            // cek penerimaan_pokok_bulan_berjalan
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

            // cek penerimaan_jasa_admin_bulan_berjalan
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

            // cek tgl_penerimaan_terakhir
                try{
                    $p = $ar['tgl_penerimaan_terakhir'] !== null? true : false;
                    if(!$p){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Penerimaan Terakhir Kosong.<br>';
                    }else if(!is_numeric($ar['tgl_penerimaan_terakhir'])){
                            DB::rollback();
                            $ar['tgl_penerimaan_terakhir'];
                            $is_gagal = true;
                            $keterangan .= 'Baris '.rtrim($ar['no']).' Kesalahan format Tanggal Penerimaan Terakhir.<br>';
                        }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Tanggal Penerimaan Terakhir Kosong.<br>';
                }
        
            // cek Kolektibilitas
                try{
                    $params = rtrim($ar['id_kolektibilitas_pendanaan'])? true : false;
                    $is_angka = is_numeric($ar['id_kolektibilitas_pendanaan'])? true : false;

                    if($params && $is_angka){
                        $kolek = KolekbilitasPendanaan::find(rtrim($ar['id_kolektibilitas_pendanaan']));
                    }else{
                        $ar['id_kolektibilitas_pendanaan'];
                    }   
                    
                    if(!$kolek){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kolektibilitas tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kolektibilitas Wajib Diisi Angka ID sesuai Referensi Kolektibilitas Pendanaan.<br>';
                }

            // cek KondisiPinjaman
                try{
                    $params = rtrim($ar['id_kondisi_pinjaman'])? true : false;
                    $is_angka = is_numeric($ar['id_kondisi_pinjaman'])? true : false;

                    if($params && $is_angka){
                        $kondisi = KondisiPinjaman::find(rtrim($ar['id_kondisi_pinjaman']));
                    }else{
                        $ar['id_kondisi_pinjaman'];
                    } 
                    
                    if(!$kondisi){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kondisi Pinjaman tidak sesuai referensi<br>';
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Kondisi Pinjaman Wajib Diisi Angka ID sesuai Referensi Kondisi Pinjaman.<br>';
                }


            // cek Jenis Pembayaran
                try{
                    $params = rtrim($ar['id_jenis_pembayaran'])? true : false;
                    $is_angka = is_numeric($ar['id_jenis_pembayaran'])? true : false;

                    if($params && $is_angka){
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
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Jenis Pembayaran Wajib Diisi Angka ID sesuai Referensi Jenis Pembayaran.<br>';
                }
            
            //cek bank account
            $jenisP = JenisPembayaran::where('nama','Manual')->pluck('id')->first();

            if(rtrim($ar['id_jenis_pembayaran']) > $jenisP){
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
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Jika Jenis Pembayaran Virtual Account, maka Bank Account wajib diisi angka ID sesuai referensi Bank Account.<br>';                    
                }

            }

            // cek produk jasa yg dihasilkan
            try{
                $produk = $ar['produkjasa_yang_dihasilkan'] !== null? true : false;
                if(!$produk){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Produk/Jasa Yang Dihasilkan Kosong.<br>';
                }
            }catch(\Exception $e){
                DB::rollback();
                $is_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' Produk/Jasa Yang Dihasilkan Kosong.<br>';
            }

            // cek produk jasa unggulan
            try{
                $produk = $ar['produkjasa_unggulan'] !== null? true : false;
                if(!$produk){
                    DB::rollback();
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Produk/Jasa Unggulan Kosong.<br>';
                }
            }catch(\Exception $e){
                DB::rollback();
                $is_gagal = true;
                $keterangan .= 'Baris '.rtrim($ar['no']).' Produk/Jasa Unggulan Kosong.<br>';
            }

            // cek tambah pendanaan
                $params = is_numeric(rtrim($ar['id_tambahan_pendanaan']))? true : false;
                if($params){
                    try{
                        if(rtrim($ar['id_tambahan_pendanaan']) == 1 || rtrim($ar['id_tambahan_pendanaan']) == 2){
                            $tambah = true;
                        }else{
                            $tambah = false;
                            $ar['id_tambahan_pendanaan'];
                        }
                        if(!$tambah){
                            DB::rollback();
                            $is_gagal = true;
                            $keterangan .= 'Baris '.rtrim($ar['no']).' Data Tambahan Pendanaan Tidak Sesuai angka ID referensi Tambahan Pendanaan.<br>';
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        $is_gagal = true;
                        $keterangan .= 'Baris '.rtrim($ar['no']).' Data Tambahan Pendanaan Harus diisi Angka ID sesuai referensi Tambahan Pendanaan.<br>';   
                    }
                }else{
                    $ar['id_tambahan_pendanaan'];
                    $is_gagal = true;
                    $keterangan .= 'Baris '.rtrim($ar['no']).' Data Tambahan Pendanaan Harus diisi Angka ID sesuai referensi Tambahan Pendanaan.<br>';                    
                }

            // cek status lunas angsuran sebelumnya
                try{
                    $lunas = KolekbilitasPendanaan::where('nama','ilike','%lunas%')->pluck('id')->first();
                    $cek_data_sebelumnya = PumkMitraBinaan::where('no_pinjaman',$ar['no_pinjaman'])
                                        ->where('no_identitas',preg_replace('/[^0-9]/','',$ar['no_identitas']))
                                        ->where('kolektibilitas_id',$lunas)    
                                        ->count();

                    if($cek_data_sebelumnya > 0){
                        $data = PumkMitraBinaan::where('no_pinjaman',$ar['no_pinjaman'])
                        ->where('no_identitas',preg_replace('/[^0-9]/','',$ar['no_identitas']))
                        ->where('kolektibilitas_id',$lunas)
                        ->orderby('id','desc')    
                        ->first();

                        $bulan = Bulan::where('id',(int)$data->bulan)->pluck('nama')->first();
                        $bumn = Perusahaan::where('id',(int)$data->perusahaan_id)->pluck('nama_lengkap')->first();

                        DB::rollback();
                        $is_gagal = true;
                        // $keterangan .= 'Baris '.rtrim($ar['no']).' Nomor pinjaman <strong>'.$ar['no_pinjaman'].'</strong> a/n. <strong>'.$data->nama_mitra.'</strong> di '.$bumn.' telah lunas pada <strong>'.$bulan.' '.$data->tahun.'</strong><br>';
                        $keterangan .= 'Baris '.rtrim($ar['no']).' data upload sebelumnya sudah tercatat di sistem dengan status Lunas.<br>';
                    }
                }catch(\Exception $e){}

        //proses data
            // cek kegiatan
            if(!$is_gagal){
                try{
                    $cek_identitas = 0;
                    $cek_kolektibilitas = 0;
                    $s_gagal = false;
    
                    //cek apakah no identitas belum ada dan kolek belum lunas? jika ya create
                    $no_id = preg_replace('/[^0-9]/','',$ar['no_identitas']);
                    $cek_identitas = PumkMitraBinaan::where('no_identitas',$no_id)
                                     ->count();
                    $cek_kolektibilitas = PumkMitraBinaan::where('kolektibilitas_id',(int)$ar['id_kolektibilitas_pendanaan'] )
                                     ->count();

                    //buat data baru jika identitas & kolek belum ada
                    if(($cek_identitas && $cek_kolektibilitas) == 0 ){
                        $mitra = PumkMitraBinaan::create([
                            'bulan' => (int)date('m') == 1? 12 : (int)date('m')-1,
                            'tahun' => (int)date('m') == 1? (int)date('Y')-1 : (int)date('Y'),
                            'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                            'no_identitas' => rtrim(preg_replace('/[^0-9]/','',$ar['no_identitas'])),
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
                            'sumber_dana' => str_replace('.',',',$ar['sumber_dana']),
                            'tgl_awal' => $ar['tgl_awal_pendanaan']? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y') : null,
                            'tgl_jatuh_tempo' => $ar['tgl_jatuh_tempo'] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y') : null,
                            'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                            'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                            'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                            'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                            'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                            'tgl_penerimaan_terakhir' => $ar['tgl_penerimaan_terakhir'] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y') : null,
                            'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                            'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                            'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                            'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                            'produk_jasa_unggulan' => $ar['produkjasa_unggulan'] ? rtrim($ar['produkjasa_unggulan']):0,
                            'created_by_id' => \Auth::user()->id,
                            'perusahaan_id' => $perusahaan->id,
                            'kode_upload' => $kode,
                            'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):2
                        ]);
                        DB::commit();
                        $berhasil++;
                    }else{
                        // jika no ktp sudah ada
                        $Tambah_ya = 1;
                        $Tambah_tidak = 2;

                        if($ar['id_tambahan_pendanaan'] == $Tambah_ya){
                            $mitra = PumkMitraBinaan::create([
                                'bulan' => (int)date('m') == 1? 12 : (int)date('m')-1,
                                'tahun' => (int)date('m') == 1? (int)date('Y')-1 : (int)date('Y'),
                                'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                                'no_identitas' => rtrim(preg_replace('/[^0-9]/','',$ar['no_identitas'])),
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
                                'sumber_dana' => str_replace('.',',',$ar['sumber_dana']),
                                'tgl_awal' => $ar['tgl_awal_pendanaan']? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y') : null,
                                'tgl_jatuh_tempo' => $ar['tgl_jatuh_tempo'] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y') : null,
                                'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                                'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                                'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                                'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                                'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                                'tgl_penerimaan_terakhir' => $ar['tgl_penerimaan_terakhir'] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y') : null,
                                'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                                'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                                'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                                'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                                'produk_jasa_unggulan' => $ar['produkjasa_unggulan'] ? rtrim($ar['produkjasa_unggulan']):0,
                                'created_by_id' => \Auth::user()->id,
                                'perusahaan_id' => $perusahaan->id,
                                'kode_upload' => $kode,
                                'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):2
                            ]);
                            DB::commit();
                            $berhasil++;
                        }
                        else{
                            //update data jika no ktp sudah ada
                            // $mitra = PumkMitraBinaan::where('no_identitas',$ar['no_identitas'])
                            // ->where('kolektibilitas_id',(int)$ar['id_kolektibilitas_pendanaan'])
                            // ->update([
                            $last_data = PumkMitraBinaan::select('no_identitas','is_arsip')
                                ->where('no_identitas',(int)preg_replace('/[^0-9]/','',$ar['no_identitas']))
                                ->where('no_pinjaman',$ar['no_pinjaman'])
                                ->where('bulan',(int) date('m')-1)
                                ->where('tahun',(int) date('Y'))
                                ->update([
                                    'is_arsip'=> true
                                ]);
                            $mitra = PumkMitraBinaan::create([                             
                            'bulan' => (int)date('m') == 1? 12 : (int)date('m')-1,
                            'tahun' => (int)date('m') == 1? (int)date('Y')-1 : (int)date('Y'),
                            'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                            'no_identitas' => rtrim(preg_replace('/[^0-9]/','',$ar['no_identitas'])),
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
                            'sumber_dana' => str_replace('.',',',$ar['sumber_dana']),
                            'tgl_awal' => $ar['tgl_awal_pendanaan']? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y') : null,
                            'tgl_jatuh_tempo' => $ar['tgl_jatuh_tempo'] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y') : null,
                            'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                            'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                            'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                            'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                            'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                            'tgl_penerimaan_terakhir' => $ar['tgl_penerimaan_terakhir'] ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y') : null,
                            'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                            'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                            'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                            'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                            'produk_jasa_unggulan' => $ar['produkjasa_unggulan'] ? rtrim($ar['produkjasa_unggulan']):0,
                            'updated_by_id' => \Auth::user()->id,
                            'perusahaan_id' => $perusahaan->id,
                            'kode_upload' => $kode,
                            'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):2
                            ]);
                            DB::commit();
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
                        //$mitra = UploadGagalPumkMitraBinaan::create([
                        //    'bulan' => (int)date('m') == 1? 12 : (int)date('m')-1,
                        //    'tahun' => (int)date('m') == 1? (int)date('Y')-1 : (int)date('Y'),
                        //    'nama_mitra' => rtrim($ar['nama_mitra_binaan']),
                        //    'no_identitas' => rtrim(preg_replace('/[^0-9]/','',$ar['no_identitas'])),
                        //    'provinsi_id' => rtrim($ar['id_provinsi']),
                        //    'kota_id' => rtrim($ar['id_kota']),
                        //    'sektor_usaha_id' => rtrim($ar['id_sektor_usaha']),
                        //    'skala_usaha_id' => rtrim($ar['id_skala_usaha']),
                        //    'cara_penyaluran_id' => rtrim($ar['id_pelaksanaan_program']),
                        //    'kolektibilitas_id' => rtrim($ar['id_kolektibilitas_pendanaan']),
                        //    'kondisi_pinjaman_id' => rtrim($ar['id_kondisi_pinjaman']),
                        //    'jenis_pembayaran_id' => rtrim($ar['id_jenis_pembayaran']),
                        //    'bank_account_id' => rtrim($ar['id_bank_account']),
                        //    'nilai_aset' => rtrim($ar['nilai_aset']),
                        //    'nilai_omset' => rtrim($ar['nilai_omset']),
                        //    'no_pinjaman' => rtrim($ar['no_pinjaman']),
                        //    'sumber_dana' => str_replace('.',',',$ar['sumber_dana']),
                        //    'tgl_awal' => is_numeric($ar['tgl_awal_pendanaan'])? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_awal_pendanaan'])->format('d-m-Y') : $ar['tgl_awal_pendanaan'],
                        //    'tgl_jatuh_tempo' => is_numeric($ar['tgl_jatuh_tempo']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_jatuh_tempo'])->format('d-m-Y') : $ar['tgl_jatuh_tempo'],
                        //    'nominal_pendanaan' => rtrim($ar['nominal_pendanaan']),
                        //    'saldo_pokok_pendanaan' => rtrim($ar['saldo_pokok_pendanaan']),
                        //    'saldo_jasa_adm_pendanaan' => rtrim($ar['saldo_jasa_admin_pendanaan']),
                        //    'penerimaan_pokok_bulan_berjalan' => rtrim($ar['penerimaan_pokok_bulan_berjalan']),
                        //    'penerimaan_jasa_adm_bulan_berjalan' => rtrim($ar['penerimaan_jasa_admin_bulan_berjalan']),
                        //    'tgl_penerimaan_terakhir' => is_numeric($ar['tgl_penerimaan_terakhir']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($ar['tgl_penerimaan_terakhir'])->format('d-m-Y') : $ar['tgl_penerimaan_terakhir'],
                        //    'jumlah_sdm' => $ar['sdm_di_mb'] ? rtrim($ar['sdm_di_mb']):0,
                        //    'kelebihan_angsuran' => $ar['kelebihan_angsuran'] ? rtrim($ar['kelebihan_angsuran']):0,
                        //    'subsektor' => $ar['subsektor'] ? rtrim($ar['subsektor']):0,
                        //    'hasil_produk_jasa' => $ar['produkjasa_yang_dihasilkan'] ? rtrim($ar['produkjasa_yang_dihasilkan']):0,
                        //    'created_by_id' => \Auth::user()->id,
                        //    'perusahaan_id' => $perusahaan->id,
                        //    'kode_upload' => $kode,
                        //    'keterangan_gagal' => $keterangan,
                        //    'id_tambahan_pendanaan' => $ar['id_tambahan_pendanaan'] ? rtrim($ar['id_tambahan_pendanaan']):$ar['id_tambahan_pendanaan']
                        //]);
                    DB::commit();
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
