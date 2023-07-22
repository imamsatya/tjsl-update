@extends('layouts.app')

@section('addbeforecss')
    <link href="{{ asset('plugins/jquery-treegrid-master/css/jquery.treegrid.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .border_bottom {
            border-bottom: 1px solid #c8c7c7;
        }
    </style>
@endsection

@section('content')
    {{-- <div id="perusahaan_id" data-variable="{{ $perusahaan_id }}"></div>
    <div id="tahun" data-variable="{{ $tahun }}"></div> --}}
    <div id="actionform" data-variable="{{ $actionform }}"></div>
    <div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="card" style="margin-bottom: 10px">
                <div class="card-header pt-5">
                    <div class="card-title">
                        <div class="accordion accordion-icon-collapse" id="kt_accordion_4">
                            <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_4_item_1">
                                <div class="row mb-4">
                                    <div class="col-1" style="text-align: center">
                                        <span class="accordion-icon">
                                            <i class="bi-duotone bi-plus-square fs-3 accordion-icon-on"></i>
                                            <i class="bi-duotone bi-dash-square fs-3 accordion-icon-off d-none"></i>
                                        </span>
                                    </div>
                                    <div class="col-11" style="">
                                        <h3>Upload Data Kegiatan Melalui Template</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="kt_accordion_4_item_1" class="fs-6 collapse" >
                    <div class="card-body p-0">
                        <div class="card-px py-10">
                            <form class="kt-form kt-form--label-right" method="POST" id="form-upload-excel">
                                <div class="form-group row  mb-5">

                                    <div class="col-lg-2 offset-lg-8">
                                        <label>Bulan</label>
                                        <select id="bulan_template_id" class="form-select form-select-solid form-select2" name="bulan_template_id"
                                            data-kt-select2="true" data-placeholder="Pilih Bulan" >
                                            <option></option>
                                            @foreach($bulan as $bulan_row)
                                            @php
                                                        $select = (($bulan_row->id == $bulan_id) ? 'selected="selected"' : '');
                                                    @endphp
                                            <option value="{{ $bulan_row->id }}" {!! $select !!}>{{ $bulan_row->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Tahun</label>
                                        <select class="form-select form-select-solid form-select2" id="tahun_template" name="tahun_template"
                                            data-kt-select2="true">
                                            @php for($i = date("Y"); $i>=2020; $i--){ @endphp
                                            @php
                                            $select = (($i == $tahun) ? 'selected="selected"' : '');
                                            @endphp
                                            <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                            @php } @endphp
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-5">
                                    <div class="col-lg-6 ">
                                        <label>File (*.xlsx)</label>
                                        <input class="form-control" type="file" name="file_name" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                                    </div>
                                    <div class="col-lg-2 pt-6">
                                        <button value="upload-excel" id="submit-upload-excel" type="submit" class="btn btn-success me-3">Proses</button>
                                    </div>
                                    <div class="col-lg-4 pt-6">
                                        <button id="download" type="button" class="btn btn-primary "><i
                                                class="fa fa-download"></i> Download Template</button>
                                    </div>
                                </div>
                            </form>
                            <!-- <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align:center;vertical-align:middle">No.</th>
                                        <th rowspan="2" style="text-align:center;vertical-align:middle">Tanggal</th>
                                        <th rowspan="2" style="text-align:center;vertical-align:middle">Nama File</th>
                                        <th colspan="2" style="text-align:center;vertical-align:middle">Hasil Upload</th>
                                        <th rowspan="2" style="text-align:center;vertical-align:middle">Keterangan</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:center;vertical-align:middle">Berhasil</th>
                                        <th style="text-align:center;vertical-align:middle">Gagal</th>
                                    </tr>
                                </thead>
                            </table> -->
                        </div>
                    </div>
                </div>                
            </div>
            
            <!--begin::Card-->
            <div class="card" style="margin-bottom: 10px">
                <!--begin::Card header-->
                <div class="card-header pt-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <div class="accordion accordion-icon-collapse" id="kt_accordion_5">
                            <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_5_item_1">
                                <div class="row mb-4">
                                    <div class="col-1" style="text-align: center">
                                        <span class="accordion-icon" style="margin-right: 60px">
                                            <i class="bi-duotone bi-plus-square fs-3 accordion-icon-on"></i>
                                            <i class="bi-duotone bi-dash-square fs-3 accordion-icon-off d-none"></i>
                                        </span>
                                    </div>
                                    <div class="col-11" style="">
                                        <h3>Input Data Kegiatan Melalui Form</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1"
                            data-kt-view-roles-table-toolbar="base">
                            {{-- <button type="button" class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search btn-search-active" style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-search cls-search btn-search-unactive" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        @if (!$view_only)
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-add" style="margin-right:3px;" data-toggle="tooltip" title="Tambah Data"><i class="bi bi-plus fs-3"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download Excel"><i class="bi bi-file-excel fs-3"></i></button>
                        @endif --}}
                        </div>
                        <!--end::Search-->
                        <!--end::Group actions-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--begin::Card body-->
                <div id="kt_accordion_5_item_1" class="fs-6 collapse" >
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
                    <div class="row">
                            <div class="col-lg-4 mb-20">
                                    <label>BUMN</label>
                                    @php
                                    // $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                    $disabled = 'disabled="true"';
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
                            <div class="col-lg-4 mb-20">
                                <label>Tahun</label>
                                @php
                                // $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                $disabled = 'disabled="true"';
                            @endphp
                                <select class="form-select form-select-solid form-select2" id="select-tahun" name="tahun" data-kt-select2="true" data-placeholder="Pilih Tahun" {{$disabled}} >
                                    @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                        @php
                                            $select = (($i == $tahun) ? 'selected="selected"' : '');
                                        @endphp
                                        <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                    @php } @endphp
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                        
                                <label>Bulan</label>
                                @php
                                // $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                $disabled = 'disabled="true"';
                            @endphp
                                <select id="bulan_id" class="form-select form-select-solid form-select2" name="bulan_id" data-kt-select2="true"  data-placeholder="Pilih Bulan" data-allow-clear="true" {{$disabled}}>
                                    <option></option>
                                    @foreach($bulan as $bulan_row)  
                                                @php
                                                $select = (($bulan_row->id == $bulan_id) ? 'selected="selected"' : '');
                                            @endphp
                                        <option  value="{{ $bulan_row->id }}" {!! $select !!}>{{ $bulan_row->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <form method="POST" id="program-form">
                                @csrf
                                <div class="mb-6 ">
                                    <div class="row mb-6">
                                        <div class="col-lg-3 ">
                                            <div class="ms-2 required">Jenis Anggaran</div>


                                        </div>
                                        <div class="col-lg-9">
                                            <select  id="jenis-anggaran" class="form-select form-select-solid form-select2" name="jenis_anggaran" data-kt-select2="true" data-placeholder="Pilih Jenis Anggaran" data-allow-clear="true">
                                                <option></option>
                                                <option value="CID" {{ request('jenis_anggaran') === 'CID' ? 'selected="selected"' : '' }} >
                                                        CID</option>
                                                <option value="non CID" {{ request('jenis_anggaran') === 'non CID' ? 'selected="selected"' : '' }} >
                                                    non CID</option>
                                            </select>

                                        </div>
                                    
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3 ">
                                            <div class="ms-2 required">Program</div>


                                        </div>
                                        <div class="col-lg-9">
                                            <select  id="program_id" class="form-select form-select-solid form-select2" name="program_id" data-kt-select2="true" data-placeholder="Pilih Program" data-allow-clear="true">
                                                <option></option>
                                                @foreach($program as $program_row)  
                                                {{-- @php
                                                    $select = (($p->no_tpb == $tpb_id) ? 'selected="selected"' : '');
                                                @endphp --}}
                                                <option data-jenis-anggaran="{{ $program_row->jenis_anggaran }}"  value="{{ $program_row->id }}" {!! $select !!}>{{ $program_row->program }} - {{$program_row->jenis_anggaran}}</option>
                                            @endforeach
                                            </select>

                                        </div>
                                    
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3 ">
                                            <div class="ms-2 required">Nama Kegiatan</div>


                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-floating">
                                                <textarea class="form-control" placeholder="Leave a comment here" id="nama_kegiatan" name="nama_kegiatan" style="height: 100px"></textarea>
                                                <label for="nama_kegiatan">Nama Kegiatan</label>
                                            </div>

                                        </div>
                                    
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3 ">
                                            <div class="ms-2 ">Jenis Kegiatan</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <select  id="jenis_kegiatan" class="form-select form-select-solid form-select2" name="jenis_kegiatan" data-kt-select2="true" data-placeholder="Pilih Jenis Kegiatan" data-allow-clear="true" onchange="filterSubkegiatan(this.value)">
                                                <option></option>
                                                @foreach($jenis_kegiatan as $jenis_kegiatan_row)  
                                                {{-- @php
                                                    $select = (($p->no_tpb == $tpb_id) ? 'selected="selected"' : '');
                                                @endphp --}}
                                                <option  value="{{ $jenis_kegiatan_row->id }}" {!! $select !!}>{{ $jenis_kegiatan_row->nama }}</option>
                                            @endforeach
                                            </select>

                                        </div>
                                    
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3">
                                            <div class="ms-2">Sub Kegiatan</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <select  id="keterangan_kegiatan" class="form-select form-select-solid form-select2" name="keterangan_kegiatan" data-kt-select2="true" data-placeholder="Pilih Sub Kegiatan" data-allow-clear="true">
                                                {{-- <option></option>
                                                @foreach($subkegiatan as $subkegiatan_row)  
                                                
                                                <option  value="{{ $subkegiatan_row->id }}" {!! $select !!}>{{ $subkegiatan_row->subkegiatan }}</option>
                                            @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3">
                                            <div class="ms-2 required">Provinsi</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <select id="provinsi" class="form-select form-select-solid form-select2" name="provinsi" data-kt-select2="true" data-placeholder="Pilih Provinsi" data-allow-clear="true">
                                                <option></option>
                                                <!-- Add options dynamically from your backend -->
                                                @foreach($provinsi as $provinsi_row)
                                                    <option value="{{ $provinsi_row->id }}">{{ $provinsi_row->nama }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3">
                                            <div class="ms-2 required">Kota/Kabupaten</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <select id="kota_kabupaten" class="form-select form-select-solid form-select2" name="kota_kabupaten" data-kt-select2="true" data-placeholder="Pilih Kota/Kabupaten" data-allow-clear="true">
                                                <option></option>
                                                <!-- Add options dynamically from your backend -->
                                                @foreach($kota_kabupaten as $kota_kabupaten_row)
                                                    <option value="{{ $kota_kabupaten_row->id }}" data-provinsi-id="{{ $kota_kabupaten_row->provinsi_id }}">{{ $kota_kabupaten_row->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3">
                                            <div class="ms-2 required">Realisasi Anggaran</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="text" name="realisasi_anggaran" id="realisasi_anggaran"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Rp ... " oninput="formatCurrency(this)" 
                                                onkeypress="return onlyNumbers(event)" style="text-align:right;"  value="{{$data->income_bumn_pembina_lain ?? ''}}"
                                                />
                                        </div>
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3 ">
                                            <div class="ms-2 required">Satuan Ukur</div>


                                        </div>
                                        <div class="col-lg-9">
                                            <select  id="satuan_ukur" class="form-select form-select-solid form-select2" name="satuan_ukur" data-kt-select2="true" data-placeholder="Pilih Satuan Ukur"  >
                                                <option></option>
                                                @foreach($satuan_ukur as $satuan_ukur_row)
                                                    <option value="{{ $satuan_ukur_row->id }}" >{{ $satuan_ukur_row->nama }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    
                                    </div>
                                    <div class="row mb-6">
                                        <div class="col-lg-3">
                                            <div class="ms-2">Realisasi Indikator</div>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="text" name="realisasi_indikator" id="realisasi_indikator"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Berdasarkan Satuan Ukur"  
                                                />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group row mt-8  mb-5 text-center">
                                <div class="col-lg-offset-3 col-lg-9">
                                    <button id="close-btn" class="btn btn-danger me-3">Close</button>
                                    <button id="clear-btn" class="btn btn-info me-3">Clear</button>
                                    <button id="simpan-btn" class="btn btn-success me-3">Simpan</button>
                                </div>
                            </div>

                            
                            <br><br>
                        
                        
                        </div>
                    </div>
                </div>                
                <!--end::Card body-->
            </div>
            <div class="card" style="margin-bottom: 10px">
                <div class="card-header pt-5">
                    <div class="card-title">
                        <div class="accordion accordion-icon-collapse" id="kt_accordion_6">
                            <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_6_item_1">
                                <div class="row mb-4">
                                    <div class="col-1" style="text-align: center">
                                        <span class="accordion-icon">
                                            <i class="bi-duotone bi-plus-square fs-3 accordion-icon-on"></i>
                                            <i class="bi-duotone bi-dash-square fs-3 accordion-icon-off d-none"></i>
                                        </span>
                                    </div>
                                    <div class="col-11" style="">
                                        <h3>History Upload Data Kegiatan Melalui Template</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div id="kt_accordion_6_item_1" class="fs-6 collapse" >
                <div class="card-body p-0">
                    <div class="card-px py-10">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="text-align:center;vertical-align:middle">No.</th>
                                    <th rowspan="2" style="text-align:center;vertical-align:middle">Tanggal</th>
                                    <th rowspan="2" style="text-align:center;vertical-align:middle">Nama File</th>
                                    <th colspan="2" style="text-align:center;vertical-align:middle">Hasil Upload</th>
                                    <th rowspan="2" style="text-align:center;vertical-align:middle">Keterangan</th>
                                </tr>
                                <tr>
                                    <th style="text-align:center;vertical-align:middle">Berhasil</th>
                                    <th style="text-align:center;vertical-align:middle">Gagal</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('addafterjs')
    <script type="text/javascript" src="{{ asset('plugins/jquery-treegrid-master/js/jquery.treegrid.js') }}"></script>

    <script>
        var urluploadstore = "{{route('laporan_realisasi.bulanan.kegiatan.upload_excel')}}";
        var urldatatable = "{{route('laporan_realisasi.bulanan.kegiatan.history_upload')}}";
        var urldownloadtemplate = "{{route('laporan_realisasi.bulanan.kegiatan.download_template')}}";

        $(document).ready(function() {                        
            setFormValidateUpload();
            $('.tree').treegrid({
                initialState : 'collapsed',
                treeColumn : 1,
                indentTemplate : '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
            });
            const urlParams = new URLSearchParams(window.location.search)
            const checkJenisAnggaran = urlParams.get('jenis_anggaran')
            if(checkJenisAnggaran !== '') {
                setTimeout(()=>{
                    $("#jenis-anggaran").val(checkJenisAnggaran).trigger('change')
                }, 1000)
            }

            $('body').on('click','#download',function(){
                downloadTemplate();
            });

            $("#jenis-anggaran").on('change', function(){
             
                const jenisAnggaran = $(this).val()
                $("#program_id").val('').trigger('change')
                
                
                $("#program_id").select2({    
                    templateResult: function(data) {
                        if($(data.element).attr('data-jenis-anggaran') === jenisAnggaran || jenisAnggaran === '') return data.text
                        return null
                    },
                    templateSelection: function(data) {
                        if($(data.element).attr('data-jenis-anggaran') === jenisAnggaran || jenisAnggaran === '') return data.text
                        return null
                    }
                })            

                let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
                $("#select2-program_id-container .select2-selection__placeholder").text('Pilih Program '+textAnggaran)
          

              
            
            })
              // Trigger the change event on page load
            $("#jenis-anggaran").trigger('change');
            $("#provinsi").on('change', function(){
                
                const provinsi = $(this).val()
                $("#kota_kabupaten").val('').trigger('change')
                
                
                $("#kota_kabupaten").select2({    
                    templateResult: function(data) {
                        if($(data.element).attr('data-provinsi-id') === provinsi || provinsi === '') return data.text
                        return null
                    },
                    templateSelection: function(data) {
                        if($(data.element).attr('data-provinsi-id') === provinsi || provinsi === '') return data.text
                        return null
                    }
                })            

                // let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
                $("#select2-kota_kabupaten-container .select2-selection__placeholder").text('Pilih Kota/Kabupaten ')
                // $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB '+textAnggaran)

              
            
            })

            $('#keterangan_kegiatan').on('change', function() {
                var subkegiatanId = $(this).val();
                if (subkegiatanId) {
                    // Find the corresponding satuan_ukur_id for the selected subkegiatan
                    var selectedSubkegiatan = {!! $subkegiatan->toJson() !!}.find(function(subkegiatan) {
                        return subkegiatan.id == subkegiatanId;
                    });

                    if (selectedSubkegiatan) {
                        // Set the selected value in the "satuan_ukur" field
                        $('#satuan_ukur').val(selectedSubkegiatan.satuan_ukur_id).trigger('change');
                        // Enable the "satuan_ukur" field
                        $('#satuan_ukur').prop('disabled', true);
                    }
                } else {
                    // If no subkegiatan is selected, enable the "satuan_ukur" field and reset its value
                    $('#satuan_ukur').prop('disabled', false).val('').trigger('change');
                }
            });

            $("#close-btn").on('click', function() {
                var url = window.location.pathname;
                var segments = url.split('/');
                console.log(segments)
                let routeTo = "{{route('laporan_realisasi.bulanan.kegiatan.index')}}"+"?perusahaan_id="+segments[5]+"&tahun="+segments[6]+"&bulan_id="+segments[7]                
                window.location.href = routeTo
            })


            let currentUrl = window.location.href
            let parts = currentUrl.split('/')
            let datatable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: urldatatable,
                    type: 'POST',
                    data: function(d) {
                        d.perusahaan_id = parts[7],
                        d.tahun = parts[8],
                        d.bulan = parts[9]
                    }
                },
                columns: [
                    { data: 'id', orderable: false, searchable: false },
                    { data: 'tanggal', name: 'tanggal' },
                    { data: 'file_name', name: 'file_name' },
                    { data: 'berhasil', name: 'berhasil' },
                    { data: 'gagal', name: 'gagal' },
                    { data: 'keterangan_trim', name: 'keterangan_trim' },
                ],
                drawCallback: function( settings ) {
                    var info = datatable.page.info();
                    $('[data-toggle="tooltip"]').tooltip();
                    datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = info.start + i + 1;
                    } );
                }
            });
           
            $(".accordion-header").click(function () {
                $(this).find(".accordion-icon-off").toggleClass("d-none");
                $(this).find(".accordion-icon-on").toggleClass("d-none");
            });
        });   
        
        function downloadTemplate()
    {
        var url = new URL(window.location.href);
        var params = url.pathname.split('/').slice(-3);
        var filter_perusahaan_id = params[0]; // "1"
        var filter_tahun = $("select[name='tahun']").val();
        var filter_bulan = $("select[name='bulan_id']").val();

        console.log(`perusahaan_id : ${filter_perusahaan_id} | tahun : ${filter_tahun} | bulan : ${filter_bulan}`)

        // var filter_perusahaan_id = $("select[name='perusahaan_id']").val();
        // var filter_tahun = $("select[name='tahun']").val();
        // var filter_bulan = $("select[name='bulan_id']").val();

        if(filter_perusahaan_id == '' || filter_tahun == '' || filter_bulan == ''){
            onbtndisablevalidasi();
        }else{
            $.ajax({
                type: 'post',
                data: {
                    'perusahaan_id' : filter_perusahaan_id,
                    'tahun' : filter_tahun,
                    // 'target_tpb_id' : $("select[name='program_id']").val(),
                    // 'pilar_pembangunan_id' : $("select[name='pilar_pembangunan_id']").val(),
                    // 'tpb_id' : $("select[name='tpb_id']").val(),
                    'bulan' : filter_bulan,
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
                    var filename = 'Template Input Data Laporan Realisasi Bulan '+ $("#bulan_id option:selected").text() +" Tahun "+`${filter_tahun}`+'.xlsx';

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

       

        function formatCurrency(element) {
           

            //ver 2
            let value = element.value.replace(/[^\d-]/g, "");
            let isNegative = false;

            if (value.startsWith("-")) {
                isNegative = true;
                value = value.substring(1);
            }

            let formatter = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });

            let formattedValue = formatter.format(value);
            formattedValue = formattedValue.replace(/,/g, ".");

            if (isNegative) {
                formattedValue = "- " + formattedValue;
            }

            element.value = formattedValue;
            
        }

        function formatCurrency2(element) {
            let value = element.value.replace(/[^\d-]/g, ""); // Remove all non-numeric characters except for hyphen "-"
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
            element.value = formattedValue;
        }

        function onlyNumbers(event) {
            // const key = event.keyCode || event.which;
            // if (key < 48 || key > 57) {
            //     event.preventDefault();
            // }
            const key = event.keyCode || event.which;

            // Allow backspace, delete, arrow keys, and "-"
            if (key == 8 || key == 46 || key == 37 || key == 39 || key == 45) {
                return true;
            }

            // Allow numbers
            if (key >= 48 && key <= 57) {
                return true;
            }

            // Prevent any other input
            event.preventDefault();
            return false;
        }

        const form = document.getElementById('program-form');

        document.getElementById('clear-btn').addEventListener('click', function() {
            var inputs = document.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type == 'text' || inputs[i].type == 'email' || inputs[i].type == 'password') {
                    inputs[i].value = '';
                } else if (inputs[i].type == 'radio' || inputs[i].type == 'checkbox') {
                    inputs[i].checked = false;
                }
            }

            // clear dropdown options
            var dropdowns = document.getElementsByTagName('select');
            for (var i = 0; i < dropdowns.length; i++) {
                dropdowns[i].selectedIndex = -1;
            }
        });
        // const clearBtn = document.querySelector("#clear-btn");
        // clearBtn.addEventListener("click", function() {
        //     const inputFields = document.querySelectorAll("input[type='text']");
        //     inputFields.forEach(function(input) {
        //         input.value = null;
        //     });
        // });
        

        const simpanBtn = document.querySelector("#simpan-btn");
        simpanBtn.addEventListener("click", async function() {
            console.log('simpan clicked')
            // e.preventDefault();
            var perusahaan_id = document.getElementById('perusahaan_id').value;
            // var perusahaan_id = perusahaan_id.getAttribute('data-variable');

            var tahun = document.getElementById('select-tahun').value;
            // var tahun = tahun.getAttribute('data-variable');
            var bulan = document.getElementById('bulan_id').value;

            var jenis_anggaran = document.getElementById('jenis-anggaran').value

             var actionform = document.getElementById('actionform');
            var actionform = actionform.getAttribute('data-variable');
            console.log(`perusahaan_id : ${perusahaan_id} | tahun : ${tahun} | jenis_anggaran : ${jenis_anggaran} | actionform : ${actionform}`)
            
            //data kegiatan

            let program_id = document.getElementById('program_id').value

            let nama_kegiatan = document.getElementById('nama_kegiatan').value
            let jenis_kegiatan = document.getElementById('jenis_kegiatan').value
            let keterangan_kegiatan = document.getElementById('keterangan_kegiatan').value
            let provinsi = document.getElementById('provinsi').value
            let kota_kabupaten = document.getElementById('kota_kabupaten').value
            let realisasi_anggaran = document.getElementById('realisasi_anggaran').value
            let satuan_ukur = document.getElementById('satuan_ukur').value
            let realisasi_indikator = document.getElementById('realisasi_indikator').value
            realisasi_anggaran = parseInt(realisasi_anggaran.replace(/[^0-9\-]/g, ''))
            let data = {
                program_id : program_id,
                nama_kegiatan : nama_kegiatan ,
                jenis_kegiatan : jenis_kegiatan ,
                keterangan_kegiatan : keterangan_kegiatan ,
                provinsi : provinsi ,
                kota_kabupaten : kota_kabupaten ,
                realisasi_anggaran : realisasi_anggaran ,
                satuan_ukur : satuan_ukur ,
                realisasi_indikator : realisasi_indikator 
            }
            console.log('data', data)
            const resultValidate = validate(data)

            if(!resultValidate.status) {
                swal.fire({                    
                    icon: 'warning',
                    html: resultValidate.message,
                    type: 'warning', 
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                });
                return
            }
            console.log(actionform)
            await $.ajax({
                url: '/laporan_realisasi/bulanan/kegiatan/store',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    data: data,
                    tahun: tahun,
                    bulan: bulan,
                    perusahaan_id: perusahaan_id,
                    actionform: actionform,
                    jenis_anggaran: jenis_anggaran
                },
                beforeSend: function() {
                    $.blockUI({
                        theme: false,
                        baseZ: 2000
                    })
                },
                success: function(response) {
                    $.unblockUI();
                    console.log(`success : ${response}`)
                    const responseObject = JSON.parse(response);
                    const flag = responseObject.result.flag;
                  
                    swal.fire({                    
                        icon: responseObject.result.flag,
                        title: responseObject.result.title,
                        html:  responseObject.result.msg,
                        type: responseObject.result.flag, 
                        confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                    })
                    if( responseObject.result.flag == 'success'){
                        window.location.reload();
                    }
                   
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        });


        const selectElement = document.getElementById('select-tahun');
        selectElement.addEventListener('change', function(event) {
            const selectedOption = event.target.value;
            console.log(selectedOption)
        // call your function here, passing in the selectedOption value as an argument
        });

        function validate(data) {
            // program_id : program_id,
            //     nama_kegiatan : nama_kegiatan ,
            //     jenis_kegiatan : jenis_kegiatan ,
            //     keterangan_kegiatan : keterangan_kegiatan ,
            //     provinsi : provinsi ,
            //     kota_kabupaten : kota_kabupaten ,
            //     realisasi_anggaran : realisasi_anggaran ,
            //     satuan_ukur : satuan_ukur ,
            //     realisasi_indikator : realisasi_indikator 
            if(data.program_id === '') return {status: false, message: 'Program harus terisi!'}
            if(data.nama_kegiatan === '') return {status: false, message: 'Nama Kegiatan harus terisi!'}
            // if(data.keterangan_kegiatan === '') return {status: false, message: 'Sub Kegiatan harus terisi!'}
            if(data.provinsi === '') return {status: false, message: 'Provinsi harus terisi!'}
            if(data.kota_kabupaten === '') return {status: false, message: 'Kota/Kabupaten harus terisi!'}
            if(data.realisasi_anggaran === '' ) return {status: false, message: 'Realisasi Anggaran anggaran harus terisi!'}
            if(data.satuan_ukur === '' ) return {status: false, message: 'Satuan Ukur anggaran harus terisi!'}
            if(data.realisasi_indikator === '' ) return {status: false, message: 'Realisasi Indikator anggaran harus terisi!'}

            
            return {status: true}
        }

        function filterSubkegiatan(jenisKegiatanId) {
            // Clear the current options in the "subkegiatan" select field
            $('#keterangan_kegiatan').empty();

            // If no jenis_kegiatan is selected, exit the function
            if (!jenisKegiatanId) return;

            // Filter the subkegiatan options based on the selected jenis_kegiatan
            var filteredSubkegiatan = {!! $subkegiatan->toJson() !!} // Convert $subkegiatan to a JavaScript object

            // Iterate through the filtered subkegiatan options
            $.each(filteredSubkegiatan, function(index, subkegiatan) {
                // Check if the jenis_kegiatan_id matches the selected jenis_kegiatan
                if (subkegiatan.jenis_kegiatan_id == jenisKegiatanId) {
                    // Create a new option element and append it to the "subkegiatan" select field
                    var option = $('<option>', { value: subkegiatan.id, text: subkegiatan.subkegiatan });
                    $('#keterangan_kegiatan').append(option);
                }
            });

            // Refresh the Select2 component for the "subkegiatan" select field
            $('#keterangan_kegiatan').trigger('change');
        }

    function setFormValidateUpload(){
        $('#form-upload-excel').validate({
            rules: {            		               		                              		               		               
            },
            messages: {                                   		                   		                   
            },	        
            highlight: function(element) {
                $(element).closest('.form-control').addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).closest('.form-control').removeClass('is-invalid');
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if(element.parent('.validated').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        submitHandler: function(form){
                var typesubmit = $("input[type=submit][clicked=true]").val();
                $(form).ajaxSubmit({
                    type: 'post',
                    url: urluploadstore,
                    data: {source : typesubmit},
                    dataType : 'json',
                    beforeSend: function(){
                        $.blockUI({
                            theme: true,
                            baseZ: 2000
                        })    
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

                        document.getElementById("form-upload-excel").reset();
                        datatable.ajax.reload();

                                               
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
                            msgerror = '';
                        } else if (exception === 'timeout') {
                            msgerror = 'RTO.';
                        } else if (exception === 'abort') {
                            msgerror = 'Gagal request ajax.';
                        } else {
                            msgerror = 'Error.\n' + jqXHR.responseText;
                        }
                        swal.fire({
                                title: "Gagal Upload",
                                html: msgerror+'Pastikan file excel tidak mengandung formula(rumus) dan format telah sesuai template dari portal TJSL !!!',
                                icon: 'error',

                                buttonsStyling: true,

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });                               
                    }
                });
                return false;
        }
        });		
    }

        

        
    </script>
@endsection
