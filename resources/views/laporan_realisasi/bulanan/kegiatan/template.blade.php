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
            <td colspan="11"
                style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">LAPORAN REALISASI</font>
                </b></td>
        </tr>
        <tr>
            <td colspan="11"
                style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">{{ @$perusahaan->nama_lengkap }}</font>
                </b></td>
            <td style="color:#ffffff;">{{ $perusahaan->id }}</td>
        </tr>
        <tr>
            <td colspan="11"
                style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Bulan {{ $bulan_string }} Tahun {{ $tahun }}
                    </font>
                </b></td>
            <td style="color:#ffffff;">{{ $bulan }}</td>
            <td style="color:#ffffff;">{{ $tahun }}</td>
        </tr>
        <tr>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">No</font>
                </b></td>
            <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Jenis Anggaran <br />[1. CID] <br />[2. non CID]</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Program <br /> [Sheet Referensi Program]</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Nama Kegiatan</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Jenis Kegiatan <br /> [Sheet Referensi Jenis
                        Kegiatan]</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Sub Kegiatan <br /> [Sheet Referensi Sub
                        Kegiatan]</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Provinsi <br /> [Sheet Referensi Provinsi]</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Kabupaten/Kota <br /> [Sheet Referensi Kota]</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Realisasi Anggaran</font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Satuan Ukur <br /> [Sheet Referensi Satuan Ukur]
                    </font>
                </b></td>
            <td style="background-color : #e3e3e3 ; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Realisasi Indikator</font>
                </b></td>
        </tr>

    </table>
    <!-- ************************************************************************** -->
</body>

</html>
