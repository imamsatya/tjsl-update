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
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;" data-kt-view-roles-table-select="delete_selected" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;" data-kt-view-roles-table-select="delete_selected" data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" data-kt-view-roles-table-select="delete_selected" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button> &nbsp
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-add" data-kt-view-roles-table-select="delete_selected" data-toggle="tooltip" title="Tambah Data"><i class="bi bi-plus fs-3"></i></button> &nbsp
                        <button type="button" class="btn btn-warning btn-sm btn-icon cls-export" data-kt-view-roles-table-select="delete_selected" data-toggle="tooltip" title="Download Excel"><i class="bi bi-file-excel fs-3"></i></button>
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
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" data-allow-clear="true">
                                <option></option>
                                @foreach($perusahaan as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" data-placeholder="Pilih Tahun" data-allow-clear="true">
                                <option></option>
                                @php for($i = date("Y"); $i>=2020; $i--){ @endphp
                                <option value="{{$i}}">{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Pilar Pembangunan</label>
                            <select class="form-select form-select-solid form-select2" name="pilar_pembangunan_id" data-kt-select2="true" data-placeholder="Pilih Pilar" data-allow-clear="true">
                                <option></option>
                                @foreach($pilar as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>TPB</label>
                            <select class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true" data-placeholder="Pilih TPB" data-allow-clear="true">
                                <option></option>
                                @foreach($tpb as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <button id="proses" class="btn btn-success me-3">Proses</button>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>BUMN</th>
                                <!-- <th>Pilar</th> -->
                                <th>Pilar - TPB</th>
                                <th>Anggaran</th>
                                <th>Status</th>
                                <th style="text-align:center;width:70px;" >Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th style="text-align:right;">Total</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
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
    var urlcreate = "{{route('anggaran_tpb.create')}}";
    var urledit = "{{route('anggaran_tpb.edit')}}";
    var urlstore = "{{route('anggaran_tpb.store')}}";
    var urldatatable = "{{route('anggaran_tpb.datatable')}}";
    var urldelete = "{{route('anggaran_tpb.delete')}}";
    var urlexport = "{{route('anggaran_tpb.export')}}";
    var urlvalidasi = "{{route('anggaran_tpb.validasi')}}";
    var urlgetstatus = "{{route('anggaran_tpb.get_status')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-add',function(){
            winform(urlcreate, {}, 'Tambah Data');
        });

        $('body').on('click','.cls-button-edit',function(){
            winform(urledit, {'id':$(this).data('id')}, 'Ubah Data');
        });

        $('body').on('click','.cls-button-delete',function(){
            onbtndelete(this);
        });

        $('body').on('click','.cls-export',function(){
            exportExcel();
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
        
        $('#proses').on('click', function(event){
            datatable.ajax.reload();
            showValidasi();
        });

        setDatatable();
    });

    function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function setDatatable(){
        datatable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urldatatable,
                type: 'GET',
                data: function (d) {
                    d.perusahaan_id = $("select[name='perusahaan_id']").val(),
                    d.tahun = $("select[name='tahun']").val(),
                    d.pilar_pembangunan_id = $("select[name='pilar_pembangunan_id']").val(),
                    d.tpb_id = $("select[name='tpb_id']").val()
                }
            },
            columns: [
                { data: 'id', orderable: false, searchable: false },
                { data: 'perusahaan', name: 'perusahaan' },
                // { data: 'pilar', name: 'pilar' },
                { data: 'tpb', name: 'tpb' },
                { data: 'anggaran', name: 'anggaran', className: 'text-center'},
                { data: 'status', name: 'status' },
                { data: 'action', name:'action'},
            ],
            footerCallback: function (row, data, start, end, display) {
                var api = this.api();
    
                var intVal = function ( i ) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                };
                
                $(api.column(3).footer()).html(api.column(3).data().reduce(function (a, b) {
                        return addCommas(intVal(a) + intVal(b));
                    }, 0)
                );
            },
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
            type: "warning",
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
                            type: data.flag,

                            buttonsStyling: false,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                            confirmButtonClass: "btn btn-default"
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
                        type: 'error',

                        buttonsStyling: false,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default"
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
                'perusahaan_id' : $("select[name='perusahaan_id']").val(),
                'tahun' : $("select[name='tahun']").val(),
                'pilar_pembangunan_id' : $("select[name='pilar_pembangunan_id']").val(),
                'tpb_id' : $("select[name='tpb_id']").val()
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
                var filename = 'Data Anggaran TPB '+today+'.xlsx';

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
                    type: 'error',

                    buttonsStyling: false,

                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    confirmButtonClass: "btn btn-default"
            });      
                
            }
        });
        return false;
    }
    
    function onbtndisablevalidasi(){
        swal.fire({
            title: "Gagal",
            html: 'Pilihan BUMN dan Tahun wajib diisi!',
            type: 'error',

            buttonsStyling: false,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
            confirmButtonClass: "btn btn-default"
        }); 
    }

    function onbtnvalidasi(){
        swal.fire({
            title: "Pemberitahuan",
            text: "Validasi Data Aggaran TPB "+$("#perusahaan_id option:selected").text() +" tahun "+$("#tahun").val()+" ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Validasi",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlvalidasi,
                data: {
                    'perusahaan_id' : $("select[name='perusahaan_id']").val(),
                    'tahun' : $("select[name='tahun']").val(),
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
                            type: data.flag,

                            buttonsStyling: false,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                            confirmButtonClass: "btn btn-default"
                    });

                    if(data.flag == 'success') {
                        datatable.ajax.reload( null, false );
                        showValidasi();
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
                        type: 'error',

                        buttonsStyling: false,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default"
                    });  
                    }
                });
            }
        });	
    }
    
    function onbtncancelvalidasi(){
        swal.fire({
            title: "Pemberitahuan",
            text: "Batalkan Validasi Data Aggaran TPB "+$("#perusahaan_id option:selected").text() +" tahun "+$("#tahun").val()+" ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Batalkan Validasi",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlvalidasi,
                data: {
                    'perusahaan_id' : $("select[name='perusahaan_id']").val(),
                    'tahun' : $("select[name='tahun']").val(),
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
                            type: data.flag,

                            buttonsStyling: false,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                            confirmButtonClass: "btn btn-default"
                    });

                    if(data.flag == 'success') {
                        datatable.ajax.reload( null, false );
                        showValidasi();
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
                        type: 'error',

                        buttonsStyling: false,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        confirmButtonClass: "btn btn-default"
                    });  
                    }
                });
            }
        });	
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
</script>
@endsection
