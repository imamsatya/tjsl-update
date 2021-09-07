    <!--begin::Anggaran PUMK-->
        <h4 class="card-title align-items-start flex-column ">
            <span class="card-label fw-bolder fs-3 mb-1" >Anggaran PUMK {{$data->bumn_lengkap == null? '':$data->bumn_lengkap}} {{$data->periode== null? '':'Periode '. $data->periode}} <br>{{$data->tahun== null? '':'Tahun '. $data->tahun}}</span>
        </h4>

            <div class="separator border-gray-200 mb-3"></div>
                <div class="form-group row">
                    <div class="col-lg-5">
                        <strong> I. Dana Tersedia</strong>
                    </div>	
                    <div class="col-lg-7">
                    </div>

                    <div class="col-lg-4 offset-sm-1">
                        <label style="padding-top: 20px;">Saldo Awal</label> 
                    </div>	
                    <div class="col-lg-7">
                        <input type="text" class="form-control input-saldo-awal incomes" name="saldo_awal" value="{{$data->saldo_awal == null? '-' : 'Rp. '.number_format($data->saldo_awal,0,',',',').',-'}}" readonly>
                    </div>
                    
                    <div class="col-lg-4 offset-sm-1">
                        <label style="padding-top: 20px;">Pengembalian Dana PUMK :</label> 
                    </div>	
                    <div class="col-lg-7">
                    </div>

                    <div class="col-lg-4 offset-sm-1">
                        <label style="padding-top: 15px;">&#9658; Dari Mitra Binaan </label> 
                    </div>	
                    <div class="col-lg-7">
                        <div class="col-md-12" style="padding-bottom : 10px;">
                            <input type="text" class="form-control input-income-mitra-binaan incomes" name="income_mitra_binaan" style="bottom: 20px;" value="{{$data->income_mitra_binaan == null? '-' : 'Rp. '.number_format($data->income_mitra_binaan,0,',',',').',-'}}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-4 offset-sm-1">
                        <label style="padding-top: 15px;">&#9658; Dari BUMN Pembina Lain </label> 
                    </div>	
                    <div class="col-lg-7">
                        <div class="col-md-12" style="padding-bottom : 10px;">
                            <input type="text" class="form-control input-income-pembina-lain incomes" name="income_bumn_pembina_lain" style="bottom: 20px;" value="{{$data->income_bumn_pembina_lain == null? '-' : 'Rp. '.number_format($data->income_bumn_pembina_lain,0,',',',').',-'}}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-4 offset-sm-1">
                        <label style="padding-top: 15px;">Pendapatan Jasa Admin PUMK</label> 
                    </div>	
                    <div class="col-lg-7">
                        <div class="col-md-12" style="padding-bottom : 10px;">
                            <input type="text" class="form-control  input-income-jasa-adm-pumk incomes" name="income_jasa_adm_pumk" style="bottom: 20px;" value="{{$data->income_jasa_adm_pumk == null? '-' : 'Rp. '.number_format($data->income_jasa_adm_pumk,0,',',',').',-'}}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-4 offset-sm-1">
                        <label style="padding-top: 15px;">Pendapatan Jasa Bank (Net)</label> 
                    </div>	
                    <div class="col-lg-7">
                        <div class="col-md-12" style="padding-bottom : 10px;">
                            <input type="text" class="form-control input-income-adm-bank incomes" name="income_adm_bank" style="bottom: 20px;" value="{{$data->income_adm_bank == null? '-' : 'Rp. '.number_format($data->income_adm_bank,0,',',',').',-'}}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-4 offset-sm-1">
                        <label style="padding-top: 15px;">Total Dana Tersedia </label> 
                    </div>	
                    <div class="col-lg-7">
                        <div class="col-md-12" style="padding-bottom : 10px;">
                            <input type="text" class="form-control sum-incomes" name="income_total" style="bottom: 20px;background-color:rgb(210, 226, 235)" value="{{$data->income_total == null? '-' : 'Rp. '.number_format($data->income_total,0,',',',').',-'}}" readonly>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <strong> II. Dana Disalurkan</strong>
                    </div>	
                    <div class="col-lg-6">
                    </div>

                <div class="col-lg-4 offset-sm-1">
                    <label style="padding-top: 20px;">Penyaluran Mandiri</label> 
                </div>	
                <div class="col-lg-7">
                    <div class="col-md-12" style="padding-bottom : 10px;">
                        <input type="text" class="form-control outcomes" name="outcome_mandiri" style="bottom: 20px;" value="{{$data->outcome_mandiri == null? '-' : 'Rp. '.number_format($data->outcome_mandiri,0,',',',').',-'}}" readonly>
                    </div>
                </div>

                <div class="col-lg-4 offset-sm-1">
                    <label style="padding-top: 15px;">Penyaluran Kolaborasi/BUMN </label> 
                </div>	
                <div class="col-lg-7">
                    <div class="col-md-12" style="padding-bottom : 10px;">
                        <input type="text" class="form-control outcomes" name="outcome_kolaborasi_bumn" style="bottom: 20px;" value="{{$data->outcome_kolaborasi_bumn == null? '-' : 'Rp. '.number_format($data->outcome_kolaborasi_bumn,0,',',',').',-'}}" readonly>
                    </div>
                </div>

                <div class="col-lg-4 offset-sm-1">
                    <label style="padding-top: 15px;">Penyaluran BUMN Khusus </label> 
                </div>	
                <div class="col-lg-7">
                    <div class="col-md-12" style="padding-bottom : 10px;">
                        <input type="text" class="form-control outcomes" name="outcome_bumn_khusus" style="bottom: 20px;" value="{{$data->outcome_bumn_khusus == null? '-' : 'Rp. '.number_format($data->outcome_bumn_khusus,0,',',',').',-'}}" readonly>
                    </div>
                </div>

                <div class="col-lg-4 offset-sm-1">
                    <label style="padding-top: 15px;">Total Dana Disalurkan </label> 
                </div>	
                <div class="col-lg-7">
                    <div class="col-md-12" style="padding-bottom : 10px;">
                        <input type="text" class="form-control sum-outcomes" name="outcome_total" style="bottom: 20px;background-color:rgb(210, 226, 235)" value="{{$data->outcome_total == null? '-' : 'Rp. '.number_format($data->outcome_total,0,',',',').',-'}}" readonly>
                    </div>
                </div>


                <div class="col-lg-5" style="padding-top : 15px;">
                   <strong>III. Saldo Akhir</strong> 
                </div>	
                <div class="col-lg-7">
                    <div class="col-md-12" style="padding-bottom : 10px;">
                        <input type="text" class="form-control saldo_akhirs" name="saldo_akhir" style="bottom: 20px;background-color:rgb(210, 226, 235)" value="{{$data->saldo_akhir == null? '-' : 'Rp. '.number_format($data->saldo_akhir,0,',',',').',-'}}" readonly>
                    </div>
                </div>
            </div>
