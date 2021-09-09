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
                    <span class="text-gray-600 fs-6 ms-1"> {{ $perusahaan->nama_lengkap }}</span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                        <button type="button" class="btn btn-success btn-sm cls-add" data-kt-view-roles-table-select="delete_selected">Tambah Kegiatan</button>
                    </div>
                    <!--end::Search-->
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <div class="card-px py-10">
                    <!--begin: Datatable -->
                    <div class="form-group row  mb-5">
                        <div class="col-lg-3">
                            <label>Bulan</label>
                            <select class="form-select form-select-solid form-select2" id="bulan" name="bulan" data-kt-select2="true" data-placeholder="Pilih Bulan">
                                <option value="1" {{ ((1 == date('m')) ? 'selected="selected"' : '') }}>Januari</option>
                                <option value="2" {{ ((2 == date('m')) ? 'selected="selected"' : '') }}>Februari</option>
                                <option value="3" {{ ((3 == date('m')) ? 'selected="selected"' : '') }}>Maret</option>
                                <option value="4" {{ ((4 == date('m')) ? 'selected="selected"' : '') }}>April</option>
                                <option value="5" {{ ((5 == date('m')) ? 'selected="selected"' : '') }}>Mei</option>
                                <option value="6" {{ ((6 == date('m')) ? 'selected="selected"' : '') }}>Juni</option>
                                <option value="7" {{ ((7 == date('m')) ? 'selected="selected"' : '') }}>Juli</option>
                                <option value="8" {{ ((8 == date('m')) ? 'selected="selected"' : '') }}>Agustus</option>
                                <option value="9" {{ ((9 == date('m')) ? 'selected="selected"' : '') }}>September</option>
                                <option value="10" {{ ((10 == date('m')) ? 'selected="selected"' : '') }}>Oktober</option>
                                <option value="11" {{ ((11 == date('m')) ? 'selected="selected"' : '') }}>November</option>
                                <option value="12" {{ ((12 == date('m')) ? 'selected="selected"' : '') }}>Desember</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
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
                    <div class="form-group row  mb-5" style="display:none;">
                        <div class="col-lg-6">
                            <label>Pilar Pembangunan</label>
                            <select id="pilar_pembangunan_id" class="form-select form-select-solid form-select2" name="pilar_pembangunan_id" data-kt-select2="true" data-placeholder="Pilih Pilar" data-allow-clear="true">
                                <option></option>
                                @foreach($pilar as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>TPB</label>
                            <select id="tpb_id" class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true" data-placeholder="Pilih TPB" data-allow-clear="true">
                                <option></option>
                                @foreach($tpb as $p)  
                                    <option value="{{ $p->id }}">{{ $p->no_tpb }} - {{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-5" style="display:none;">
                        <div class="col-lg-6">
                            <button id="filter" class="btn btn-success me-3">Filter</button>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                        <thead>
                            <tr>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">No.</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Program</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Kegiatan</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Kabupaten/Kota</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Indikator</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Satuan Ukur</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Target</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Realisasi</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Realisasi Anggaran</th>
                                <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach($kegiatan as $p)
                            <tr>
                                <td>{{$no++}}</td>
                                <td>{{@$p->target_tpb->program}}</td>
                                <td>{{@$p->kegiatan}}</td>
                                <td>{{@$p->provinsi->nama}} -<br>
                                    {{@$p->kota->nama}}
                                </td>
                                <td>{{@$p->indikator}}</td>
                                <td>{{@$p->satuan_ukur->nama}}</td>
                                <td contenteditable="true" style="text-align:right;">10</td>
                                <td contenteditable="true" style="text-align:right;">20</td>
                                <td contenteditable="true" style="text-align:right;">100.000.000</td>
                                <td style="text-align:center;">
                                    <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="{{$p->id}}" data-toggle="tooltip" title="Ubah data {{@$p->tpb->no_tpb}}"><i class="bi bi-pencil fs-3"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-id="{{$p->id}}" data-nama="{{@$p->tpb->no_tpb}}" data-toggle="tooltip" title="Hapus data {{@$p->tpb->no_tpb}}"><i class="bi bi-trash fs-3"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
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
    var urlcreate = "{{route('target.kegiatan.create')}}";
    var urledit = "{{route('target.kegiatan.edit')}}";
    var urlstore = "{{route('target.kegiatan.store')}}";
    var urldatatable = "{{route('target.kegiatan.datatable')}}";
    var urldelete = "{{route('target.kegiatan.delete')}}";

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
        
        $('#bulan').on('change', function(event){
            // datatable.ajax.reload()
            var url = window.location.origin + '/target/kegiatan/index';
            var perusahaan_id = {{ $perusahaan_id }};
            var tahun = $('#tahun').val();

            window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun;
        });

        // setDatatable();
    });

    function setDatatable(){
        datatable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: urldatatable,
            columns: [
                { data: 'id', orderable: false, searchable: false },
                { data: 'program', name: 'program' },
                { data: 'kegiatan', name: 'kegiatan' },
                { data: 'provinsi', name: 'provinsi' },
                { data: 'kota', name: 'kota' },
                { data: 'indikator', name: 'indikator' },
                { data: 'satuan_ukur', name: 'satuan_ukur' },
                { data: 'target', name: 'target' },
                { data: 'realisasi', name: 'realisasi' },
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
