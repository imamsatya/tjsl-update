


    <html>

<head>

<style type="text/css">
    body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Calibri"; font-size:x-small }
    a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
    a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
    comment { display:none;  } 
</style>

</head>

<body>
<table cellspacing="0" border="0">
<tr>
    <td colspan="11" style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">PROGRAM DAN KEGIATAN BUMN</font></b></td>
</tr>
<tr>
    <td colspan="11" style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">{{ @$perusahaan }}</font></b></td>
</tr>
<tr>
    <td colspan="11" style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Bulan {{ $bulan_string }} Tahun {{ $tahun }}</font></b></td>
    <td style="color:#ffffff;">{{ $bulan }}</td>
    <td style="color:#ffffff;">{{ $tahun }}</td>
</tr>
<tr>
    <td style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">No</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Program</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Kegiatan</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Provinsi <br>Kegiatan</font></b></td>
    <td style="background-color : #e3e3e3 ;width:25px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Kabupaten/<br>Kotamadya <br>Kegiatan</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Indikator Capaian <br>Kegiatan</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Satuan Ukur</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Alokasi Anggaran <br> Tahun {{ $tahun }} (Rp)</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Realisasi Anggaran<br>Bulan {{ $bulan_string }}</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Target<br>Bulan {{ $bulan_string }}</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Realisasi<br>Bulan {{ $bulan_string }}</font></b></td>
</tr>

@php $num = 1; @endphp
@foreach($kegiatan as $a)
<tr>
    <td>{{$num++}}</td>
    <td>{{@$a->target_tpb_id}}</td>
    <td>{{@$a->kegiatan}}</td>
    <td>{{@$a->provinsi_id}}</td>
    <td>{{@$a->kota_id}}</td>
    <td>{{@$a->indikator}}</td>
    <td>{{@$a->satuan_ukur_id}}</td>
    <td>{{@$a->anggaran_alokasi}}</td>
    <td>{{@$a->realisasi}}</td>
    <td>{{@$a->target}}</td>
    <td>{{@$a->anggaran}}</td>
</tr>
@endforeach
</table>
<!-- ************************************************************************** -->
</body>

</html>
