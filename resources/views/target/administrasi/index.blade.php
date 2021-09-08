@extends('layouts.app')

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
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;"  data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button> &nbsp
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-upload"  data-toggle="tooltip" title="Upload Data"><i class="bi bi-upload fs-3"></i></button> &nbsp
                        <button type="button" class="btn btn-warning btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download Excel"><i class="bi bi-file-excel fs-3"></i></button>
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
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
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
                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" >
                                @php for($i = date("Y"); $i>=2020; $i--){ @endphp
                                    @php
                                        $select = (($i == $tahun) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <div class="col-lg-6">
                            <label>Pilar Pembangunan</label>
                            <select id="pilar_pembangunan_id" class="form-select form-select-solid form-select2" name="pilar_pembangunan_id" data-kt-select2="true" data-placeholder="Pilih Pilar" data-allow-clear="true">
                                <option></option>
                                @foreach($pilar as $p)  
                                    @php
                                        $select = (($p->id == $pilar_pembangunan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>TPB</label>
                            <select id="tpb_id" class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true" data-placeholder="Pilih TPB" data-allow-clear="true">
                                <option></option>
                                @foreach($tpb as $p)  
                                    @php
                                        $select = (($p->id == $tpb_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->no_tpb }} - {{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <div class="col-lg-6">
                            <label>Status</label>
                            <select id="status_id" class="form-select form-select-solid form-select2" name="status_id" data-kt-select2="true" data-placeholder="Pilih Status" data-allow-clear="true">
                                <option></option>
                                @foreach($status as $p)  
                                    @php
                                        $select = (($p->id == $status_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 pt-7">
                            <button id="cari" class="btn btn-sm btn-success me-3">Cari</button>
                            <button id="download" class="btn btn-sm btn-primary me-3"><i class="bi bi-download fs-3"></i>Download Template</button>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                    
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>
@endsection

@section('addafterjs')
<script>
    var urlcreate = "{{route('target.administrasi.create')}}";
    var urlupload = "{{route('target.administrasi.upload')}}";
    var urledit = "{{route('target.administrasi.edit')}}";
    var urlstore = "{{route('target.administrasi.store')}}";
    var urldelete = "{{route('target.administrasi.delete')}}";
    var urldownloadtemplate = "{{route('target.administrasi.download_template')}}";
    var urlgetstatus = "{{route('target.administrasi.get_status')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-upload',function(){
            winform(urlupload, {}, 'Upload Data');
        });

        $('body').on('click','.cls-button-edit',function(){
            winform(urledit, {'id':$(this).data('id')}, 'Ubah Data');
        });

        $('body').on('click','.cls-button-delete',function(){
            onbtndelete(this);
        });
        
        $('body').on('click','#download',function(){
            downloadTemplate();
        });
        
        $('#cari').on('click', function(event){
            // datatable.ajax.reload()
            var url = window.location.origin + '/target/administrasi/index';
            var perusahaan_id = $('#perusahaan_id').val();
            var tahun = $('#tahun').val();
            var pilar_pembangunan_id = $('#pilar_pembangunan_id').val();
            var tpb_id = $('#tpb_id').val();
            var status_id = $('#status_id').val();

            window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun + '&pilar_pembangunan_id=' + pilar_pembangunan_id + '&tpb_id=' + tpb_id + '&status_id=' + status_id;
        });

        if(!"{{ $admin_bumn }}"){
            showValidasi();
        }
        
        if("{{ $perusahaan_id }}" == ''){
            $('.table-responsive').hide();
        }else{
            $('.table-responsive').show();
        }
    });

    function downloadTemplate()
    {
        $.ajax({
            type: 'post',
            data: {
                'perusahaan_id' : $("select[name='perusahaan_id']").val(),
                'tahun' : $("select[name='tahun']").val(),
                'pilar_pembangunan_id' : $("select[name='pilar_pembangunan_id']").val(),
                'tpb_id' : $("select[name='tpb_id']").val()
            },
            beforeSend: function () {
                $.blockUI();
            },
            url: urldownloadtemplate,
            xhrFields: {
                responseType: 'blob',
            },
            success: function(data){
                $.unblockUI();
                var filename = 'Template Data Target TPB.xlsx';

                var blob = new Blob([data], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);

                link.click();
                document.body.removeChild(link);
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

                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
            });      
                
            }
        });
        return false;
    }

    function showValidasi(){
        var perusahaan_id = $("select[name='perusahaan_id']").val();
        var tahun = $("select[name='tahun']").val();

        if(perusahaan_id == '' || tahun == ''){
            $('.btn-disable-validasi').show();
            $('.btn-validasi').hide();
            $('.btn-cancel-validasi').hide();
        }else{
            $.ajax({
                url: urlgetstatus,
                data: {
                    'perusahaan_id' : perusahaan_id,
                    'tahun' : tahun
                },
                type:'post',
                dataType:'json',
                beforeSend: function(){
                    $.blockUI();
                },
                success: function(data){
                    $.unblockUI();
                    
                    if(data.status_id==1){
                        $('.btn-disable-validasi').hide();
                        $('.btn-validasi').hide();
                        $('.btn-cancel-validasi').show();
                    }else if(data.status_id==2){
                        $('.btn-disable-validasi').hide();
                        $('.btn-validasi').show();
                        $('.btn-cancel-validasi').hide();
                    }
                }
            });
        }
    }

    function onbtndelete(element){
        swal.fire({
            title: "Pemberitahuan",
            text: "Yakin hapus data "+$(element).data('nama')+" ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urldelete,
                data:{
                    "id": $(element).data('id')
                },
                type:'post',
                dataType:'json',
                beforeSend: function(){
                    $.blockUI();
                },
                success: function(data){
                    $.unblockUI();

                    swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    });

                    if(data.flag == 'success') {
                        datatable.ajax.reload( null, false );
                    }
                    
                },
                error: function(jqXHR, exception) {
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

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    });  
                    }
                });
            }
        });	
    }
</script>
@endsection
