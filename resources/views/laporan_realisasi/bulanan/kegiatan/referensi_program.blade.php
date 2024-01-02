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
    <td>
       <strong>Referensi Program</strong> 
    </td>
</tr>
<tr>
    <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">ID</font></b></td>
    <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Nama Program</font></b></td>
    <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Jenis Anggaran</font></b></td>
    <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Pilar Pembangunan</font></b></td>
    <td style="background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">TPB</font></b></td>
</tr>


{{$num = 1 }}
@foreach($program as $data)
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle sdval="1" sdnum="1033;"><font face="Arial" color="#000000">{{$data->id}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$data->program}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$data->jenis_anggaran}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$data->nama_pilar_pembangunan}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$data->no_tpb}} - {{$data->nama_tpbs}}</font></td>
    </tr>
@endforeach

</table>
<!-- ************************************************************************** -->
</body>

</html>
