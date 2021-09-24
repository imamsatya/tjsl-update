@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />
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
                        <button type="button" class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search btn-search-active" style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-search cls-search btn-search-unactive" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;margin-right:3px;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;margin-right:3px;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-upload" style="margin-right:3px;" data-toggle="tooltip" title="Upload Data Program"><i class="bi bi-upload fs-3"></i></button>
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
                  <div class="row" id="form-cari">
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
                                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
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
                    
                    <!--begin: Datatable -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tree  table-checkable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">No.</th>
                                    <th style="font-weight:bold;width:550px;border-bottom: 1px solid #c8c7c7;">Pilar - TPB - Program</th>
                                    <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">Anggaran</th>
                                    <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">Jangka Waktu</th>
                                    <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">Status</th>
                                    <th style="text-align:center;width:220px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Aksi</th>
                                </tr>
                            </thead>
                            <tbody>       
                            @php 
                                $total=0;
                                $bumn = $anggaran_bumn;
                            @endphp       
                            @foreach ($bumn as $b)     
                                @php 
                                    $no=0;
                                    $sum_bumn = $anggaran_bumn->where('perusahaan_id', $b->id)->first(); 
                                    $anggaran_pilar_bumn = $anggaran_pilar->where('perusahaan_id', $b->id);
                                @endphp
                                @if(!$perusahaan_id)
                                <tr class="treegrid-bumn{{@$b->id}}" >
                                    <td style="text-align:center;"></td>
                                    <td>{{$b->nama_lengkap}}</td>
                                    <td style="text-align:right;">
                                        @if($sum_bumn)
                                        {{number_format($sum_bumn->sum_anggaran,0,',',',')}}
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>  
                                @endif    
                                @foreach ($anggaran_pilar_bumn as $p)                                  
                                    @php 
                                        $no++;
                                        $anggaran_anak = $anggaran->where('perusahaan_id', $b->id)->where('pilar_pembangunan_id', $p->pilar_pembangunan_id);
                                        
                                        $class_parent = '';
                                        if(!$perusahaan_id){
                                            $class_parent = 'treegrid-parent-bumn' . $p->perusahaan_id;
                                        }
                    
                                        $total += $p->sum_anggaran;
                                    @endphp
                                
                                    <tr class="treegrid-bumn{{@$b->id}}pilar{{@$p->pilar_id}} {{$class_parent}} item-bumn{{@$b->id}}pilar{{$p->pilar_id}}" >
                                        <td style="text-align:center;">{{$no}}</td>
                                        <td>{{$p->pilar_nama}}</td>
                                        <td style="text-align:right;">{{number_format($p->sum_anggaran,0,',',',')}}</td>
                                        <td style="text-align:center;">
                                        </td>
                                        <td style="text-align:center;">
                                        </td>
                                        <td></td>
                                    </tr>
                                    
                                    @foreach ($anggaran_anak as $a)  
                                        @php 
                                            $target_tpb = $target->where('anggaran_tpb_id', $a->id);
                                        @endphp     
                                        <tr class="treegrid-bumn{{@$b->id}}tpb{{$a->id}} treegrid-parent-bumn{{@$b->id}}pilar{{@$p->pilar_id}} itembumn{{@$b->id}}tpb{{$a->id}}">
                                            <td></td>
                                            <td>{{@$a->no_tpb .' - '. @$a->tpb_nama}}</td>
                                            <td style="text-align:right;">{{number_format($a->anggaran,0,',',',')}}</td>
                                            <td style="text-align:center;">
                                            </td>
                                            <td style="text-align:center;">
                                            </td>
                                            <td></td>
                                        </tr>
                                        @foreach($target_tpb as $t)  
                                        @php 
                                            $status_class = 'primary';
                                            if($t->status_id == 1){
                                                $status_class = 'success';
                                            }else if($t->status_id == 3){
                                                $status_class = 'warning';
                                            }
                                        @endphp  
                                            <tr class="treegrid-target{{$t->id}} treegrid-parent-bumn{{@$b->id}}tpb{{@$a->id}} itemtarget{{$t->id}}">
                                                <td></td>
                                                <td>{{$t->program}}</td>
                                                <td style="text-align:right;">{{number_format($t->anggaran_alokasi,0,',',',')}}</td>
                                                <td>{{@$t->jangka_waktu}} tahun</td>
                                                <td style="text-align:center;">
                                                    <span class="btn cls-log badge badge-light-{{$status_class}} fw-bolder me-auto px-4 py-3" data-id="{{$t->id}}">{{@$t->status->nama}}</span>
                                                </td>
                                                <td style="text-align:center;">
                                                    @if($t->status_id != 1)
                                                    <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="{{$t->id}}" data-toggle="tooltip" title="Ubah data {{@$t->program}}"><i class="bi bi-pencil fs-3"></i></button>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-light btn-icon btn-info cls-button-detail" data-id="{{$t->id}}" data-toggle="tooltip" title="Detail data {{@$t->program}}"><i class="bi bi-info fs-3"></i></button>
                                                    @if($t->status_id != 1)
                                                    <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="{{$t->id}}" data-nama="{{@$t->program}}" data-toggle="tooltip" title="Hapus data {{@$t->program}}"><i class="bi bi-trash fs-3"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                            @if($total==0)
                                <td></td>
                                <td style="text-align:left;">-</td>
                                <td style="text-align:center;">-</td>
                                <td style="text-align:center;">-</td>
                                <td style="text-align:center;"><span class="btn cls-log badge badge-light-warning fw-bolder me-auto px-4 py-3">Unfilled</span></td>
                                <td></td>
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;"></th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">Total</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total,0,',',',')}}</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;"></th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;"></th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;"></th>
                                </tr>
                            </tfoot>
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
    var urlcreate = "{{route('target.administrasi.create')}}";
    var urlupload = "{{route('target.administrasi.upload')}}";
    var urledit = "{{route('target.administrasi.edit')}}";
    var urluploadstore = "{{route('target.upload_target.store')}}";
    var urlstore = "{{route('target.administrasi.store')}}";
    var urldelete = "{{route('target.administrasi.delete')}}";
    var urldownloadtemplate = "{{route('target.administrasi.download_template')}}";
    var urlgetstatus = "{{route('target.administrasi.get_status')}}";
    var urldetail = "{{route('target.administrasi.detail')}}";
    var urlexport = "{{route('target.administrasi.export')}}";
    var urllog = "{{route('target.administrasi.log_status')}}";
    var urlvalidasi = "{{route('target.administrasi.validasi')}}";

    $(document).ready(function(){
        $('.tree').treegrid({
            initialState : 'collapsed',
            treeColumn : 1,
            indentTemplate : '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
        });
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-export',function(){
            exportExcel();
        });

        $('body').on('click','.cls-upload',function(){
            winform(urlupload, {}, 'Upload Data');
        });

        $('body').on('click','.cls-button-detail',function(){
            winform(urldetail, {'id':$(this).data('id')}, 'Ubah Data');
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
        
        $('body').on('click','.cls-log',function(){
            winform(urllog, {'id':$(this).data('id')}, 'Log Status');
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

        $('body').on('click','.cls-add-kegiatan',function(){
            var url = window.location.origin + '/target/kegiatan/index';
            var perusahaan_id = $('#perusahaan_id').val();
            var tahun = $('#tahun').val();

            window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun;
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
    });
    
    function onbtndisablevalidasi(){
        swal.fire({
            title: "Gagal",
            html: 'Pilihan BUMN dan Tahun wajib diisi!',
            icon: 'error',

            buttonsStyling: true,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
        }); 
    }

    function onbtnvalidasi(){
        swal.fire({
            title: "Pemberitahuan",
            text: "Validasi Data Program "+$("#perusahaan_id option:selected").text() +" tahun "+$("#tahun").val()+" ?",
            icon: "warning",
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
    
    function onbtncancelvalidasi(){
        swal.fire({
            title: "Pemberitahuan",
            text: "Batalkan Validasi Data Aggaran TPB "+$("#perusahaan_id option:selected").text() +" tahun "+$("#tahun").val()+" ?",
            icon: "warning",
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
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
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
                'perusahaan_id' : $("select[name='perusahaan_id']").val(),
                'tahun' : $("select[name='tahun']").val(),
                'pilar_pembangunan_id' : $("select[name='pilar_pembangunan_id']").val(),
                'status_id' : $("select[name='status_id']").val(),
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
                var filename = 'Rekap Data Program '+today+'.xlsx';

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
                var filename = 'Template Data Program.xlsx';

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

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    });  
                    }
                });
            }
        });	
    }
</script>
@endsection
