@extends('layouts.app')

@section('addbeforecss')
@endsection

@section('content')

<style>
    .chartdiv {
        width: 100%;
        height: 35vh;
    }

    .chart {
        position: relative;
        display: inline-block;
        width: 150px;
        height: 150px;
        margin-top: 20px;
        margin-bottom: 10px;
        text-align: center;
        font-size: 16pt;
    }

    .chart canvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .percent {
        display: inline-block;
        line-height: 150px;
        z-index: 2;
    }

    .percent:after {
        content: '%';
        margin-left: 0.1em;
        font-size: 16pt;
    }

    .chart2 {
        position: relative;
        display: inline-block;
        width: 150px;
        height: 150px;
        margin-top: 20px;
        margin-bottom: 10px;
        text-align: center;
        font-size: 16pt;
    }

    .chart2 canvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .percent2 {
        display: inline-block;
        line-height: 150px;
        z-index: 2;
        margin-left: 60px;
        padding-top: 20px;
    }

    .percent2:after {
        content: '%';
        margin-left: 0.1em;
        font-size: 16pt;
    }

</style>


<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <div class="card" style="margin-bottom: 10px">
            <div class="card-header pt-5">
                <div class="card-title">
                    <div class="accordion accordion-icon-collapse" id="kt_accordion_9">
                        <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse"
                            data-bs-target="#kt_accordion_9_item_1">
                            <div class="row mb-4">
                                <div class="col-1" style="text-align: center">
                                    <span class="accordion-icon">
                                        <i class="bi-duotone bi-plus-square fs-3 accordion-icon-on"></i>
                                        <i class="bi-duotone bi-dash-square fs-3 accordion-icon-off d-none"></i>
                                    </span>
                                </div>
                                <div class="col-11" style="">
                                    <h3>Status Pengisian Data Berdasarkan Menu </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_accordion_9_item_1" class="fs-6 collapse">
                <div class="card-body p-0">
                    <div class="card-px py-10">
                        <div class="form-group row mb-20">
                            <div class="col-lg-6">
                                <label>BUMN </label>
                                @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                @endphp
                                <select class="form-select form-select-solid form-select2" id="perusahaan_id_status"
                                    name="perusahaan_id_status" data-kt-select2="true" data-placeholder="Pilih BUMN"
                                    {{ $disabled }}>
                                    <option></option>
                                    @foreach($perusahaan as $bumn)
                                    @php
                                    if ($filter_bumn_id == null) {
                                        $filter_bumn_id = $users->id_bumn;
                                    }
                                    $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Tahun</label>
    
                                <select class="form-select form-select-solid form-select2" id="tahunStatus"
                                    name="tahunStatus" data-kt-select2="true" data-placeholder="Pilih Tahun">
                                    @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                    @php
                                    $select = (($i == $tahun) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                    @php } @endphp
                                </select>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table  table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>RKA</th>
                                        <th>TW I</th>
                                        <th>TW II</th>
                                        <th>TW III</th>
                                        <th>Prognosa</th>
                                        <th>TW 4</th>
                                        <th>Audited</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr class="group-header">
                                        <td colspan="8"><h3>Rencana Kerja Anggaran</h3></td>
                                    </tr>
                                    @for ($index = 0; $index < 4; $index++)

                                        <tr id="row_{{$index}}">
                                            @if ($index == 0)
                                            <td >{{$menuStatus[$index]['menu']}}</td>
                                            <td ><span class="cls-log-{{$menuStatus[$index]['class']}}" data-no_tpb="{{$menuStatus[$index]['support_props']['no_tpb']}}" data-nama_pilar="{{$menuStatus[$index]['support_props']['nama_pilar']}}"  data-id="{{$menuStatus[$index]['id'] ?? null}}">{!! renderStatusBadge($menuStatus[$index]['rka']) !!}</span></td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw1']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw2']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw3']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['prognosa']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw4']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['audited']) !!}</td>
                                            @else
                                            <td >{{$menuStatus[$index]['menu']}}</td>
                                            <td ><span class="cls-log-{{$menuStatus[$index]['class']}}"  data-id="{{$menuStatus[$index]['id'] ?? null}}">{!! renderStatusBadge($menuStatus[$index]['rka']) !!}</span></td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw1']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw2']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw3']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['prognosa']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['tw4']) !!}</td>
                                            <td >{!! renderStatusBadge($menuStatus[$index]['audited']) !!}</td>
                                            @endif
                                            
                                        </tr>
                                    @endfor
                                    <tr class="group-header">
                                        <td colspan="8"><h3>Laporan Realisasi</h3></td>
                                    </tr>
                                    @for ($index = 4; $index < 8; $index++)
                                    <tr id="row_{{$index}}">
                                        {{-- {{dd($menuStatus[$index]['rka'])}} --}}
                                        
                                        {{-- {{dd($menuStatus)}} --}}
                                        <td>{{$menuStatus[$index]['menu']}}</td>
                                        <td>{!! isset($menuStatus[$index]['rka']) ? renderStatusBadge($menuStatus[$index]['rka']) : '' !!}</td>
                                        <td><span class="cls-log-{{$menuStatus[$index]['class']}}" data-id="{{$menuStatus[$index]['tw1']['id'] ?? null}}">
                                            {!! isset($menuStatus[$index]['tw1']['value']) ? renderStatusBadge($menuStatus[$index]['tw1']['value']) : '' !!}
                                        </span></td>
                                        <td><span class="cls-log-{{$menuStatus[$index]['class']}}" data-id="{{$menuStatus[$index]['tw2']['id'] ?? null}}">
                                            {!! isset($menuStatus[$index]['tw2']['value']) ? renderStatusBadge($menuStatus[$index]['tw2']['value']) : '' !!}
                                        </span></td>
                                        <td><span class="cls-log-{{$menuStatus[$index]['class']}}" data-id="{{$menuStatus[$index]['tw3']['id'] ?? null}}">
                                            {!! isset($menuStatus[$index]['tw3']['value']) ? renderStatusBadge($menuStatus[$index]['tw3']['value']) : '' !!}
                                        </span></td>
                                        <td><span class="cls-log-{{$menuStatus[$index]['class']}}" data-id="{{$menuStatus[$index]['prognosa']['id'] ?? null}}">
                                            {!! isset($menuStatus[$index]['prognosa']['value']) ? renderStatusBadge($menuStatus[$index]['prognosa']['value']) : '' !!}
                                        </span></td>
                                        <td><span class="cls-log-{{$menuStatus[$index]['class']}}" data-id="{{$menuStatus[$index]['tw4']['id'] ?? null}}">
                                            {!! isset($menuStatus[$index]['tw4']['value']) ? renderStatusBadge($menuStatus[$index]['tw4']['value']) : '' !!}
                                        </span></td>
                                        <td><span class="cls-log-{{$menuStatus[$index]['class']}}" data-id="{{$menuStatus[$index]['audited']['id'] ?? null}}">
                                            {!! isset($menuStatus[$index]['audited']['value']) ? renderStatusBadge($menuStatus[$index]['audited']['value']) : '' !!}
                                        </span></td>
                                    </tr>
                                @endfor

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h3 class="d-flex align-items-center">Grafik Realisasi
                        <span class="text-gray-600 fs-6 ms-1"></span></h3>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10">
                    <!--begin: Datatable -->
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                            $disabled = (($admin_bumn) ? 'disabled="true"' : '');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id"
                                name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN"
                                {{ $disabled }}>
                                <option></option>
                                <option value="all">Semua BUMN</option>
                                @foreach($perusahaan as $p)
                                @php
                                $select = (($p->id == $perusahaan_id) ? 'selected="selected"' : '');
                                @endphp
                                <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label> Jenis Anggaran</label>
                            <select class="form-select form-select-solid form-select2" id="owner_id" name="owner_id"
                                data-kt-select2="true" data-placeholder="Pilih">
                                {{-- @php
                                        $select = ($filter_owner_id ? 'selected="selected"' : '');
                                    @endphp
                                <option value="all" {!! $select !!}>Semua Owner</option> --}}
                                <option value="all">Semua Jenis Anggaran</option>
                                {{-- @foreach($owner as $p)  
                                    <option value="{{ $p->id }}" >{{ $p->nama }}</option>
                                @endforeach --}}

                                <option value="CID">
                                    CID</option>
                                <option value="non CID">
                                    non CID</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
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

                    <div class="form-group row mb-5">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar1" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan
                                <br>Sosial</span><br>
                            <span id="chart_detail1" style="font-size:12px;"></span>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar2" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar
                                Pembangunan<br> Ekonomi</span><br>
                            <span id="chart_detail2" style="font-size:12px;"></span>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar3" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar
                                Pembangunan<br> Lingkungan</span><br>
                            <span id="chart_detail3" style="font-size:12px;"></span>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar4" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan
                                <br>Hukum dan Tata Kelola<br></span>
                            <span id="chart_detail4" style="font-size:12px;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>

