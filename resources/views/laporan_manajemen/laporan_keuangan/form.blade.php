@extends('layouts.app')

@section('addbeforecss')
    <link href="{{asset('plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .border_bottom {
            border-bottom: 1px solid #c8c7c7;
        }
        .table td 
        {
            vertical-align: middle;
        }
    </style>
@endsection

@section('content')
<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="d-flex align-items-center">{{ $pagetitle }}
                    <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                    </div>
                    <!--end::Search-->
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10">
                    <form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	                @csrf
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                $perusahaan_hidden = '';
                                if($admin_bumn){
                                    $perusahaan_hidden = '<input type="hidden" name="perusahaan_id" value="'.$perusahaan_id.'" />';
                                }
                            @endphp
                            {!!$perusahaan_hidden!!}
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }} required>
                                <option></option>
                                @foreach($perusahaan as $p)  
                                    @php
                                        $select = (($p->id == $perusahaan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" required >
                                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                    @php
                                        $select = (($i ==  date("Y")) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Periode Laporan</label>
                            <select class="form-select form-select-solid form-select2" id="periode_laporan_id" name="periode_laporan_id" data-kt-select2="true" data-placeholder="Pilih Periode" required>
                                <option></option>
                                @foreach($periode_laporan as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Jenis Laporan</label>
                            <select class="form-select form-select-solid form-select2" id="laporan_keuangan_id" name="laporan_keuangan_id" data-kt-select2="true" data-placeholder="Pilih Jenis Laporan" required>
                                <option></option>
                                @foreach($jenis_laporan as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <a id="proses" class="btn btn-primary btn-proses">Proses</a>
                            <a id="batal" class="btn btn-danger btn-proses">Batal</a>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>

                    <div class="input-laporan mb-5">
                    </div>
                    </form>
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>
@endsection

@section('addafterjs')
<script type="text/javascript" src="{{asset('plugins/jquery-treegrid-master/js/jquery.treegrid.js')}}"></script>

<script>
    var urlstore = "{{route('laporan_manajemen.laporan_keuangan.store')}}";
    var urlindex = "{{route('laporan_manajemen.laporan_keuangan.index')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','#batal',function(){
            window.location.href = urlindex;
        });

        $('body').on('click','#proses',function(){
            if(!$('#laporan_keuangan_id').val()){
                swal.fire({
                        title: "Gagal",
                        html: 'Pilihan Jenis laporan wajib diisi!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                });	
            }else if(!$('#perusahaan_id').val()){
                swal.fire({
                        title: "Gagal",
                        html: 'Pilihan BUMN wajib diisi!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                });	
            }else if(!$('#periode_laporan_id').val()){
                swal.fire({
                        title: "Gagal",
                        html: 'Pilihan Periode Laporan wajib diisi!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                });	
            }else if(!$('#tahun').val()){
                swal.fire({
                        title: "Gagal",
                        html: 'Pilihan Tahun wajib diisi!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                });	
            }else{
                onbtnproses(); 
            }
        });

        setFormValidate();
    });
    
    function setFormValidate(){
        $('#form-edit').validate({
            rules: {      		               		                              		               		               
            },
            messages: {                                  		                   		                   
            },	        
            highlight: function(element) {
                $(element).closest('.form-control').addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).closest('.form-control').removeClass('is-invalid');
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if(element.parent('.validated').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        submitHandler: function(form){
                var typesubmit = $("input[type=submit][clicked=true]").val();
                
                $(form).ajaxSubmit({
                    type: 'post',
                    url: urlstore,
                    data: {source : typesubmit},
                    dataType : 'json',
                    beforeSend: function(){
                        $.blockUI({
                            theme: true,
                            baseZ: 2000
                        })    
                    },
                    success: function(data){
                        $.unblockUI();

                        swal.fire({
                                title: data.title,
                                html: data.msg,
                                icon: data.flag,

                                buttonsStyling: true,

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });	                   

                        if(data.flag == 'success') {
                            $('#winform').modal('hide');
                            window.location.href = urlindex;
                        }
                    },
                    error: function(jqXHR, exception){
                        $.unblockUI();
                        var msgerror = '';
                        if (jqXHR.status === 0) {
                            msgerror = 'jaringan tidak terkoneksi.';
                        } else if (jqXHR.status == 404) {
                            msgerror = 'Halaman tidak ditemukan. [404]';
                        } else if (jqXHR.status == 500) {
                            msgerror = 'Internal Server Error [500].';
                        } else if (exception === 'parsererror') {
                            msgerror = 'Requested JSON parse gagal.';
                        } else if (exception === 'timeout') {
                            msgerror = 'RTO.';
                        } else if (exception === 'abort') {
                            msgerror = 'Gagal request ajax.';
                        } else {
                            msgerror = 'Error.\n' + jqXHR.responseText;
                        }
                        swal.fire({
                                title: "Error System",
                                html: msgerror+', coba ulangi kembali !!!',
                                icon: 'error',

                                buttonsStyling: true,

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });	                               
                    }
                });
                return false;
        }
        });		
    }
    
    function calculateFormula() {
        $('.nilai').each(function() {
            var sum = 0;
            var formula = $(this).attr('data-formula'); 
            var input_formula = $(this);
            if(formula){
                var arr_plus = formula.split('+');
                for(i=0;i<arr_plus.length;i++){
                    var arr_minus = arr_plus[i].split('-');
                    for(j=0;j<arr_minus.length;j++){
                        var class_name = '*[data-kode="'+arr_minus[j]+'"]';
                        var value = 0;
                        $(class_name).each( function() { 
                            if (this.value.length != 0) {
                                value = $(this).val().replace(/-\D/g, "").replace(/,/g, "");
                            }
                        });
                        if(j==0){
                            sum+=parseInt(value);
                        }else{
                            sum-=parseInt(value);
                        }
                    }
                }
                input_formula.val(addCommas(sum));
            }
        });
    }
    
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function calculateJumlah() {
        var sum = 0;
        $('.nilai').each(function() {
            if (this.value.length != 0) {
                Ins = this.value.replace(/-\D/g, "").replace(/,/g, "");
                var is_pengurangan = $(this).attr('data-is_pengurangan'); 
                if(is_pengurangan == 'true'){
                    sum -= parseInt(Ins);
                }else{
                    sum += parseInt(Ins);
                }
            }
        });
        var sums = parseFloat(sum).toLocaleString('en-US', {
                    style: 'decimal',
                });
        $(".jumlah").html(sums);
    }

    function onbtnproses(){
        $.ajax({
            url: "/laporan_manajemen/laporan_keuangan/getlaporankeuangan?id="+$('#laporan_keuangan_id').val()+"&perusahaan_id="+$('#perusahaan_id').val()+"&tahun="+$('#tahun').val()+"&periode_laporan_id="+$('#periode_laporan_id').val(),
            type: "POST",
            dataType: "json", 
            beforeSend: function(){
                $.blockUI({
                    theme: true,
                    baseZ: 2000
                })    
            },
            success: function(data){
                $.unblockUI();
                if(data.success){
                    $(".input-laporan").empty();

                    var contentData = '<div class="form-group row mb-5"><h4 style="text-align:center;font-weight:bold;">'+$('#laporan_keuangan_id option:selected').text()+'</h4></div>';
                    contentData += '<div class="form-group row mb-5"><div class="col-lg-12"><div class="table-responsive"><table class="table">';
                    for(var i = 0, len = data.parent.length; i < len; ++i) {
                        contentData += '<tr>';
                            contentData += '<td>';
                                contentData += '<span style="font-weight:bold;">'+data.parent[i].label+'</span>';
                            contentData += '</td>';
                            contentData += '<td>';
                            if(data.parent[i].formula!=null){
                                contentData += '<input name="relasi_id[]" type="hidden" value="'+data.parent[i].relasi_laporan_keuangan_id+'"/>';
                                contentData += '<input name="nilai[]" value="0" data-formula="'+data.parent[i].formula+'" data-is_pengurangan="'+data.parent[i].is_pengurangan+'" class="nilai form-control" data-kode="'+data.parent[i].kode+'" style="text-align:right;background-color:#eef3f7;" type="text" readonly/>';
                            }else if(data.parent[i].is_input){
                                contentData += '<input name="relasi_id[]" type="hidden" value="'+data.parent[i].relasi_laporan_keuangan_id+'"/>';
                                contentData += '<input name="nilai[]" data-is_pengurangan="'+data.parent[i].is_pengurangan+'" class="nilai nilai-input form-control" data-kode="'+data.parent[i].kode+'" style="text-align:right;" type="text"/>';
                            }else{
                                contentData += '<input name="relasi_id[]" type="hidden" value="'+data.parent[i].relasi_laporan_keuangan_id+'"/>';
                                contentData += '<input name="nilai[]" data-is_pengurangan="'+data.parent[i].is_pengurangan+'" class="nilai nilai-input form-control" data-kode="'+data.parent[i].kode+'" style="text-align:right;" type="hidden"/>';
                            }
                            contentData += '</td>';
                        contentData += '</tr>';

                        //get child
                        var child = data.child[data.parent[i].parent_id];
                        for(var j = 0, len2 = child.length; j < len2; ++j) {
                            contentData += '<tr>';
                                contentData += '<td>';
                                    contentData += '<span style="padding-left: 30px;">'+child[j].label+'</span>';
                                contentData += '</td>';
                                contentData += '<td>';
                                if(child[j].formula!=null){
                                    contentData += '<input name="relasi_id[]" type="hidden" value="'+child[j].relasi_laporan_keuangan_id+'"/>';
                                    contentData += '<input name="nilai[]" value="0" data-formula="'+child[j].formula+'" data-is_pengurangan="'+child[j].is_pengurangan+'" class="nilai form-control" data-kode="'+child[j].kode+'" style="text-align:right;background-color:#eef3f7;" type="text" readonly/>';
                                }else if(child[j].is_input){
                                    contentData += '<input name="relasi_id[]" type="hidden" value="'+child[j].relasi_laporan_keuangan_id+'"/>';
                                    contentData += '<input name="nilai[]" data-is_pengurangan="'+child[j].is_pengurangan+'" class="nilai nilai-input form-control" data-kode="'+child[j].kode+'" style="text-align:right;" type="text"/>';
                                }else{
                                    contentData += '<input name="relasi_id[]" type="hidden" value="'+child[j].relasi_laporan_keuangan_id+'"/>';
                                    contentData += '<input name="nilai[]" data-is_pengurangan="'+child[j].is_pengurangan+'" class="nilai nilai-input form-control" data-kode="'+child[j].kode+'" style="text-align:right;" type="hidden"/>';
                                }
                                contentData += '</td>';
                            contentData += '</tr>';
                        }
                    }

                    contentData += '<tr>';
                        contentData += '<td>';
                        contentData += '</td>';
                        contentData += '<td style="text-align:right;">';
                            contentData += '<button id="simpan" type="submit" class="btn btn-success me-3">Simpan</button>';
                        contentData += '</td>';
                    contentData += '</tr>';

                    contentData += '</table></div></div></div>';

                    $(".input-laporan").html(contentData);
                    $('.nilai').keyup(function(event) {
                        if(event.which >= 37 && event.which <= 40) return;
                            $(this).val(function(index, value) {
                            return value
                                .replace(/[^-\d]/g, '')
                                .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
                        });
                    });
                    $('.nilai-input').keyup(function() {
                        calculateFormula();
                    });
                }else{
                    swal.fire({
                            title: "Gagal",
                            html: 'Data Sudah Ada!',
                            icon: 'error',
                            buttonsStyling: true,
                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });	
                }
            },
            error: function(jqXHR, exception){
                $.unblockUI();
                var msgerror = '';
                if (jqXHR.status === 0) {
                    msgerror = 'jaringan tidak terkoneksi.';
                } else if (jqXHR.status == 404) {
                    msgerror = 'Halaman tidak ditemukan. [404]';
                } else if (jqXHR.status == 500) {
                    msgerror = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msgerror = 'Requested JSON parse gagal.';
                } else if (exception === 'timeout') {
                    msgerror = 'RTO.';
                } else if (exception === 'abort') {
                    msgerror = 'Gagal request ajax.';
                } else {
                    msgerror = 'Error.\n' + jqXHR.responseText;
                }
                swal.fire({
                        title: "Error System",
                        html: msgerror+', coba ulangi kembali !!!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                });	                               
            }                    
        });
    }
</script>
@endsection