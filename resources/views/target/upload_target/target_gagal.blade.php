


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
    <td colspan="11" style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">PROGRAM DAN TARGET TPB BUMN</font></b></td>
</tr>
<tr>
    <td colspan="11" style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">{{ $perusahaan }}</font></b></td>
</tr>
<tr>
    <td colspan="11" style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Tahun {{ $tahun }}</font></b></td>
</tr>
<tr>
    <td style="background-color : #e3e3e3 ;width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">No</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Program</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Unit Owner</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Kriteria Program</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Core Subject<br> (ISO 26000)</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID TPB</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Kode Indikator</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Pelaksanaan Program</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">ID Mitra BUMN</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Jangka waktu penerapan <br>(dalam tahun)</font></b></td>
    <td style="background-color : #e3e3e3 ;width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Alokasi Anggaran <br>Tahun {{ $tahun }} <br>(dalam rupiah)</font></b></td>
</tr>

@php $num = 1; $total=0; @endphp
@foreach($target as $a)
<tr>
    <td>{{$num++}}</td>
    <td>{{$a->program}}</td>
    <td>{{$a->unit_owner}}</td>
    <td>{{@$a->jenis_program_id}}</td>
    <td>{{@$a->core_subject_id}}</td>
    <td>{{@$a->tpb_id}}</td>
    <td>{{@$a->kode_indikator_id}}</td>
    <td>{{@$a->cara_penyaluran_id}}</td>
    <td>{{@$a->mitra_bumn_id}}</td>
    <td>{{@$a->jangka_waktu}}</td>
    <td>{{$a->anggaran_alokasi}}</td>
</tr>
@endforeach
</table>
<!-- ************************************************************************** -->
</body>

</html>
