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
                <div class="card-toolbar" style="display: none">
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
                                        <option value="{{ $p->no_tpb }}" {!! $select !!}>{{ $p->no_tpb }} - {{ $p->nama }}</option>
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
                                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected="selected"' : '' }} >
                                        In Progress</option>
                                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected="selected"' : '' }}  >
                                            Completed</option>
                                    <option value="Verified" {{ request('status') === 'Verified' ? 'selected="selected"' : '' }}  >
                                            Verified</option>
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
                                @php
                                    $enable_input = false;
                                    if($isOkToInput || $isEnableInputBySuperadmin) $enable_input = true;
                                    $isVerified = false;
                                    if($countInprogress > 0) $isVerified = false;
                                    else if($countVerified > 0) $isVerified = true;
                                @endphp
                                @can('view-kegiatan')
                                    <button type="button" class="btn btn-success me-2 btn-sm rekap-data">Rekap Data
                                    </button>
                                    @can('delete-kegiatan')
                                    <button {{ $isSuperAdmin ? '' : ($enable_input ? (!$isVerified ? '' : 'disabled') : 'disabled') }} type="button" class="btn btn-danger me-2 btn-sm delete-selected-data">Hapus Data
                                    </button>
                                    @endcan
                                    @can('edit-kegiatan')
                                    <button {{ $isSuperAdmin ? '' : ($enable_input ? (!$isVerified ? '' : 'disabled') : 'disabled') }} type="button" class="btn btn-success btn-sm input-data me-2" onclick="redirectToNewPage()">Input Data
                                    </button>
                                    @endcan
                                    
                                @endcan
                              
                                @can('view-verify')
                                    @if($countInprogress || !$data->count())
                                    <button {{ $enable_input || $isSuperAdmin ? '' : 'disabled' }} type="button" class="btn btn-primary btn-sm me-2" id="completed-data" >Complete
                                    </button>    
                                    @endif
                                @endcan

                                @can('view-unverify')
                                    @if(!$countInprogress && $data->count())
                                    <button {{ $enable_input || $isSuperAdmin ? '' : 'disabled' }} type="button" class="btn btn-warning btn-sm me-2" id="uncompleted-data" >Un-Complete
                                    </button>  
                                    @endif
                                @endcan

                                @can('view-finalVerify')
                                    @if($countInprogress || !$data->count())
                                        <button {{ $enable_input || $isSuperAdmin ? '' : 'disabled' }} type="button" class="btn btn-success btn-sm" id="verify-data" >Verify
                                        </button>    
                                    @endif
                                @endcan
                            </div>
                            <!--end::Search-->
                            <!--end::Group actions-->
                        </div>
                        <!--end::Card toolbar-->
                    </div> 
                    <div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tree-new table-checkable">
                            <thead>
                                <tr>
                                    <th style="text-align:center;font-weight:bold;width:5%;border-bottom: 1px solid #c8c7c7;">No.</th>
                                    <th style="font-weight:bold;width:30%;border-bottom: 1px solid #c8c7c7;">Pilar - TPB</th>
                                    <th style="text-align:center;font-weight:bold;width:15%;border-bottom: 1px solid #c8c7c7;"> CID</th>
                                    <th style="text-align:center;font-weight:bold;width:15%;border-bottom: 1px solid #c8c7c7;">Non CID</th>
                                    <th style="text-align:center;font-weight:bold;width:15%;border-bottom: 1px solid #c8c7c7;">Total</th>
                                    <th style="text-align:center;font-weight:bold;width:10%;border-bottom: 1px solid #c8c7c7;">Status</th>
                                    <th style="text-align:center;width:5%;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Aksi</th>
                                    <th style="width: 5%"><label
                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input 
                                            class="form-check-input" type="checkbox" id="select-all"></label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_cid = 0;
                                    $total_noncid = 0;
                                @endphp
                                @foreach($data as $perusahaan)
                                    @php
                                        $total_cid += $perusahaan->sum_cid;
                                        $total_noncid += $perusahaan->sum_noncid;
                                        
                                        if($perusahaan->inprogress) $status_class = 'primary';
                                        else if($perusahaan->completed) $status_class = 'success';
                                        else if($perusahaan->verified) $status_class = 'success';
                                        else $status_class = 'danger';
                                    @endphp
                                <tr class="treegrid-perusahaan-{{ $perusahaan->perusahaan_id }}" id="perusahaan-{{ $perusahaan->perusahaan_id }}" data-type="perusahaan" data-value="{{ $perusahaan->perusahaan_id }}">
                                    <td style="text-align:center;"></td>
                                    <td style="display: flex"><div style="flex: 1">{{$perusahaan->nama_lengkap}}</div></td>
                                    <td style="text-align:right;">
                                        {{number_format($perusahaan->sum_cid,0,',',',')}}
                                    </td>
                                    <td style="text-align:right;">
                                        {{number_format($perusahaan->sum_noncid,0,',',',')}}
                                    </td>
                                    <td style="text-align:right;">
                                        {{number_format($perusahaan->sum_cid + $perusahaan->sum_noncid,0,',',',')}}
                                    </td>
                                    <td style="text-align:center;">
                                        <a class="badge badge-light-{{ $status_class }} fw-bolder me-auto px-4 py-3" data-toggle="tooltip" title="Lihat Log">{{ $perusahaan->inprogress ? 'In Progress' : ($perusahaan->completed ? 'Completed' : ($perusahaan->verified ? 'Verified' : '-') ) }}</a>
                                    </td>
                                    <td></td>
                                    <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                        <input disabled class="form-check-input perusahaan-check" data-perusahaan-parent="perusahaan-{{ $perusahaan->perusahaan_id }}" type="checkbox">
                                        </label></td>                                  
                                </tr>
                                <tr class="treegrid-parent-perusahaan-{{ $perusahaan->perusahaan_id }}" id="treegrid-parent-perusahaan-{{ $perusahaan->perusahaan_id }}" style="visibility: hidden"><td style="text-align:center;"></td></tr>
                                @endforeach  
                                @if(!$data->count())
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 15px; color: #696969; text-transform: uppercase">Data tidak tersedia!</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    @if($total_cid + $total_noncid > 0)
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;"></th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">Total</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_cid,0,',',',')}}</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_noncid,0,',',',')}}</th>
                                    <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{number_format($total_cid + $total_noncid,0,',',',')}}</th>
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
    var urledit = "{{route('anggaran_tpb.edit2')}}";
    var urlstore = "{{route('anggaran_tpb.update_anggaran')}}";
    var urldatatable = "{{route('anggaran_tpb.datatable')}}";
    var urldelete = "{{route('anggaran_tpb.delete')}}";
    var urldeletepilar = "{{route('anggaran_tpb.delete_by_pilar')}}";
    var urldeletebyselect = "{{route('anggaran_tpb.delete_by_select2')}}";
    var urlexport = "{{route('anggaran_tpb.export')}}";
    var urlvalidasi = "{{route('anggaran_tpb.validasi')}}";
    var urlgetstatus = "{{route('anggaran_tpb.get_status')}}";
    var urllog = "{{route('anggaran_tpb.log_status2')}}";
    var urlverifikasidata = "{{route('anggaran_tpb.verifikasi_data')}}";
    var urlgetdataperusahaan = "{{ route('anggaran_tpb.get_data_perusahaan_tree') }}";
    var urlbatalverifikasidata = "{{route('anggaran_tpb.batal_verifikasi_data')}}";
    var urlenableinputdata = "{{route('anggaran_tpb.enable_disable_input_data')}}";
    var urlgetdataperusahaanpilar = "{{ route('anggaran_tpb.get_data_perusahaan_pilar_tree') }}";
    var urlverifikasidataFinal = "{{route('anggaran_tpb.verifikasi_data_final')}}";

    $(document).ready(function(){
        
        const viewOnly = "{{ $view_only }}";
        const isOkToInput = "{{ $isOkToInput }}";  
        const countInprogress = parseInt("{{ $countInprogress }}")
        const countCompleted = parseInt("{{ $countCompleted }}")
        const isSuperAdmin = "{{ $isSuperAdmin }}";

        $(".tree-new").treegrid({            
            initialState : 'collapsed',
            treeColumn : 1,
            // indentTemplate : '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>',            
            // indentTemplate: '<div></div>'
            // onExpand: function(nodeId) {
                
            //     // Add child elements when expanded
            //     var parentNode = $(this).treegrid('getNodeId')
                
            //     // Make an AJAX request or perform any necessary operations to fetch child data
                
            //     // Example: Adding a new child row
            //     var newChildRow = $("<tr>").addClass("treegrid-parent-" + nodeId)
            //                             .addClass("treegrid-expanded")
            //                             .attr("data-node-id", "newChildNodeId")
            //                             .append($("<td>").text("New Child"))
            //                             .append($("<td>").text("Child Data"));
                
            //     // Append the new child row to the parent node
            //     $(this).treegrid('getNodeId').after(newChildRow);
            // }
        });
        

        var dataPerusahaanLoading = new Map()
        var dataPilarLoading = new Map()
        var dataTpbLoading = new Map()
        var expandedTree = []

        $('.tree-new').on('click', 'tbody tr .treegrid-expander', async function() {            
            let $arrow = $(this);
            let $row = $(this).closest('tr');
            let typeRow = $row.data('type');
            let value = $row.data('value');
            var isExpanded = $row.hasClass('treegrid-expanded');
            let selectedYear = $("#tahun").val()        

            if(isExpanded) {
                expandedTree.push(`#${$row.prop('id')}`)
            } else {
                expandedTree = expandedTree.filter(index => index != `#${$row.prop('id')}`)
            }            

            if(typeRow === 'perusahaan' && isExpanded) {
                let selectedClassTree = $row.attr('class').split(' ').filter((cls) => cls.startsWith('treegrid-perusahaan-'))[0]

                // get current url parameter
                const url = new URL(window.location.href);
                const queryParams = url.searchParams;
                const pilar_pembangunan = queryParams.get('pilar_pembangunan');
                const tpb = queryParams.get('tpb')
                const status = queryParams.get('status')

                let dataPerusahaanLoaded = dataPerusahaanLoading.get(parseInt(value));
                if(!dataPerusahaanLoaded) {
                    // kalo belum pernah diload, request data ...
                    // value == id perusahaan
                    let data = await loadDataPerusahaan(value, selectedYear, pilar_pembangunan, tpb, status);
                    dataPerusahaanLoading.set(value, true);

                    // populate tree
                    let pilarRow = '';
                    let pilar = data.result;
                    let parentClass = selectedClassTree.replace('treegrid-', 'treegrid-parent-')
                    for(let i=0; i<pilar.length; i++) {

                        // defining progress status
                        let status_class = ''
                        if(pilar[i].inprogress) status_class = 'primary'
                        else if(pilar[i].completed) status_class = 'success'
                        else if(pilar[i].verified) status_class = 'success'
                        else status_class = 'danger'

                        let tempPilarRow = `
                        <tr class="treegrid-bumn-${value}-pilar-${pilar[i].nama_pilar.split(' ').join('-')} ${parentClass}" data-type="pilar" data-value="${pilar[i].nama_pilar.split(' ').join('-')}" data-perusahaan="${value}" id="perusahaan-${value}-pilar-${pilar[i].nama_pilar.split(' ').join('-')}">
                            <td style="text-align:center;">${i+1}</td>
                            <td style="display: flex"><div style="flex: 1">${pilar[i].nama_pilar}</div></td>
                            <td style="text-align:right;">${parseInt(pilar[i].sum_cid).toLocaleString()}</td>
                            <td style="text-align:right;">${parseInt(pilar[i].sum_noncid).toLocaleString()}</td>
                            <td style="text-align:right;">${(parseInt(pilar[i].sum_cid) + parseInt(pilar[i].sum_noncid)).toLocaleString()}</td>
                            <td style="text-align:center;">
                                <a class="badge badge-light-${status_class} fw-bolder me-auto px-4 py-3" data-toggle="tooltip" title="Lihat Log">${pilar[i].inprogress ? 'In Progress' : (pilar[i].completed ? 'Completed' : (pilar[i].verified ? 'Verified' : '-') )}</a>
                            </td>
                            <td style="text-align:center;">                                            
                            </td>
                            
                            <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                <input disabled class="form-check-input pilar-check perusahaan-${value}" data-pilar-parent="pilar-${value}-${pilar[i].nama_pilar.split(' ').join('-')}" type="checkbox">
                                </label></td>
                        </tr>
                        <tr class="treegrid-parent-bumn-${value}-pilar-${pilar[i].nama_pilar.split(' ').join('-')}" id="treegrid-parent-bumn-${value}-pilar-${pilar[i].nama_pilar.split(' ').join('-')}" style="visibility: hidden"><td style="text-align:center;"></td></tr>
                        `;
                        pilarRow += tempPilarRow;
                    }

                    $("#"+parentClass).before(pilarRow);

                    // refresh treegrid
                    $(".tree-new").treegrid({
                        initialState : 'inherit',
                        treeColumn : 1,
                    })

                    // expand all tree that previously expanded
                    $(`${expandedTree.join(',')}`).treegrid('expand')
                    
                }
            } else if(typeRow === 'pilar' && isExpanded) {
                let selectedClassTree = $row.attr('class').split(' ').filter((cls) => cls.startsWith('treegrid-bumn-'))[0]
                // get current url parameter
                const url = new URL(window.location.href);
                const queryParams = url.searchParams;
                const tpb_filter = queryParams.get('tpb')
                const status = queryParams.get('status')

                // key: idperusahaan + nama pilar
                // value == nama pilar
                let tempPerusahaan = $row.data('perusahaan')
                let key = `${tempPerusahaan}-${value}`
                let dataPilarLoaded = dataPilarLoading.get(key)
                if(!dataPilarLoaded) {
                    // kalo belum pernah diload, request data ...
                    let data = await loadDataPerusahaanPilar(value, tempPerusahaan, selectedYear, tpb_filter, status);
                    dataPilarLoading.set(key, true);

                    // populate tree
                    let tpbRow = '';
                    let tpb = data.result;
                    let parentClass = selectedClassTree.replace('treegrid-', 'treegrid-parent-')
                    for(let i=0; i<tpb.length; i++) {      
                        
                        // defining progress status
                        let status_class = ''
                        if(tpb[i].inprogress) status_class = 'primary'
                        else if(tpb[i].completed) status_class = 'success'
                        else if(tpb[i].verified) status_class = 'success'
                        else status_class = 'danger'

                        let btnEdit = `<button ${isOkToInput || tpb[i].enable_by_admin > 0 || isSuperAdmin ? '' : 'disabled'} type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-tahun="${selectedYear}" data-notpb="${tpb[i].no_tpb}" data-namapilar="${value}" data-perusahaan="${tempPerusahaan}" data-toggle="tooltip" title="Ubah data ${tpb[i].no_tpb}"><i class="bi bi-pencil fs-3"></i></button>`;
                        let totalTpb = (tpb[i].sum_cid ? parseInt(tpb[i].sum_cid) : 0) + (tpb[i].sum_noncid ? parseInt(tpb[i].sum_noncid) : 0)
                        
                        let isCheckAll = $("#select-all").prop('checked');

                        let checkboxData = '';

                        if((isOkToInput || tpb[i].enable_by_admin > 0 || isSuperAdmin) && (tpb[i].inprogress)) {
                            checkboxData = `
                                <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                    <input class="form-check-input is_active-check tpb-check perusahaan-${tempPerusahaan} pilar-${tempPerusahaan}-${value}" data-tahun="${selectedYear}" data-notpb="${tpb[i].no_tpb}" data-namapilar="${value}" data-perusahaan="${tempPerusahaan}" type="checkbox" ${isCheckAll ? 'checked' : ''}>
                                </label>
                            `;
                        }

                        let tempTpbRow = `
                        <tr class="treegrid-bumn-${tempPerusahaan}-pilar-${value}-tpb-${tpb[i].no_tpb.split(' ').join('-')} ${parentClass}" data-type="tpb" data-value="${tpb[i].no_tpb.split(' ').join('-')}" data-perusahaan="${tempPerusahaan}" data-pilar="${value}">
                            <td style="text-align:center;"></td>
                            <td style="display: flex"><div style="flex: 1">${tpb[i].no_tpb} - ${tpb[i].nama_tpb}</div></td>
                            <td style="text-align:right;">${tpb[i].sum_cid ? parseInt(tpb[i].sum_cid).toLocaleString() : '-'}</td>
                            <td style="text-align:right;">${tpb[i].sum_noncid ? parseInt(tpb[i].sum_noncid).toLocaleString() : '-'}</td>
                            <td style="text-align:right;">${totalTpb.toLocaleString()}</td>
                            <td style="text-align:center;">
                                <span class="btn cls-log badge badge-light-${status_class} fw-bolder me-auto px-4 py-3" data-tahun="${selectedYear}" data-notpb="${tpb[i].no_tpb}" data-namapilar="${value}" data-perusahaan="${tempPerusahaan}">${tpb[i].inprogress ? 'In Progress' : (tpb[i].completed ? 'Completed' : (tpb[i].verified ? 'Verified' : '-'))}</span>
                            </td>
                            <td style="text-align:center;">    
                                ${!viewOnly && tpb[i].inprogress ? btnEdit : ''}
                            </td>
                            
                            <td>
                                ${checkboxData}
                            </td>
                        </tr>                        
                        `;
                        tpbRow += tempTpbRow;
                    }

                    $("#"+parentClass).before(tpbRow);

                    // refresh treegrid
                    $(".tree-new").treegrid({
                        initialState : 'inherit',
                        treeColumn : 1,
                    })

                    // expand all tree that previously expanded
                    $(`${expandedTree.join(',')}`).treegrid('expand')
                    
                }
            } else if(typeRow === 'tpb' && isExpanded) {
                console.log('Tpb ID:', value);
                let dataTpbLoaded = dataTpbLoading.get(parseInt(value))
                if(!dataTpbLoaded) {
                    // kalo belum pernah diload, request data ...
                }
            }                                        
        });
        

        $('.tree').treegrid({
            initialState : 'collapsed',
            treeColumn : 1,
            // indentTemplate : '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
        });

        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-add',function(){
            winform(urlcreate, {}, 'Tambah Data');
        });

        $('body').on('click','.cls-log',function(){
            winform(urllog, {'tahun': $(this).data('tahun'), 'no_tpb':$(this).data('notpb'), 'nama_pilar':$(this).data('namapilar'), 'perusahaan': $(this).data('perusahaan')}, 'Log Status');
        });

        $('body').on('click','.cls-button-edit',function(){
            winform(urledit, {'tahun': $(this).data('tahun'), 'no_tpb':$(this).data('notpb'), 'nama_pilar':$(this).data('namapilar'), 'perusahaan': $(this).data('perusahaan')}, 'Ubah Data');
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

        $('#proses').on('click', function(event){
            // datatable.ajax.reload()
            var url = window.location.origin + '/anggaran_tpb/rka';
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
            const selectedData = [];

            selectedCheckboxes.forEach(function(checkbox) {
                selectedData.push({
                    'tahun': checkbox.getAttribute('data-tahun'),
                    'no_tpb': checkbox.getAttribute('data-notpb'),
                    'nama_pilar': checkbox.getAttribute('data-namapilar'),
                    'perusahaan': checkbox.getAttribute('data-perusahaan')
                });
            });

            if(!selectedData.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk dihapus!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }
            deleteAnggaranSelectedTpb(selectedData)            
        })

        $("#select-all").on('click', function(){
            var checkboxes = $('.is_active-check');         
            checkboxes.prop('checked', $(this).prop('checked'));
        }) 

        $("body").on('click', '.is_active-check', function() {
            $('.is_active-check').each(function () {
                if(!$(this).prop('checked')) {
                    $('#select-all').prop('checked', false)
                    return
                }
            })
            
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
    

        $("#completed-data").on('click', function() {  

            
            if(!countInprogress) {
                swal.fire({
                    title: "Pemberitahuan",
                    html: "Tidak ada data yang bisa diverifikasi!",
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: "Close",
                })

                return
            }

            const bumn = "{{ $perusahaan_id }}"
            const tahun = "{{ $tahun }}"
            const nama_bumn = "{{ $perusahaan_nama }}"
            

            swal.fire({
                title: "Pemberitahuan",
                html: `<span style="color: red; font-weight: bold">Yakin set status completed data ? </span><br/>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Perusahaan</td>
                                    <td>${nama_bumn}</td>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Tahun</td>
                                    <td>${tahun}</td>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Jumlah Verifikasi</td>
                                    <td>${countInprogress} rows</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, verifikasi data",
                cancelButtonText: "Tidak"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: urlverifikasidata,
                        data:{
                            "bumn": bumn,
                            "tahun": tahun
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
            
        })

        $(".rekap-data").on('click', function(){
            exportExcel();
        })

        $("#uncompleted-data").on('click', function() {
            if(countInprogress) {
                swal.fire({
                    title: "Pemberitahuan",
                    html: "Tidak ada data yang bisa di-uncompleted!",
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: "Close",
                })

                return
            }

            const bumn = "{{ $perusahaan_id }}"
            const tahun = "{{ $tahun }}"
            const nama_bumn = "{{ $perusahaan_nama }}"

            swal.fire({
                title: "Pemberitahuan",
                html: `<span style="color: red; font-weight: bold">Yakin batalkan status completed data ? </span><br/>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Perusahaan</td>
                                    <td>${nama_bumn}</td>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Tahun</td>
                                    <td>${tahun}</td>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Jumlah Un-Completed</td>
                                    <td>${countCompleted} rows</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, batalkan verifikasi data",
                cancelButtonText: "Tidak"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: urlbatalverifikasidata,
                        data:{
                            "bumn": bumn,
                            "tahun": tahun
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
        })

        $(".enable-disable-input-by-superadmin").on('click', function() {
            const bumn = "{{ $perusahaan_id }}"
            const tahun = "{{ $tahun }}"
            const nama_bumn = "{{ $perusahaan_nama }}"
            const status = $(this).data('status')
            

            swal.fire({
                title: "Pemberitahuan",
                html: `<span style="color: red; font-weight: bold">${status === 'enable' ? 'Enable' : 'Disable'} Admin untuk input data ? </span><br/>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Perusahaan</td>
                                    <td>${nama_bumn}</td>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Tahun</td>
                                    <td>${tahun}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: `Ya, ${status} input data`,
                cancelButtonText: "Tidak"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: urlenableinputdata,
                        data:{
                            "bumn": bumn,
                            "tahun": tahun,
                            "status": status,
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
        }) 
        
        $("#verify-data").on('click', function() {  
            
            if(!countInprogress) {
                swal.fire({
                    title: "Pemberitahuan",
                    html: "Tidak ada data yang bisa diverifikasi!",
                    icon: "warning",
                    showCancelButton: false,
                    confirmButtonText: "Close",
                })

                return
            }

            const bumn = "{{ $perusahaan_id }}"
            const tahun = "{{ $tahun }}"
            const nama_bumn = "{{ $perusahaan_nama }}"


            swal.fire({
                title: "Pemberitahuan",
                html: `<span style="color: red; font-weight: bold">Yakin verifikasi data ? </span><br/>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Perusahaan</td>
                                    <td>${nama_bumn}</td>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Tahun</td>
                                    <td>${tahun}</td>
                                </tr>
                                <tr class="fw-bold fs-6 text-gray-800" style="text-align: left">
                                    <td>Jumlah Verifikasi</td>
                                    <td>${countCompleted} rows</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                        `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, verifikasi data",
                cancelButtonText: "Tidak"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: urlverifikasidataFinal,
                        data:{
                            "bumn": bumn,
                            "tahun": tahun
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

            })
      
    });    

    function deleteAnggaranSelectedTpb(selectedAnggaran) {
        let isSelectAll = $("#select-all").prop('checked');  
        let parameterSelectAll = {};
        if(isSelectAll) {
            const queryParams = new URLSearchParams(window.location.search)            
            parameterSelectAll = {
                'perusahaan_id' : queryParams.get('perusahaan_id'),
                'tahun' : queryParams.get('tahun'),
                'pilar_pembangunan' : queryParams.get('pilar_pembangunan'),
                'tpb' : queryParams.get('tpb')
            }
        }  
        const jumlahDataDeleted = isSelectAll ? 'ALL' : selectedAnggaran.length 
        let pesanHapus = `
            Yakin hapus data ? (Data terkait akan ikut terhapus juga: <strong>Program</strong>, <strong>Kegiatan</strong>, dan <strong>Kegiatan Realisasi</strong>) 
            <br/>
            <span style='color: red; font-weight: bold'>
                [Data selected: ${jumlahDataDeleted} rows]
            </span>
        `
        swal.fire({
            title: "Pemberitahuan",
            html: pesanHapus,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data",
            cancelButtonText: "Tidak"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                url: urldeletebyselect,
                data:{
                    "anggaran_deleted": selectedAnggaran,
                    "isDeleteAll": isSelectAll,
                    "parameterSelectAll": parameterSelectAll
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

        // Use the Laravel's built-in route function to generate the new URL
        var url = "{{ route('anggaran_tpb.rka.create', ['perusahaan_id' => ':perusahaan_id', 'tahun' => ':tahun']) }}";
        url = url.replace(':perusahaan_id', selectedPerusahaanId).replace(':tahun', selectedTahun);

        // Redirect the user to the new page
        window.location.href = url;
    }

    function loadDataPerusahaan(perusahaanId, tahun, pilar_pembangunan, tpb, status) {
        return $.ajax({
            url: urlgetdataperusahaan,
            data:{
                "id": perusahaanId,
                "tahun": tahun,
                "pilar_pembangunan": pilar_pembangunan,
                "tpb": tpb,
                "status": status
            },
            type:'post',
            dataType:'json',
            beforeSend: function(){
                $.blockUI();
            },
            success: function(data){
                $.unblockUI();                
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

    function loadDataPerusahaanPilar(namaPilar, perusahaanId, tahun, tpb, status) {
        return $.ajax({
            url: urlgetdataperusahaanpilar,
            data:{
                "id": perusahaanId,
                "tahun": tahun,
                "pilar": namaPilar,
                "tpb": tpb,
                "status": status
            },
            type:'post',
            dataType:'json',
            beforeSend: function(){
                $.blockUI();
            },
            success: function(data){
                $.unblockUI();                
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
</script>
@endsection