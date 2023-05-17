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
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-add" style="margin-right:3px;" data-toggle="tooltip" title="Tambah Data"><i class="bi bi-plus fs-3"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download Excel"><i class="bi bi-file-excel fs-3"></i></button>
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
                                <label>Jenis Anggaran</label>
                                <select  id="jenis-anggaran" class="form-select form-select-solid form-select2" name="jenis_anggaran" data-kt-select2="true" data-placeholder="Pilih Jenis Anggaran" data-allow-clear="true">
                                    <option></option>
                                    <option value="CID" {{ request('jenis_anggaran') === 'CID' ? 'selected="selected"' : '' }} >
                                            CID</option>
                                    <option value="non CID" {{ request('jenis_anggaran') === 'non CID' ? 'selected="selected"' : '' }} >
                                        non CID</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>TPB</label>
                                <select id="tpb_id" class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true"  data-placeholder="Pilih TPB" data-allow-clear="true">
                                    <option></option>
                                    @foreach($tpb as $p)  
                                        @php
                                            $select = (($p->no_tpb == $tpb_id) ? 'selected="selected"' : '');
                                        @endphp
                                        <option data-jenis-anggaran="{{ $p->jenis_anggaran }}" value="{{ $p->no_tpb }}" {!! $select !!}>{{ $p->no_tpb }} - {{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <label>Pilar Pembangunan</label>
                                <select id="pilar_pembangunan_id" class="form-select form-select-solid form-select2" name="pilar_pembangunan_id" data-kt-select2="true" data-placeholder="Pilih Pilar" data-allow-clear="true">
                                    <option></option>
                                    @foreach($pilar as $p)  
                                        @php
                                            $select = (($p->nama == $pilar_pembangunan_id) ? 'selected="selected"' : '');
                                        @endphp
                                        <option data-jenis-anggaran="{{ $p->jenis_anggaran }}" value="{{ $p->nama }}" {!! $select !!}>{{ $p->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Status</label>
                                <select  id="status-anggaran" class="form-select form-select-solid form-select2" name="status_anggaran" data-kt-select2="true" data-placeholder="Pilih Status Anggaran" data-allow-clear="true">
                                    <option></option>
                                    <option value="Finish" {{ request('status') === 'Finish' ? 'selected="selected"' : '' }}  >
                                            Finish</option>
                                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected="selected"' : '' }} >
                                        In Progress</option>
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

                    <!--begin::Table Button-->
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
                            <div class="d-flex align-items-center position-relative my-1"
                                data-kt-view-roles-table-toolbar="base">
                                {{-- <button type="button" class="btn btn-danger btn-sm cls-add "
                                    data-kt-view-roles-table-select="delete_selected">Hapus Data</button> --}}
                                @unlessrole('Verifikator BUMN')
                                    <button type="button" class="btn btn-success me-2 btn-sm rekap-data">Rekap Data
                                    </button>
                                    <button type="button" class="btn btn-danger me-2 btn-sm delete-selected-data">Hapus Data
                                    </button>
                                <button type="button" class="btn btn-success btn-sm input-data me-2" onclick="redirectToNewPage()">Input Data
                                </button>
                                @endunlessrole
                                @hasanyrole('Super Admin|Verifikator BUMN')
                                <button type="button" class="btn btn-primary btn-sm" id="verify-data" >Verify
                                </button>
                                @endhasanyrole
                                {{-- <a href="{{ route('anggaran_tpb.create2', ['param1' => 'parameter1', 'param2' => 'parameter2']) }}" class="btn btn-success btn-sm input-data">Input Data</a> --}}
                            </div>
                            <!--end::Search-->
                            <!--end::Group actions-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>                    
                    <!--begin: Datatable -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tree  table-checkable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">No.</th>
                                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Pilar - TPB</th>
                                    <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;"> CID</th>
                                    <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">Non CID</th>
                                    <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">Total</th>
                                    <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Status</th>
                                    <th style="text-align:center;width:100px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Aksi</th>
                                    <th><label
                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                            class="form-check-input addCheck" type="checkbox"
                                            id="select-all"></label>
                                </th>
                                </tr>
                            </thead>
                            <tbody>       
                            @php 
                                $total=0;
                                $total_cid = 0;
                                $total_noncid = 0;
                                $bumn = $anggaran_bumn;
                            @endphp       
                            @foreach ($bumn as $b)     
                                @php 
                                    $no=0;
                                    $sum_bumn = $anggaran_bumn->where('perusahaan_id', $b->id)->first(); 
                                    $anggaran_pilar_bumn = $anggaran_pilar->where('perusahaan_id', $b->id);

                                    $statusInProgress = $anggaran->where('perusahaan_id', $b->id)->where('status_id', 2)->first();
                                    if($statusInProgress) $statusPerusahaan = $statusInProgress;
                                    else $statusPerusahaan = $anggaran->where('perusahaan_id', $b->id)->first();
                                    
                                    $status_class = 'primary';
                                    if($statusPerusahaan->status_id == 1){
                                        $status_class = 'success';
                                    } else if($statusPerusahaan->status_id == 3){
                                        $status_class = 'warning';
                                    }

                                    $total_cid += $sum_bumn->sum_anggaran_cid;
                                    $total_noncid += $sum_bumn->sum_anggaran_noncid;                                    
                                @endphp
                                @if(!$perusahaan_id)
                                <tr class="treegrid-bumn{{@$b->id}}" >
                                    <td style="text-align:center;"></td>
                                    <td>{{$b->nama_lengkap}}</td>
                                    <td style="text-align:right;">
                                        @if($sum_bumn)
                                        {{number_format($sum_bumn->sum_anggaran_cid,0,',',',')}}
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        @if($sum_bumn)
                                        {{number_format($sum_bumn->sum_anggaran_noncid,0,',',',')}}
                                        @endif
                                    </td>
                                    <td style="text-align:right;">
                                        @if($sum_bumn)
                                        {{number_format($sum_bumn->sum_anggaran_noncid +$sum_bumn->sum_anggaran_cid ,0,',',',')}}
                                        @endif
                                    </td>
                                    <td style="text-align:center;">
                                        <a class="badge badge-light-{{$status_class}} fw-bolder me-auto px-4 py-3" data-toggle="tooltip" title="Lihat Log">{{@$statusPerusahaan->status->nama}}</a>
                                    </td>
                                    <td></td>
                                    <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                        <input class="form-check-input is_active-check perusahaan-check" data-perusahaan-parent="perusahaan-{{$b->id}}" type="checkbox" data-no_tpb="${row.no_tpb}" data-nama="${row.nama}" data-jenis_anggaran="${row.jenis_anggaran}"  ${isChecked} name="selected-is_active[]" value="${row.id}">
                                        </label></td>
                                  
                                </tr>  
                                @endif   
                                @foreach ($anggaran_pilar_bumn as $p)                              
                                    @php 
                                        $no++;
                                        $anggaran_anak = $anggaran->where('perusahaan_id', $b->id)->where('pilar_nama', $p->pilar_nama);                                        

                                        
                                        $statusInProgress = $anggaran->where('perusahaan_id', $b->id)->where('pilar_nama', $p->pilar_nama)->where('status_id', 2)->first();
                                        if($statusInProgress) $statusPilar = $statusInProgress;
                                        else $statusPilar = $anggaran->where('perusahaan_id', $b->id)->where('pilar_nama', $p->pilar_nama)->first();
                                            
                                        $status_class = 'primary';
                                        
                                        if($statusPilar->status_id == 1){
                                            $status_class = 'success';
                                        } else if($statusPilar->status_id == 3){
                                            $status_class = 'warning';
                                        }
                                        
                                        $class_parent = '';
                                        if(!$perusahaan_id){
                                            $class_parent = 'treegrid-parent-bumn' . $p->perusahaan_id;
                                        }
                    
                                        $total += $p->sum_anggaran;
                                        $currentPrintable = true;
                                        $nextPrintable = true;
                                    @endphp
                                    
                                    @if(number_format($p->sum_anggaran_cid) > 0 || number_format($p->sum_anggaran_noncid) > 0)
                                    <tr class="treegrid-bumn{{@$b->id}}pilar{{str_replace(' ', '-', @$p->pilar_nama)}} {{$class_parent}} item-bumn{{@$b->id}}pilar{{str_replace(' ', '-', @$p->pilar_nama)}}" >
                                        <td style="text-align:center;">{{$no}}</td>
                                        <td>{{$p->pilar_nama}}</td>
                                        <td style="text-align:right;">{{number_format($p->sum_anggaran_cid,0,',',',')}}</td>
                                        <td style="text-align:right;">{{number_format($p->sum_anggaran_noncid,0,',',',')}}</td>
                                        <td style="text-align:right;">{{number_format($p->sum_anggaran_noncid + $p->sum_anggaran_cid,0,',',',')}}</td>
                                        <td style="text-align:center;">
                                            <a class="badge badge-light-{{$status_class}} fw-bolder me-auto px-4 py-3" data-toggle="tooltip" title="Lihat Log">{{@$statusPilar->status->nama}} {{ $statusInProgress }}</a>
                                        </td>
                                        <td style="text-align:center;">                                            
                                        </td>
                                       
                                        <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                            <input class="form-check-input is_active-check pilar-check perusahaan-{{$b->id}}" data-pilar-parent="pilar-{{$b->id}}-{{str_replace(' ', '-', @$p->pilar_nama)}}" type="checkbox" data-no_tpb="${row.no_tpb}" data-nama="${row.nama}" data-jenis_anggaran="${row.jenis_anggaran}"  ${isChecked} name="selected-is_active[]" value="${row.id}">
                                            </label></td>
                                    </tr>
                                    @endif
                                                                                                      
                                    
                                    @php
                                        $anggaran_anak = $anggaran_anak->values();
                                    @endphp
                                    
                                    @foreach ($anggaran_anak as $key => $a)                                     
                                    @php     
                                        

                                        $currentPrintable = $nextPrintable;
                                        if(!$nextPrintable) $nextPrintable = true;

                                        $id_anggaran_cid = $a->jenis_anggaran === 'CID' ? $a->id_anggaran : null;
                                        $id_anggaran_noncid = $a->jenis_anggaran === 'non CID' ? $a->id_anggaran : null;
                                        $anggaran_cid = $a->anggaran_cid;
                                        $anggaran_noncid = $a->anggaran_noncid;   
                                        $status = $a->status->nama;   
                                        $status_id = $a->status_id;

                                        $nextTpb = isset($anggaran_anak[$key+1]) ? $anggaran_anak[$key+1] : null;
                                        
                                        if($nextTpb !== null) {
                                            if($a->no_tpb === $nextTpb->no_tpb) {
                                                if($nextTpb->jenis_anggaran == 'CID') {
                                                    $anggaran_cid = $nextTpb->anggaran_cid;
                                                    $id_anggaran_cid = $nextTpb->id_anggaran;
                                                } else {
                                                    $anggaran_noncid = $nextTpb->anggaran_noncid;
                                                    $id_anggaran_noncid = $nextTpb->id_anggaran;
                                                }

                                                if($nextTpb->status->nama == 'In Progress') $status = $nextTpb->status->nama;
                                                if($nextTpb->status_id != 1) $status_id = $nextTpb->status_id;

                                                $currentPrintable = true;
                                                $nextPrintable = false;
                                            }
                                        }
                                        
                                        $status_class = 'primary';
                                        if($status_id == 1){
                                            $status_class = 'success';
                                        }else if($status_id == 3){
                                            $status_class = 'warning';
                                        }
                                    @endphp                                       
                                     @if($currentPrintable)
                                     @if(number_format($anggaran_cid) > 0 || number_format($anggaran_noncid) > 0)
                                    <tr class="treegrid-{{$a->id}} treegrid-parent-bumn{{@$b->id}}pilar{{str_replace(' ', '-', @$p->pilar_nama)}} item{{$a->id}}">
                                        <td></td>
                                        <td>{{@$a->no_tpb .' - '. @$a->tpb_nama}}</td>
                                        <td style="text-align:right;">{{$id_anggaran_cid ? number_format($anggaran_cid,0,',',',') : '-'}}</td>
                                        <td style="text-align:right;">{{$id_anggaran_noncid ? number_format($anggaran_noncid,0,',',',') : '-'}}</td>
                                        <td style="text-align:right;">{{number_format($anggaran_noncid + $anggaran_cid,0,',',',')}}</td>
                                        <td style="text-align:center;">
                                            <span class="btn cls-log badge badge-light-{{$status_class}} fw-bolder me-auto px-4 py-3" data-id="{{$a->id}}" data-anggaran-cid="{{ $id_anggaran_cid }}" data-anggaran-noncid="{{ $id_anggaran_noncid }}">{{$status}}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            @if(!$view_only)
                                                @if($status_id != 1)
                                                <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-anggaran-cid="{{ $id_anggaran_cid }}" data-anggaran-noncid="{{ $id_anggaran_noncid }}" data-id="{{$a->id}}" data-toggle="tooltip" title="Ubah data {{@$a->no_tpb}}"><i class="bi bi-pencil fs-3"></i></button>
                                                <!-- <button type="button" class="btn btn-sm btn-danger btn-icon cls-button-delete" data-anggaran="{{ $a->id_anggaran }}" data-perusahaan_id="{{$b->id}}" data-id="{{$a->id}}" data-nama="{{@$a->no_tpb}}" data-toggle="tooltip" title="Hapus data {{@$a->no_tpb}}"><i class="bi bi-trash fs-3"></i></button> -->
                                                @endif
                                            @endif
                                        </td>
                                        
                                        <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                            <input class="form-check-input is_active-check tpb-check perusahaan-{{$b->id}} pilar-{{$b->id}}-{{str_replace(' ', '-', @$p->pilar_nama)}}" data-anggaran-cid="{{ $id_anggaran_cid }}" data-anggaran-noncid="{{ $id_anggaran_noncid }}" type="checkbox" data-no_tpb="${row.no_tpb}" data-nama="${row.nama}" data-jenis_anggaran="${row.jenis_anggaran}"  ${isChecked} name="selected-is_active[]" value="${row.id}">
                                            </label></td>
                                    </tr>
                                    @endif
                                    @endif                                    
                                    @endforeach
                                @endforeach
                            @endforeach
                            @php
                                $total = $total_cid + $total_noncid;
                            @endphp
                            @if($total==0)
                                <td></td>
                                <td style="text-align:left;">-</td>
                                <td style="text-align:center;">-</td>
                                <td style="text-align:center;"><span class="badge badge-light-warning fw-bolder me-auto px-4 py-3">Unfilled</span></td>
                                <td></td>
                            @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    @if($total>0)
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;"></th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">Total</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_cid,0,',',',')}}</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_noncid,0,',',',')}}</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total,0,',',',')}}</th>
                                    @endif
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
    var datatable;
    var urlcreate = "{{route('anggaran_tpb.create')}}";
    var urledit = "{{route('anggaran_tpb.edit')}}";
    var urlstore = "{{route('anggaran_tpb.store')}}";
    var urldatatable = "{{route('anggaran_tpb.datatable')}}";
    var urldelete = "{{route('anggaran_tpb.delete')}}";
    var urldeletepilar = "{{route('anggaran_tpb.delete_by_pilar')}}";
    var urldeletebyselect = "{{route('anggaran_tpb.delete_by_select')}}";
    var urlexport = "{{route('anggaran_tpb.export')}}";
    var urlvalidasi = "{{route('anggaran_tpb.validasi')}}";
    var urlgetstatus = "{{route('anggaran_tpb.get_status')}}";
    var urllog = "{{route('anggaran_tpb.log_status')}}";
    var urlverifikasidata = "{{route('anggaran_tpb.verifikasi_data')}}";

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
            // winform(urllog, {'id':$(this).data('id')}, 'Log Status');
            winform(urllog, {'id_cid':$(this).data('anggaran-cid'), 'id_noncid':$(this).data('anggaran-noncid')}, 'Log Status');
        });

        $('body').on('click','.cls-button-edit',function(){
            // winform(urledit, {'id':$(this).data('id')}, 'Ubah Data');
            winform(urledit, {'id_cid':$(this).data('anggaran-cid'), 'id_noncid':$(this).data('anggaran-noncid') }, 'Ubah Data');
        });

        $('body').on('click','.cls-button-delete',function(){
            onbtndelete(this);
        });

        $('body').on('click','.cls-button-delete-pilar',function(){
            onbtndeletepilar(this);
        });

        $('body').on('click','.cls-export',function(){
            exportExcel();
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
        setDatatable();
        $('#proses').on('click', function(event){
            // datatable.ajax.reload()
            var url = window.location.origin + '/anggaran_tpb/index';
            var perusahaan_id = $('#perusahaan_id').val();
            var tahun = $('#tahun').val();
            var pilar_pembangunan_id = $('#pilar_pembangunan_id').val();
            var tpb_id = $('#tpb_id').val();
            const jenisAnggaran = $("#jenis-anggaran").val()
            const statusAnggaran = $("#status-anggaran").val()            

            window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun + '&pilar_pembangunan=' + pilar_pembangunan_id + '&tpb=' + tpb_id + '&jenis_anggaran=' +jenisAnggaran+ '&status=' +statusAnggaran;
        });
       
        if(!"{{ $admin_bumn }}"){
            showValidasi();
        }   

        $('.delete-selected-data').on('click', function(){
            var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            var selectedCids = [];
            var selectedNonCids = [];

            selectedCheckboxes.forEach(function(checkbox) {
                if(checkbox.getAttribute('data-anggaran-cid')) selectedCids.push(checkbox.getAttribute('data-anggaran-cid'));
                if(checkbox.getAttribute('data-anggaran-noncid')) selectedNonCids.push(checkbox.getAttribute('data-anggaran-noncid'));
            });

            if(!selectedCids.length && !selectedNonCids.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk dihapus!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }
            
            deleteAnggaranSelectedTpb(selectedCids.concat(selectedNonCids))            
        })

        $("#select-all").on('click', function(){
            var checkboxes = $('.is_active-check');            
            checkboxes.prop('checked', $(this).prop('checked'));
        }) 

        $(".pilar-check").on('click', function() {
            const parentPilar = $(this).data('pilar-parent')
            var checkboxes = $(`.${parentPilar}`)
            checkboxes.prop('checked', $(this).prop('checked'))
        })

        $(".perusahaan-check").on('click', function() {
            const parentPerusahaan = $(this).data('perusahaan-parent')
            var checkboxes = $(`.${parentPerusahaan}`)
            checkboxes.prop('checked', $(this).prop('checked'))
        })

        $("#verify-data").on('click', function() {
            var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            var selectedCids = [];
            var selectedNonCids = [];

            selectedCheckboxes.forEach(function(checkbox) {
                if(checkbox.getAttribute('data-anggaran-cid')) selectedCids.push(checkbox.getAttribute('data-anggaran-cid'));
                if(checkbox.getAttribute('data-anggaran-noncid')) selectedNonCids.push(checkbox.getAttribute('data-anggaran-noncid'));
            });

            if(!selectedCids.length && !selectedNonCids.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk diverifikasi!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }
            
            verifySelectedData(selectedCids.concat(selectedNonCids)) 
            
        })
      
    });

    function verifySelectedData(selectedData) {
        const jumlahSelected = selectedData.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin verifikasi data ? <br/><span style='color: red; font-weight: bold'>[Data selected: "+jumlahSelected+" rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, verifikasi data",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urlverifikasidata,
                data:{
                    "anggaran_verifikasi": selectedData
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

    function deleteAnggaranSelectedTpb(selectedAnggaran) {
        const jumlahDataDeleted = selectedAnggaran.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin hapus data ? <br/><span style='color: red; font-weight: bold'>[Data selected: "+jumlahDataDeleted+" rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urldeletebyselect,
                data:{
                    "anggaran_deleted": selectedAnggaran
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

    function addCommas(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

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
                    "id": $(element).data('id'),
                    "anggaran": $(element).data('anggaran'),
                    "perusahaan_id": $(element).data('perusahaan_id'),
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
            text: "Yakin hapus data "+$(element).data('nama')+" tahun "+$(element).data('tahun')+" ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urldeletepilar,
                data:{
                    "id": $(element).data('id'),
                    "tahun": $(element).data('tahun'),
                    "nama_pilar": $(element).data('nama'),
                    "perusahaan_id": $(element).data('perusahaan_id'),
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
                var filename = 'Data Anggaran TPB '+today+'.xlsx';

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
            text: "Validasi Data Aggaran TPB "+$("#perusahaan_id option:selected").text() +" tahun "+$("#tahun").val()+" ?",
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

    //Imam
    function redirectToNewPage() {
        var selectedPerusahaanId = $('#perusahaan_id').val();
        var selectedPerusahaanText = $('#perusahaan_id option:selected').text();

    var selectedTahun = $('#tahun').val();
    var selectedTahunText = $('#tahun option:selected').text();

    if(selectedPerusahaanId === '' || selectedTahun === '') {
        swal.fire({                    
            icon: 'warning',
            html: 'Perusahaan (BUMN) dan Tahun harus terisi!',
            type: 'warning', 
            confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
        });
        return
    }

    // Do something with the selected value and text
    // console.log("selectedPerusahaanId: " + selectedPerusahaanId);
    // console.log("selectedPerusahaanText: " + selectedPerusahaanText);

    // console.log("selectedTahun: " + selectedTahun);
    // console.log("selectedTahunText: " + selectedTahunText);

        // Use the Laravel's built-in route function to generate the new URL
        var url = "{{ route('anggaran_tpb.create2', ['perusahaan_id' => ':perusahaan_id', 'tahun' => ':tahun']) }}";
        url = url.replace(':perusahaan_id', selectedPerusahaanId).replace(':tahun', selectedTahun);

        // Redirect the user to the new page
        window.location.href = url;
    }
</script>
@endsection

