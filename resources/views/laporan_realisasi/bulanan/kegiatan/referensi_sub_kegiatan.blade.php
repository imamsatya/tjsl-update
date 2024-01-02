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
            <td>
                <strong>Referensi Jenis Kegiatan</strong>
            </td>
        </tr>
        <tr>
            <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" color="#000000">ID</font>
                </b></td>
            <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" color="#000000">Sub Kegiatan</font>
                </b></td>
            <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" color="#000000">Jenis Kegiatan ID</font>
                </b></td>
            <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" color="#000000">Jenis Kegiatan</font>
                </b></td>
            {{-- <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" color="#000000">Satuan Ukur ID</font>
                </b></td>
            <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                align="left" valign=middle><b>
                    <font face="Arial" color="#000000">Satuan Ukur</font>
                </b></td> --}}
        </tr>


        {{ $num = 1 }}
        @foreach ($sub_kegiatan as $sk)
            <tr>
                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000"
                    align="left" valign=middle sdval="1" sdnum="1033;">
                    <font face="Arial" color="#000000">{{ $sk->id }}</font>
                </td>
                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                    align="left" valign=middle>
                    <font face="Arial" color="#000000">{{ $sk->subkegiatan }}</font>
                </td>
                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                    align="left" valign=middle>
                    <font face="Arial" color="#000000">{{ $sk->jenis_kegiatan_id }}</font>
                </td>
                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                    align="left" valign=middle>
                    <font face="Arial" color="#000000">{{ $sk->nama_jenis_kegiatan }}</font>
                </td>
                {{-- <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                    align="left" valign=middle>
                    <font face="Arial" color="#000000">{{ $sk->satuan_ukur_id }}</font>
                </td>
                <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000"
                    align="left" valign=middle>
                    <font face="Arial" color="#000000">{{ $sk->nama_satuan_ukur }}</font>
                </td> --}}
            </tr>
        @endforeach

    </table>
    <!-- ************************************************************************** -->
</body>

</html>
