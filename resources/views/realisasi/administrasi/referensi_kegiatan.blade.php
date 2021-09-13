


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
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Program</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">ID Program</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Kegiatan</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">ID Provinsi Kegiatan</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">ID Kabupaten/Kotamadya Kegiatan</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Indikator Capaian Kegiatan</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">ID Satuan Ukur</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Alokasi Anggaran</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Nama Provinsi Kegiatan</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Nama Kabupaten/Kotamadya Kegiatan</font></b></td>
    <td style="width:20px; background-color : #e3e3e3 ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial"  color="#000000">Nama Satuan Ukur</font></b></td>
</tr>


{{$num = 1 }}
@foreach($kegiatan as $a)
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle sdval="1" sdnum="1033;"><font face="Arial" color="#000000">{{$a->target_tpb->program}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->target_tpb_id}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle sdval="1" sdnum="1033;"><font face="Arial" color="#000000">{{$a->kegiatan}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->provinsi_id}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->kota_id}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->indikator}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->satuan_ukur_id}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->anggaran_alokasi}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->provinsi->nama}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->kota->nama}}</font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle><font face="Arial"  color="#000000">{{$a->satuan_ukur->nama}}</font></td>
</tr>
@endforeach

</table>
<!-- ************************************************************************** -->
</body>

</html>
