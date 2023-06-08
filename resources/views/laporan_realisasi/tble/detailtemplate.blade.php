<!DOCTYPE html>
<html>

<head>
    {{-- <title>{{ $dataPermohonan->ticket_permohonan }} - Detail</title> --}}
    <title> - Detail</title>
    <style type="text/css">
        @page {
            margin: 0cm 0cm;
        }

        body {
            height: 842px;
            width: 595px;
            /* to centre page on screen*/
            margin-left: auto;
            margin-right: auto;
            margin-top: 140px;
            margin-bottom: 30px;

        }

        header {
            position: fixed;
            /* top: 0.75cm;                 */
            top: -2px;
            margin-top: 10px;
            text-align: center
        }

        footer {
            position: fixed;
            bottom: 15px;
            text-align: center;
            margin-bottom: 10px;
        }

        hr {
            margin: 8px 0px;
            color: #333;
            height: 2px;
        }

        table {
            border-style: double;
            border-width: 3px;
            border-color: white;
        }


        table tr td {
            font-size: 13px;
        }
    </style>
</head>

<body>
    <header>
        <center>
            <table>
                <tr>
                    <td><img src="logo_only.png" height="80"></td>
                    <td>
                        <center>

                            <h2 style="height:14px;">KEMENTERIAN BADAN USAHA MILIK NEGARA <br />REPUBLIK INDONESIA</h2>
                            <br>
                            <font size="1">GEDUNG KEMENTERIAN BUMN, JALAN MEDAN MERDEKA SELATAN NO. 13 JAKARTA
                                10110<br />TELEPHONE (021) 2311949, SITUS www.bumn.go.id</font><br>

                        </center>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr style="width: 585px;background-color: #000;">
                    </td>
                </tr>

            </table>
        </center>
    </header>



    <footer>
        <center>
            <hr style="width: 585px;background-color: #000;">
            <div style="text-align: left;">AKHLAK - Amanah, Kompeten, Harmonis, Loyal, Adaptif, Kolaboratif</div>
        </center>
    </footer>

    <main>
        <h4 style="text-align: center; margin-bottom: 0;">TANDA BUKTI PELAPORAN ELEKTRONIK<br>PORTAL TJSL PERIODE
            {{ strtoupper(str_replace(['TW', '-'], ['TRIWULAN', strpos($data[0]['periode'], 'Audited') !== false || strpos($data[0]['periode'], 'Prognosa') !== false ? ' ' : '/'], $data[0]['periode'])) }}

        </h4><br>
        <p style="text-align: center;margin-top: 0;">{{ $perusahaan->nama_lengkap }}</p>
        <table style="border: 1px solid black;
        border-collapse: collapse;width: 100%;
        ">
            <tr>
                <th style="width: 4%;border: 1px solid black;
                border-collapse: collapse;">No</th>
                <th style="width: 24%;border: 1px solid black;
                border-collapse: collapse;">Jenis Laporan
                </th>
                <th style="width: 24%;border: 1px solid black;
                border-collapse: collapse;">Periode</th>
                <th style="width: 24%;border: 1px solid black;
                border-collapse: collapse;">Tanggal
                    Update
                </th>
                <th style="width: 24%;border: 1px solid black;
                border-collapse: collapse;">Status</th>
            </tr>
            @foreach ($data as $index => $item)
                <tr>
                    <td style="border: 1px solid black;
            border-collapse: collapse;">
                        {{ $index + 1 }}</td>
                    <td style="border: 1px solid black;
            border-collapse: collapse;">
                        {{ $item['jenis_laporan'] }}</td>
                    <td style="border: 1px solid black;
            border-collapse: collapse;">
                        {{ $item['periode'] }}</td>
                    <td style="border: 1px solid black;
            border-collapse: collapse;">
                        {{ $item['tanggal_update'] }}</td>
                    <td style="border: 1px solid black;
            border-collapse: collapse;"> {{ $item['status'] }}
                    </td>
                </tr>
            @endforeach
        </table>
        <p>Tanggal cetak : {{ $tanggal_cetak }}</p>
        <br><br><br><br><br>
        <br><br><br><br><br>
        <br><br><br><br><br>
        {{-- align right --}}
        <div>
            <div style="text-align: center;margin-left:45%;">
                <img src="qrcode.png" height="100">

            </div>
        </div>
    </main>
</body>

</html>
