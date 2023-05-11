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
                            {{-- {{ $pagetitle }}  --}}
                            Data Program TPB
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
                        <div class="col-lg-4 mb-20">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="select-tahun" name="tahun" data-kt-select2="true" >
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
                            <select id="bulan_id" class="form-select form-select-solid form-select2" name="bulan_id" data-kt-select2="true"  data-placeholder="Pilih Bulan" data-allow-clear="true">
                                <option></option>
                                @foreach($bulan as $bulan_row)  
                                    {{-- @php
                                        $select = (($p->no_tpb == $tpb_id) ? 'selected="selected"' : '');
                                    @endphp --}}
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
                                        <div class="ms-2 required">Program???</div>


                                    </div>
                                    <div class="col-lg-9">
                                        <select  id="program_id" class="form-select form-select-solid form-select2" name="program_id" data-kt-select2="true" data-placeholder="Pilih Program" data-allow-clear="true">
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
                                        <div class="ms-2 required">Nama Kegiatan</div>


                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a comment here" id="unit_owner" name="unit_owner" style="height: 100px"></textarea>
                                            <label for="unit_owner">Nama Kegiatan</label>
                                        </div>

                                    </div>
                                
                                </div>
                                <div class="row mb-6">
                                    <div class="col-lg-3 ">
                                        <div class="ms-2 required">Jenis Kegiatan</div>
                                    </div>
                                    <div class="col-lg-9">
                                        <select  id="jenis_kegiatan" class="form-select form-select-solid form-select2" name="jenis_kegiatan" data-kt-select2="true" data-placeholder="Pilih Jenis Kegiatan" data-allow-clear="true">
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
                                        <div class="ms-2">Keterangan Kegiatan</div>
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="text" name="keterangan_kegiatan" id="keterangan_kegiatan"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="Keterangan Singkat"  
                                            />
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
                                        <select  id="satuan_ukur" class="form-select form-select-solid form-select2" name="satuan_ukur" data-kt-select2="true" data-placeholder="Pilih Satuan Ukur" data-allow-clear="true">
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
                                <button id="proses" class="btn btn-danger me-3">Close</button>
                                <button id="clear-btn" class="btn btn-info me-3">Clear</button>
                                <button id="simpan-btn" class="btn btn-success me-3">Simpan</button>
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
        $(document).ready(function() {
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
            $("#jenis-anggaran").on('change', function(){
                // yovi
                const jenisAnggaran = $(this).val()
                $("#tpb_id, #pilar_pembangunan_id").val('').trigger('change')
                
                
                $("#tpb_id, #pilar_pembangunan_id").select2({    
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
                $("#select2-pilar_pembangunan_id-container .select2-selection__placeholder").text('Pilih Pilar '+textAnggaran)
                $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB '+textAnggaran)

              
            
            })
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
       
        });

       

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
            
            var perusahaan_id = document.getElementById('perusahaan_id').value;
            // var perusahaan_id = perusahaan_id.getAttribute('data-variable');

            var tahun = document.getElementById('select-tahun').value;
            // var tahun = tahun.getAttribute('data-variable');

            var jenis_anggaran = document.getElementById('jenis-anggaran').value

             var actionform = document.getElementById('actionform');
            var actionform = actionform.getAttribute('data-variable');
            console.log(`perusahaan_id : ${perusahaan_id} | tahun : ${tahun} | jenis_anggaran : ${jenis_anggaran} | actionform : ${actionform}`)
            //data program tpb
            let nama_program = document.getElementById('nama_program').value
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
            let program_multiyears =  document.querySelector('input[name="program"]:checked').value
            let alokasi_anggaran = document.getElementById('alokasi_anggaran').value
            alokasi_anggaran = parseInt(alokasi_anggaran.replace(/[^0-9\-]/g, ''))
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
            console.log('data', data)
           
            console.log(actionform)
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
                success: function(response) {
                    
                    console.log(`success : ${response}`)
                    toastr.success(
                        `Berhasil!`
                    );
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


        

        
    </script>
@endsection