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
            <td colspan="3"
                style="width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="center" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Rekapitulasi Data Perusahaan Unfilled {{ $tahun }}
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
                    <font face="Arial" size=4 color="#000000">Nama Lengkap Perusahaan</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">Nama Singkat Perusahaan</font>
                </b></td>
            {{-- <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Angka</font>
                </b></td>
            <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" size=4 color="#000000">ID Huruf</font>
                </b></td> --}}


        </tr>


        @php $num = 1; @endphp
        @foreach ($perusahaanUnfilled as $data)
            <tr>
                <td>{{ $num++ }}</td>
                <td>{{ @$data->nama_lengkap }}</td>
                <td>{{ @$data->nama_singkat }}</td>
                {{-- <td>{{ @$data->id_angka }}</td>
                <td>{{ @$data->id_huruf }}</td> --}}
            </tr>
        @endforeach

    </table>
    <!-- ************************************************************************** -->
</body>

</html>
