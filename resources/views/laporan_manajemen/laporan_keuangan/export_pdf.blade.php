<?php
// echo '<pre>';
// print_r($data);
// echo '</pre>';
// exit();
setlocale(LC_ALL, 'IND');
global $i;
$i = 0;
?>
<html>
<head>
    <style>
        /* custom */
        body,
        html, p, div, table {
            margin: 0;
            padding: 0;
            text-rendering: optimizeLegibility;
            font-size: 12px;
            color: #111315;
            font-family:"Book Antiqua";
            line-height: normal;
        }
        div{
            line-height:1;
        }

        h1 {
            
            text-align: center;
            font-size: 1.56em;
            line-height: 1;
            letter-spacing: 1px;
            margin:5px 0px;
        }

        hr{
            margin:8px 0px;
            color: #333;
            height:2px;
        }
        .subtitle{
            line-height:0;
            margin:0;
            font-size:10px;
        }
        table {
            font-weight:normal;
            border-collapse: collapse;
        }
        td{
            vertical-align:top;
        }
        /* custom */
        .page-break {
            page-break-after: always;
        }
        .page-break-avoid{
            page-break-inside: avoid;
        }
        /* default */
        .padding-0{
            padding: 0!important;
        }

        .width-33,.width-66,
        .width-15,.width-30,.width-45,.width-90,
        .width-50,.width-25,.width-75,.width-100 {
            float: left;
            padding: 0px 15px;
        }
        .width-33{
            width: 33.33333%;
        }
        .width-66{
            width: 66.667%;
        }
        .width-15{
            width: 15%;
        }
        .width-30{
            width: 30%;
        }
        .width-45{
            width: 45%;
        }
        .width-90{
            width: 90%;
        }
        .width-50 {
            width: 50%;
        }

        .width-25 {
            width: 25%;
        }

        .width-75 {
            width: 75%;
        }
        .width-85 {
            width: 85%;
        }
        .width-100 {
            width: 75%;
        }
        .clearfix{
            clear:both;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .text-lowercase {
            text-transform: lowercase;
        }

        .text-uppercase,
        .initialism {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }
        .margin-top-10 {
            margin-top:10px;
        }
        .margin-top-15 {
            margin-top:15px;
        }
        .margin-top-20 {
            margin-top:20px;
        }
        .margin-top-40 {
            margin-top:40px;
        }

        .border-top{
            border-top:3px solid #333;
            padding:0px 8px;
        }
        .left{
            float: left;
        }
        .bold{
            font-weight:bold;
        }
        .barcode {
            padding: 0.5mm;
            color: #000044;
            width:40px;
        }
        .barcodecell {
            text-align: center;
            vertical-align: top;
        }
        @page {
            size: auto;
            /*margin-header: 15mm;*/
            odd-footer-name: html_myfooter;
            even-footer-name: html_myfooter;
        }
        @page heads1 {
            odd-footer-name: html_myfooter;
            even-footer-name: html_myfooter;
        }
        @page heads2 {
            odd-footer-name: html_myfooter;
            even-footer-name: html_myfooter;
        }
        .head1 {
            page-break-before: right;
            page: heads1;
        }
        .head2 {
            page-break-before: right;
            page: heads2;
        }
        /* default */

        
        .foto-talenta {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            height: 100px;
        }
        tr.border_bottom td{
            border-bottom: 1px solid #d2d2d2;
            /* border-right: 1px solid #d2d2d2; */
        }
        table {
            border-collapse:separate; 
            font-size: 10px;
            text-align:center;
        }
        table td {
            padding:7px;
        }
    </style>
</head>
<body>
    <div class="padding-10"> 
        @foreach ($laporan_bumn as $b)  
            @php 
                $jenis = $laporan_jenis->where('perusahaan_id', $b->perusahaan_id);
            @endphp 
            @foreach ($jenis as $j)     
            <div class="margin-top-40">  
                <div style="text-align:center;margin-bottom:30px;margin-top:10px;">
                    <h4>PROGRAM KEMITRAAN DAN BINA LINGKUNGAN </h4>
                    <h4>{{strtoupper($b->nama_lengkap)}}</h4>
                    <h4>{{strtoupper($j->nama)}}</h4>
                    <h4>Tahun yang berakhir pada tanggal 31 Desember {{$tahun}}</h4>
                    <h4>(Disajikan dalam rupiah, kecuali dinyatakan lain)</h4>
                </div>
                            
                <!--begin: Datatable -->
                <div class="table-responsive">
                    <table width="100%" style="margin-left:40px;margin-right:40px;">
                        <tbody>
                            @php
                                $parent = $laporan_parent->where('perusahaan_id', $b->perusahaan_id)->where('laporan_keuangan_id', $j->laporan_keuangan_id);
                            @endphp 
                            @foreach ($parent as $p)   
                            <tr>
                                <td style="font-weight:bold;text-align:left;">{{$p->label}}</td>
                                <td style="text-align:right;">
                                    @php
                                        $nilai=number_format($p->nilai,0,'.','.');
                                        if($p->is_pengurangan || $p->nilai<0){
                                            $nilai = '('.number_format(abs($p->nilai),0,'.','.').')';
                                        }else if(!$p->is_input && $p->formula==''){
                                            $nilai = '';
                                        }
                                    @endphp
                                    {{$nilai}}
                                </td>
                            </tr> 
                            
                                @php 
                                    $child = $laporan_child->where('perusahaan_id', $b->perusahaan_id)->where('laporan_keuangan_id', $j->laporan_keuangan_id)->where('parent_id', $p->parent_id);
                                @endphp 
                                @foreach ($child as $c)   
                                <tr>
                                    <td style="text-align:left;">{{$c->label}}</td>
                                    <td style="text-align:right;">
                                        @php
                                            $nilai=number_format($c->nilai,0,'.','.');
                                            if($c->is_pengurangan || $c->nilai<0){
                                                $nilai = '('.number_format(abs($c->nilai),0,'.','.').')';
                                            }else if(!$c->is_input && $c->formula==''){
                                                $nilai = '';
                                            }
                                        @endphp
                                        {{$nilai}}
                                    </td>
                                </tr>  
                                @endforeach 
                            @endforeach
                        </tbody>
                    </table> 
                </div>
            </div>
            <!--mpdf
                <htmlpagefooter name="myfooter">
                    <div class="width-75 padding-0 text-left margin-top-20">
                        <i style="font-size:10px;">Copyright © TJSL Kementerian BUMN {{date('Y')}}</i>
                    </div>
                </htmlpagefooter>
            <sethtmlpagefooter name="myfooter" value="on" />
            mpdf-->
            <pagebreak />
            @endforeach
        @endforeach
    </div>

    
</body>
<html>