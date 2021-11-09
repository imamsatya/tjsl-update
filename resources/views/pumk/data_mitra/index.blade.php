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
                        <button type="button" class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search btn-search-active" style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-search cls-search btn-search-unactive" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
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
                        <div class="col-lg-3">   
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
                                <option value=""></option>
                                @foreach($perusahaan as $bumn)  
                                    @php
                                        $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Provinsi</label>
                            <select id="provinsi_id" class="form-select form-select-solid form-select2" name="provinsi_id" data-kt-select2="true" data-placeholder="Pilih Provinsi" data-allow-clear="true">
                                <option></option>
                                @foreach($provinsi as $prov)  
                                    <option value="{{ $prov->id }}" >{{ $prov->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Kabupaten/Kota</label>
                            <select id="kota_id" class="form-select form-select-solid form-select2" name="kota_id" data-kt-select2="true" data-placeholder="Pilih Kab/Kota" data-allow-clear="true">
                                <option></option>
                                @foreach($kota as $kotas)  
                                    <option value="{{ $kotas->id }}" >{{ $kotas->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Sektor Usaha</label>
                            <select id="sektor_usaha_id" class="form-select form-select-solid form-select2" name="sektor_usaha_id" data-kt-select2="true" data-placeholder="Pilih Sektor Usaha" data-allow-clear="true">
                                <option></option>
                                @foreach($sektor_usaha as $sektor)  
                                    <option value="{{ $sektor->id }}" >{{ $sektor->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-3">
                            <label>Cara Penyaluran</label>
                            <select id="cp_id" class="form-select form-select-solid form-select2" name="cp_id" data-kt-select2="true" data-placeholder="Pilih Cara" data-allow-clear="true">
                                <option></option>
                                @foreach($cara_penyaluran as $cp)  
                                    <option value="{{ $cp->id }}" >{{ $cp->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Skala Usaha</label>
                            <select class="form-select form-select-solid form-select2" id="skala_usaha_id" name="skala_usaha_id" data-kt-select2="true" data-placeholder="Pilih Skala Usaha" data-allow-clear="true">
                                <option></option>
                                @foreach($skala_usaha as $su)  
                                    <option value="{{ $su->id }}" >{{ $su->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Kolektibilitas Pendanaan</label>
                            <select class="form-select form-select-solid form-select2" id="kolekbilitas_id" name="kolekbilitas_id" data-kt-select2="true" data-placeholder="Pilih Kolekbilitas" data-allow-clear="true">
                                <option></option>
                                @foreach($kolektibilitas_pendanaan as $kp)  
                                    <option value="{{ $kp->id }}" >{{ $kp->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Kondisi Pinjaman</label>
                            <select class="form-select form-select-solid form-select2" id="kondisi_id" name="kondisi_id" data-kt-select2="true" data-placeholder="Pilih Kondisi" data-allow-clear="true">
                                <option></option>
                                @foreach($kondisi_pinjaman as $kondisi)  
                                    <option value="{{ $kondisi->id }}" >{{ $kondisi->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-3">
                            <label>Jenis Pembayaran</label>
                            <select id="jp_id" class="form-select form-select-solid form-select2" name="jp_id" data-kt-select2="true" data-placeholder="Pilih Jenis" data-allow-clear="true">
                                <option></option>
                                @foreach($jenis_pembayaran as $jp)  
                                    <option value="{{ $jp->id }}" >{{ $jp->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Bank Account</label>
                            <select class="form-select form-select-solid form-select2" id="bank_account_id" name="bank_account_id" data-kt-select2="true" data-placeholder="Pilih Bank" data-allow-clear="true">
                                <option></option>
                                @foreach($bank_account as $ba)  
                                    <option value="{{ $ba->id }}" >{{ $ba->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Bulan</label>
                            <select class="form-select form-select-solid form-select2" id="bulan_id" name="bulan_id" data-kt-select2="true" data-placeholder="Pilih Bulan" data-allow-clear="true">
                                <option></option>
                                @foreach($bulan as $bu)  
                                    <option value="{{ $bu->id }}" >{{ $bu->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label>Tambahan Pendanaan</label>
                            <select class="form-select form-select-solid form-select2" id="tambahan_pendanaan_id" name="tambahan_pendanaan_id" data-kt-select2="true" data-placeholder="Pilih status Tambahan" data-allow-clear="true">
                                <option></option>
                                    <option value="1" >Ya</option>
                                    <option value="2" >Tidak</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-5">
                            <label>Nama Mitra Binaan</label>
                            <input type="text" class="form-control " id="nama_mitra" name="nama_mitra" placeholder="masukan nama mitra binaan..." >
                        </div>
                        <div class="col-lg-4">
                            <label>No. Identitas</label>
                            <input type="text" class="form-control " id="identitas" name="identitas" placeholder="masukan nomor identitas..." >
                        </div>
                        <div class="col-lg-3 mt-6 text-center">
                            <button id="proses" class="btn-small btn-success me-3 text-white" style="padding:10px 8px 10px 8px;"><i class="fa fa-search text-white"></i> Cari</button>
                            <button id="reset" class="btn-small btn-danger me-3 text-white" style="padding:10px 8px 10px 8px;"><i class="fa fa-times text-white"></i> Batal</button>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">

                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                </div>   
                    <!--begin: Datatable -->
                    <div class="table-responsive"  >
                        <table class="table table-striped table-bordered table-hover " id="datatable">
                            <thead>
                                <tr style="border-top:ridge;">
                                    <th style="text-align:center;">No</th>
                                    <th >Nama Mitra</th>
                                    <th >Provinsi</th>
                                    {{-- <th >Kabupaten/Kota</th> --}}
                                    <th >Sektor Usaha</th>
                                    <th >Nominal Pendanaan</th>
                                    <th >Saldo Pokok</th>
                                    <th >Kolektibilitas</th>
                                    <th style="width: 50px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
    var urledit = "{{route('pumk.data_mitra.edit')}}";
    var urlshow = "{{route('pumk.data_mitra.show')}}";
    var urldelete = "{{route('pumk.data_mitra.delete')}}";
    var urlexportmitra = "{{route('pumk.data_mitra.export')}}";
    var urldatatable = "{{route('pumk.data_mitra.datatable')}}";

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
                        d.identitas = $('#identitas').val();
                        d.nama_mitra = $('#nama_mitra').val();
                        d.perusahaan_id =  $("#perusahaan_id").val();
                        d.provinsi_id =  $("#provinsi_id").val();
                        d.kota_id =  $("#kota_id").val();
                        d.sektor_usaha_id =  $("#sektor_usaha_id").val();
                        d.cara_penyaluran_id =  $("#cp_id").val();
                        d.skala_usaha_id =  $("#skala_usaha_id").val();
                        d.kolektibilitas_id =  $("#kolekbilitas_id").val();
                        d.kondisi_pinjaman_id =  $("#kondisi_id").val();
                        d.jenis_pembayaran_id =  $("#jp_id").val();
                        d.bank_account_id =  $("#bank_account_id").val();
                        d.bulan_id =  $("#bulan_id").val();
                        d.tambahan_pendanaan_id =  $("#tambahan_pendanaan_id").val();
                    }
            },
            columns: [
                      {data: "id",
                        render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                            ,sClass:'text-center'
                        },
                        { data: 'nama_mitra', name: 'nama_mitra' },
                        { data: 'provinsi', name: 'provinsi' },
                        // { data: 'kota', name: 'kota' },
                        { data: 'sektor_usaha', name: 'sektor_usaha' },
                        { data: 'nominal_pendanaan', name: 'nominal_pendanaan' ,sClass:'text-center'},
                        { data: 'saldo_pokok_pendanaan', name: 'saldo_pokok_pendanaan' ,sClass:'text-center'},
                        { data: 'kolektibilitas', name: 'kolektibilitas' ,sClass:'text-center'},
                        { data: 'action', name:'action' ,sClass:'text-center'},
                     ],
            order: [[0, 'desc']]
        });

        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-button-edit',function(){
            winform(urledit, {'id':$(this).data('id')}, 'Ubah Data');
        });

        $('body').on('click','.cls-button-delete-mitra',function(){
            onbtndeletemitra(this);
        });

        $('body').on('click','.cls-button-show-mitra',function(){
            winform(urlshow, {'id':$(this).data('id')}, 'Detail Data');
        });

        $('body').on('click','.cls-export',function(){
            exportExcel();
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
        
        // if("{{ $admin_bumn }}"){
        //     $('.cls-export').hide();
        //     $('.cls-button-update-status').hide();
        // }
        // if("{{ $admin_tjsl }}"){
        //     $('.cls-add').hide();
        //     $('.cls-button-edit').hide();
        // }
        
    });

    $('#proses').click(function(){
        $('#datatable').DataTable().draw(true);
    }); 

    $('#reset').click(function(){
       $('#perusahaan_id').val("").trigger('change');
       $('#identitas').val("").trigger('change');
       if("{{ $admin_bumn }}"){
        $("#perusahaan_id").val("{{$filter_bumn_id}}").trigger('change');
       }else{
        $("#perusahaan_id").val("").trigger('change');
       }
       $("#provinsi_id").val("").trigger('change');
       $("#kota_id").val("").trigger('change');
       $("#sektor_usaha_id").val("").trigger('change');
       $("#cp_id").val("").trigger('change');
       $("#skala_usaha_id").val("").trigger('change');
       $("#kolekbilitas_id").val("").trigger('change');
       $("#kondisi_id").val("").trigger('change');
       $("#jp_id").val("").trigger('change');
       $("#bank_account_id").val("").trigger('change');
       $("#bulan_id").val("").trigger('change');
       $("#tambahan_pendanaan_id").val("").trigger('change');
       $('#proses').trigger('click');
    });

    function onbtndeletemitra(element){
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
                perusahaan_id :  $("#perusahaan_id").val(),
                provinsi_id :  $("#provinsi_id").val(),
                kota_id :  $("#kota_id").val(),
                sektor_usaha_id :  $("#sektor_usaha_id").val(),
                cara_penyaluran_id :  $("#cp_id").val(),
                skala_usaha_id :  $("#skala_usaha_id").val(),
                kolektibilitas_id :  $("#kolekbilitas_id").val(),
                kondisi_pinjaman_id :  $("#kondisi_id").val(),
                jenis_pembayaran_id :  $("#jp_id").val(),
                bank_account_id :  $("#bank_account_id").val(),
                identitas : $('#identitas').val()
            },
            beforeSend: function () {
                $.blockUI();
            },
            url: urlexportmitra,
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
                var filename = 'Data Mitra Binaan '+today+'.xlsx';

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

