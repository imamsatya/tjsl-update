<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PUMK PDF</title>

<style>
    body{
        margin: center;
    }
    .text-right{
        text-align: right;
        padding: 15px;
    }
    .text-left{
        text-align: left;
        padding-left: 15px;
        padding-right: 10px;
    }
    .text-center{
        text-align: center;
        padding: 15px;
    }
    #tbl thead tr{
        border-bottom: 1px ridge;
        padding: 15px;
    }
    #tbl thead tr{
        border-bottom: 1px ridge;
        padding: 15px;
    }
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
</head>

        <body class="antialiased container mt-5">
            <h4 class="card-title align-items-start flex-column " style="text-align: center;">
                <span class="card-label fw-bolder fs-3 mb-1" >Data PUMK {{$data->bumn_lengkap == null? '':$data->bumn_lengkap}} {{$data->periode== null? '':'Periode '. $data->periode}} <br>{{$data->tahun== null? '':'Tahun '. $data->tahun}}</span>
            </h4>

                <div class="container" >
                    <table class="table" id="tbl">
                        <thead>
                            <tr style="border-bottom:ridge;">
                                <th class="text-left"></th>
                                <th class="text-center"><strong>RKA</strong></th>
                                <th class="text-center"><strong>{{$data->periode  == null? 'Periode': $data->periode}}</strong></th>
                                <th class="text-center"><strong>(%)</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" style="padding: 12px;background-color:#AED6F1;"><b> I.  Dana Tersedia </b></td>
                            </tr>
                            <tr>
                                <td class="text-left">Saldo Awal</td>
                                <td class="text-right">{{ $data_rka->saldo_awal == null? 0 : (substr($data_rka->saldo_awal,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->saldo_awal,0,'.','.')).')' : number_format($data_rka->saldo_awal,0,'.','.')) }}</td>
                                <td class="text-right">{{ $data->saldo_awal == null? 0 : (substr($data->saldo_awal,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->saldo_awal,0,'.','.')).')' : number_format($data->saldo_awal,0,'.','.')) }}</td>
                                <td class="text-right">{{$p_saldo_awal == null? 0 : (substr($p_saldo_awal,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_saldo_awal, 2, ',', ' ')).')' : number_format($p_saldo_awal, 2, ',', ' '))}}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Pengembalian Dana Dari Mitra Binaan</td>
                                <td class="text-right">{{ $data_rka->income_mitra_binaan == null? 0 : (substr($data_rka->income_mitra_binaan,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_mitra_binaan,0,'.','.')).')' : number_format($data_rka->income_mitra_binaan,0,'.','.')) }}</td>
                                <td class="text-right">{{ $data->income_mitra_binaan == null? 0 : (substr($data->income_mitra_binaan,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_mitra_binaan,0,'.','.')).')' : number_format($data->income_mitra_binaan,0,'.','.')) }}</td>
                                <td class="text-right">{{$p_income_mitra_binaan == null? 0 : (substr($p_income_mitra_binaan,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_mitra_binaan, 2, ',', ' ')).')' : number_format($p_income_mitra_binaan, 2, ',', ' '))}}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Pengembalian Dana Dari BUMN Pembina Lain</td>
                                <td class="text-right">{{ $data_rka->income_bumn_pembina_lain == null? 0 : (substr($data_rka->income_bumn_pembina_lain,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_bumn_pembina_lain,0,'.','.')).')' : number_format($data_rka->income_bumn_pembina_lain,0,'.','.')) }}</td>
                                <td class="text-right">{{$data->income_bumn_pembina_lain == null? 0 : (substr($data->income_bumn_pembina_lain,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_bumn_pembina_lain,0,'.','.')).')' : number_format($data->income_bumn_pembina_lain,0,'.','.'))}}</td>
                                <td class="text-right">{{$p_income_bumn_pembina_lain == null? 0 : (substr($p_income_bumn_pembina_lain,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_bumn_pembina_lain, 2, ',', ' ')).')' : number_format($p_income_bumn_pembina_lain, 2, ',', ' '))}}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Pendapatan Jasa Admin PUMK</td>
                                <td class="text-right">{{$data_rka->income_jasa_adm_pumk == null? 0 : (substr($data_rka->income_jasa_adm_pumk,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_jasa_adm_pumk,0,'.','.')).')' : number_format($data_rka->income_jasa_adm_pumk,0,'.','.'))}}</td>
                                <td class="text-right">{{$data->income_jasa_adm_pumk == null? 0 : (substr($data->income_jasa_adm_pumk,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_jasa_adm_pumk,0,'.','.')).')' : number_format($data->income_jasa_adm_pumk,0,'.','.'))}}</td>
                                <td class="text-right">{{$p_income_jasa_adm_pumk == null? 0 : (substr($p_income_jasa_adm_pumk,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_jasa_adm_pumk, 2, ',', ' ')).')' : number_format($p_income_jasa_adm_pumk, 2, ',', ' '))}}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Pendapatan Jasa Bank (Net)</td>
                                <td class="text-right">{{$data_rka->income_adm_bank == null? 0 : (substr($data_rka->income_adm_bank,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_adm_bank,0,'.','.')).')' : number_format($data_rka->income_adm_bank,0,'.','.'))}}</td>
                                <td class="text-right">{{$data->income_adm_bank == null? 0 : (substr($data->income_adm_bank,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_adm_bank,0,'.','.')).')' : number_format($data->income_adm_bank,0,'.','.'))}}</td>
                                <td class="text-right">{{$p_income_adm_bank == null? 0 : (substr($p_income_adm_bank,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_adm_bank, 2, ',', ' ')).')' : number_format($p_income_adm_bank, 2, ',', ' '))}}</td>
                            </tr>
                            <tr style="border-top:ridge;">
                                <td class="text-left"><strong>Total Dana Tersedia</strong></td>
                                <td class="text-right"><strong>{{$data_rka->income_total == null? 0 : (substr($data_rka->income_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_total,0,'.','.')).')' : number_format($data_rka->income_total,0,'.','.'))}}</strong></td>
                                <td class="text-right"><strong>{{$data->income_total == null? 0 : (substr($data->income_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_total,0,'.','.')).')' : number_format($data->income_total,0,'.','.'))}}</strong></td>
                                <td class="text-right"><strong>{{$p_income_total == null? 0 : (substr($p_income_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_total, 2, ',', ' ')).')' : number_format($p_income_total, 2, ',', ' '))}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" style="padding: 12px;background-color:#F8C471 ;"><b> II.  Dana Disalurkan </b></td>
                            </tr>               
                            <tr>
                                <td class="text-left">Penyaluran Mandiri</td>
                                <td class="text-right">{{$data_rka->outcome_mandiri == null? 0 : (substr($data_rka->outcome_mandiri,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_mandiri,0,'.','.')).')' : number_format($data_rka->outcome_mandiri,0,'.','.'))}}</td>
                                <td class="text-right">{{$data->outcome_mandiri == null? 0 : (substr($data->outcome_mandiri,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_mandiri,0,'.','.')).')' : number_format($data->outcome_mandiri,0,'.','.'))}}</td>
                                <td class="text-right">{{$p_outcome_mandiri == null? 0 : (substr($p_outcome_mandiri,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_mandiri, 2, ',', ' ')).')' : number_format($p_outcome_mandiri, 2, ',', ' '))}}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Penyaluran Kolaborasi/BUMN</td>
                                <td class="text-right">{{$data_rka->outcome_kolaborasi_bumn == null? 0 : (substr($data_rka->outcome_kolaborasi_bumn,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_kolaborasi_bumn,0,'.','.')).')' : number_format($data_rka->outcome_kolaborasi_bumn,0,'.','.'))}}</td>
                                <td class="text-right">{{$data->outcome_kolaborasi_bumn == null? 0 : (substr($data->outcome_kolaborasi_bumn,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_kolaborasi_bumn,0,'.','.')).')' : number_format($data->outcome_kolaborasi_bumn,0,'.','.'))}}</td>
                                <td class="text-right">{{$p_outcome_kolaborasi_bumn == null? 0 : (substr($p_outcome_kolaborasi_bumn,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_kolaborasi_bumn, 2, ',', ' ')).')' : number_format($p_outcome_kolaborasi_bumn, 2, ',', ' '))}}</td>
                            </tr>
                            <tr>
                                <td class="text-left">Penyaluran BUMN Khusus</td>
                                <td class="text-right">{{$data_rka->outcome_bumn_khusus == null? 0 : (substr($data_rka->outcome_bumn_khusus,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_bumn_khusus,0,'.','.')).')' : number_format($data_rka->outcome_bumn_khusus,0,'.','.'))}}</td>
                                <td class="text-right">{{$data->outcome_bumn_khusus == null? 0 : (substr($data->outcome_bumn_khusus,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_bumn_khusus,0,'.','.')).')' : number_format($data->outcome_bumn_khusus,0,'.','.'))}}</td>
                                <td class="text-right">{{$p_outcome_bumn_khusus == null? 0 : (substr($p_outcome_bumn_khusus,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_bumn_khusus, 2, ',', ' ')).')' : number_format($p_outcome_bumn_khusus, 2, ',', ' '))}}</td>
                            </tr>
                            <tr style="border-top:ridge;">
                                <td class="text-left"><strong>Total Dana Disalurkan</strong></td>
                                <td class="text-right"><strong>{{$data_rka->outcome_total == null? 0 : (substr($data_rka->outcome_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_total,0,'.','.')).')' : number_format($data_rka->outcome_total,0,'.','.'))}}</strong></td>
                                <td class="text-right"><strong>{{$data->outcome_total == null? 0 : (substr($data->outcome_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_total,0,'.','.')).')' : number_format($data->outcome_total,0,'.','.'))}}</strong></td>
                                <td class="text-right"><strong>{{$p_outcome_total == null? 0 : (substr($p_outcome_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_total, 2, ',', ' ')).')' : number_format($p_outcome_total, 2, ',', ' '))}}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <div>
                    <small><i>Tanggal cetak : {{date('d-m-Y')}}</i></small><br>
                    <small><i>Sumber : {{url('/')}}</i></small>
                </div>
        </body>
</html>