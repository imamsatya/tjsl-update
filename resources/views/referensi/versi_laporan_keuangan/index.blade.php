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
                        <button type="button" class="btn btn-success btn-sm cls-add"  data-toggle="tooltip" title="Tambah Data">Tambah Data</button>
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
                    <!--begin: Datatable -->
                    <div class="table-responsive">
                        
                        <table class="table table-striped table-bordered table-hover tree  table-checkable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">No.</th>
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
                                <tr class="treegrid-versi{{@$p->id}}" >
                                    <td style="text-align:center;">{{$no}}</td>
                                    <td>Versi {{$p->versi}}</td>
                                    <td>
                                        @if($p->tanggal_awal)
                                        {{date("d-m-Y",strtotime($p->tanggal_awal))}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($p->tanggal_akhir)
                                        {{date("d-m-Y",strtotime($p->tanggal_akhir))}}
                                        @endif
                                    </td>
                                    <td>{{$p->keterangan}}</td>
                                    <td style="text-align:center;">
                                        @php
                                            $status = '';
                                            if($p->tanggal_akhir == '' || $p->tanggal_akhir >=  date('Y-m-d')){
                                                $status = 'Aktif';
                                            }
                                        @endphp
                                        {{ $status }}
                                    </td>
                                    <td></td>
                                    <td style="text-align:center;">
                                        <button type="button" data-id="{{$p->id}}" class="btn btn-sm btn-light btn-icon btn-success cls-button-add-laporan" data-id="{{$p->id}}" data-toggle="tooltip" title="Tamba data Laporan"><i class="bi bi-plus fs-3"></i></button>
                                        <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="{{$p->id}}" data-toggle="tooltip" title="Ubah data versi {{@$p->versi}}"><i class="bi bi-pencil fs-3"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="{{$p->id}}" data-nama="Versi {{$p->versi}}" data-toggle="tooltip" title="Hapus data Versi {{$p->versi}}"><i class="bi bi-trash fs-3"></i></button>
                                    </td>
                                </tr>  

                                @php 
                                    $no++;
                                    $pilar = $laporankeuangan->where('versi_laporan_id', $p->id);
                                @endphp
                                
                                @foreach ($pilar as $a)      
                                    <tr class="treegrid-pilar{{$a->id}}versi{{@$p->id}} treegrid-parent-versi{{@$p->id}} item-pilar{{$a->id}}versi{{@$p->id}}">
                                        <td></td>
                                        <td colspan="6">{{$a->nama}}</td>
                                        <td style="text-align:center;">

                                            <button type="button" data-id="{{$p->id}}" data-versi_laporan_id="{{$p->id}}" data-laporan_keuangan_id="{{$a->id}}" class="btn btn-sm btn-light btn-icon btn-info cls-button-add-parent" data-id="{{$p->id}}" data-toggle="tooltip" title="Tambah data Parent Laporan"><i class="bi bi-plus fs-3"></i></button>

                                            <button type="button" data-id="{{$a->id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit-pilar" data-id="{{$a->id}}" data-toggle="tooltip" title="Ubah data {{@$a->nama}}"><i class="bi bi-pencil fs-3"></i></button>
                                            <button type="button" data-id="{{$a->id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-danger btn-icon cls-button-delete-pilar" data-id="{{$a->id}}" data-nama="{{$a->nama}}" data-toggle="tooltip" title="Hapus data {{$a->nama}}"><i class="bi bi-trash fs-3"></i></button>
                                        </td>
                                    </tr>
                                
                                    @php 
                                        $tpb = $parent->where('versi_laporan_id', $p->id)->where('laporan_keuangan_id', $a->id);
                                   
                                    @endphp
                                    @foreach ($tpb as $c)      
                                        <tr class="treegrid-tpb{{$c->id}} treegrid-parent-pilar{{@$a->id}}versi{{@$p->id}} item-tpb{{$c->id}}">
                                            <td></td>
                                            <td colspan="5">{{$c->kode}} - {{$c->label}}</td>
                                            <td style="text-align:center;">{{$c->is_pengurangan ? "Aktif" : ""}}</td>
                                            <td style="text-align:center;">

                                                <button type="button" data-id="{{$p->id}}"  data-versi_laporan_id="{{$p->id}}" data-laporan_keuangan_id="{{$a->id}}" data-parent_id="{{$c->id}}" class="btn btn-sm btn-light btn-icon btn-warning cls-button-add-child" data-id="{{$p->id}}" data-toggle="tooltip" title="Tambah data Child Laporan"><i class="bi bi-plus fs-3"></i></button>

                                                <button type="button" data-id="{{$a->id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit-pilar" data-id="{{$a->id}}" data-toggle="tooltip" title="Ubah data {{@$a->nama}}"><i class="bi bi-pencil fs-3"></i></button>
                                                <button type="button" data-id="{{$a->id}}" data-versi="{{$p->id}}" class="btn btn-sm btn-danger btn-icon cls-button-delete-pilar" data-id="{{$a->id}}" data-nama="{{$a->nama}}" data-toggle="tooltip" title="Hapus data {{$a->nama}}"><i class="bi bi-trash fs-3"></i></button>
                                            </td>
                                        </tr>


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
    var datatable;
    var urlcreate = "{{route('referensi.versi_laporan_keuangan.create')}}";
    var urledit = "{{route('referensi.versi_laporan_keuangan.edit')}}";
    var urleditpilar = "{{route('referensi.versi_laporan_keuangan.edit_laporan')}}";
    var urladdlaporan = "{{route('referensi.versi_laporan_keuangan.add_laporan')}}";
    var urladdparent = "{{route('referensi.versi_laporan_keuangan.add_parent')}}";
    var urladdchild = "{{route('referensi.versi_laporan_keuangan.add_child')}}";
    var urlstore = "{{route('referensi.versi_laporan_keuangan.store')}}";
    var urlstorelaporan = "{{route('referensi.versi_laporan_keuangan.store_laporan')}}";
    var urlstoreParent = "{{route('referensi.versi_laporan_keuangan.store_parent')}}";
    var urlstoreChild = "{{route('referensi.versi_laporan_keuangan.store_child')}}";
    var urldatatable = "{{route('referensi.versi_laporan_keuangan.datatable')}}";
    var urldelete = "{{route('referensi.versi_laporan_keuangan.delete')}}";
    var urldeletepilar = "{{route('referensi.versi_pilar.delete_pilar')}}";
    var urlupdatestatus = "{{route('referensi.versi_laporan_keuangan.update_status')}}";

    $(document).ready(function(){
        $('.tree').treegrid({
            initialState : 'collapsed',
            treeColumn : 1,
            indentTemplate : '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
        });

        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-add',function(){
            winform(urlcreate, {}, 'Tambah Data');
        });

        $('body').on('click','.cls-log',function(){
            winform(urllog, {'id':$(this).data('id')}, 'Log Status');
        });

        $('body').on('click','.cls-button-edit',function(){
            winform(urledit, {'id':$(this).data('id')}, 'Ubah Data');
        });

        $('body').on('click','.cls-button-edit-pilar',function(){
            winform(urleditpilar, {'id':$(this).data('id'),'versi':$(this).data('versi')}, 'Ubah Data');
        });

        $('body').on('click','.cls-button-delete',function(){
            onbtndelete(this);
        });

        $('body').on('click','.cls-button-add-laporan',function(){
            winform(urladdlaporan, {'id':$(this).data('id')}, 'Tambah Data Laporan');
        });

        $('body').on('click','.cls-button-add-parent',function(){
            winform(urladdparent, {
                'id':$(this).data('id'),
                'versi_laporan_id':$(this).data('versi_laporan_id'),
                'laporan_keuangan_id':$(this).data('laporan_keuangan_id')
            }, 'Tambah Data Laporan');
        });

        $('body').on('click','.cls-button-add-child',function(){
            winform(urladdchild, {
                'id':$(this).data('id'),
                'versi_laporan_id':$(this).data('versi_laporan_id'),
                'laporan_keuangan_id':$(this).data('laporan_keuangan_id'),
                'parent_id':$(this).data('parent_id')
            }, 'Tambah Data Laporan');
        });

        $('body').on('click','.cls-button-delete-pilar',function(){
            onbtndeletepilar(this);
        });
        
        $('body').on('change', '#edit_active', function() {
            onbtneditactive(this);
        });

    });

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
    
    function onbtndeletepilar(element){
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
                url: urldeletepilar,
                data:{
                    "pilar_pembangunan_id": $(element).data('id'),
                    "versi_pilar_id": $(element).data('versi'),
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

    function onbtneditactive(element){
        var status_nama = 'Tidak Aktif';
        var status = false;
        if(element.checked) {
            status_nama = 'Aktif';
            status = true;
        }

        swal.fire({
            title: "Pemberitahuan",
            text: "Ubah data "+$(element).data('nama')+" menjadi "+status_nama+" ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlupdatestatus,
                data:{
                    "id": $(element).data('id'),
                    "status": status
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