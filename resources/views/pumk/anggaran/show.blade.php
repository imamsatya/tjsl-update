
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
              @if(!$status_rka && $data->periode !== 'RKA')
              <div class="alert alert-danger text-center" role="alert">
                <i class="fa fa-exclamation-triangle fa-2x" style="color: red;"></i><br>
                <span>
                    <strong> 
                    Status RKA Belum Finish
                    </strong>
                </span> 
              </div>
              @endif
                <div class="form-group row" style="border:ridge;">
                    <div class="col-lg-12" style="background-color:#AED6F1;">
                        <strong>Dana Tersedia</strong>
                    </div>	
                    
                    <div class="container">
                        <table class="table table-striped table-bordered table-hover table-checkable">
                            <thead>
                                <tr style="border-bottom:ridge;">
                                    <th width="40%"></th>
                                    @if($data->periode !== 'RKA')
                                    <th class="text-right"><strong>RKA</strong></th>
                                    <th class="text-right"><strong>{{$data->periode  == null? 'Periode': $data->periode}}</strong></th>
                                    <th class="text-right"><strong>(%)</strong></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Saldo Awal</td>
                                    <td class="text-right">{{ $data_rka->saldo_awal == null? 0 : (substr($data_rka->saldo_awal,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->saldo_awal,0,'.','.')).')' : number_format($data_rka->saldo_awal,0,'.','.')) }}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{ $data->saldo_awal == null? 0 : (substr($data->saldo_awal,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->saldo_awal,0,'.','.')).')' : number_format($data->saldo_awal,0,'.','.')) }}</td>
                                    <td class="text-right">{{$p_saldo_awal == null? 0 : (substr($p_saldo_awal,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_saldo_awal, 2, ',', ' ')).')' : number_format($p_saldo_awal, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Pengembalian Dana Dari Mitra Binaan</td>
                                    <td class="text-right">{{ $data_rka->income_mitra_binaan == null? 0 : (substr($data_rka->income_mitra_binaan,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_mitra_binaan,0,'.','.')).')' : number_format($data_rka->income_mitra_binaan,0,'.','.')) }}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{ $data->income_mitra_binaan == null? 0 : (substr($data->income_mitra_binaan,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_mitra_binaan,0,'.','.')).')' : number_format($data->income_mitra_binaan,0,'.','.')) }}</td>
                                    <td class="text-right">{{$p_income_mitra_binaan == null? 0 : (substr($p_income_mitra_binaan,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_mitra_binaan, 2, ',', ' ')).')' : number_format($p_income_mitra_binaan, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Pengembalian Dana Dari BUMN Pembina Lain</td>
                                    <td class="text-right">{{ $data_rka->income_bumn_pembina_lain == null? 0 : (substr($data_rka->income_bumn_pembina_lain,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_bumn_pembina_lain,0,'.','.')).')' : number_format($data_rka->income_bumn_pembina_lain,0,'.','.')) }}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{$data->income_bumn_pembina_lain == null? 0 : (substr($data->income_bumn_pembina_lain,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_bumn_pembina_lain,0,'.','.')).')' : number_format($data->income_bumn_pembina_lain,0,'.','.'))}}</td>
                                    <td class="text-right">{{$p_income_bumn_pembina_lain == null? 0 : (substr($p_income_bumn_pembina_lain,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_bumn_pembina_lain, 2, ',', ' ')).')' : number_format($p_income_bumn_pembina_lain, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Pendapatan Jasa Admin PUMK</td>
                                    <td class="text-right">{{$data_rka->income_jasa_adm_pumk == null? 0 : (substr($data_rka->income_jasa_adm_pumk,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_jasa_adm_pumk,0,'.','.')).')' : number_format($data_rka->income_jasa_adm_pumk,0,'.','.'))}}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{$data->income_jasa_adm_pumk == null? 0 : (substr($data->income_jasa_adm_pumk,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_jasa_adm_pumk,0,'.','.')).')' : number_format($data->income_jasa_adm_pumk,0,'.','.'))}}</td>
                                    <td class="text-right">{{$p_income_jasa_adm_pumk == null? 0 : (substr($p_income_jasa_adm_pumk,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_jasa_adm_pumk, 2, ',', ' ')).')' : number_format($p_income_jasa_adm_pumk, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Pendapatan Jasa Bank (Net)</td>
                                    <td class="text-right">{{$data_rka->income_adm_bank == null? 0 : (substr($data_rka->income_adm_bank,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_adm_bank,0,'.','.')).')' : number_format($data_rka->income_adm_bank,0,'.','.'))}}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{$data->income_adm_bank == null? 0 : (substr($data->income_adm_bank,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_adm_bank,0,'.','.')).')' : number_format($data->income_adm_bank,0,'.','.'))}}</td>
                                    <td class="text-right">{{$p_income_adm_bank == null? 0 : (substr($p_income_adm_bank,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_adm_bank, 2, ',', ' ')).')' : number_format($p_income_adm_bank, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr style="border-top:ridge;">
                                    <td><strong>Total Dana Tersedia</strong></td>
                                    <td class="text-right"><strong>{{$data_rka->income_total == null? 0 : (substr($data_rka->income_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->income_total,0,'.','.')).')' : number_format($data_rka->income_total,0,'.','.'))}}</strong></td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right"><strong>{{$data->income_total == null? 0 : (substr($data->income_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->income_total,0,'.','.')).')' : number_format($data->income_total,0,'.','.'))}}</strong></td>
                                    <td class="text-right"><strong>{{$p_income_total == null? 0 : (substr($p_income_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_income_total, 2, ',', ' ')).')' : number_format($p_income_total, 2, ',', ' '))}}</strong></td>
                                    @endif
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
                                    @if($data->periode !== 'RKA')
                                    <th class="text-right"><strong>RKA</strong></th>
                                    <th class="text-right"><strong>{{$data->periode == null? 'Periode': $data->periode}}</strong></th>
                                    <th class="text-right"><strong>(%)</strong></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Penyaluran Mandiri</td>
                                    <td class="text-right">{{$data_rka->outcome_mandiri == null? 0 : (substr($data_rka->outcome_mandiri,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_mandiri,0,'.','.')).')' : number_format($data_rka->outcome_mandiri,0,'.','.'))}}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{$data->outcome_mandiri == null? 0 : (substr($data->outcome_mandiri,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_mandiri,0,'.','.')).')' : number_format($data->outcome_mandiri,0,'.','.'))}}</td>
                                    <td class="text-right">{{$p_outcome_mandiri == null? 0 : (substr($p_outcome_mandiri,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_mandiri, 2, ',', ' ')).')' : number_format($p_outcome_mandiri, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Penyaluran Kolaborasi/BUMN</td>
                                    <td class="text-right">{{$data_rka->outcome_kolaborasi_bumn == null? 0 : (substr($data_rka->outcome_kolaborasi_bumn,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_kolaborasi_bumn,0,'.','.')).')' : number_format($data_rka->outcome_kolaborasi_bumn,0,'.','.'))}}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{$data->outcome_kolaborasi_bumn == null? 0 : (substr($data->outcome_kolaborasi_bumn,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_kolaborasi_bumn,0,'.','.')).')' : number_format($data->outcome_kolaborasi_bumn,0,'.','.'))}}</td>
                                    <td class="text-right">{{$p_outcome_kolaborasi_bumn == null? 0 : (substr($p_outcome_kolaborasi_bumn,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_kolaborasi_bumn, 2, ',', ' ')).')' : number_format($p_outcome_kolaborasi_bumn, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Penyaluran BUMN Khusus</td>
                                    <td class="text-right">{{$data_rka->outcome_bumn_khusus == null? 0 : (substr($data_rka->outcome_bumn_khusus,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_bumn_khusus,0,'.','.')).')' : number_format($data_rka->outcome_bumn_khusus,0,'.','.'))}}</td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right">{{$data->outcome_bumn_khusus == null? 0 : (substr($data->outcome_bumn_khusus,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_bumn_khusus,0,'.','.')).')' : number_format($data->outcome_bumn_khusus,0,'.','.'))}}</td>
                                    <td class="text-right">{{$p_outcome_bumn_khusus == null? 0 : (substr($p_outcome_bumn_khusus,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_bumn_khusus, 2, ',', ' ')).')' : number_format($p_outcome_bumn_khusus, 2, ',', ' '))}}</td>
                                    @endif
                                </tr>
                                <tr style="border-top:ridge;">
                                    <td><strong>Total Dana Disalurkan</strong></td>
                                    <td class="text-right"><strong>{{$data_rka->outcome_total == null? 0 : (substr($data_rka->outcome_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data_rka->outcome_total,0,'.','.')).')' : number_format($data_rka->outcome_total,0,'.','.'))}}</strong></td>

                                    @if($data->periode !== 'RKA')
                                    <td class="text-right"><strong>{{$data->outcome_total == null? 0 : (substr($data->outcome_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($data->outcome_total,0,'.','.')).')' : number_format($data->outcome_total,0,'.','.'))}}</strong></td>
                                    <td class="text-right"><strong>{{$p_outcome_total == null? 0 : (substr($p_outcome_total,0,1) == "-"? '('.preg_replace('/-/',"",number_format($p_outcome_total, 2, ',', ' ')).')' : number_format($p_outcome_total, 2, ',', ' '))}}</strong></td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
            @if($status_rka)
            <div style="text-align: center;">
                <button class="btn-sm btn-info text-white" id="btn-pdf"><i class="fa fa-print text-white"></i> Cetak PDF</button>
            </div>
            @else
            <div style="text-align: center;" >
                <button class="btn-sm btn-secondary text-white" id="btn-pdf" style="cursor: not-allowed;" title="Status RKA belum Finish." disabled><i class="fa fa-print text-white"></i> Cetak PDF</button>
            </div>
            @endif


<script type="text/javascript">
    $("#btn-pdf").on('click',function () {
       window.location.href = "{{ route('pumk.anggaran.create-pdf',$data->id) }}";
    });
</script>