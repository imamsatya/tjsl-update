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
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="d-flex align-items-center">{{ $pagetitle }}
                        <span class="text-gray-600 fs-6 ms-1"></span>
                    </h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->

                    <!--end::Search-->
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10">
                    @if ($errors->any())
                    <div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10">

                        <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3"
                                    d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                    fill="currentColor" />
                                <rect x="9" y="13.0283" width="7.3536" height="1.2256" rx="0.6128"
                                    transform="rotate(-45 9 13.0283)" fill="currentColor" />
                                <rect x="9.86664" y="7.93359" width="7.3536" height="1.2256" rx="0.6128"
                                    transform="rotate(45 9.86664 7.93359)" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Icon-->

                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column text-white pe-0 pe-sm-10">
                            <!--begin::Title-->
                            <h4 class="mb-2 text-white">Error !</h4>
                            <!--end::Title-->

                            <!--begin::Content-->
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Close-->
                        <button type="button"
                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                            data-bs-dismiss="alert">
                            <span class="svg-icon svg-icon-2x svg-icon-light"><svg width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3"
                                        d="M6 19.7C5.7 19.7 5.5 19.6 5.3 19.4C4.9 19 4.9 18.4 5.3 18L18 5.3C18.4 4.9 19 4.9 19.4 5.3C19.8 5.7 19.8 6.29999 19.4 6.69999L6.7 19.4C6.5 19.6 6.3 19.7 6 19.7Z"
                                        fill="currentColor" />
                                    <path
                                        d="M18.8 19.7C18.5 19.7 18.3 19.6 18.1 19.4L5.40001 6.69999C5.00001 6.29999 5.00001 5.7 5.40001 5.3C5.80001 4.9 6.40001 4.9 6.80001 5.3L19.5 18C19.9 18.4 19.9 19 19.5 19.4C19.3 19.6 19 19.7 18.8 19.7Z"
                                        fill="currentColor" />
                                </svg></span>
                        </button>
                        <!--end::Close-->
                    </div>
                    @endif
                    @if (\Session::has('success'))
                    <!--begin::Alert-->
                    <div class="alert alert-dismissible bg-success d-flex flex-column flex-sm-row p-5 mb-10">

                        <!--begin::Icon-->
                        <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3"
                                    d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z"
                                    fill="currentColor" />
                                <path
                                    d="M10.5606 11.3042L9.57283 10.3018C9.28174 10.0065 8.80522 10.0065 8.51412 10.3018C8.22897 10.5912 8.22897 11.0559 8.51412 11.3452L10.4182 13.2773C10.8099 13.6747 11.451 13.6747 11.8427 13.2773L15.4859 9.58051C15.771 9.29117 15.771 8.82648 15.4859 8.53714C15.1948 8.24176 14.7183 8.24176 14.4272 8.53714L11.7002 11.3042C11.3869 11.6221 10.874 11.6221 10.5606 11.3042Z"
                                    fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Icon-->

                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column text-white pe-0 pe-sm-10">
                            <!--begin::Title-->
                            <h4 class="mb-2 text-white">Sukses !</h4>
                            <!--end::Title-->

                            <!--begin::Content-->
                            <span>{{ Session::get('success') }}</span>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Close-->
                        <button type="button"
                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                            data-bs-dismiss="alert">
                            <span class="svg-icon svg-icon-2x svg-icon-light"><svg width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3"
                                        d="M6 19.7C5.7 19.7 5.5 19.6 5.3 19.4C4.9 19 4.9 18.4 5.3 18L18 5.3C18.4 4.9 19 4.9 19.4 5.3C19.8 5.7 19.8 6.29999 19.4 6.69999L6.7 19.4C6.5 19.6 6.3 19.7 6 19.7Z"
                                        fill="currentColor" />
                                    <path
                                        d="M18.8 19.7C18.5 19.7 18.3 19.6 18.1 19.4L5.40001 6.69999C5.00001 6.29999 5.00001 5.7 5.40001 5.3C5.80001 4.9 6.40001 4.9 6.80001 5.3L19.5 18C19.9 18.4 19.9 19 19.5 19.4C19.3 19.6 19 19.7 18.8 19.7Z"
                                        fill="currentColor" />
                                </svg></span>
                        </button>
                        <!--end::Close-->
                    </div>
                    <!--end::Alert-->
                    @endif
                    <!--begin: Datatable -->
                    <div class="row" id="form-cari">
                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <label>BUMN</label>
                                @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                @endphp
                                <select class="form-select form-select-solid form-select2" id="perusahaan_id"
                                    name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN"
                                    {{ $disabled }}>
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
                                <select class="form-select form-select-solid form-select2" id="tahun" name="tahun"
                                    data-kt-select2="true">
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
                                <label>Jenis Anggaran</label>
                                <select id="jenis-anggaran" class="form-select form-select-solid form-select2"
                                    name="jenis_anggaran" data-kt-select2="true" data-placeholder="Pilih Jenis Anggaran"
                                    data-allow-clear="true">
                                    <option></option>
                                    <option value="CID" {{ $jenis_anggaran == 'CID' ? 'selected="selected"' : '' }}>
                                        CID</option>
                                    <option value="non CID"
                                        {{ $jenis_anggaran == 'non CID' ? 'selected="selected"' : '' }}>
                                        non CID</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Program</label>
                                <select id="program_id" class="form-select form-select-solid form-select2"
                                    name="program_id" data-kt-select2="true" data-placeholder="Pilih Program"
                                    data-allow-clear="true">
                                    <option></option>
                                    <option></option>
                                    @foreach($program as $program_row)
                                    @php
                                    $select = (($program_row->id == $program_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option data-jenis-anggaran="{{ $program_row->jenis_anggaran }}"
                                        value="{{ $program_row->id }}" {!! $select !!}>{{ $program_row->program }} -
                                        {{$program_row->jenis_anggaran}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <label>Pilar Pembangunan</label>
                                <select id="pilar_pembangunan_id" class="form-select form-select-solid form-select2"
                                    name="pilar_pembangunan_id" data-kt-select2="true" data-placeholder="Pilih Pilar"
                                    data-allow-clear="true">
                                    <option></option>
                                    @foreach($pilar as $p)
                                    @php
                                    $select = ($p->id == $pilar_pembangunan_id) ? 'selected="selected"' : '';
                                    @endphp
                                    <option data-jenis-anggaran="{{ $p->jenis_anggaran }}" value="{{ $p->id }}" {!!
                                        $select !!}>
                                        {{ $p->nama }} - {{ $p->jenis_anggaran }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Bulan</label>
                                <select id="bulan_id" class="form-select form-select-solid form-select2" name="bulan_id"
                                    data-kt-select2="true" data-placeholder="Pilih Bulan" data-allow-clear="true">
                                    <option></option>
                                    @foreach($bulan as $bulan_row)
                                    @php
                                    $select = (($bulan_row->id == $bulan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bulan_row->id }}" {!! $select !!}>{{ $bulan_row->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <label>TPB</label>
                                <select id="tpb_id" class="form-select form-select-solid form-select2" name="tpb_id"
                                    data-kt-select2="true" data-placeholder="Pilih TPB" data-allow-clear="true">
                                    <option></option>
                                    @foreach($tpb as $p)
                                    @php
                                    $select = (($p->id == $tpb_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option data-jenis-anggaran="{{ $p->jenis_anggaran }}" value="{{ $p->id }}" {!!
                                        $select !!}>{{ $p->no_tpb }} - {{ $p->nama }} [{{$p->jenis_anggaran}}]</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Jenis Kegiatan</label>
                                <select id="jenis_kegiatan" class="form-select form-select-solid form-select2"
                                    name="jenis_kegiatan" data-kt-select2="true" data-placeholder="Pilih Jenis Kegiatan"
                                    data-allow-clear="true">
                                    <option></option>
                                    {{-- <option value="prioritas"
                                        {{ request('jenis_kegiatan') == 'prioritas' ? 'selected="selected"' : '' }}>
                                    Prioritas</option>
                                    <option value="umum"
                                        {{ request('jenis_kegiatan') == 'umum' ? 'selected="selected"' : '' }}>
                                        Umum</option>
                                    <option value="csv"
                                        {{ request('jenis_kegiatan') == 'csv' ? 'selected="selected"' : '' }}>
                                        CSV</option> --}}
                                    @foreach($jenis_kegiatan as $jenis_kegiatan_row)
                                    @php
                                    $select = (($jenis_kegiatan_row->id == $jenis_kegiatan_id) ? 'selected="selected"' :
                                    '');
                                    @endphp
                                    <option value="{{ $jenis_kegiatan_row->id }}" {!! $select !!}>
                                        {{ $jenis_kegiatan_row->nama }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <button id="proses" class="btn btn-success me-3">Proses</button>

                            </div>
                            {{-- <div class="col-lg-6 text-end">
                                <button id="download" class="btn btn-sm btn-primary me-3"><i class="bi bi-download fs-3"></i>Download Template</button>                                
                            </div> --}}
                        </div>
                        <div class="separator border-gray-200 mb-10"></div>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <br><br>
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <div class="col">
                    <h2 class="d-flex align-items-center mb-4">{{ $pagetitle }}
                        <span class="text-gray-600 fs-6 ms-1"></span>
                    </h2>
                    <div class="d-flex align-items-center position-relative my-1">
                        {{-- <button type="button" class="btn btn-success me-2 btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Simpan Status</button> --}}
                        {{-- <button type="button" class="btn btn-success btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Tambah</button> --}}
                        @can('view-kegiatan')
                        <button type="button" class="btn btn-success me-2 btn-sm rekap-data">Rekap Data</button>
                        @endcan
                        @can('edit-kegiatan')
                        <button type="button" class="btn btn-primary btn-sm me-2" onclick="redirectToNewPage()">Input
                            Data
                        </button>
                        @endcan
                        @can('delete-kegiatan')
                        <button type="button" class="btn btn-danger btn-sm delete-selected-data me-2">Hapus Data
                        </button>
                        @endcan
                        
                        @can('view-verify')
                        <button type="button" class="btn btn-primary btn-sm me-2" id="verify-data">Verify
                        </button>
                        @endcan
                        @can('view-unverify')
                        <button type="button" class="btn btn-warning btn-sm me-2" id="unverify-data">Un-Verify
                        </button>
                        @endcan
                        @can('view-finalVerify')
                        <button type="button" class="btn btn-success btn-sm me-2" id="finalVerify-data">Validate
                        </button>
                        @endcan
                        @can('view-finalUnverify')
                        <button type="button" class="btn btn-warning btn-sm me-2" id="finalUnverify-data">Un-Validate
                        </button>
                        @endcan
                    </div>
                    </div>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    {{-- <div class="d-flex align-items-center position-relative my-1"
                            data-kt-view-roles-table-toolbar="base">
                            <button type="button" class="btn btn-success me-2 btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Simpan Status</button>
                            <button type="button" class="btn btn-success btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Tambah</button>
                            <button type="button" class="btn btn-danger btn-sm delete-selected-data">Hapus Data
                                x</button>
                        </div> --}}
                   
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
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Bulan</th>
                                <th>Program</th>
                                <th>Kegiatan </th>
                                <th>Jenis Kegiatan</th>
                                <th>Prov - Kota/Kab</th>
                                <th>Realisasi (Rp)</th>
                                <th>Indikator Capaian</th>
                                <th>Status</th>
                                <th style="text-align:center; ">Aksi</th>
                                <th><label
                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                            class="form-check-input addCheck" type="checkbox" id="select-all"></label>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6">Total</th>
                                
                                <th style="white-space: nowrap;" class="text-end">@if ($totalAnggaranAlokasi < 0)
                                    Rp ( {{ number_format($totalAnggaranAlokasi, 0, ',', ',') }} )
                                @else
                                    Rp {{ number_format($totalAnggaranAlokasi, 0, ',', ',') }}
                                @endif</th>
                                <th></th>
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
<script type="text/javascript" src="{{asset('plugins/jquery-treegrid-master/js/jquery.treegrid.js')}}"></script>
<script>
    var datatable;
    // var urlcreate = "{{ route('referensi.tpb.create') }}";
    // var urledit = "{{ route('referensi.tpb.edit') }}";
    // var urlstore = "{{ route('referensi.tpb.store') }}";
    // var urlupdate = "{{ route('referensi.tpb.update') }}";
    // var urldatatable = "{{ route('referensi.tpb.datatable') }}";
    var urldelete = "{{ route('laporan_realisasi.bulanan.kegiatan.delete') }}";
    var urldatatable = "{{ route('laporan_realisasi.bulanan.kegiatan.datatable') }}";
    var urllog = "{{route('laporan_realisasi.bulanan.kegiatan.log')}}";
    var urledit = "{{route('laporan_realisasi.bulanan.kegiatan.edit')}}";
    var urlverifikasidata = "{{route('laporan_realisasi.bulanan.kegiatan.verifikasi_data')}}";
    var urlbatalverifikasidata = "{{route('laporan_realisasi.bulanan.kegiatan.batal_verifikasi_data')}}";
    var urlfinalverifikasidata = "{{route('laporan_realisasi.bulanan.kegiatan.final_verifikasi_data')}}";
    var urlbatalfinalverifikasidata = "{{route('laporan_realisasi.bulanan.kegiatan.batal_final_verifikasi_data')}}";
    var urldetail = "{{route('laporan_realisasi.bulanan.kegiatan.detail')}}";
    var urldownloadtemplate = "{{route('laporan_realisasi.bulanan.kegiatan.download_template')}}";
    var urlexport = "{{route('laporan_realisasi.bulanan.kegiatan.export')}}";

    $(document).ready(function () {
        $('.tree').treegrid({
            initialState: 'collapsed',
            treeColumn: 1,
            indentTemplate: '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
        });
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click', '.cls-add', function () {
            winform(urlcreate, {}, 'Tambah Data');
        });

        $('body').on('click', '.cls-button-edit', function () {
            winform(urledit, {
                'id': $(this).data('id'),
                'perusahaan_id': $("select[name='perusahaan_id']").val(),
                'tahun': $("select[name='tahun']").val(),
                'jenis_anggaran': $('#jenis-anggaran').val()
            }, 'Ubah Data');
        });

        $('body').on('click', '.cls-button-delete', function () {
            onbtndelete(this);
        });

        $('body').on('click', '.cls-log', function () {
            winform(urllog, {
                'id': $(this).data('id')
            }, 'Log Data');
        });

        $('body').on('click', '.cls-button-detail', function () {
            winform(urldetail, {
                'id': $(this).data('id')
            }, 'Ubah Data');
        });
        $('body').on('click', '#download', function () {
            downloadTemplate();
        });


        setDatatable();

        $('#proses').on('click', function (event) {
            // datatable.ajax.reload()
            var url = window.location.origin + '/laporan_realisasi/bulanan/kegiatan/index';
            var perusahaan_id = $('#perusahaan_id').val();
            var tahun = $('#tahun').val();
            var pilar_pembangunan_id = $('#pilar_pembangunan_id').val();
            var tpb_id = $('#tpb_id').val();
            var program_id = $('#program_id').val();
            var bulan_id = $('#bulan_id').val()
            var jenis_kegiatan = $('#jenis_kegiatan').val()
            const jenisAnggaran = $("#jenis-anggaran").val()
            // const statusAnggaran = $("#status-anggaran").val()   

            window.location.href = url + '?perusahaan_id=' + perusahaan_id +
                '&tahun=' + tahun +
                '&pilar_pembangunan=' + pilar_pembangunan_id +
                '&tpb=' + tpb_id +
                '&jenis_anggaran=' + jenisAnggaran +
                '&program_id=' + program_id +
                '&bulan_id=' + bulan_id +
                '&jenis_kegiatan=' + jenis_kegiatan;
        });


        //Imam
        // Add event listener for the "select all" checkbox in the table header
        $('#select-all').on('click', function () {
            // Get all checkboxes in the table body
            var checkboxes = $('.row-check');
            // Set the "checked" property of all checkboxes to the same as the "checked" property of the "select all" checkbox
            checkboxes.prop('checked', $(this).prop('checked'));
        });

        // Add event listener for individual checkboxes in the table body
        $('tbody').on('click', 'input[type="checkbox"]', function () {
            // Get all checkboxes in the table body
            var checkboxes = $('tbody input[type="checkbox"]');
            // Set the "checked" property of the "select all" checkbox based on whether all checkboxes in the table body are checked
            $('#select-all').prop('checked', checkboxes.length == checkboxes.filter(':checked').length);
        });

        // Add event listener for the page event of the datatable
        datatable.on('page.dt', function () {
            // Uncheck the "select all" checkbox
            $('#select-all').prop('checked', false);
        });

        $('tbody').on('click', '.is_active-check', function () {
            var id = $(this).val();
            var finalStatus = $(this).prop('checked') ? true : false;
            var rowData = $(this).data('row')
            var no_tpb = $(this).data('no_tpb');
            var nama_tpb = $(this).data('nama');
            var jenis_anggaran = $(this).data('jenis_anggaran');

            // Send an AJAX request to set the "selected" attribute in the database
            $.ajax({
                url: '/referensi/tpb/update_status',
                type: 'POST',
                data: {
                    id: id,
                    finalStatus: finalStatus
                },
                success: function (response) {
                    toastr.success(
                        `Status data <strong>${nama_tpb}</strong> dengan Kode TPB <strong>${no_tpb}</strong> dan jenis anggaran <strong>${jenis_anggaran}</strong> berhasil diubah menjadi <strong>${finalStatus}</strong>!`
                    );
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        });

        //body
        // $('body').on('click', '.delete-selected-data', function () {
        //     console.log('halo')
        //     var selectedData = $('input[name="selected-data[]"]:checked').map(function () {
        //         return $(this).val();
        //     }).get();
        //     Swal.fire({
        //         title: 'Apakah Anda Yakin?',
        //         text: 'Apakah anda yakin akang menghapus data yang sudah dipilih?',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonText: 'Ya',
        //         cancelButtonText: 'Batal',
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // If the user confirmed the deletion, do something here
        //             console.log('User confirmed deletion');
        //             // Send an AJAX request to set the "selected" attribute in the database
        //             $.ajax({
        //                 url: '/referensi/tpb/delete',
        //                 type: 'POST',
        //                 data: {
        //                     "_token": "{{ csrf_token() }}",
        //                     selectedData: selectedData
        //                 },
        //                 success: function (response) {
        //                     window.location.reload();
        //                     // console.log(`success : ${response}`)
        //                     // toastr.success(
        //                     //     `Status data <strong>${nama_tpb}</strong> dengan ID TPB <strong>${no_tpb}</strong> dan jenis anggaran <strong>${jenis_anggaran}</strong> berhasil diubah menjadi <strong>${finalStatus}</strong>!`
        //                     // );
        //                 },
        //                 error: function (jqXHR, textStatus, errorThrown) {
        //                     console.log(errorThrown);
        //                 }
        //             });
        //         } else {
        //             // If the user cancelled the deletion, do something here
        //             console.log('User cancelled deletion');
        //         }
        //     })
        //     console.log(selectedData)


        // });


        const urlParams = new URLSearchParams(window.location.search)
        // const checkJenisAnggaran = urlParams.get('jenis_anggaran')
        // if (checkJenisAnggaran !== '') {
        //     setTimeout(() => {
        //         $("#jenis-anggaran").val(checkJenisAnggaran).trigger('change')
        //     }, 1000)
        // }
        $("#jenis-anggaran").on('change', function () {
            // yovi
            const jenisAnggaran = $(this).val()
            // $("#tpb_id, #pilar_pembangunan_id, #program_id").val('').trigger('change')


            $("#tpb_id, #pilar_pembangunan_id, #program_id").select2({
                templateResult: function (data) {
                    if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
                        jenisAnggaran === '') return data.text
                    return null
                },
                templateSelection: function (data) {
                    if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
                        jenisAnggaran === '') return data.text
                    return null
                }
            })

            let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
            $("#select2-pilar_pembangunan_id-container .select2-selection__placeholder").text(
                'Pilih Pilar ' + textAnggaran)
            $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB ' +
                textAnggaran)
            $("#select2-program_id-container .select2-selection__placeholder").text('Pilih Program ' +
                textAnggaran)

        })
        $("#jenis-anggaran").trigger('change');

        $(".delete-selected-data").on('click', function () {
            var selectedProgram = $('input[name="selected-data[]"]:checked').map(function () {
                return $(this).val();
            }).get();


            if (!selectedProgram.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk dihapus!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }
            deleteSelectedProgram(selectedProgram)
        })
        $("#finalUnverify-data").on('click', function () {
            var selectedProgram = $('input[name="selected-data[]"]:checked').map(function () {
                return $(this).val();
            }).get();

            if (!selectedProgram.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk di unvalidate!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }

            finalUnverifySelectedData(selectedProgram)

        })
        $("#finalVerify-data").on('click', function () {
            var selectedProgram = $('input[name="selected-data[]"]:checked').map(function () {
                return $(this).val();
            }).get();

            if (!selectedProgram.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk di validate!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }

            finalVerifySelectedData(selectedProgram)

        })

        $("#verify-data").on('click', function () {
            var selectedProgram = $('input[name="selected-data[]"]:checked').map(function () {
                return $(this).val();
            }).get();

            if (!selectedProgram.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk di complete!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }

            verifySelectedData(selectedProgram)

        })

        $("#unverify-data").on('click', function () {
            var selectedProgram = $('input[name="selected-data[]"]:checked').map(function () {
                return $(this).val();
            }).get();

            if (!selectedProgram.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk di uncomplete!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }

            unverifySelectedData(selectedProgram)

        })

        $(".rekap-data").on('click', function(){
                exportExcel();
            })

            function exportExcel()
        {
            $.ajax({
                type: 'post',
                data: {
                    'perusahaan_id' : $("select[name='perusahaan_id']").val(),
                        'tahun' : $("select[name='tahun']").val(),

                        'jenis_anggaran' : $('#jenis-anggaran').val(),
                        'program_id' : $('#program_id').val(),
                        'pilar_pembangunan_id' : $('#pilar_pembangunan_id').val(),
                        'bulan' : $('#bulan_id').val(),
                        'tpb_id' : $('#tpb_id').val(),
                        'jenis_kegiatan' : $('#jenis_kegiatan').val()
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
                    var filename = 'Kegiatan '+today+'.xlsx';

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


    });

    function setDatatable() {
        datatable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urldatatable,
                type: 'GET',
                data: function (d) {
                    d.perusahaan_id = $("select[name='perusahaan_id']").val(),
                        d.tahun = $("select[name='tahun']").val(),

                        d.jenis_anggaran = $('#jenis-anggaran').val(),
                        d.program_id = $('#program_id').val(),
                        d.pilar_pembangunan_id = $('#pilar_pembangunan_id').val(),
                        d.bulan = $('#bulan_id').val(),
                        d.tpb_id = $('#tpb_id').val(),
                        d.jenis_kegiatan = $('#jenis_kegiatan').val()
                }
            },
            columns: [
                // ['id', 'target_tpb_program', 'kegiatan', 'jenis_kegiatan_nama', 'provinsi_nama','kota_nama', 'anggaran_alokasi', 'indikator', 'kegiatan_realisasi_status_id', 'action']
                {
                    data: 'id',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'kegiatan_realisasi_bulan',
                    name: 'kegiatan_realisasi_bulan',
                    render: function (data, type, row) {
                        return row.bulan_nama;
                    }
                },
                {
                    data: 'target_tpb_program',
                    name: 'target_tpb_program',
                    render: function (data, type, row) {

                        return data + ' - ' + row.jenis_anggaran;
                    }
                },
                {
                    data: 'kegiatan',
                    name: 'kegiatan',
                    render: function (data, type, row) {

                        detailKegiatan =
                            `<a href="javascript:void(0)"><div class="cls-button-detail" data-id=${row.id}>${data}</div></a>`
                        return detailKegiatan;
                    }
                },
                {
                    data: 'jenis_kegiatan_nama',
                    name: 'jenis_kegiatan_nama',
                    render: function (data, type, row) {

                        jenisKegiatan = data ? `${data}` : `--`
                        return jenisKegiatan;
                    }
                },

                {
                    data: 'provinsi_nama',
                    name: 'provinsi_nama',
                    className: 'text-center',
                    render: function (data, type, row) {

                        return data + ' - ' + row.kota_nama;
                    }
                },
                {
                    data: 'anggaran_alokasi',
                    name: 'anggaran_alokasi',
                    render: function (data, type, row) {

                        let formattedValue = formatCurrency2(data.toString());
                            return `<div class="text-end">${formattedValue}</div>`;
                    }
                },
                {
                    data: 'indikator',
                    name: 'indikator',
                    render: function (data, type, row) {

                        return data + ' ' + row.satuan_ukur_nama;
                    }
                },
                {
                    data: 'kegiatan_realisasi_status_id',
                    name: 'kegiatan_realisasi_status_id',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        console.log(row)
                        let status = null
                        if (data === 1) {
                            status =
                                `<span class="btn cls-log badge badge-light-success fw-bolder me-auto px-4 py-3" data-id="${row.id}">Verified</span>`
                        }
                        if (data === 2) {
                            status =
                                `<span class="btn cls-log badge badge-light-primary fw-bolder me-auto px-4 py-3" data-id="${row.id}">In Progress</span>`
                        }
                        if (data === 4) {
                            status =
                                `<span class="btn cls-log badge badge-light-success fw-bolder me-auto px-4 py-3" data-id="${row.id}">Validated</span>`
                        }
                        return status;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    render: function (data, type, row) {
                        // console.log(row)
                        let button = null;
                        if (row.kegiatan_realisasi_status_id === 2) {
                            button =
                                `@can('edit-kegiatan')<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="${row.id}"  data-toggle="tooltip" title="Ubah data "><i class="bi bi-pencil fs-3"></i></button>@endcan`
                        }

                        if (row.kegiatan_realisasi_status_id === 1 || row.kegiatan_realisasi_status_id === 4) {
                            button =
                                `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-info cls-button-detail" data-id="${row.id}"  data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                        }
                        return button
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input class="form-check-input row-check" type="checkbox" name="selected-data[]" value="${row.id}"></label>`;
                    }
                }
            ],
            // footerCallback: function (row, data, start, end, display) {
            //     var api = this.api();

            //     var intVal = function ( i ) {
            //         return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            //     };

            //     $(api.column(3).footer()).html(api.column(3).data().reduce(function (a, b) {
            //             return addCommas(intVal(a) + intVal(b));
            //         }, 0)
            //     );
            // },
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                pageLength: 10,
                footerCallback: function (tfoot, data, start, end, display) {
            // var api = this.api();

            // var getColumnTotal = function (columnIndex) {
            //     return api
            //         .column(columnIndex, { page: 'current' })
            //         .data()
            //         .reduce(function (acc, val) {
            //             return acc + parseFloat(val);
            //         }, 0);
            // };

            // // Calculate and display the total in the footer
            // $(api.column(6).footer()).html('<div class="text-end">' + formatCurrency2(getColumnTotal(6).toFixed(0)) + '</div>');
        },
            drawCallback: function (settings) {
                var info = datatable.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                datatable.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                });


            }
        });
    }

    function onbtndelete(element) {
        swal.fire({
            title: "Pemberitahuan",
            text: "Yakin hapus data " + $(element).data('nama') + " ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data",
            cancelButtonText: "Tidak"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urldelete,
                    data: {
                        "id": $(element).data('id')
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });

                        if (data.flag == 'success') {
                            datatable.ajax.reload(null, false);
                        }

                    },
                    error: function (jqXHR, exception) {
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
                            html: msgerror + ', coba ulangi kembali !!!',
                            icon: 'error',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });
                    }
                });
            }
        });
    }

    //Imam
    function redirectToNewPage() {
        var selectedPerusahaanId = $('#perusahaan_id').val();
        var selectedPerusahaanText = $('#perusahaan_id option:selected').text();

        var selectedTahun = $('#tahun').val();
        var selectedTahunText = $('#tahun option:selected').text();

        var selectedBulan = $('#bulan_id').val()
        var selectedJenisAnggaran = $('#jenis-anggaran').val();

        // Do something with the selected value and text
        console.log("selectedPerusahaanId: " + selectedPerusahaanId);
        console.log("selectedPerusahaanText: " + selectedPerusahaanText);

        console.log("selectedTahun: " + selectedTahun);
        console.log("selectedTahunText: " + selectedTahunText);
        if (selectedPerusahaanId === '' || selectedTahun === '' || selectedBulan === '') {
            swal.fire({
                icon: 'warning',
                html: 'Perusahaan (BUMN), Tahun dan Bulan harus terisi!',
                type: 'warning',
                confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
            });
            return
        }

        // Use the Laravel's built-in route function to generate the new URL
        var url =
            "{{ route('laporan_realisasi.bulanan.kegiatan.create', ['perusahaan_id' => ':perusahaan_id', 'tahun' => ':tahun', 'bulan' => ':bulan_id']) }}";
        url = url.replace(':perusahaan_id', selectedPerusahaanId).replace(':tahun', selectedTahun).replace(':bulan_id',
            selectedBulan)
        // Redirect the user to the new page
        window.location.href = url;
    }

    function deleteSelectedProgram(selectedProgram) {
        const jumlahDataDeleted = selectedProgram.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin hapus data ? <br/><span style='color: red; font-weight: bold'>[Data selected: " +
                jumlahDataDeleted + " rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data",
            cancelButtonText: "Tidak"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urldelete,
                    data: {
                        "kegiatan_deleted": selectedProgram
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,
                            buttonsStyling: true,
                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });

                        if (data.flag == 'success') {
                            location.reload();
                        }

                    },
                    error: function (jqXHR, exception) {
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
                            html: msgerror + ', coba ulangi kembali !!!',
                            icon: 'error',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });
                    }
                });
            }
        });
    }

    function finalUnverifySelectedData(selectedData) {
        const jumlahSelected = selectedData.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin membatalkan Validasi data ? <br/><span style='color: red; font-weight: bold'>[Data selected: " +
                jumlahSelected + " rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urlbatalfinalverifikasidata,
                    data: {
                        "kegiatan_verifikasi": selectedData
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });

                        if (data.flag == 'success') {
                            // datatable.ajax.reload( null, false );
                            location.reload();
                        }

                    },
                    error: function (jqXHR, exception) {
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
                            html: msgerror + ', coba ulangi kembali !!!',
                            icon: 'error',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });
                    }
                });
            }
        });
    }

    function finalVerifySelectedData(selectedData) {
        const jumlahSelected = selectedData.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin Validasi data ? <br/><span style='color: red; font-weight: bold'>[Data selected: " +
                jumlahSelected + " rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urlfinalverifikasidata,
                    data: {
                        "kegiatan_verifikasi": selectedData
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });

                        if (data.flag == 'success') {
                            // datatable.ajax.reload( null, false );
                            location.reload();
                        }

                    },
                    error: function (jqXHR, exception) {
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
                            html: msgerror + ', coba ulangi kembali !!!',
                            icon: 'error',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });
                    }
                });
            }
        });
    }

    function verifySelectedData(selectedData) {
        const jumlahSelected = selectedData.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin Verifikasi data ? <br/><span style='color: red; font-weight: bold'>[Data selected: " +
                jumlahSelected + " rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urlverifikasidata,
                    data: {
                        "kegiatan_verifikasi": selectedData
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });

                        if (data.flag == 'success') {
                            // datatable.ajax.reload( null, false );
                            location.reload();
                        }

                    },
                    error: function (jqXHR, exception) {
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
                            html: msgerror + ', coba ulangi kembali !!!',
                            icon: 'error',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });
                    }
                });
            }
        });
    }

    function unverifySelectedData(selectedData) {
        const jumlahSelected = selectedData.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin membatalkan Verifikasi data ? <br/><span style='color: red; font-weight: bold'>[Data selected: " +
                jumlahSelected + " rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urlbatalverifikasidata,
                    data: {
                        "kegiatan_verifikasi": selectedData
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });

                        if (data.flag == 'success') {
                            // datatable.ajax.reload( null, false );
                            location.reload();
                        }

                    },
                    error: function (jqXHR, exception) {
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
                            html: msgerror + ', coba ulangi kembali !!!',
                            icon: 'error',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });
                    }
                });
            }
        });
    }

    function downloadTemplate() {
        var filter_perusahaan_id = $("select[name='perusahaan_id']").val();
        var filter_tahun = $("select[name='tahun']").val();
        var filter_bulan = $("select[name='bulan_id']").val();

        if (filter_perusahaan_id == '' || filter_tahun == '' || filter_bulan == '') {
            onbtndisablevalidasi();
        } else {
            $.ajax({
                type: 'post',
                data: {
                    'perusahaan_id': filter_perusahaan_id,
                    'tahun': filter_tahun,
                    'target_tpb_id': $("select[name='program_id']").val(),
                    'pilar_pembangunan_id': $("select[name='pilar_pembangunan_id']").val(),
                    'tpb_id': $("select[name='tpb_id']").val(),
                    'bulan': filter_bulan,
                },
                beforeSend: function () {
                    $.blockUI();
                },
                url: urldownloadtemplate,
                xhrFields: {
                    responseType: 'blob',
                },
                success: function (data) {
                    $.unblockUI();
                    var filename = 'Template Input Data Laporan Realisasi Bulan ' + $(
                        "#bulan_id option:selected").text() + " Tahun " + $("#tahun").val() + '.xlsx';

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
                error: function (jqXHR, exception) {
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
                        html: msgerror + ', coba ulangi kembali !!!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                    });

                }
            });
        }
        return false;
    }

    function onbtndisablevalidasi() {
        swal.fire({
            title: "Gagal",
            html: 'Pilihan BUMN, Bulan dan Tahun wajib diisi!',
            icon: 'error',

            buttonsStyling: true,

            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
        });
    }

    function formatCurrency2(element) {
         
         let value = element.replace(/[^\d-]/g, ""); // Remove all non-numeric characters except for hyphen "-"
         const isNegative = value.startsWith("-");
         value = value.replace("-", ""); // Remove hyphen if it exists
         const formatter = new Intl.NumberFormat("id-ID", {
             style: "currency",
             currency: "IDR",
             minimumFractionDigits: 0,
             maximumFractionDigits: 0
         });
         let formattedValue = formatter.format(value);
         formattedValue = formattedValue.replace(/,/g, ".");
         if (isNegative) {
             formattedValue = "( " + formattedValue + " )";
         } 
         element = formattedValue;
         return element
      
     }

</script>
@endsection
