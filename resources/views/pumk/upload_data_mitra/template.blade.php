<html>

<head>

    <style type="text/css">
        body,
        div,
        table,
        thead,
        tbody,
        tfoot,
        tr,
        th,
        td,
        p {
            font-family: "Calibri";
            font-size: x-small
        }

        a.comment-indicator:hover+comment {
            background: #ffd;
            position: absolute;
            display: block;
            border: 1px solid black;
            padding: 0.5em;
        }

        a.comment-indicator {
            background: red;
            display: inline-block;
            border: 1px solid black;
            width: 0.5em;
            height: 0.5em;
        }

        comment {
            display: none;
        }
    </style>

</head>

<body>
    <table cellspacing="0" border="0">
        <tr>
            <td colspan="14"
                style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">LAPORAN PENDANAAN UMK</font>
                </b></td>
        </tr>
        @php
            if (!empty($bumn)) {
                $perusahaan = $bumn
                    ->where('id', (int) $data[0]->perusahaan_id)
                    ->pluck('nama_lengkap')
                    ->first();
            }
        @endphp
        <tr>
            <td colspan="14"
                style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">{{ $perusahaan }}</font>
                </b></td>
        </tr>
        <tr>
            <td colspan="14"
                style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000"></font>
                </b>{{ $periode }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td style="color: red;text-align:center;">(format tgl : hh/bb/tttt)</td>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">No</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Nama Mitra Binaan</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">NIK</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Sektor Usaha</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Provinsi</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Kabupaten/Kota</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Nominal Pendanaan (Rp)</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Tanggal Mendapatkan Pendanaan</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Tenor (Bulan)</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Outstanding Pokok</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Outstanding Jasa Admin</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Kolektibilitas</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Kondisi Pinjaman (Belum/Telah di Restruktur)</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">BUMN Sumber Dana</font>
                </b></td>
        </tr>

        @if (!empty($data))
            @php
                $i = 1;
            @endphp
            @foreach ($data as $val)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $val->nama_mitra ? $val->nama_mitra : '' }}</td>
                    <td>{{ $val->no_identitas ? "'" . $val->no_identitas : '' }}</td>
                    <td>{{ $val->sektor_usaha_id ? $val->sektor_usaha_id : '' }}</td>
                    <td>{{ $val->provinsi_id ? $val->provinsi_id : '' }}</td>
                    <td>{{ $val->kota_id ? $val->kota_id : '' }}</td>
                    <td>{{ $val->nominal_pendanaan ? $val->nominal_pendanaan : '' }}</td>
                    <td>{{ $val->tgl_awal ? (\PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($val->tgl_awal) ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($val->tgl_awal) : $val->tgl_awal) : '-' }}
                    </td>
                    <td>{{ $val->tenor ? $val->tenor : '' }}</td>
                    <td>{{ $val->saldo_pokok_pendanaan ? $val->saldo_pokok_pendanaan : '' }}</td>
                    <td>{{ $val->saldo_jasa_adm_pendanaan ? $val->saldo_jasa_adm_pendanaan : '' }}</td>
                    <td>{{ $val->kolektibilitas_id ? $val->kolektibilitas_id : '' }}</td>
                    <td>{{ $val->kondisi_pinjaman_id ? $val->kondisi_pinjaman_id : '' }}</td>
                    <td>{{ $val->sumber_dana ? $val->sumber_dana : '' }}</td>
                </tr>
            @endforeach
        @endif

    </table>
    <!-- ************************************************************************** -->
</body>

</html>
