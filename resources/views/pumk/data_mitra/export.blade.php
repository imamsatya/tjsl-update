


<html>

    <head>
    
    <style type="text/css">
        body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Calibri"; font-size:x-small }
        a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solblack; padding:0.5em;  } 
        a.comment-indicator { background:red; display:inline-block; border:1px solblack; width:0.5em; height:0.5em;  } 
        comment { display:none;  } 
    </style>
    
    </head>
    
    <body>
    <table cellspacing="0" border="0">
    <tr>
        <td colspan="30" style="background-color : #e3e3e3 ;width:5px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 2px sol#000000; border-right: 1px sol#000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">LAPORAN PENDANAAN UMK</font></b></td>
    </tr>
    <tr>
        <td colspan="30" style="background-color : #e3e3e3 ;width:5px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 2px sol#000000; border-right: 1px sol#000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Tanggal Cetak : {{date('d-F-Y')}}</font></b></td>
    </tr>
    {{-- <tr>
        <td colspan="29" style="background-color : #e3e3e3 ;width:5px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 2px sol#000000; border-right: 1px sol#000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000"></font></b></td>
    </tr> --}}
    <tr>
        {{-- <td colspan="12"></td>
        <td style="color: red;text-align:center;">(format tgl : hh/bb/tttt)</td>
        <td style="color: red;text-align:center;">(format tgl : hh/bb/tttt)</td>
        <td colspan="5"></td>
        <td style="color: red;text-align:center;">(format tgl : hh/bb/tttt)</td> --}}
    </tr>
    <tr>
        <td style="background-color : #e3e3e3 ;width:5px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 2px sol#000000; border-right: 1px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">No</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Perusahaan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Nama Mitra Binaan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Provinsi</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Kota</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Sektor Usaha</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Skala Usaha</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">No Identitas</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Nilai Aset</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Nilai Omset</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">No Pinjaman</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Pelaksanaan Program</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Sumber Dana</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Tgl Awal Pendanaan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Tgl Jatuh Tempo</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Nominal Pendanaan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Saldo Pokok Pendanaan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Saldo Jasa Admin Pendanaan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:35px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Penerimaan Pokok Bulan Berjalan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:40px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Penerimaan Jasa Admin Bulan Berjalan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Tgl Penerimaan Terakhir</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Kolektibilitas Pendanaan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Kondisi Pinjaman</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Jenis Pembayaran</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Bank Account</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">SDM di MB</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Kelebihan Angsuran</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">SubSektor</font></b></td>
        <td style="background-color : #e3e3e3 ;width:30px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Produk/Jasa yang dihasilkan</font></b></td>
        <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px sol#000000; border-bottom: 1px sol#000000; border-left: 1px sol#000000; border-right: 2px sol#000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Tambahan Pendanaan</font></b></td>
    </tr>

    {{-- @if(!empty($data)) --}}
        @php
            $i=1;
        @endphp
        @foreach($data as $val)
        <tr>
            <td style="text-align: center;vertical-align: top;">{{$i++}}</td>
            <td style="vertical-align: top;">{{$val->bumn?$val->bumn:""}}</td>
            <td style="vertical-align: top;">{{$val->nama_mitra?$val->nama_mitra:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->provinsi? ucwords($val->provinsi):""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->kota? ucwords($val->kota):""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->sektor_usaha?$val->sektor_usaha:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->skala_usaha?$val->skala_usaha:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->no_identitas?"'".$val->no_identitas:""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->nilai_aset? number_format($val->nilai_aset,0,',',','):""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->nilai_omset? number_format($val->nilai_omset,0,',',','):""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->no_pinjaman?$val->no_pinjaman:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->cara_penyaluran?$val->cara_penyaluran:""}}</td>
            <td style="text-align: center;word-wrap:break-word;vertical-align: top;">{{$val->sumber_dana?$val->sumber_dana:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->tgl_awal?date('d/m/Y', strtotime($val->tgl_awal)):""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->tgl_jatuh_tempo?date('d/m/Y', strtotime($val->tgl_jatuh_tempo)):""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->nominal_pendanaan? number_format($val->nominal_pendanaan,0,',',','):""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->saldo_pokok_pendanaan? number_format($val->saldo_pokok_pendanaan,0,',',','):""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->saldo_jasa_adm_pendanaan? number_format($val->saldo_jasa_adm_pendanaan,0,',',','):""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->penerimaan_pokok_bulan_berjalan? number_format($val->penerimaan_pokok_bulan_berjalan,0,',',','):""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->penerimaan_jasa_adm_bulan_berjalan? number_format($val->penerimaan_jasa_adm_bulan_berjalan,0,',',','):""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->tgl_penerimaan_terakhir?date('d/m/Y', strtotime($val->tgl_penerimaan_terakhir)):""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->kolektibilitas?$val->kolektibilitas:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->kondisi_pinjaman?$val->kondisi_pinjaman:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->jenis_pembayaran?$val->jenis_pembayaran:""}}</td>

            @if($val->bank_account_id !== null || $val->bank_account_id !== "")
                @php
                    $banks = $bank->where('id',(int)$val->bank_account_id)->pluck('nama')->first();
                @endphp
                <td style="text-align: center;vertical-align: top;">{{$banks}}</td>
            @else
                <td style="text-align: center;vertical-align: top;"></td>
            @endif

            <td style="text-align: center;vertical-align: top;">{{$val->jumlah_sdm?$val->jumlah_sdm:""}}</td>
            <td style="text-align: right;vertical-align: top;">{{$val->kelebihan_angsuran? number_format($val->kelebihan_angsuran,0,',',','):""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->subsektor?$val->subsektor:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->hasil_produk_jasa?$val->hasil_produk_jasa:""}}</td>
            <td style="text-align: center;vertical-align: top;">{{$val->id_tambahan_pendanaan? ($val->id_tambahan_pendanaan == 1? "Ya":""):""}}</td>
        </tr>
        @endforeach
    {{-- @endif --}}
    
    </table>
    <!--  -->
    </body>
    
    </html>
    