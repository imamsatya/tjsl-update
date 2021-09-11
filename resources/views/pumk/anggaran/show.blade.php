
<style>
    .text-right{
        text-align: right;
    }

</style>
    <!--begin::Anggaran PUMK-->
        <h4 class="card-title align-items-start flex-column ">
            <span class="card-label fw-bolder fs-3 mb-1" >Anggaran PUMK {{$data->bumn_lengkap == null? '':$data->bumn_lengkap}} {{$data->periode== null? '':'Periode '. $data->periode}} <br>{{$data->tahun== null? '':'Tahun '. $data->tahun}}</span>
        </h4>

            <div class="separator border-gray-200 mb-3"></div>
                <div class="form-group row" style="border:ridge;">
                    <div class="col-lg-12" style="background-color:#AED6F1;">
                        <strong>Dana Tersedia</strong>
                    </div>	
                    
                    <div class="container">
                        <table class="table table-striped table-bordered table-hover table-checkable">
                            <thead>
                                <tr style="border-bottom:ridge;">
                                    <th width="40%"></th>
                                    <th class="text-right"><strong>RKA</strong></th>
                                    <th class="text-right"><strong>{{$data->periode  == null? 'Periode': $data->periode}}</strong></th>
                                    <th class="text-right"><strong>(%)</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Saldo Awal</td>
                                    <td class="text-right">{{$data_rka->saldo_awal == null? 0 : number_format($data_rka->saldo_awal,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->saldo_awal == null? 0 : number_format($data->saldo_awal,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_saldo_awal, 2, ',', ' ')}}</td>
                                </tr>
                                <tr>
                                    <td>Pengembalian Dana Dari Mitra Binaan</td>
                                    <td class="text-right">{{$data_rka->income_mitra_binaan == null? 0 : number_format($data_rka->income_mitra_binaan,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->income_mitra_binaan == null? 0 : number_format($data->income_mitra_binaan,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_income_mitra_binaan, 2, ',', ' ')}}</td>
                                </tr>
                                <tr>
                                    <td>Pengembalian Dana Dari BUMN Pembina Lain</td>
                                    <td class="text-right">{{$data_rka->income_bumn_pembina_lain == null? 0 : number_format($data_rka->income_bumn_pembina_lain,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->income_bumn_pembina_lain == null? 0 : number_format($data->income_bumn_pembina_lain,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_income_bumn_pembina_lain, 2, ',', ' ')}}</td>
                                </tr>
                                <tr>
                                    <td>Pendapatan Jasa Admin PUMK</td>
                                    <td class="text-right">{{$data_rka->income_jasa_adm_pumk == null? 0 : number_format($data_rka->income_jasa_adm_pumk,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->income_jasa_adm_pumk == null? 0 : number_format($data->income_jasa_adm_pumk,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_income_jasa_adm_pumk, 2, ',', ' ')}}</td>
                                </tr>
                                <tr>
                                    <td>Pendapatan Jasa Bank (Net)</td>
                                    <td class="text-right">{{$data_rka->income_adm_bank == null? 0 : number_format($data_rka->income_adm_bank,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->income_adm_bank == null? 0 : number_format($data->income_adm_bank,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_income_adm_bank, 2, ',', ' ')}}</td>
                                </tr>
                                <tr style="border-top:ridge;">
                                    <td><strong>Total Dana Tersedia</strong></td>
                                    <td class="text-right"><strong>{{$data_rka->income_total == null? 0 : number_format($data_rka->income_total,0,'.','.')}}</strong></td>
                                    <td class="text-right"><strong>{{$data->income_total == null? 0 : number_format($data->income_total,0,'.','.')}}</strong></td>
                                    <td class="text-right"><strong>{{number_format($p_income_total, 2, ',', ' ')}}</strong></td>
                                </tr>               
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="form-group row" style="border:ridge;">
                    <div class="col-lg-12" style="background-color:#F8C471 ;">
                        <strong>Dana Disalurkan</strong>
                    </div>	
                    
                    <div class="container">
                        <table class="table table-striped table-bordered table-hover table-checkable">
                            <thead>
                                <tr style="border-bottom:ridge;">
                                    <th width="40%"></th>
                                    <th class="text-right"><strong>RKA</strong></th>
                                    <th class="text-right"><strong>{{$data->periode == null? 'Periode': $data->periode}}</strong></th>
                                    <th class="text-right"><strong>(%)</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Penyaluran Mandiri</td>
                                    <td class="text-right">{{$data_rka->outcome_mandiri == null? 0 : number_format($data_rka->outcome_mandiri,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->outcome_mandiri == null? 0 : number_format($data->outcome_mandiri,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_outcome_mandiri, 2, ',', ' ')}}</td>
                                </tr>
                                <tr>
                                    <td>Penyaluran Kolaborasi/BUMN</td>
                                    <td class="text-right">{{$data_rka->outcome_kolaborasi_bumn == null? 0 : number_format($data_rka->outcome_kolaborasi_bumn,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->outcome_kolaborasi_bumn == null? 0 : number_format($data->outcome_kolaborasi_bumn,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_outcome_kolaborasi_bumn, 2, ',', ' ')}}</td>
                                </tr>
                                <tr>
                                    <td>Penyaluran BUMN Khusus</td>
                                    <td class="text-right">{{$data_rka->outcome_bumn_khusus == null? 0 : number_format($data_rka->outcome_bumn_khusus,0,'.','.')}}</td>
                                    <td class="text-right">{{$data->outcome_bumn_khusus == null? 0 : number_format($data->outcome_bumn_khusus,0,'.','.')}}</td>
                                    <td class="text-right">{{number_format($p_outcome_bumn_khusus, 2, ',', ' ')}}</td>
                                </tr>
                                <tr style="border-top:ridge;">
                                    <td><strong>Total Dana Disalurkan</strong></td>
                                    <td class="text-right"><strong>{{$data_rka->outcome_total == null? 0 : number_format($data_rka->outcome_total,0,'.','.')}}</strong></td>
                                    <td class="text-right"><strong>{{$data->outcome_total == null? 0 : number_format($data->outcome_total,0,'.','.')}}</strong></td>
                                    <td class="text-right"><strong>{{number_format($p_outcome_total, 2, ',', ' ')}}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
