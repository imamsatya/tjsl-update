


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
    <td colspan="6" style="width:5px; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="center" valign=middle ><b><font face="Arial" size=4 color="#000000">Rekapitulasi Data Anggaran Per Pilar Pembangunan {{$tahun}}</font></b></td>
</tr>
<tr>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">No</font></b></td>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Perusahaan</font></b></td>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Pilar Pembangunan</font></b></td>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Jenis Anggaran (Pilar)</font></b></td>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">TPB</font></b></td>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Jenis Anggaran (TPB)</font></b></td>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Anggaran (Rp)</font></b></td>
    <td style=" border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Status</font></b></td>
    
</tr>


@php $num = 1; @endphp
@foreach($anggaran as $a)
<tr>
    <td>{{$num++}}</td>
    <td>{{@$a->perusahaan->nama_lengkap}}</td>
    <td>{{@$a->relasi->pilar->nama}}</td>
    <td>{{@$a->relasi->pilar->jenis_anggaran}}</td>
    <td>{{@$a->relasi->tpb->no_tpb}} - {{@$a->relasi->tpb->nama}}</td>
    <td>{{@$a->relasi->tpb->jenis_anggaran}}</td>
    <td>{{$a->anggaran}}</td>
    <td>{{@$a->status->nama}}</td>    
</tr>
@endforeach

</table>
<!-- ************************************************************************** -->
</body>

</html>
