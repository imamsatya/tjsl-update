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
            <!--begin::Card-->
            <div class="card">

                <!--begin::Card header-->
                <div class="card-header pt-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="d-flex align-items-center">
                            {{ $pagetitle }}
                            <span class="text-gray-600 fs-6 ms-1"></span>
                        </h2>
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
                            <select disabled class="form-select form-select-solid form-select2" id="select-tahun" name="tahun" data-kt-select2="true" >
                                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                    @php
                                        $select = (($i == $tahun) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                        <div class="col-lg-4 mb-20">
                       
                            <label>Jenis Anggaran</label>
                            <select disabled  id="jenis-anggaran" class="form-select form-select-solid form-select2" name="jenis_anggaran" data-kt-select2="true" data-placeholder="Pilih Jenis Anggaran" >
                                <option></option>
                                <option value="CID" {{ $jenis_anggaran === 'CID' ? 'selected="selected"' : '' }} >
                                        CID</option>
                                <option value="non CID" {{ $jenis_anggaran === 'non CID' ? 'selected="selected"' : '' }} >
                                    non CID</option>
                            </select>
                        </div>
                    </div>
                        <form method="POST" id="program-form">
                            @csrf
                            <div class="mb-6 ">
                                
                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Nama Program<span style="color: red">*</span></div>
                                    </div>
                                    <div class="col-lg-9">
                                        <textarea class="form-control" id="nama_program" name="nama_program" style="height: 100px"></textarea>                                        
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Pilih TPB<span style="color: red">*</span></div>
                                    </div>
                                    <div class="col-lg-9">
                                        <select id="tpb_id" class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true"  data-placeholder="Pilih TPB" data-allow-clear="true">
                                            <option></option>
                                            @foreach($tpb as $p)                                                
                                                <option data-jenis-anggaran="{{ $p->jenis_anggaran }}" value="{{ $p->id }}" {!! $select !!}>{{ $p->no_tpb }} - {{ $p->nama }} [{{$p->jenis_anggaran}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Unit Owner</div>
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="unit_owner" id="unit_owner"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Unit Owner"  
                                                />                     
                                    </div>
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Kriteria Program<span style="color: red">*</span></div>
                                    </div>
                                    <div class="col-lg-9 fv-row d-flex align-items-center justify-content-start">
                                        <div style="display:flex; flex-direction: row;">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                                                <input class="form-check-input" type="checkbox" name="kriteria_program" value="prioritas" id="checkboxPrioritas"/>
                                                <label class="form-check-label" for="checkboxPrioritas">
                                                    Prioritas
                                                </label>
                                            </div> 
                                            <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                                                <input class="form-check-input" type="checkbox" name="kriteria_program" value="csv" id="checkboxCSV"/>
                                                <label class="form-check-label" for="checkboxCSV">
                                                    CSV
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                <input class="form-check-input" type="checkbox" name="kriteria_program" value="umum" id="checkboxUmum"/>
                                                <label class="form-check-label" for="checkboxUmum">
                                                    Umum
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">ID Core Subject<span style="color: red">*</span></div>
                                    </div>
                                    <div class="col-lg-9">
                                        <select id="core_subject_id" class="form-select form-select-solid form-select2" name="core_subject_id" data-kt-select2="true" data-placeholder="Pilih ID Core Subject" data-allow-clear="true">
                                            <option></option>
                                            @foreach($core_subject as $c)                                              
                                                <option value="{{ $c->id }}" >{{ $c->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Pelaksanaan Program<span style="color: red">*</span></div>
                                    </div>
                                    <div class="col-lg-9">
                                        <select  id="pelaksanaan_program" class="form-select form-select-solid form-select2" name="pelaksanaan_program" data-kt-select2="true" data-placeholder="Pilih Pelaksanaan Program" data-allow-clear="true">
                                            <option></option>
                                            <option value="Mandiri" {{ request('pelaksanaan_program') === 'Mandiri' ? 'selected="selected"' : '' }} >
                                                    Mandiri</option>
                                            <option value="Kolaborasi" {{ request('pelaksanaan_program') === 'Kolaborasi' ? 'selected="selected"' : '' }} >
                                                Kolaborasi</option>
                                        </select>
                                    </div>
                                
                                </div>

                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Mitra BUMN</div>
                                    </div>
                                    <div class="col-lg-9">
                                        <select class="form-select form-select-solid form-select2" id="mitra_bumn" name="mitra_bumn" data-kt-select2="true" data-placeholder="Pilih Mitra BUMN" data-allow-clear="true">
                                            <option></option>
                                            @foreach($perusahaan as $p)                                                  
                                                <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                
                                </div>

                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Program Multi Years<span style="color: red">*</span></div>
                                    </div>
                                    <div class="col-lg-9 fv-row d-flex align-items-center justify-content-start">
                                        <div style="display:flex; flex-direction: row;">
                                            <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                                                <input class="form-check-input" type="radio" name="program" id="multiyears_ya" value="ya"/>
                                                <label class="form-check-label" for="multiyears_ya">
                                                    Ya
                                                </label>
                                            </div> 
                                            <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                                                <input class="form-check-input" type="radio" name="program" id="multiyears_tidak" value="tidak"/>
                                                <label class="form-check-label" for="multiyears_tidak">
                                                    Tidak
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                

                                <div class="row mb-6">
                                    <div class="col-lg-3">
                                        <div class="ms-2">Alokasi Anggaran<span style="color: red">*</span></div>
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="alokasi_anggaran" id="alokasi_anggaran"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="Rp ... " oninput="formatCurrency(this)" 
                                            onkeypress="return onlyNumbers(event)" style="text-align:right;"  value="{{$data->income_bumn_pembina_lain ?? ''}}"
                                            />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-8 mb-5">
                                <div class="col-lg-3"></div>
                                <div class="col-lg-9">
                                    <button id="proses" class="btn btn-danger me-3">Close</button>
                                    <button id="clear-btn" class="btn btn-info me-3">Clear</button>
                                    <button id="simpan-btn" class="btn btn-success me-3">Simpan</button>
                                </div>
                            </div>
                        </form>
                        

                        <div class="card-px py-10">
                            <!--begin: Datatable -->
                            <div style="text-align:right">
                            <button type="button" class="btn btn-danger btn-sm delete-selected-data me-2">Hapus Data
                            </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover tree  table-checkable">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">No.</th>
                                            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Pilar - TPB</th>
                                            <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;"> {{ strtoupper($jenis_anggaran) }}</th>
                                            <th style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">Kriteria</th>
                                            <th style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">Status</th>
                                            <th style="text-align:center;width:100px;font-weight:bold;border-bottom: 1px solid #c8c7c7;" >Aksi</th>
                                            <th style="width: 5%"><label
                                                class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                                    class="form-check-input addCheck" type="checkbox"
                                                    id="select-all"></label>
                                        </th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                    @php 
                                        $no = 0;
                                        $total_program = 0;
                                        $total_rka = 0;                                                                            
                                    @endphp     
                                    @foreach($anggaran_pilar as $p)                          
                                        @php                                                                  
                                            $anggaran_anak = $anggaran->where('pilar_nama', $p->pilar_nama);                                        
                                            $pilar_anggaran = $anggaran->where('pilar_nama', $p->pilar_nama)->where('jenis_anggaran', $jenis_anggaran)->first();                                                                                        
                                        @endphp             
                                            
                                        @if($pilar_anggaran)
                                        @php 
                                            $no++;                                            
                                            $total_program_per_pilar = $anggaran_program->where('pilar_nama', $p->pilar_nama)->where('jenis_anggaran', $jenis_anggaran)->sum('anggaran_alokasi');
                                            $total_rka_pilar = $jenis_anggaran === 'CID' ? $p->sum_anggaran_cid : $p->sum_anggaran_noncid;
                                            $total_program += $total_program_per_pilar;
                                            $total_rka += $total_rka_pilar;

                                            $pilar_temp = $anggaran_program->where('pilar_nama', $p->pilar_nama);
                                            if($pilar_temp->where('status_id', 2)->first()) {
                                                $status_class = 'primary';
                                                $status = 'In Progress';
                                            } else {
                                                if($pilar_temp->where('status_id', 1)->first()) {
                                                    $status_class = 'success';
                                                    $status = 'Finish';
                                                } else {
                                                    $status_class = 'warning';
                                                    $status = 'Unfilled';
                                                }
                                            }

                                        @endphp    
                                        @if($total_rka_pilar)
                                            <tr class="treegrid-bumn-pilar-{{str_replace(' ', '-', @$p->pilar_nama)}}" >
                                                <td style="text-align:center;">{{$no}}</td>
                                                <td>{{$p->pilar_nama}}</td>
                                                <td style="text-align:right;">{{ number_format($total_program_per_pilar,0,',',',') }} <br/> <span style="color: {{ $total_program_per_pilar <= $total_rka_pilar ? 'green' : 'red'}}; font-size: smaller">RKA: {{number_format($total_rka_pilar,0,',',',')}}</span></td>
                                                <td style="text-align:right;"></td>
                                                <td style="text-align:center;">
                                                    <span class="badge badge-light-{{$status_class}} fw-bolder me-auto px-4 py-3">{{$status}}</span>
                                                </td>
                                                <td style="text-align:center;"></td>
                                                <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                                    <input class="form-check-input is_active-check pilar-check" data-pilar-parent="pilar-{{$p->perusahaan_id}}-{{str_replace(' ', '-', @$p->pilar_nama)}}" type="checkbox" data-anggaran="" name="selected-is_active[]" value="${row.id}">
                                                    </label></td>
                                            </tr>
                                                @foreach ($anggaran_anak as $key => $a)                                                                                                                                              
                                                    @if($a->jenis_anggaran === $jenis_anggaran)                                                    
                                                        @php
                                                            $program = $anggaran_program->where('pilar_nama', $p->pilar_nama)->where('tpb_id', $a->id_tpbs);
                                                            $total_program_per_tpb = $program->where('jenis_anggaran', $jenis_anggaran)->sum('anggaran_alokasi');

                                                            $status_tpb_inprogress = $program->where('status_id', 2)->first();
                                                            if($status_tpb_inprogress) {
                                                                $status_class = 'primary';
                                                                $status = 'In Progress';
                                                            } else {
                                                                if($program->where('status_id', 1)->first()) {
                                                                    $status_class = 'success';
                                                                    $status = 'Finish';
                                                                } else {
                                                                    $status_class = 'warning';
                                                                    $status = 'Unfilled';
                                                                }
                                                            }
                                                        @endphp 
                                                        @if($a->anggaran)   
                                                            <tr class="treegrid-anggaran-{{$a->id_anggaran}} treegrid-parent-bumn-pilar-{{str_replace(' ', '-', @$p->pilar_nama)}} item-{{$a->id_anggaran}}">
                                                                <td></td>
                                                                <td>{{@$a->no_tpb .' - '. @$a->tpb_nama}}</td>                                                        
                                                                <td style="text-align:right;">{{ number_format($total_program_per_tpb,0,',',',') }} <br/> <span style="color: {{ $total_program_per_tpb <= $a->anggaran ? 'green': 'red'}}; font-size: smaller">RKA: {{number_format($a->anggaran,0,',',',')}}</span></td>
                                                                <td style="text-align:center;"></td>
                                                                <td style="text-align:center;">
                                                                    <span class="badge badge-light-{{$status_class}} fw-bolder me-auto px-4 py-3">{{$status}}</span>
                                                                </td>
                                                                <td style="text-align:center;"></td>
                                                                
                                                                <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                                                    <input class="form-check-input is_active-check tpb-check pilar-{{$p->perusahaan_id}}-{{str_replace(' ', '-', @$p->pilar_nama)}} pilar-{{str_replace(' ', '-', @$p->pilar_nama)}}" data-tpb-parent="tpb-{{str_replace(' ', '-', @$a->no_tpb)}}-pilar-{{$p->perusahaan_id}}-{{str_replace(' ', '-', @$p->pilar_nama)}}" type="checkbox" name="selected-is_active[]" value="${row.id}">
                                                                    </label></td>
                                                            </tr>
                                                            
                                                            @foreach($program as $ap)
                                                                @if($ap->jenis_anggaran === $jenis_anggaran)
                                                                <tr class="treegrid-parent-anggaran-{{$a->id_anggaran}}">
                                                                    <td></td>
                                                                    <td>{{$ap->program}}</td>
                                                                    <td style="text-align:right;">{{number_format($ap->anggaran_alokasi,0,',',',')}}</td>                                                            
                                                                    <td style="text-align:center;">
                                                                        {{ $ap->kriteria_program_umum ? 'Umum; ' : '' }}
                                                                        {{ $ap->kriteria_program_prioritas ? 'Prioritas; ' : '' }}
                                                                        {{ $ap->kriteria_program_csv ? 'CSV; ' : '' }}
                                                                    </td>
                                                                    <td style="text-align:center;">
                                                                        @php
                                                                            $status_class = 'primary';
                                                                            $status = 'In Progress';
                                                                            if($ap->status_id == 1){
                                                                                $status_class = 'success';
                                                                                $status = 'Finish';
                                                                            } else if($ap->status_id == 3){
                                                                                $status_class = 'warning';
                                                                                $status = '-';
                                                                            }
                                                                        @endphp
                                                                        <span class="btn cls-log badge badge-light-{{$status_class}} fw-bolder me-auto px-4 py-3" data-id="{{$ap->id_target_tpbs}}">{{$status}}</span>
                                                                    </td>
                                                                    <td style="text-align:center;">
                                                                        @if($ap->status_id == 2)
                                                                        <button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="{{$ap->id_target_tpbs}}" data-toggle="tooltip" title="Ubah data {{$ap->program}}"><i class="bi bi-pencil fs-3"></i></button>
                                                                        @endif
                                                                    </td>
                                                                    
                                                                    <td><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                                                        <input class="form-check-input is_active-check tpb-{{str_replace(' ', '-', @$a->no_tpb)}}-pilar-{{$p->perusahaan_id}}-{{str_replace(' ', '-', @$p->pilar_nama)}} pilar-{{$p->perusahaan_id}}-{{str_replace(' ', '-', @$p->pilar_nama)}} pilar-{{str_replace(' ', '-', @$p->pilar_nama)}}" data-anggaran="{{ $ap->id_target_tpbs }}" type="checkbox" name="selected-is_active[]" value="${row.id}">
                                                                        </label></td>
                                                                </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif                                            
                                                @endforeach
                                            @endif
                                        @endif
                                        
                                        
                                    @endforeach                                    
                                    @if($total_program==0)
                                        <td></td>
                                        <td style="text-align:left;">-</td>
                                        <td style="text-align:center;">-</td>
                                        <td style="text-align:center;"><span class="badge badge-light-warning fw-bolder me-auto px-4 py-3">Unfilled</span></td>
                                        <td></td>
                                    @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            @if($total_program > 0)
                                            <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;"></th>
                                            <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">Total</th>
                                            <th style="text-align:right;font-weight:bold;border-top: 1px solid #c8c7c7;">{{ number_format($total_program,0,',',',') }} <br/> <span style="color: {{ $total_program <= $total_rka ? 'green' : 'red' }}; font-size: smaller">RKA: {{number_format($total_rka,0,',',',')}}</span></th>                                            
                                            @endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <br><br>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>
@endsection

@section('addafterjs')
    <script type="text/javascript" src="{{ asset('plugins/jquery-treegrid-master/js/jquery.treegrid.js') }}"></script>

    <script>
        var urledit = "{{route('rencana_kerja.program.edit')}}";
        var urlprogram = "{{ route('rencana_kerja.program.index') }}"     
        var urldelete = "{{ route('rencana_kerja.program.delete') }}";

        $(document).ready(function() {
            $('.tree').treegrid({
                initialState : 'collapsed',
                treeColumn : 1,
                indentTemplate : '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
            });   
                       
            setTpbOption("{{$jenis_anggaran}}")
                    
            $("#jenis-anggaran").on('change', function(){
                const jenisAnggaran = $(this).val()
                $("#tpb_id").val('').trigger('change')
                
                
                $("#tpb_id").select2({    
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
                $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB '+textAnggaran)
            })

            $(".cls-button-edit").on('click', function() {
                const program = $(this).data('id')
                winform(urledit, {'program':program}, 'Ubah Data');
            })

            $("#proses").on('click', function(e) {
                e.preventDefault()
                window.location.href=urlprogram
            })

            $('#select-all').on('click', function() {
                var checkboxes = $('.is_active-check');            
                checkboxes.prop('checked', $(this).prop('checked'));
            });

            $(".pilar-check").on('click', function() {
                const parentPilar = $(this).data('pilar-parent')
                var checkboxes = $(`.${parentPilar}`)
                checkboxes.prop('checked', $(this).prop('checked'))
            })

            $(".tpb-check").on('click', function() {
                const parentTpb = $(this).data('tpb-parent')
                var checkboxes = $(`.${parentTpb}`)
                checkboxes.prop('checked', $(this).prop('checked'))
            })

            $(".delete-selected-data").on('click', function() {
                var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                var selectedProgram = []
                selectedCheckboxes.forEach(function(checkbox) {
                    if(checkbox.getAttribute('data-anggaran')) selectedProgram.push(checkbox.getAttribute('data-anggaran'));
                });
                if(!selectedProgram.length) {
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

            $("#pelaksanaan_program").on('change', function() {
                const pelaksanaanProgram = $(this).val().toLowerCase()
                if(pelaksanaanProgram === 'mandiri') {
                    $("#mitra_bumn").val('').trigger('change')
                    $("#mitra_bumn").prop('disabled', true)
                    return
                } 
                $("#mitra_bumn").prop('disabled', false)
            })            
        });

        function setTpbOption(jenisAnggaran) {
            $("#tpb_id").select2({    
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
            $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB '+textAnggaran)
        }

        function formatCurrency(element) {
            //ver 1
            // const value = element.value.replace(/[^\d]/g, "");
            // const isNegative = value.startsWith("-");
            // const formatter = new Intl.NumberFormat("id-ID", {
            //     style: "currency",
            //     currency: "IDR",
            //     minimumFractionDigits: 0,
            //     maximumFractionDigits: 0
            // });
            // let formattedValue = formatter.format(value);
            // formattedValue = formattedValue.replace(/,/g, ".");
            // if (isNegative) {
            //     formattedValue = "- " + formattedValue;
                
            // }
            // element.value = formattedValue;

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

        document.getElementById('clear-btn').addEventListener('click', function(event) {
            event.preventDefault();
            var inputs = document.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type == 'text' || inputs[i].type == 'email' || inputs[i].type == 'password') {
                    inputs[i].value = '';
                } else if (inputs[i].type == 'radio' || inputs[i].type == 'checkbox') {
                    inputs[i].checked = false;
                }
            }

            $('#tpb_id, #core_subject_id, #pelaksanaan_program, #mitra_bumn').val('').trigger('change');
            $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB')
            $("#unit_owner").val('')

            // clear dropdown options
            var dropdowns = document.getElementsByTagName('select');
            for (var i = 0; i < dropdowns.length; i++) {
                dropdowns[i].selectedIndex = -1;
            }
        });
        

        const simpanBtn = document.querySelector("#simpan-btn");
        simpanBtn.addEventListener("click", async function(e) {

            e.preventDefault();
            
            var perusahaan_id = document.getElementById('perusahaan_id').value;
            // var perusahaan_id = perusahaan_id.getAttribute('data-variable');

            var tahun = document.getElementById('select-tahun').value;
            // var tahun = tahun.getAttribute('data-variable');

            var jenis_anggaran = document.getElementById('jenis-anggaran').value

            var actionform = document.getElementById('actionform');
            var actionform = actionform.getAttribute('data-variable');
            // console.log(`perusahaan_id : ${perusahaan_id} | tahun : ${tahun} | jenis_anggaran : ${jenis_anggaran} | actionform : ${actionform}`)
            
            //data program tpb
            let nama_program = document.getElementById('nama_program').value.trim()
            let tpb_id = document.getElementById('tpb_id').value
            let unit_owner = document.getElementById('unit_owner').value
            const kriteria_program_checkboxes = document.getElementsByName("kriteria_program"); // mengambil semua checkbox dengan name="kriteria_program"
            const selectedKriteriaProgram = []; // deklarasi array untuk menyimpan nilai dari checkbox yang dipilih

            for (let i = 0; i < kriteria_program_checkboxes.length; i++) { // iterasi semua checkbox
                if (kriteria_program_checkboxes[i].checked) { // jika checkbox terpilih
                    selectedKriteriaProgram.push(kriteria_program_checkboxes[i].value); // tambahkan nilai checkbox ke dalam array
                }
            }

            let core_subject_id = document.getElementById('core_subject_id').value
            let pelaksanaan_program = document.getElementById('pelaksanaan_program').value
            let mitra_bumn = document.getElementById('mitra_bumn').value
            let program_multiyears =  document.querySelector('input[name="program"]:checked') ? document.querySelector('input[name="program"]:checked').value : ''
            let alokasi_anggaran = document.getElementById('alokasi_anggaran').value
            alokasi_anggaran = alokasi_anggaran ? parseInt(alokasi_anggaran.replace(/[^0-9\-]/g, '')) : ''

            let data = {
                nama_program : nama_program,
                tpb_id : tpb_id,
                unit_owner : unit_owner,
                kriteria_program : selectedKriteriaProgram,
                core_subject_id : core_subject_id,
                pelaksanaan_program : pelaksanaan_program,
                mitra_bumn : mitra_bumn,
                program_multiyears : program_multiyears,
                alokasi_anggaran : alokasi_anggaran
            }
            
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

            await $.ajax({
                url: '/rencana_kerja/program/store',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    data: data,
                    tahun: tahun,
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

                    swal.fire({                    
                        icon: 'success',
                        title: 'Sukses!',
                        html: 'Berhasil menyimpan data',
                        type: 'success', 
                        confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                    })

                    console.log(`success : ${response}`)
                    window.location.reload();
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
            if(data.nama_program === '') return {status: false, message: 'Nama program harus terisi!'}
            if(data.tpb_id === '') return {status: false, message: 'TPB harus terisi!'}
            if(data.kriteria_program.length === 0) return {status: false, message: 'Pilihan kriteria program harus terisi minimal 1!'}
            if(data.core_subject_id === '') return {status: false, message: 'ID core subject harus terisi!'}
            if(data.pelaksanaan_program === '') return {status: false, message: 'Pelaksanaan program harus terisi!'}
            if(data.program_multiyears === '') return {status: false, message: 'Opsi Multiyears harus terisi!'}
            if(data.alokasi_anggaran === '' ) return {status: false, message: 'Alokasi anggaran harus terisi!'}

            if(data.pelaksanaan_program.toLowerCase() === 'mandiri' && data.mitra_bumn != '') return {status: false, message: 'Jika pelaksanaan program = Mandiri, isian Mitra BUMN harus kosong/tidak terisi!'}
            return {status: true}
        }

        function deleteSelectedProgram(selectedProgram) {
            const jumlahDataDeleted = selectedProgram.length
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
                    url: urldelete,
                    data:{
                        "program_deleted": selectedProgram
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
