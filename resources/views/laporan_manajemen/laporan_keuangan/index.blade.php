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
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;"  data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;"  data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button> &nbsp
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
                            <select class="form-select form-select-solid form-select2" id="periode_laporan" name="periode_laporan" data-kt-select2="true" data-placeholder="Pilih Periode">
                                <option></option>
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
                            <select class="form-select form-select-solid form-select2" id="jenis_laporan" name="jenis_laporan" data-kt-select2="true" data-placeholder="Pilih Jenis Laporan">
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

                    <!--begin: Datatable -->
                    <div class="table-responsive">
                        <table class="table  table-bordered  tree  table-checkable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">No</th>
                                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Versi Laporan</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Tanggal Awal</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Tanggal Akhir</th>
                                    <th style="text-align:center;width:100px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Keterangan</th>
                                    <th style="text-align:center;width:100px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Status</th>
                                    <th style="text-align:center;width:100px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Nilai Pengurangan</th>
                                    <th style="text-align:center;width:120px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php $no=1; @endphp
                                @foreach ($versilaporankeuangan as $p)   
                                  <tr class="treegrid-{{$p->id}} versi" style="background-color: rgb(231, 231, 231) ;border-bottom:ridge;">
                                        <td style="text-align:center;">{{$no++}}</td>
                                        <td><strong> Versi {{$p->versi}} </strong></td>
                                        <td>
                                        <strong>
                                            @if($p->tanggal_awal)
                                            {{date("d-m-Y",strtotime($p->tanggal_awal))}}
                                            @endif
                                        </strong>
                                        </td>
                                        <td>
                                        <strong>
                                            @if($p->tanggal_akhir)
                                            {{date("d-m-Y",strtotime($p->tanggal_akhir))}}
                                            @endif
                                        </strong>
                                        </td>
                                        <td><strong>{{$p->keterangan}}</strong></td>
                                        <td style="text-align:center;">
                                        <strong>
                                            @php
                                                $status = '';
                                                if($p->tanggal_akhir == '' || $p->tanggal_akhir >=  date('Y-m-d')){
                                                    $status = 'Aktif';
                                                }
                                            @endphp
                                            {{ $status }}
                                        </strong>
                                        </td>
                                        <td></td>
                                        <td style="text-align:center;">
                                            <button type="button" data-id="{{$p->id}}" class="btn btn-sm btn-light btn-icon btn-success cls-button-add-laporan" data-id="{{$p->id}}" data-toggle="tooltip" title="Tamba data Laporan"><i class="bi bi-plus fs-3"></i></button>
                                            <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="{{$p->id}}" data-toggle="tooltip" title="Ubah data versi {{@$p->versi}}"><i class="bi bi-pencil fs-3"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="{{$p->id}}" data-nama="Versi {{$p->versi}}" data-toggle="tooltip" title="Hapus data Versi {{$p->versi}}"><i class="bi bi-trash fs-3"></i></button>
                                        </td>
                                  </tr>
                                  @php
                                       $lapor = $laporankeuangan->where('versi_laporan_id', $p->id);
                                  @endphp
                                  @foreach ($lapor as $l)  
                                  <tr class="treegrid-lapor{{$l->id}}versi{{$p->id}} treegrid-parent-{{$p->id}}" style="border-bottom:ridge;">
                                    <td></td>
                                    <td colspan="6">
                                        <a class="badge badge-light-info fw-bolder me-auto px-4 py-3">
                                       <strong style="font-size: 14px;"> {{$l->nama}} </strong>
                                        </a>
                                    </td>
                                    <td style="text-align:center;">

                                        <button type="button" data-id="{{$p->id}}" data-versi_laporan_id="{{$p->id}}" data-laporan_keuangan_id="{{$l->id}}" class="btn btn-sm btn-light btn-icon btn-secondary cls-button-add-parent" data-id="{{$p->id}}" data-toggle="tooltip" title="Tambah data Parent Laporan"><i class="bi bi-plus fs-3"></i></button>

                                        <button type="button" data-id="{{$l->id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit-versi-laporan-keuangan" data-id="{{$l->id}}" data-toggle="tooltip" title="Ubah data {{@$l->nama}}"><i class="bi bi-pencil fs-3"></i></button>

                                        <button type="button" data-id="{{$l->id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-danger btn-icon cls-button-delete-versi-laporan-keuangan" data-id="{{$l->id}}" data-nama="{{$l->nama}}" data-toggle="tooltip" title="Hapus data {{$l->nama}}"><i class="bi bi-trash fs-3"></i></button>
                                    </td>
                                  </tr>

                                    @php 
                                    $par = $parent->where('versi_laporan_id',$p->id)->where('laporan_id',$l->id);   
                                    
                                    @endphp
                                    @foreach($par as $c)
                                        <tr class="treegrid-par{{$c->parent_id}}lapor{{$l->id}}versi{{$p->id}} treegrid-parent-lapor{{$l->id}}versi{{$p->id}}" style="border-bottom:ridge;">
                                            <td></td>
                                            <td colspan="5">
                                                {{-- <a class="badge badge-light-primary fw-bolder me-auto px-4 py-3"> --}}
                                                <strong> {{$c->kode}} - {{$c->label}}</strong>
                                                {{-- </a> --}}
                                            </td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;">

                                                <button type="button" data-id="{{$p->id}}"  data-versi_laporan_id="{{$p->id}}" data-laporan_keuangan_id="{{$l->id}}" data-parent_id="{{$c->parent_id}}" class="btn btn-sm btn-light btn-icon btn-warning cls-button-add-child" data-id="{{$p->id}}" data-toggle="tooltip" title="Tambah data Child Laporan"><i class="bi bi-plus fs-3"></i></button>

                                                {{-- <button type="button" data-id="{{$l->id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit-pilar" data-id="{{$l->id}}" data-toggle="tooltip" title="Ubah data {{@$l->nama}}"><i class="bi bi-pencil fs-3"></i></button> --}}
                                                <button type="button" data-id="{{$c->parent_id}}" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit-parent" data-toggle="tooltip" title="Ubah data {{@$c->label}}"><i class="bi bi-pencil fs-3"></i></button>

                                                <button type="button" data-id="{{$c->parent_id}}" class="btn btn-sm btn-danger btn-icon cls-button-delete-parent" data-toggle="tooltip" title="Hapus data "><i class="bi bi-trash fs-3"></i></button>
                                            </td>
                                        </tr>

                                        @php 
                                        $childs = $child->where('versi_laporan_id',$p->id)->where('laporan_id',$l->id)->where('parent_id',$c->parent_id);   

                                        @endphp
                                        @foreach($childs as $d)                                        
                                        <tr class="treegrid-child{{$d->child_id}} treegrid-parent-par{{$c->parent_id}}lapor{{$l->id}}versi{{$p->id}} child{{$d->child_id}}" style="border-bottom:ridge;">
                                            <td></td>
                                            <td colspan="5"> 
                                                {{-- <a class="badge badge-light-info fw-bolder me-auto px-4 py-3"> --}}
                                                    {{$d->kode}} - {{$d->label}}
                                                {{-- </a> --}}
                                            </td>
                                            <td style="text-align:center;">
                                                @if($d->is_pengurangan)
                                                <a class="badge badge-light-info fw-bolder me-auto px-4 py-3">
                                                {{$d->is_pengurangan ? "Ya" : ""}}
                                                </a>
                                                @endif
                                            </td>
                                            <td style="text-align:center;">

                                                {{-- <button type="button" data-id="{{$d->child_id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit-pilar" data-id="{{$l->id}}" data-toggle="tooltip" title="Ubah data {{@$l->nama}}"><i class="bi bi-pencil fs-3"></i></button> --}}

                                                <button type="button" data-id="{{$d->child_id}}" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit-child" data-toggle="tooltip" title="Ubah data "><i class="bi bi-pencil fs-3"></i></button>

                                                <button type="button" data-id="{{$d->child_id}}" class="btn btn-sm btn-danger btn-icon cls-button-delete-child" data-toggle="tooltip" title="Hapus data "><i class="bi bi-trash fs-3"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                  @endforeach
                                @endforeach

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
    var urldelete = "{{route('laporan_manajemen.laporan_keuangan.delete')}}";

    $(document).ready(function(){
//        $('.tree').treegrid();

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
            winform(urledit, {'id':$(this).data('id')}, 'Ubah Data');
        });

        $('body').on('click','.cls-button-delete',function(){
            onbtndelete(this);
        });

    });
    
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