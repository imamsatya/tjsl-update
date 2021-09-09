


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
    <td colspan="7" style="width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Rekapitulasi Data Target TPB Per Pilar Pembangunan {{$tahun}}</font></b></td>
</tr>
<tr>
    <td style="width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">No</font></b></td>
    <td style="width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Perusahaan</font></b></td>
    <td style="width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Pilar Pembangunan</font></b></td>
    <td style="width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">TPB</font></b></td>
    <td style="width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Program</font></b></td>
    <td style="width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Alokasi Anggaran (Rp)</font></b></td>
    <td style="width:20px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Status</font></b></td>
    
</tr>


@php $num = 1; $total=0; @endphp
@foreach($target as $a)
<tr>
    <td>{{$num++}}</td>
    <td>{{@$a->anggaran_tpb->perusahaan->nama_lengkap}}</td>
    <td>{{@$a->anggaran_tpb->relasi->pilar->nama}}</td>
    <td>{{@$a->anggaran_tpb->relasi->tpb->no_tpb}} - {{@$a->anggaran_tpb->relasi->tpb->nama}}</td>
    <td>{{$a->program}}</td>
    <td>{{$a->anggaran_alokasi}}</td>
    <td>{{@$a->status->nama}}</td>
    {{$total += $a->anggaran}}
</tr>
@endforeach
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td style="text-align:right;"><b>Total</b></td>
    <td>{{$total}}</td>
</tr>

</table>
<!-- ************************************************************************** -->
</body>

</html>