<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h3 class="d-flex align-items-center">Grafik TPB
                        <span class="text-gray-600 fs-6 ms-1"></span></h3>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10">
                    <!--begin: Datatable -->
                    <div class="form-group row  mb-5">
                        <div class="col-lg-4">
                            <label>TPB</label>
                            <select class="form-select form-select-solid form-select2" id="tpb_id" name="tpb_id"
                                data-kt-select2="true" data-placeholder="Pilih TPB">
                                <option></option>
                                <option value="all">Semua TPB</option>
                                @foreach($tpb as $p)
                                @php
                                $select = (($p->id == $tpb_id) ? 'selected="selected"' : '');
                                @endphp
                                <option value="{{ $p->id }}" {!! $select !!}>{{ $p->no_tpb }} - {{ $p->nama }} -
                                    {{$p->jenis_anggaran}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-5">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_tpb" class="chart2" data-percent="0"
                                style="margin-left: -50px; margin-bottom:60px;">
                                <span class="percent2"></span>
                            </span><br>
                            <span id="chart_title" class="caption-subject font-grey-gallery"
                                style="font-weight:bold;">Semua TPB</span><br>
                            <span id="chart_detail" style="font-size:12px;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>


<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="d-flex align-items-center">Data Kegiatan
                        <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1"
                        data-kt-view-roles-table-toolbar="base">

                        <button type="button"
                            class="btn btn-active btn-info btn-sm btn-icon btn-search-dataKegiatan cls-search-dataKegiatan btn-search-dataKegiatan-active"
                            style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i
                                class="bi bi-search fs-3"></i></button>
                        <button type="button"
                            class="btn btn-active btn-light btn-sm btn-icon btn-search-dataKegiatan cls-search-dataKegiatan btn-search-dataKegiatan-unactive"
                            style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i
                                class="bi bi-search fs-3"></i></button>

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
                    <div class="row" id="form-cari-dataKegiatan">
                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <label>BUMN</label>
                                @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                @endphp
                                <select class="form-select form-select-solid form-select2" id="perusahaan_id_datakegiatan"
                                    name="perusahaan_id_datakegiatan" data-kt-select2="true" data-placeholder="Pilih BUMN"
                                    {{ $disabled }}>
                                    <option></option>
                                    @foreach($perusahaan as $bumn)
                                    @php
                                    $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>Jenis Kegiatan</label>
                                {{-- @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                @endphp
                                <select class="form-select form-select-solid form-select2" id="perusahaan_id_dataKegiatan"
                                    name="perusahaan_id_dataKegiatan" data-kt-select2="true" data-placeholder="Pilih BUMN"
                                    {{ $disabled }}>
                                    <option></option>
                                    @foreach($perusahaan as $bumn)
                                    @php
                                    $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                    @endforeach
                                </select> --}}
                                <select class="form-select form-select-solid form-select2" id="jenisKegiatan_id"
                                    name="jenisKegiatan_id" data-kt-select2="true" data-placeholder="Pilih Jenis Kegiatan"
                                    {{-- {{ $disabled }} --}}
                                    >
                                    <option></option>
                                    @foreach($jenisKegiatan as $jenisKegiatanRow)
                                    @php
                                    $select = (($jenisKegiatanRow->id == $filter_jenisKegiatan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $jenisKegiatanRow->id }}" {!! $select !!}>{{ $jenisKegiatanRow->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-3">
                                <label>Tahun</label>
                                <select class="form-select form-select-solid form-select2" id="tahun_dataKegiatan"
                                    name="tahun_dataKegiatan" data-kt-select2="true" data-placeholder="Pilih Tahun"
                                    data-allow-clear="true">
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

                        {{-- <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <button id="proses" class="btn-small btn-success me-3 text-white"><i class="fa fa-search text-white"></i> Filter</button>
                            <button  onclick="window.location.href='{{route('dashboard.index')}}'" class="btn-small
                        btn-danger me-3 text-white"><i class="fa fa-times text-white"></i> Batal</button>
                    </div>
                </div> --}}
                <div class="separator border-gray-200 mb-10"></div>
            </div>
            <!--begin: Datatable -->
            <div>
                <div class="portlet-body" id="dataKegiatan_chart">
                </div>
            </div>
        </div>
    </div>
    <!--end::Card body-->
</div>
</div>
</div>

<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="d-flex align-items-center">Pendanaan PUMK
                        <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1"
                        data-kt-view-roles-table-toolbar="base">

                        <button type="button"
                            class="btn btn-active btn-info btn-sm btn-icon btn-search-pumk cls-search-pumk btn-search-pumk-active"
                            style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i
                                class="bi bi-search fs-3"></i></button>
                        <button type="button"
                            class="btn btn-active btn-light btn-sm btn-icon btn-search-pumk cls-search-pumk btn-search-pumk-unactive"
                            style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i
                                class="bi bi-search fs-3"></i></button>

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
                    <div class="row" id="form-cari-pumk">
                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <label>BUMN</label>
                                @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                                @endphp
                                <select class="form-select form-select-solid form-select2" id="perusahaan_id_danapumk"
                                    name="perusahaan_id_danapumk" data-kt-select2="true" data-placeholder="Pilih BUMN"
                                    {{ $disabled }}>
                                    <option></option>
                                    @foreach($perusahaan as $bumn)
                                    @php
                                    $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label>Tahun</label>
                                <select class="form-select form-select-solid form-select2" id="tahun_danapumk"
                                    name="tahun_danapumk" data-kt-select2="true" data-placeholder="Pilih Tahun"
                                    data-allow-clear="true">
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

                        {{-- <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <button id="proses" class="btn-small btn-success me-3 text-white"><i class="fa fa-search text-white"></i> Filter</button>
                            <button  onclick="window.location.href='{{route('dashboard.index')}}'" class="btn-small
                        btn-danger me-3 text-white"><i class="fa fa-times text-white"></i> Batal</button>
                    </div>
                </div> --}}
                <div class="separator border-gray-200 mb-10"></div>
            </div>
            <!--begin: Datatable -->
            <div>
                <div class="portlet-body" id="pumk_chart">
                </div>
            </div>
        </div>
    </div>
    <!--end::Card body-->
</div>
</div>
</div>


<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="d-flex align-items-center">Realisasi PUMK
                        <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1"
                        data-kt-view-roles-table-toolbar="base">

                        <button type="button"
                            class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search btn-search-active"
                            style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i
                                class="bi bi-search fs-3"></i></button>
                        <button type="button"
                            class="btn btn-active btn-light btn-sm btn-icon btn-search cls-search btn-search-unactive"
                            style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i
                                class="bi bi-search fs-3"></i></button>

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
                                <select class="form-select form-select-solid form-select2" id="perusahaan_id_pumk"
                                    name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN"
                                    {{ $disabled }}>
                                    <option></option>
                                    @foreach($perusahaan as $bumn)
                                    @php
                                    $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Semester</label>
                                <select id="bulan_id_pumk" class="form-select form-select-solid form-select2"
                                    name="bulan_id_pumk" data-kt-select2="true" data-placeholder="Pilih Bulan"
                                    data-allow-clear="true">
                                    {{-- <option></option>
                                    @foreach($bulan as $p)
                                    @php
                                    $select = (($p->id == $filter_status_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                    @endforeach --}}
                                    <option></option>
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label>Tahun</label>
                                <select class="form-select form-select-solid form-select2" id="tahun_pumk"
                                    name="tahun_pumk" data-kt-select2="true" data-placeholder="Pilih Tahun"
                                    data-allow-clear="true">
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

                        {{-- <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <button id="proses" class="btn-small btn-success me-3 text-white"><i class="fa fa-search text-white"></i> Filter</button>
                            <button  onclick="window.location.href='{{route('dashboard.index')}}'" class="btn-small
                        btn-danger me-3 text-white"><i class="fa fa-times text-white"></i> Batal</button>
                    </div>
                </div> --}}
                <div class="separator border-gray-200 mb-10"></div>
            </div>
            <!--begin: Datatable -->
            <div>
                <div class="portlet-body" id="mb_chart">
                </div>
            </div>
        </div>
    </div>
    <!--end::Card body-->
</div>
</div>
</div>
@endsection


@section('addafterjs')
<script src="{{ asset('plugins/Highcharts-9.2.2/code/highcharts.js') }}"></script>
<script src="{{ asset('plugins/Highcharts-9.2.2/code/highcharts-3d.js') }}"></script>
<script src="{{ asset('plugins/Highcharts-9.2.2/code/modules/exporting.js') }}"></script>
<script src="{{ asset('plugins/Highcharts-9.2.2/code/modules/accessibility.js') }}"></script>
<script src="{{ asset('plugins/Highcharts-9.2.2/code/modules/export-data.js') }}"></script>

<script>
    var urlchartrealisasi = "{{route('home.chartrealisasi')}}";
    var urlcharttpb = "{{route('home.charttpb')}}";
    var urlchartmb = "{{route('home.chartmb')}}";
    var urlchartpumk = "{{route('home.chartpumk')}}";
    var urlallstatus = "{{route('home.allstatus')}}";
    var urlchartdatakegiatan = "{{route('home.chartdataKegiatan')}}"

    var urllog_rka = "{{route('anggaran_tpb.log_status2')}}";
    var urllog_program = "{{ route('rencana_kerja.program.log') }}";
    var urllog_spdpumk_rka = "{{route('rencana_kerja.spdpumk_rka.log')}}";
    var urllog_laporan_rka = "{{route('rencana_kerja.laporan_manajemen.log')}}";

    //laporan realisasi
    var urllog_kegiatan = "{{route('laporan_realisasi.bulanan.kegiatan.log')}}";
    var urllog_pumk = "{{ route('laporan_realisasi.bulanan.pumk.log') }}";
    var urllog_spdpumk_bulan = "{{route('laporan_realisasi.triwulan.spd_pumk.log')}}";
    var urllog_laporan_bulan = "{{route('laporan_realisasi.triwulan.laporan_manajemen.log')}}";
    $(document).ready(function () {
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");
        //logs
        //rka
        // $('body').on('click','.cls-log-rka',function(){
        //     winform(urllog_rka, {'tahun': $("#tahunStatus").val(), 'no_tpb':$(this).data('no_tpb'), 'nama_pilar':$(this).data('nama_pilar'), 'perusahaan': $("#perusahaan_id_status").val()}, 'Log Status');
        // });
        // //program
        //     $('body').on('click', '.cls-log-program',function(){
        //     console.log(`program ${$(this).data('id')}`)
        //     // console.log($(this).data('id'))
        //     // let id = $(this).data('id')
        //     // console.log(id)
        //     const id = $(this).data('id');
        //     console.log('Clicked element ID:', id);
        //         winform(urllog_program, {'id':$(this).data('id')}, 'Log Data');
        //     });
        // //spdpumk_rka
        //     $('body').on('click','.cls-log-spdpumk_rka',function(){
        //         console.log(`spdpumk_rka ${$(this).data('id')}`)
        //         winform(urllog_spdpumk_rka, {'id':$(this).data('id')}, 'Log Data');
        //     });
        // //laporan manajemen rka
        // $('body').on('click','.cls-log-laporan_manajemen_rka',function(){
            
        //         winform(urllog_laporan_rka, {'id':$(this).data('id')}, 'Log Data');
        //     });

        // //laporan realisasi
        // //kegiatan
        // $('body').on('click','.cls-log-kegiatan',function(){
        //     console.log($(this).data('id'))
        //         winform(urllog_kegiatan, {'id':$(this).data('id')}, 'Log Data');
        // });
        // $('body').on('click','.cls-log-pumk',function(){
        //     console.log($(this).data('id'))
        //         winform(urllog_pumk, {'id':$(this).data('id')}, 'Log Data');
        // });
        // $('body').on('click','.cls-log-spdpumk_bulan',function(){
        //     console.log($(this).data('id'))
        //         winform(urllog_spdpumk_bulan, {'id':$(this).data('id')}, 'Log Data');
        // });
        // $('body').on('click','.cls-log-laporan_manajemen_bulan',function(){
        //     console.log($(this).data('id'))
        //         winform(urllog_laporan_bulan, {'id':$(this).data('id')}, 'Log Data');
        // });
        $(".accordion-header").click(function () {
                $(this).find(".accordion-icon-off").toggleClass("d-none");
                $(this).find(".accordion-icon-on").toggleClass("d-none");
            });
        $('#perusahaan_id').on('change', function (event) {
            updatechartrealisasi();
            updatecharttpb();
        });
        $('#tahun').on('change', function (event) {
            updatechartrealisasi();
            updatecharttpb();
        });
        $('#tpb_id').on('change', function (event) {
            updatecharttpb();
        });
        $('#owner_id').on('change', function (event) {
            updatechartrealisasi();
            updatecharttpb();
        });

        initchartrealisasi();
        initcharttpb();

        //pumk mitra binaan
        $('#perusahaan_id_pumk').on('change', function (event) {
            updatechartmb();
        });
        $('#tahun_pumk').on('change', function (event) {
            updatechartmb();
        });
        $('#bulan_id_pumk').on('change', function (event) {
            updatechartmb();
        });

        updatechartmb();


        $('#form-cari').hide();
        $('body').on('click', '.btn-search-active', function () {
            $('.btn-search-active').hide();
            $('.btn-search-unactive').show();
            $('#form-cari').toggle(600);
        });

        $('body').on('click', '.btn-search-unactive', function () {
            $('.btn-search-active').show();
            $('.btn-search-unactive').hide();
            $('#form-cari').toggle(600);
        });

        //data kegiatan
        
        $('#perusahaan_id_datakegiatan').on('change', function (event) {
            updatechartdataKegiatan();
        });
        $('#jenisKegiatan_id').on('change', function (event) {
            updatechartdataKegiatan();
        });
        $('#tahun_dataKegiatan').on('change', function (event) {
            updatechartdataKegiatan();
        });

        updatechartdataKegiatan();

        $('#form-cari-dataKegiatan').hide();
        $('body').on('click', '.btn-search-dataKegiatan-active', function () {
            $('.btn-search-dataKegiatan-active').hide();
            $('.btn-search-dataKegiatan-unactive').show();
            $('#form-cari-dataKegiatan').toggle(600);
        });

        $('body').on('click', '.btn-search-dataKegiatan-unactive', function () {
            $('.btn-search-dataKegiatan-active').show();
            $('.btn-search-dataKegiatan-unactive').hide();
            $('#form-cari-dataKegiatan').toggle(600);
        });


        //pendanaan pumk
        $('#perusahaan_id_danapumk').on('change', function (event) {
            updatechartpumk();
        });
        $('#tahun_danapumk').on('change', function (event) {
            updatechartpumk();
        });

        updatechartpumk();

        $('#form-cari-pumk').hide();
        $('body').on('click', '.btn-search-pumk-active', function () {
            $('.btn-search-pumk-active').hide();
            $('.btn-search-pumk-unactive').show();
            $('#form-cari-pumk').toggle(600);
        });

        $('body').on('click', '.btn-search-pumk-unactive', function () {
            $('.btn-search-pumk-active').show();
            $('.btn-search-pumk-unactive').hide();
            $('#form-cari-pumk').toggle(600);
        });

        $('#tahunStatus').on('change', function (event) {
            updateTableStatus();
        });

        $('#perusahaan_id_status').on('change', function (event) {
            updateTableStatus();
        });

        function updateTableStatus() {
        $.ajax({
            url: urlallstatus,
            data: {
                'tahunStatus': $("#tahunStatus").val(),
                'perusahaan_id': $("#perusahaan_id_status").val()
            },
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                // Show the SweetAlert animation here
                Swal.fire({
                    title: 'Loading...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            // Hide the SweetAlert when the AJAX request is completed
            complete: function () {
                Swal.close();
            },
            success: function (data) {
                // initmitra(data);
                          
                 // Loop for index 0 to 3
                for (let index = 0; index < 4; index++) {
                    console.log(data[index])
                    const rkaData = data[index].rka ?? null;
                    const classAttribute =`cls-log-${data[index].class}`;
                    const dataIdAttribute = data[index].id ?? null;
                    console.log(`${rkaData} | ${classAttribute} | ${dataIdAttribute}`)
                    if (index == 0) {
                        $("#row_" + index + " td:nth-child(2)").html(`<span class="${classAttribute}" data-no_tpb="${data[index].support_props.no_tpb}" data-nama_pilar="${data[index].support_props.nama_pilar}" data-id="${dataIdAttribute}">` + renderStatusBadge(rkaData) + '</span>');
                    
                    $("#row_" + index + " td:nth-child(3)").html(renderStatusBadge(data[index].tw1));
                    $("#row_" + index + " td:nth-child(4)").html(renderStatusBadge(data[index].tw2));
                    $("#row_" + index + " td:nth-child(5)").html(renderStatusBadge(data[index].tw3));
                    $("#row_" + index + " td:nth-child(6)").html(renderStatusBadge(data[index].prognosa));
                    $("#row_" + index + " td:nth-child(7)").html(renderStatusBadge(data[index].tw4));
                    $("#row_" + index + " td:nth-child(8)").html(renderStatusBadge(data[index].audited));
                    }
                    else{
                        $("#row_" + index + " td:nth-child(2)").html(`<span class="${classAttribute}" data-id="${dataIdAttribute}">` + renderStatusBadge(rkaData) + '</span>');
                    
                    $("#row_" + index + " td:nth-child(3)").html(renderStatusBadge(data[index].tw1));
                    $("#row_" + index + " td:nth-child(4)").html(renderStatusBadge(data[index].tw2));
                    $("#row_" + index + " td:nth-child(5)").html(renderStatusBadge(data[index].tw3));
                    $("#row_" + index + " td:nth-child(6)").html(renderStatusBadge(data[index].prognosa));
                    $("#row_" + index + " td:nth-child(7)").html(renderStatusBadge(data[index].tw4));
                    $("#row_" + index + " td:nth-child(8)").html(renderStatusBadge(data[index].audited));
                    }
              
                }

                // Loop for index 4 to 7
                for (let index = 4; index < 8; index++) {
                    // const dataIndex = index - 4; // Adjusting the data index to access correct data element
                    
                    // const  = data[index].rka ?? null;
                    // const classAttribute =`cls-log-${data[index].class}`;
                    // const dataIdAttribute = data[index].id ?? null;
                    
                    $("#row_" + index + " td:nth-child(2)").html(renderStatusBadge(data[index].rka));
                    $("#row_" + index + " td:nth-child(3)").html(`<span class="cls-log-${data[index].class}" data-id="${data[index].tw1.id}">` + renderStatusBadge(data[index].tw1.value) + '</span>');
                    $("#row_" + index + " td:nth-child(4)").html(`<span class="cls-log-${data[index].class}" data-id="${data[index].tw2.id}">` + renderStatusBadge(data[index].tw2.value) + '</span>');
                    $("#row_" + index + " td:nth-child(5)").html(`<span class="cls-log-${data[index].class}" data-id="${data[index].tw3.id}">` + renderStatusBadge(data[index].tw3.value) + '</span>');
                    $("#row_" + index + " td:nth-child(6)").html(`<span class="cls-log-${data[index].class}" data-id="${data[index].prognosa.id}">` + renderStatusBadge(data[index].prognosa.value) + '</span>');
                    $("#row_" + index + " td:nth-child(7)").html(`<span class="cls-log-${data[index].class}" data-id="${data[index].tw4.id}">` + renderStatusBadge(data[index].tw4.value) + '</span>');
                    $("#row_" + index + " td:nth-child(8)").html(`<span class="cls-log-${data[index].class}" data-id="${data[index].audited.id}">` + renderStatusBadge(data[index].audited.value) + '</span>');
                }
            }
        });
    }




    });

    function renderStatusBadge(status) {
        const classMapping = {
            'Verified': 'badge-light-success',
            'Completed': 'badge-light-success',
            'In Progress': 'badge-light-primary',
            'Unfilled': 'badge-light-warning',
        };

        if (status in classMapping) {
            const className = classMapping[status];
            return `<span class="btn badge ${className} fw-bolder me-auto px-4 py-3" style='cursor: default;'>${status}</span>`;
        }

        return '';
    }

   

    function updatechartmb() {
       //bulan_id_pumk = semester
        $.ajax({
            url: urlchartmb,
            data: {
                'perusahaan_id_pumk': $("#perusahaan_id_pumk").val(),
                'tahun_pumk': $("#tahun_pumk").val(),
                'bulan_pumk': $("#bulan_id_pumk").val()
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                initmitra(data);
            }
        });
    }


    function initmitra(data) {
        let s_lancar = data.saldo_lancar ? parseInt(data.saldo_lancar) : 0;
        let s_kurang_lancar = data.saldo_kurang_lancar ? parseInt(data.saldo_kurang_lancar) : 0;
        let s_diragukan = data.saldo_diragukan ? parseInt(data.saldo_diragukan) : 0;
        let s_macet = data.saldo_macet ? parseInt(data.saldo_macet) : 0;
        let s_bermasalah = data.saldo_bermasalah ? parseInt(data.saldo_bermasalah) : 0;

        Highcharts.setOptions({
            colors: ['#E67E22', '#6495ED']
        });
        Highcharts.chart('mb_chart', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Kualitas Piutang' + data.bumn + ' ' + data.bulan + ' ' + data.tahun
            },
            subtitle: {
                text: ''
            },
            xAxis: [{
                categories: ["Lancar", "Kurang Lancar", "Diragukan", "Macet", "Bermasalah"],
                crosshair: true
            }],
            yAxis: [{
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Mitra Binaan',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                }
            }, {
                title: {
                    text: 'Saldo Pinjaman (Rp)',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                labels: {
                    formatter: function () {
                        return this.value.toLocaleString("fi-FI");
                    },
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                x: 0,
                verticalAlign: 'top',
                y: 15,
                floating: true,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor ||
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Saldo Pinjaman (Rp)',
                type: 'spline',
                yAxis: 1,
                zIndex: 1,
                data: [
                    s_lancar,
                    s_kurang_lancar,
                    s_diragukan,
                    s_macet,
                    s_bermasalah
                ],
                tooltip: {
                    valueSuffix: '{value}'

                },
                style: {
                    color: Highcharts.getOptions().colors[0]
                },

            }, {
                name: 'Mitra Binaan',
                type: 'column',
                zIndex: 0,
                data: [
                    data.mitra_lancar,
                    data.mitra_kurang_lancar,
                    data.mitra_diragukan,
                    data.mitra_macet,
                    data.mitra_bermasalah
                ],
                tooltip: {
                    valueSuffix: ''
                },
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }]
        });
    }

    function updatechartpumk() {
        $.ajax({
            url: urlchartpumk,
            data: {
                'perusahaan_id_danapumk': $("#perusahaan_id_danapumk").val(),
                'tahun_danapumk': $("#tahun_danapumk").val()
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                initpumk(data);
            }
        });
    }

    function initpumk(data) {
        let bln = data.bulan;
        let mitra = data.mitra;
        let nominal = data.nominal;
        let tahun = data.tahun;

        Highcharts.setOptions({
            colors: ['#24E500', '#0093AD']
        });
        Highcharts.chart('pumk_chart', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Statistik Penyaluran Dana PUMK ' + tahun
            },
            subtitle: {
                text: ''
            },
            xAxis: [{
                categories: bln,
                crosshair: true
            }],
            yAxis: [{
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Mitra Binaan',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                }
            }, {
                title: {
                    text: 'Nominal Pendanaan (Rp) dalam Miliar',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                labels: {
                    formatter: function () {
                        return this.value.toLocaleString("fi-FI");
                    },
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                x: 0,
                verticalAlign: 'top',
                y: 15,
                floating: true,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor ||
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Nominal Pendanaan (Rp)',
                type: 'spline',
                yAxis: 1,
                zIndex: 1,
                data: nominal,
                tooltip: {
                    valueSuffix: '{value} Miliar'
                },
                style: {
                    color: Highcharts.getOptions().colors[0]
                },

            }, {
                name: 'Mitra Binaan',
                type: 'column',
                zIndex: 0,
                data: mitra,
                tooltip: {
                    valueSuffix: '{value} Mitra'
                },
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }]
        });
    }

    function updatechartrealisasi() {
        $.ajax({
            url: urlchartrealisasi,
            data: {
                'tpb_id': $("#tpb_id").val(),
                'perusahaan_id': $("#perusahaan_id").val(),
                'tahun': $("#tahun").val(),
                'owner_id': $("#owner_id").val()
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                var detail1 = "<i>Target :</i> Rp. " + data.target1 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi1 + "<br><i>Sisa :</i> Rp. " + data.sisa1;
                var detail2 = "<i>Target :</i> Rp. " + data.target2 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi2 + "<br><i>Sisa :</i> Rp. " + data.sisa2;
                var detail3 = "<i>Target :</i> Rp. " + data.target3 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi3 + "<br><i>Sisa :</i> Rp. " + data.sisa3;
                var detail4 = "<i>Target :</i> Rp. " + data.target4 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi4 + "<br><i>Sisa :</i> Rp. " + data.sisa4;
                $('#chart_detail1').html(detail1);
                $('#chart_detail2').html(detail2);
                $('#chart_detail3').html(detail3);
                $('#chart_detail4').html(detail4);

                $('#chart_pilar1').attr('data-percent', data.pilar1);
                $('#chart_pilar2').attr('data-percent', data.pilar2);
                $('#chart_pilar3').attr('data-percent', data.pilar3);
                $('#chart_pilar4').attr('data-percent', data.pilar4);
                $('#chart_pilar1').data('easyPieChart').update(
                    Math.round(data.pilar1)
                )
                $('#chart_pilar2').data('easyPieChart').update(
                    Math.round(data.pilar2)
                )
                $('#chart_pilar3').data('easyPieChart').update(
                    Math.round(data.pilar3)
                )
                $('#chart_pilar4').data('easyPieChart').update(
                    Math.round(data.pilar4)
                )
            }
        });
    }

    function initchartrealisasi() {
        $.ajax({
            url: urlchartrealisasi,
            data: {
                'perusahaan_id': $("#perusahaan_id").val(),
                'tahun': $("#tahun").val(),
                'owner_id': $("#owner_id").val()
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                var detail1 = "<i>Target :</i> Rp. " + data.target1 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi1 + "<br><i>Sisa :</i> Rp. " + data.sisa1;
                var detail2 = "<i>Target :</i> Rp. " + data.target2 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi2 + "<br><i>Sisa :</i> Rp. " + data.sisa2;
                var detail3 = "<i>Target :</i> Rp. " + data.target3 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi3 + "<br><i>Sisa :</i> Rp. " + data.sisa3;
                var detail4 = "<i>Target :</i> Rp. " + data.target4 + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi4 + "<br><i>Sisa :</i> Rp. " + data.sisa4;
                $('#chart_detail1').html(detail1);
                $('#chart_detail2').html(detail2);
                $('#chart_detail3').html(detail3);
                $('#chart_detail4').html(detail4);

                $('#chart_pilar1').attr('data-percent', data.pilar1);
                $('#chart_pilar2').attr('data-percent', data.pilar2);
                $('#chart_pilar3').attr('data-percent', data.pilar3);
                $('#chart_pilar4').attr('data-percent', data.pilar4);

                $('#chart_pilar1').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#f44265',
                    trackColor: '#ffd8e6',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function (from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });

                $('#chart_pilar2').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#32a852',
                    trackColor: '#ccf0d5',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function (from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });

                $('#chart_pilar3').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#2c79c7',
                    trackColor: '#d1e8ff',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function (from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });

                $('#chart_pilar4').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#e38a2b',
                    trackColor: '#ffdfbd',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function (from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });
            }
        });
    }

    function initcharttpb() {
        $.ajax({
            url: urlcharttpb,
            data: {
                'tpb_id': $("#tpb_id").val(),
                'perusahaan_id': $("#perusahaan_id").val(),
                'tahun': $("#tahun").val(),
                'owner_id': $("#owner_id").val()
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                var detail = "<i>Target :</i> Rp. " + data.target + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi + "<br><i>Sisa :</i> Rp. " + data.sisa;
                $('#chart_detail').html(detail);
                $('#chart_tpb').attr('data-percent', data.tpb);

                $('#chart_tpb').easyPieChart({
                    size: 200,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#b42ded',
                    trackColor: '#f2d9fc',
                    scaleColor: false,
                    lineWidth: 60,
                    trackWidth: 50,
                    lineCap: 'butt',
                    onStep: function (from, to, percent) {
                        $(this.el).find('.percent2').text(Math.round(percent));
                    }
                });
            }
        });
    }

    function updatecharttpb() {
        $.ajax({
            url: urlcharttpb,
            data: {
                'tpb_id': $("#tpb_id").val(),
                'perusahaan_id': $("#perusahaan_id").val(),
                'tahun': $("#tahun").val(),
                'owner_id': $("#owner_id").val()
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                var detail = "<i>Target :</i> Rp. " + data.target + "<br><i>Realisasi :</i> Rp. " + data
                    .realisasi + "<br><i>Sisa :</i> Rp. " + data.sisa;
                $('#chart_detail').html(detail);
                $('#chart_tpb').data('easyPieChart').update(
                    Math.round(data.tpb)
                )
                var tpb = $("#tpb_id option:selected").text();
                $('#chart_title').html(tpb);
            }
        });
    }

    //data Kegiatan
    function updatechartdataKegiatan() {
        $.ajax({
            url: urlchartdatakegiatan,
            data: {
                'perusahaan_id' : $('#perusahaan_id_datakegiatan').val(),
                'jenis_kegiatan_id': $("#jenisKegiatan_id").val(),
                'tahun_dataKegiatan': $("#tahun_dataKegiatan").val()
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                initDataKegiatan(data);
            }
        });
    }

    function initDataKegiatan(data) {
        let bln = data.bulan;
        let mitra = data.indikator;
        let nominal = data.anggaran;
        let tahun = data.tahun;
        let satuan_ukur = data.satuan_ukur
        console.log(data)

        Highcharts.setOptions({
            colors: ['#24E500', '#0093AD']
        });
        Highcharts.chart('dataKegiatan_chart', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Statistik Data Kegiatan '+ $("#jenisKegiatan_id option:selected").text()+' '  + tahun
            },
            subtitle: {
                text: ''
            },
            xAxis: [{
                categories: bln,
                crosshair: true
            }],
            yAxis: [{
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: satuan_ukur,
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                }
            }, {
                title: {
                    text: 'Anggaran',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                labels: {
                    formatter: function () {
                        return this.value.toLocaleString("fi-FI");
                    },
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                x: 0,
                verticalAlign: 'top',
                y: 15,
                floating: true,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor ||
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Anggaran',
                type: 'spline',
                yAxis: 1,
                zIndex: 1,
                data: nominal,
                tooltip: {
                    valueSuffix: '{value} Miliar'
                },
                style: {
                    color: Highcharts.getOptions().colors[0]
                },

            }, {
                name: satuan_ukur,
                type: 'column',
                zIndex: 0,
                data: mitra,
                tooltip: {
                    valueSuffix: '{value} '+ satuan_ukur
                },
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }]
        });
    }

</script>
<script src="{{ asset('plugins/easy-pie-chart/jquery.easypiechart.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('plugins/easy-pie-chart/jquery.easing.min.js') }}" type="text/javascript"></script>
@endsection
