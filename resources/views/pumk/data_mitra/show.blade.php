
            <style>
                .table{
                    width: 100%;
                }
            </style>
                    <hr>
                    <div class="container">
                        <table class="table table-striped table-bordered table-hover table-checkable">
                            <tbody>
                                <tr>
                                    <td>Perusahaan</td>
                                    <td>{{$data->perusahaan_text?$data->perusahaan_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Bulan</td>
                                    <td>{{$data->bulan_text?$data->bulan_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Tahun</td>
                                    <td>{{$data->tahun?$data->tahun:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Nama Mitra</td>
                                    <td>{{$data->nama_mitra?$data->nama_mitra:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>No. Identitas</td>
                                    <td>{{$data->no_identitas?$data->no_identitas:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>No. Pinjaman</td>
                                    <td>{{$data->no_pinjaman?$data->no_pinjaman:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Provinsi</td>
                                    <td>{{$data->prov_text?$data->prov_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Kabupaten/Kota</td>
                                    <td>{{$data->kota_text?$data->kota_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Sektor Usaha</td>
                                    <td>{{$data->sektor_usaha_text?$data->sektor_usaha_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Skala Usaha</td>
                                    <td>{{$data->skala_usaha_text?$data->skala_usaha_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Kondisi Pinjaman</td>
                                    <td>{{$data->kondisi_pinjaman_text?$data->kondisi_pinjaman_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Pembayaran</td>
                                    <td>{{$data->jenis_pembayaran_text?$data->jenis_pembayaran_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Bank Account</td>
                                    <td>{{$bank?$bank:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Nominal Pendanaan</td>
                                    <td>{{$data->nominal_pendanaan?number_format($data->nominal_pendanaan,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>Pelaksanaan Program</td>
                                    <td>{{$data->cara_penyaluran_text?$data->cara_penyaluran_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Sumber Dana</td>
                                    <td>{{$data->sumber_dana?$data->sumber_dana:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Awal Pendanaan</td>
                                    <td>{{$data->tgl_awal?$data->tgl_awal:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Jatuh Tempo</td>
                                    <td>{{$data->tgl_jatuh_tempo?$data->tgl_jatuh_tempo:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Saldo Pokok Pendanaan</td>
                                    <td>{{$data->saldo_pokok_pendanaan?number_format($data->saldo_pokok_pendanaan,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>Saldo Jasa Admin Pendanaan</td>
                                    <td>{{$data->saldo_jasa_adm_pendanaan?number_format($data->saldo_jasa_adm_pendanaan,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>Penerimaan Pokok Bulan Berjalan</td>
                                    <td>{{$data->penerimaan_pokok_bulan_berjalan?number_format($data->penerimaan_pokok_bulan_berjalan,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>Penerimaan Jasa Admin Bulan Berjalan</td>
                                    <td>{{$data->penerimaan_jasa_adm_bulan_berjalan?number_format($data->penerimaan_jasa_adm_bulan_berjalan,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Penerimaan Terakhir</td>
                                    <td>{{$data->tgl_penerimaan_terakhir?$data->tgl_penerimaan_terakhir:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah SDM di Mitra Binaan</td>
                                    <td>{{$data->jumlah_sdm?$data->jumlah_sdm:0}}</td>
                                </tr>
                                <tr>
                                    <td>Nilai Aset</td>
                                    <td>{{$data->nilai_aset?number_format($data->nilai_aset,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>Nilai Omset</td>
                                    <td>{{$data->nilai_omset?number_format($data->nilai_omset,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>Kelebihan Angsuran</td>
                                    <td>{{$data->kelebihan_angsuran?number_format($data->kelebihan_angsuran,0,',',',') : 0}}</td>
                                </tr>
                                <tr>
                                    <td>subsektor</td>
                                    <td>{{$data->subsektor?$data->subsektor:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Produk/Jasa yang dihasilkan</td>
                                    <td>{{$data->hasil_produk_jasa?$data->hasil_produk_jasa:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Kolektibilitas Pendanaan</td>
                                    <td>{{$data->kolektibilitas_text?$data->kolektibilitas_text:"-"}}</td>
                                </tr>
                                <tr>
                                    <td>Status Tambah Pendanaan</td>
                                    <td>{{$data->id_tambahan_pendanaan? ($data->id_tambahan_pendanaan == 1? "Ya" : "Tidak") : "-"}}</td>
                                </tr>      
                            </tbody>
                        </table>
                    </div>
                    <hr>
