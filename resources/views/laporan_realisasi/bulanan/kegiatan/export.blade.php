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
                style="width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Rekapitulasi Data Program dan Kegiatan TJSL
                    </font>
                </b></td>
        </tr>
        <tr>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">No</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Perusahaan</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Pilar Pembangunan</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">TPB</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Program</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Program</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Kegiatan</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Provinsi Kegiatan</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Kabupaten/Kotamadya Kegiatan</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Bulan</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Tahun</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Realisasi</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Satuan Ukur</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Realisasi Anggaran</font>
                </b></td>


        </tr>


        @php $num = 1; @endphp
        @foreach ($kegiatans as $kegiatan)
            @if ($kegiatan)
                <tr>
                    <td>{{ $num++ }}</td>
                    <td>{{ @$kegiatan->perusahaan_nama_lengkap }}</td>
                    <td>{{ @$kegiatan->pilar_pembangunan_nama }}</td>
                    <td>{{ @$kegiatan->tpb_nama }}</td>
                    <td>{{ @$kegiatan->target_tpb_id }}</td>
                    <td>{{ @$kegiatan->target_tpb_program }}</td>
                    <td>{{ @$kegiatan->kegiatan }}</td>
                    <td>{{ @$kegiatan->provinsi_nama }}</td>
                    <td>{{ @$kegiatan->kota_nama }}</td>
                    <td>{{ @$kegiatan->bulan_nama }}</td>
                    <td>{{ @$kegiatan->kegiatan_realisasi_tahun }}</td>
                    <td>{{ @$kegiatan->indikator }}</td>
                    <td>{{ @$kegiatan->satuan_ukur_nama }}</td>
                    <td>{{ @$kegiatan->anggaran_alokasi }}</td>
                </tr>
            @endif
        @endforeach

    </table>
    <!-- ************************************************************************** -->
</body>

</html>
