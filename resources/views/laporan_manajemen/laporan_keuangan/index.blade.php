@extends('layouts.app')

@section('addbeforecss')
    <link href="{{asset('plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .border_bottom {
            border-bottom: 1px solid #c8c7c7;
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
                        <button type="button" class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search btn-search-active" style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-search cls-search btn-search-unactive" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        @if(!$view_only)
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;margin-right:3px;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;margin-right:3px;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-add" style="margin-right:3px;" data-toggle="tooltip" title="Tambah Data"><i class="bi bi-plus fs-3"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download PDF"><i class="bi bi-file-pdf fs-3"></i></button>
                        @endif
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
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Periode Laporan</label>
                            <select class="form-select form-select-solid form-select2" id="periode_laporan_id" name="periode_laporan_id" data-kt-select2="true" data-placeholder="Pilih Periode">
                                @foreach($periode_laporan as $p)  
                                    @php
                                        $select = (($p->id == $periode_laporan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Jenis Laporan</label>
                            <select class="form-select form-select-solid form-select2" id="jenis_laporan_id" name="jenis_laporan_id" data-kt-select2="true" data-placeholder="Pilih Jenis Laporan">
                                <option></option>
                                @foreach($jenis_laporan as $p)  
                                    @php
                                        $select = (($p->id == $jenis_laporan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
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
                  </div>

                    <!--begin: Datatable -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tree  table-checkable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">No.</th>
                                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Laporan Manajemen</th>
                                    {{-- <th style="text-align:right;font-weight:bold;width:160px;border-bottom: 1px solid #c8c7c7;">Nilai (Rp.)</th> --}}
                                    <th style="text-align:center;font-weight:bold;border-bottom: 1px solid #c8c7c7;">Status</th>
                                    <th style="text-align:center;width:150px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Aksi</th>
                                </tr>
                            </thead>
                            <tbody>    
                            @php 
                                $total=0;
                                $no=1;
                            @endphp  
                            @foreach ($laporan_bumn as $b)     
                                @if(!$perusahaan_id)
                                <tr class="treegrid-bumn{{@$b->perusahaan_id}}" >
                                    <td style="text-align:center;">
                                        @if(!$perusahaan_id)
                                        {{$no++}}
                                        @endif
                                    </td>
                                    <td>{{$b->nama_lengkap}}</td>
                                    {{-- <td></td> --}}
                                    <td style="text-align:center;">
                                        @php
                                        $class = 'primary';
                                        if($b->status_id == 1){
                                            $class = 'success';
                                        }else if($b->status_id == 3){
                                            $class = 'warning';
                                        }
                                        @endphp
                                        @if(!$perusahaan_id)
                                        <span class="btn cls-log badge badge-light-{{$class}} fw-bolder me-auto px-4 py-3" data-id="{{$b->laporan_manajemen_id}}" >{{$b->status_nama}}</span>
                                        @endif
                                    </td>
                                    <td></td>
                                </tr>  
                                @endif   
                                
                                @php 
                                    $no2=1;
                                    $jenis = $laporan_jenis->where('perusahaan_id', $b->perusahaan_id);
                                    
                                    $class_parent = '';
                                    if(!$perusahaan_id){
                                        $class_parent = 'treegrid-parent-bumn' . $b->perusahaan_id;
                                    }
                                @endphp 
                                @foreach ($jenis as $j)   
                                <tr class="treegrid-bumn{{@$b->perusahaan_id}}jenis{{$j->laporan_keuangan_id}} {{$class_parent}} item-bumn{{@$b->perusahaan_id}}jenis{{$j->laporan_keuangan_id}}" >
                                    <td style="text-align:center;">
                                        @if($perusahaan_id)
                                        {{$no2++}}
                                        @endif
                                    </td>
                                    <td>{{$j->nama}}</td>
                                    {{-- <td></td> --}}
                                    <td style="text-align:center;">
                                        @php
                                        $class = 'primary';
                                        if($j->status_id == 1){
                                            $class = 'success';
                                        }else if($j->status_id == 3){
                                            $class = 'warning';
                                        }
                                        @endphp
                                        @if($perusahaan_id)
                                        <span class="btn cls-log badge badge-light-{{$class}} fw-bolder me-auto px-4 py-3" data-id="{{$j->laporan_manajemen_id}}" >{{$j->status_nama}}</span>
                                        @endif
                                    </td>
                                    <td style="text-align:center;width:60px;">
                                        @if($j->status_id!=1)
                                            <button type="button" class="btn btn-sm btn-light btn-icon btn-info cls-button-show" data-perusahaan_id="{{$b->perusahaan_id}}" data-laporan_keuangan_id="{{$j->laporan_keuangan_id}}" data-id="{{$p->id}}" data-toggle="tooltip" title="Detail {{$p->label}}"><i class="bi bi-info fs-3"></i></button>
                                            @if(!$view_only)
                                            <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-perusahaan_id="{{$b->perusahaan_id}}" data-laporan_keuangan_id="{{$j->laporan_keuangan_id}}" data-id="{{$p->id}}" data-toggle="tooltip" title="Ubah data {{$p->label}}"><i class="bi bi-pencil fs-3"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-perusahaan_id="{{$b->perusahaan_id}}" data-laporan_keuangan_id="{{$j->laporan_keuangan_id}}" data-nama="{{ $j->nama }}" data-toggle="tooltip" title="Hapus data {{ $j->nama }}"><i class="bi bi-trash fs-3"></i></button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>  
                                    
                                    {{-- @php 
                                        $parent = $laporan_parent->where('perusahaan_id', $b->perusahaan_id)->where('laporan_keuangan_id', $j->laporan_keuangan_id);
                                    @endphp 
                                    @foreach ($parent as $p)   
                                    <tr class="treegrid-bumn{{@$b->perusahaan_id}}id{{$p->id}} treegrid-parent-bumn{{@$b->perusahaan_id}}jenis{{$j->laporan_keuangan_id}} item-bumn{{@$b->perusahaan_id}}id{{$p->id}}" >
                                        <td></td>
                                        <td style="font-weight:bold;">{{$p->label}}</td>
                                        <td style="text-align:right;">
                                            @php
                                                $nilai=number_format($p->nilai,0,',',',');
                                                if($p->is_pengurangan || $p->nilai<0){
                                                    $nilai = '('.number_format(abs($p->nilai),0,',',',').')';
                                                }else if(!$p->is_input && $p->formula==''){
                                                    $nilai = '';
                                                }
                                            @endphp
                                            {{$nilai}}
                                        </td>
                                        <td></td>
                                        <td style="text-align:center;">
                                            @if($p->is_input && $j->status_id!=1)
                                            <!-- <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="{{$p->id}}" data-toggle="tooltip" title="Ubah data {{$p->label}}"><i class="bi bi-pencil fs-3"></i></button> -->
                                            @endif
                                        </td>
                                    </tr> 
                                    
                                        @php 
                                            $child = $laporan_child->where('perusahaan_id', $b->perusahaan_id)->where('laporan_keuangan_id', $j->laporan_keuangan_id)->where('parent_id', $p->parent_id);
                                            $total += $p->nilai;
                                        @endphp 
                                        @foreach ($child as $c)   
                                        <tr class="treegrid-bumn{{@$b->perusahaan_id}}id{{$c->id}} treegrid-parent-bumn{{@$b->perusahaan_id}}id{{$p->id}} item-bumn{{@$b->perusahaan_id}}id{{$c->id}}" >
                                            <td></td>
                                            <td>{{$c->label}}</td>
                                            <td style="text-align:right;">
                                                @php
                                                    $nilai=number_format($c->nilai,0,',',',');
                                                    if($c->is_pengurangan || $c->nilai<0){
                                                        $nilai = '('.number_format(abs($c->nilai),0,',',',').')';
                                                    }else if(!$c->is_input && $c->formula==''){
                                                        $nilai = '';
                                                    }
                                                @endphp
                                                {{$nilai}}
                                            </td>
                                            <td></td>
                                            <td style="text-align:center;">
                                                @if($c->is_input && $j->status_id!=1)
                                                <!-- <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="{{$c->id}}" data-toggle="tooltip" title="Ubah data {{$c->label}}"><i class="bi bi-pencil fs-3"></i></button> -->
                                                @endif
                                            </td>
                                            @php 
                                                if($c->is_pengurangan){
                                                    $total -= $c->nilai;
                                                }else{
                                                    $total += $c->nilai;
                                                }
                                            @endphp 
                                        </tr>  
                                        @endforeach 
                                    @endforeach --}}
                                @endforeach
                            @endforeach
                            {{-- @if($total==0)
                                <td colspan="5" style="text-align:center;font-style:italic">Data Kosong</td>
                            @endif --}}
                            </tbody>
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
    var urlcreate = "{{route('laporan_manajemen.laporan_keuangan.create')}}";
    var urledit = "{{route('laporan_manajemen.laporan_keuangan.edit')}}";
    var urlstore = "{{route('laporan_manajemen.laporan_keuangan.store')}}";
    var urlupdate = "{{route('laporan_manajemen.laporan_keuangan.update')}}";
    var urldelete = "{{route('laporan_manajemen.laporan_keuangan.delete')}}";
    var urlexport = "{{route('laporan_manajemen.laporan_keuangan.export_pdf')}}";

    $(document).ready(function(){
        $('.tree').treegrid({
            initialState : 'collapsed',
            treeColumn : 1,
            indentTemplate : '<span style="width: 20px; height: 10px; display: inline-block; position: relative;"></span>'
        });

        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-add',function(){
            window.location.href = urlcreate;
        });

        $('body').on('click','.cls-log',function(){
            winform(urllog, {'id':$(this).data('id')}, 'Log Status');
        });

        $('body').on('click','.cls-button-edit',function(){
            var url = window.location.origin + '/laporan_manajemen/laporan_keuangan/edit';
            var perusahaan_id = $(this).data('perusahaan_id');
            var tahun = $('#tahun').val();
            var laporan_keuangan_id = $(this).data('laporan_keuangan_id');
            var periode_laporan_id = $('#periode_laporan_id').val();

            var url = window.location.origin + '/laporan_manajemen/laporan_keuangan/edit';
            var form = $('<form action="' + url + '" method="post">' +
            '@csrf <input type="hidden" name="perusahaan_id" value="' + perusahaan_id + '" />' +
            '<input type="hidden" name="tahun" value="' + tahun + '" />' +
            '<input type="hidden" name="laporan_keuangan_id" value="' + laporan_keuangan_id + '" />' +
            '<input type="hidden" name="periode_laporan_id" value="' + periode_laporan_id + '" />' +
            '</form>');
            $('body').append(form);
            form.submit();
        });

        $('body').on('click','.cls-button-show',function(){
            var url = window.location.origin + '/laporan_manajemen/laporan_keuangan/show';
            var perusahaan_id = $(this).data('perusahaan_id');
            var tahun = $('#tahun').val();
            var laporan_keuangan_id = $(this).data('laporan_keuangan_id');
            var periode_laporan_id = $('#periode_laporan_id').val();

            var url = window.location.origin + '/laporan_manajemen/laporan_keuangan/show';
            var form = $('<form action="' + url + '" method="post">' +
            '@csrf <input type="hidden" name="perusahaan_id" value="' + perusahaan_id + '" />' +
            '<input type="hidden" name="tahun" value="' + tahun + '" />' +
            '<input type="hidden" name="laporan_keuangan_id" value="' + laporan_keuangan_id + '" />' +
            '<input type="hidden" name="periode_laporan_id" value="' + periode_laporan_id + '" />' +
            '</form>');
            $('body').append(form);
            form.submit();
        });

        $('body').on('click','.cls-button-delete',function(){
            onbtndelete(this);
        });

        $('body').on('click','.cls-export',function(){
            exportPdf();
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

        $('#proses').on('click', function(event){
            var url = window.location.origin + '/laporan_manajemen/laporan_keuangan/index';
            var perusahaan_id = $('#perusahaan_id').val();
            var tahun = $('#tahun').val();
            var jenis_laporan_id = $('#jenis_laporan_id').val();
            var periode_laporan_id = $('#periode_laporan_id').val();

            window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun + '&periode_laporan_id=' + periode_laporan_id + '&jenis_laporan_id=' + jenis_laporan_id;
        });
    });
    
    function exportPdf()
    {
        var perusahaan_id = $('#perusahaan_id').val();
        var tahun = $('#tahun').val();
        var jenis_laporan_id = $('#jenis_laporan_id').val();
        var periode_laporan_id = $('#periode_laporan_id').val();

        $.ajax({
            type: 'post',
            data: {
                'perusahaan_id' : perusahaan_id,
                'tahun' : tahun,
                'laporan_keuangan_id' : jenis_laporan_id,
                'periode_laporan_id' : periode_laporan_id
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
                var filename = 'Data Laporan Keuangan '+today+'.pdf';

                var blob = new Blob([data], {
                    type: "application/pdf"
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
                    "laporan_keuangan_id": $(element).data('laporan_keuangan_id'),
                    "perusahaan_id": $(element).data('perusahaan_id'),
                    "periode_laporan_id":$('#periode_laporan_id').val(),
                    "tahun":$('#tahun').val(),
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
    
</script>
@endsection