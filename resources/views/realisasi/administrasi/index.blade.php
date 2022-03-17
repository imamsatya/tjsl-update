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
                        <button type="button" class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search btn-search-active" style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-search cls-search btn-search-unactive" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        @if(!$view_only)
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;margin-right:3px;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;margin-right:3px;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-upload" style="margin-right:3px;" data-toggle="tooltip" title="Upload Data Program"><i class="bi bi-upload fs-3"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download Excel" style="margin-right:3px;"><i class="bi bi-file-excel fs-3"></i></button>
                            @if(auth()->user()->getRoleNames()[0] == "Admin TJSL" || auth()->user()->getRoleNames()[0] == "Super Admin")
                            <button type="button" class="btn btn-sm  btn-primary cls-sync"  data-toggle="tooltip" title="Sync Kegiatan Aplikasi Tjsl"><i class="bi bi-bootstrap-reboot"></i> Sync App TJSL</button>
                            @endif
                        @endif
                    </div>
                    <!--end::Search-->
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <div class="card-px py-10">
                  <div class="row" id="form-cari">
                    <!--begin: Datatable -->
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="filter_perusahaan_id" name="filter_perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
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
                            <label>Program</label>
                            <select class="form-select form-select-solid form-select2" id="filter_target_tpb_id" name="filter_target_tpb_id" data-kt-select2="true" data-placeholder="Pilih Program" data-allow-clear="true">
                                <option></option>
                                @foreach($target_tpb as $p)  
                                    <option value="{{ $p->id }}">{{ $p->program }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Pilar Pembangunan</label>
                            <select id="filter_pilar_pembangunan_id" class="form-select form-select-solid form-select2" name="filter_pilar_pembangunan_id" data-kt-select2="true" data-placeholder="Pilih Pilar" data-allow-clear="true">
                                <option></option>
                                @foreach($pilar as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>TPB</label>
                            <select id="filter_tpb_id" class="form-select form-select-solid form-select2" name="filter_tpb_id" data-kt-select2="true" data-placeholder="Pilih TPB" data-allow-clear="true">
                                <option></option>
                                @foreach($tpb as $p)  
                                    <option value="{{ $p->id }}">{{ $p->no_tpb }} - {{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Owner Program</label>
                            <select class="form-select form-select-solid form-select2" id="filter_owner" name="filter_owner" data-kt-select2="true" data-placeholder="Pilih Owner Program" data-allow-clear="true">
                                <option></option>
                                @foreach($owner as $op)  
                                    <option value="{{ $op->id }}">{{ $op->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Bulan</label>
                            <select class="form-select form-select-solid form-select2" id="filter_bulan" name="filter_bulan" data-kt-select2="true" data-placeholder="Pilih Bulan" data-allow-clear="true">
                                <option></option>
                                @foreach($bulans as $p)  
                                    <option value="{{ $p->id }}" {{ date('m') == $p->id? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="filter_tahun" name="filter_tahun" data-kt-select2="true" >
                                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                    @php
                                        $select = (($i == $tahun) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                        <div class="col-lg-6 pt-7">
                            <button id="cari" class="btn btn-sm btn-success me-3">Cari</button>
                            @if(!$view_only)
                            @if($can_download_template)
                            <button id="download" class="btn btn-sm btn-primary me-3"><i class="bi bi-download fs-3"></i>Download Template</button>
                            @endif
                            @endif
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                  </div>
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Bulan</th>
                                <th>Program</th>
                                <th>Kegiatan </th>
                                <th>Kota</th>
                                <th style="text-align:center">Target & Realisasi</th>
                                <th>Anggaran</th>
                                <th>Status</th>
                                <th style="text-align:center;width:120px;" >Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>
@endsection

@section('addafterjs')
<script>
    var datatable;
    var urlcreate = "{{route('realisasi.administrasi.create')}}";
    var urledit = "{{route('realisasi.administrasi.edit')}}";
    var urlstore = "{{route('realisasi.administrasi.store')}}";
    var urldatatable = "{{route('realisasi.administrasi.datatable')}}";
    var urldelete = "{{route('realisasi.administrasi.delete')}}";
    var urldetail = "{{route('realisasi.administrasi.detail')}}";
    var urldownloadtemplate = "{{route('realisasi.administrasi.download_template')}}";
    var urlexport = "{{route('realisasi.administrasi.export')}}";
    var urlupload = "{{route('realisasi.administrasi.upload')}}";
    var urllog = "{{route('realisasi.administrasi.log_status')}}";
    var urluploadstore = "{{route('realisasi.upload_realisasi.store')}}";
    var urlvalidasi = "{{route('realisasi.administrasi.validasi')}}";
    var urlgetstatus = "{{route('realisasi.administrasi.get_status')}}";
    var urlapisync = "{{route('realisasi.administrasi.api_sync')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-log',function(){
            winform(urllog, {'id':$(this).data('id')}, 'Log Status');
        });

        $('body').on('click','.cls-add',function(){
            winform(urlcreate, {}, 'Tambah Data');
        });

        $('body').on('click','.cls-button-detail',function(){
            winform(urldetail, {'id':$(this).data('id')}, 'Ubah Data');
        });

        $('body').on('click','.cls-export',function(){
            exportExcel();
        });

        $('body').on('click','.cls-sync',function(){
            syncKegiatan();
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
        
        $('body').on('click','.btn-validasi',function(){
            onbtnvalidasi(this);
        });

        $('body').on('click','.btn-cancel-validasi',function(){
            onbtncancelvalidasi(this);
        });

        $('body').on('click','.btn-disable-validasi',function(){
            onbtndisablevalidasi(this);
        });

        $('body').on('click','.cls-upload',function(){
            winform(urlupload, {}, 'Upload Data');
        });

        $('body').on('click','.btn-search-active',function(){
            $('.btn-search-active').hide();
            $('.btn-search-unactive').show();
            $('#form-cari').toggle(600);
        });

        $('body').on('click','.btn-search-unactive',function(){
            $('.btn-search-active').show();
            $('.btn-search-unactive').hide();
            $('#form-cari').toggle(600);
        });

        $('#cari').on('click', function(event){
            datatable.ajax.reload( null, false );
            showValidasi();
        });
        
        if(!"{{ $admin_bumn }}"){
            showValidasi();
        }

        setDatatable();
    });
    
    function showValidasi(){
        var filter_perusahaan_id = $("select[name='filter_perusahaan_id']").val();
        var filter_tahun = $("select[name='filter_tahun']").val();
        var filter_bulan = $("select[name='filter_bulan']").val();

        if(filter_perusahaan_id == '' || filter_tahun == '' || filter_bulan == ''){
            $('.btn-disable-validasi').show();
            $('.btn-validasi').hide();
            $('.btn-cancel-validasi').hide();
        }else{
            $.ajax({
                url: urlgetstatus,
                data: {
                    'perusahaan_id' : filter_perusahaan_id,
                    'tahun' : filter_tahun,
                    'bulan' : filter_bulan,
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

    function onbtndisablevalidasi(){
        swal.fire({
            title: "Gagal",
            html: 'Pilihan BUMN, Bulan dan Tahun wajib diisi!',
            icon: 'error',

            buttonsStyling: true,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
        }); 
    }

    function onbtnvalidasi(){
        swal.fire({
            title: "Pemberitahuan",
            text: "Validasi Data Target TPB "+$("#filter_perusahaan_id option:selected").text() +" bulan " + $("#filter_bulan option:selected").text() +" tahun "+$("#filter_tahun").val()+" ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Validasi",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlvalidasi,
                data: {
                    'perusahaan_id' : $("select[name='filter_perusahaan_id']").val(),
                    'tahun' : $("select[name='filter_tahun']").val(),
                    'bulan' : $("select[name='filter_bulan']").val(),
                    'status_id' : 1,
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });

                    if(data.flag == 'success') {
                        datatable.ajax.reload( null, false );
                        // location.reload(); 
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

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });  
                    }
                });
            }
        });	
    }
    
    function onbtncancelvalidasi(){
        swal.fire({
            title: "Pemberitahuan",
            text: "Batalkan Validasi Data Aggaran TPB "+$("#filter_perusahaan_id option:selected").text() +" bulan " + $("#filter_bulan option:selected").text() +" tahun "+$("#filter_tahun").val()+" ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Batalkan Validasi",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlvalidasi,
                data: {
                    'perusahaan_id' : $("select[name='filter_perusahaan_id']").val(),
                    'tahun' : $("select[name='filter_tahun']").val(),
                    'bulan' : $("select[name='filter_bulan']").val(),
                    'status_id' : 2,
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
                            html: 'Sukses Batalkan Validasi',
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    });

                    if(data.flag == 'success') {
                        datatable.ajax.reload( null, false );
                        // location.reload(); 
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

    function syncKegiatan()
    {
        swal.fire({
            title: "Pemberitahuan",
            text: " Yakin ingin Sinkronisasi Kegiatan dari Aplikasi TJSL  ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Sinkronisasi",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlapisync,
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
                        // location.reload(); 
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


    function exportExcel()
    {
        $.ajax({
            type: 'post',
            data: {
                'perusahaan_id' : $("select[name='filter_perusahaan_id']").val(),
                'bulan' : $("select[name='filter_bulan']").val(),
                'tahun' : $("select[name='filter_tahun']").val(),
                'pilar_pembangunan_id' : $("select[name='filter_pilar_pembangunan_id']").val(),
                'tpb_id' : $("select[name='filter_tpb_id']").val()
            },
            beforeSend: function () {
                $.blockUI();
            },
            url: urlexport,
            xhrFields: {
                responseType: 'blob',
            },
            success: function(data){
                $.unblockUI();

                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                
                today = dd + '-' + mm + '-' + yyyy;
                var filename = 'Data Kegiatan '+today+'.xlsx';

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

    function downloadTemplate()
    {
        var filter_perusahaan_id = $("select[name='filter_perusahaan_id']").val();
        var filter_tahun = $("select[name='filter_tahun']").val();
        var filter_bulan = $("select[name='filter_bulan']").val();

        if(filter_perusahaan_id == '' || filter_tahun == '' || filter_bulan == ''){
            onbtndisablevalidasi();
        }else{
            $.ajax({
                type: 'post',
                data: {
                    'perusahaan_id' : $("select[name='filter_perusahaan_id']").val(),
                    'tahun' : $("select[name='filter_tahun']").val(),
                    'target_tpb_id' : $("select[name='filter_target_tpb_id']").val(),
                    'pilar_pembangunan_id' : $("select[name='filter_pilar_pembangunan_id']").val(),
                    'tpb_id' : $("select[name='filter_tpb_id']").val(),
                    'bulan' : $("select[name='filter_bulan']").val(),
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
                    var filename = 'Template Kegiatan Bulan '+ $("#filter_bulan option:selected").text() +" Tahun "+$("#filter_tahun").val()+'.xlsx';

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
        }
        return false;
    }

    function setDatatable(){
        datatable = $('#datatable').DataTable({
            processing: true,
            bFilter: true,
            serverSide: true,
            beforeSend: function(){
                $.blockUI();
            },
            ajax: {
                url: urldatatable,
                type: 'GET',
                data: function (d) {
                    d.perusahaan_id = $("select[name='filter_perusahaan_id']").val();
                    d.tahun = $("select[name='filter_tahun']").val();
                    d.target_tpb_id = $("select[name='filter_target_tpb_id']").val();
                    d.pilar_pembangunan_id = $("select[name='filter_pilar_pembangunan_id']").val();
                    d.tpb_id = $("select[name='filter_tpb_id']").val();
                    d.bulan = $("select[name='filter_bulan']").val();
                    d.owner = $("select[name='filter_owner']").val();
                }
            },
            columns: [
                { data: 'id', orderable: false, searchable: false },
                { data: 'bulan', name: 'bulan' },
                { data: 'program', name: 'program' },
                { data: 'kegiatan', name: 'kegiatan' },
                { data: 'kota', name: 'kota' },
                { data: 'realisasi', name: 'realisasi' },
                { data: 'anggaran', name: 'anggaran' },
                { data: 'status', name: 'status' },
                { data: 'action', name:'action'},
            ],
            drawCallback: function( settings ) {
                var info = datatable.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                } );
            }
        });
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
