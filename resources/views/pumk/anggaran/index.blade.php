@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />

<style>
.border_bottom {
    border-bottom: 1px solid #c8c7c7;
}
.cls-button-log:hover{
    background-color: rgb(211, 249, 250);
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
                    <h2 class="d-flex align-items-center"><i class="fa fa-database" style="font-size: 20px;"></i> &nbsp {{ $pagetitle }}
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
                        <button type="button" class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search"  data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button> &nbsp
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-add"  data-toggle="tooltip" title="Tambah Data"><i class="bi bi-plus fs-3"></i></button> &nbsp
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
                <div class="card-px py-10" >
                  <div class="row" id="form-cari">
                    <div class="form-group row  mb-5" >
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
                                <option></option>
                                @foreach($perusahaan as $bumn)  
                                    @php
                                        $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Periode Laporan</label>
                            <select id="periode_id" class="form-select form-select-solid form-select2" name="periode_id" data-kt-select2="true" data-placeholder="Pilih Periode" data-allow-clear="true">
                                <option></option>
                                @foreach($periode as $p)  
                                    @php
                                        $select = (($p->id == $filter_periode_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Status</label>
                            <select id="status_id" class="form-select form-select-solid form-select2" name="status_id" data-kt-select2="true" data-placeholder="Pilih Status" data-allow-clear="true">
                                <option></option>
                                @foreach($status as $p)  
                                    @php
                                        $select = (($p->id == $filter_status_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" data-placeholder="Pilih Tahun" data-allow-clear="true">
                                @php
                                    for($i = date("Y"); $i>=2020; $i--){ @endphp
                                    <option value="{{$i}}">{{$i}}</option>
                                    @php }
                                    $select = (($i == date("Y")) ? 'selected="selected"' : '');
                                @endphp
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <button id="proses" class="btn-small btn-success me-3 text-white"><i class="fa fa-search text-white"></i> Filter</button>
                            <button  onclick="window.location.href='{{route('pumk.anggaran.index')}}'" class="btn-small btn-danger me-3 text-white"><i class="fa fa-times text-white"></i> Batal</button>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                </div>   
                    <!--begin: Datatable -->
                    <div class="table-responsive"  >
                        <table class="table table-striped table-bordered table-hover table-checkable" id="datatable">
                            <thead>
                                <tr style="border-top: ridge;">
                                    <th style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">No</th>
                                    <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">Tahun</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">BUMN</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Periode Laporan</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Dana Tersedia</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Dana Tersalurkan</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Saldo Akhir</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Status</th>
                                    <th style="text-align:center;width:100px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            {{--                  
                            @php $no=1; $total_income = 0; $total_outcome = 0; $total_saldo_akhir = 0;  @endphp
                            @foreach ($anggaran_pumk as $p)
                            @php
                                $total_income += $p->income_total;
                                $total_outcome += $p->outcome_total;
                                $total_saldo_akhir += $p->saldo_akhir;
                            @endphp                                  
                            {{-- <tfoot>
                                <tr style="background-color:#c4e4e4;display:none;" id="total">
                                    <th style="text-align:center;font-weight:bold;border-top: 1px solid #c8c7c7;" colspan="4">Total</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_income,0,',',',')}}</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_outcome,0,',',',')}}</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_saldo_akhir,0,',',',')}}</th>
                                    <th style="text-align:left;font-weight:bold;border-top: 1px solid #c8c7c7;" colspan="2"></th>
                                </tr>
                            </tfoot> --}}
                        </table>


                    </div>
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
    var datatable;
    var urlcreate = "{{route('pumk.anggaran.create')}}";
    var urledit = "{{route('pumk.anggaran.edit')}}";
    var urlstore = "{{route('pumk.anggaran.store')}}";
    var urlshow = "{{route('pumk.anggaran.show')}}";
    var urldelete = "{{route('pumk.anggaran.delete')}}";
    var urlupdatestatus = "{{route('pumk.anggaran.updatestatus')}}";
    var urlexport = "{{route('pumk.anggaran.export')}}";
    var urllog = "{{route('pumk.anggaran.log')}}";
    var urldatatable = "{{route('pumk.anggaran.datatable')}}";

    $(document).ready(function(){
        $.ajaxSetup({
              headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
              });
            
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            "dom": 'lrtip',
            ajax: {
                    url: urldatatable,
                    type: 'GET',
                    data: function (d) {
                        d.perusahaan_id =  $("#perusahaan_id").val();
                        d.periode_id =  $("#periode_id").val();
                        d.status_id =  $("#status_id").val();
                        d.tahun =  $("#tahun").val();
                    }
            },
            columns: [
                      {data: "id",
                        render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                            ,sClass:'text-center'
                        },
                        { data: 'tahun', name: 'tahun' ,sClass:'text-center' },
                        { data: 'bumn_singkat', name: 'bumn_singkat' },
                        { data: 'periode', name: 'periode' ,sClass:'text-center' },
                        { data: 'income_total', name: 'income_total',sClass:'text-center' },
                        { data: 'outcome_total', name: 'outcome_total' ,sClass:'text-center'},
                        { data: 'saldo_akhir', name: 'saldo_akhir' ,sClass:'text-center'},
                        { data: 'status', name: 'status' },
                        { data: 'action', name:'action' ,sClass:'text-center'},
                     ],
            order: [[0, 'desc']]
        });


        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");
        // $('#form-cari').hide();

        $('body').on('click','.cls-add',function(){
            winform(urlcreate, {}, 'Tambah Data');
        });

        $('body').on('click','.cls-button-log',function(){
            winform(urllog, {'id':$(this).data('id')}, 'Log Data');
        });

        $('body').on('click','.cls-button-edit',function(){
            winform(urledit, {'id':$(this).data('id')}, 'Ubah Data');
        });

        $('body').on('click','.cls-button-delete-pumkanggaran',function(){
            onbtndeletepumkanggaran(this);
        });

        $('body').on('click','.cls-button-update-status',function(){
            onbtnupdatestatus(this);
        });

        $('body').on('click','.cls-button-aktivasi-status',function(){
            onbtnaktivasistatus(this);
        });

        $('body').on('click','.cls-button-show',function(){
            winform(urlshow, {'id':$(this).data('id')}, 'Detail Data');
        });

        $('body').on('click','.cls-export',function(){
            exportExcel();
        });

        $('body').on('click','.btn-search',function(){
            $('#form-cari').toggle(600);
        });
        
        if("{{ $admin_bumn }}"){
            $('.cls-export').hide();
            $('.cls-button-update-status').hide();
        }
        if("{{ $admin_tjsl }}"){
            $('.cls-add').hide();
            $('.cls-button-edit').hide();
        }

    });

    $('#proses').click(function(){
        $('#datatable').DataTable().draw(true);
    }); 

    $('#reset').click(function(){
       $('#perusahaan_id').val("").trigger('change');
       $('#periode_id').val("").trigger('change');
       $("#status_id").val("").trigger('change');
       $("#tahun").val("").trigger('change');
       $('#proses').trigger('click');
    });    
    
    function onbtndeletepumkanggaran(element){
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });

                    if(data.flag == 'success') {
                        // datatable.ajax.reload( null, false );
                        location.reload(); 
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
    
    function onbtnupdatestatus(element){
        swal.fire({
            title: "Pemberitahuan",
            text: "Yakin update status "+$(element).data('nama')+" ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, update status",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlupdatestatus,
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });

                    if(data.flag == 'success') {
                        // datatable.ajax.reload( null, false );
                        location.reload(); 
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


    function onbtnaktivasistatus(element){
        swal.fire({
            title: "Pemberitahuan",
            text: "Apakah anda yakin ingin melakukan aktivasi kembali status data "+$(element).data('nama')+" ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, aktivasi",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlupdatestatus,
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });

                    if(data.flag == 'success') {
                        // datatable.ajax.reload( null, false );
                        location.reload(); 
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

    function exportExcel()
    {
        $.ajax({
            type: 'post',
            data: {
                'perusahaan_id' : $("select[name='perusahaan_id']").val(),
                'tahun' : $("select[name='tahun']").val(),
                'status' : $("select[name='status_id']").val(),
                'periode_id' : $("select[name='periode_id']").val()
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
                var filename = 'Data Anggaran PUMK '+today+'.xlsx';

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
    
</script>
@endsection

