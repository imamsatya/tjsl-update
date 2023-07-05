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
    <div id="perusahaan_id" data-variable="{{ $perusahaan_id }}"></div>
    <div id="tahun" data-variable="{{ $tahun }}"></div>
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
                            Sumber dan Penggunaan Dana PUMK - RKA
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
                    <div class="col-lg-6 mb-20">
                        <label>BUMN</label>
                        @php
                        $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                    @endphp
                    <select class="form-select form-select-solid form-select2" id="select-perusahaan" name="select-perusahaan" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
                        <option></option>
                        @foreach($perusahaan as $p)  
                            @php
                                $select = (($p->id == $perusahaan_id) ? 'selected="selected"' : '');
                            @endphp
                            <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    </div>
                        <div class="col-lg-6 mb-20">
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
                    </div>
                        <form method="POST">
                            @csrf
                        <div class="mb-8">
                            <div class="row mb-4">
                                
                                <div class="col-lg-6">
                                    <h1>Dana Tersedia</h1>

                                </div>
                                
                            </div>
                            <hr> 
                            <div class="row mb-4 mt-8">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Saldo Awal</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="saldo_awal"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->saldo_awal ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Pengembalian Dana dari Mitra Binaan</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="pengembalian_dana_mitra_binaan"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->income_mitra_binaan ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Pengembalian Dana dari BUMN Penyalur</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="pengembalian_dana_bumn_penyalur"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->income_bumn_pembina_lain ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Pendapatan Jasa Admin PUMK</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="pendapatan_jasa_admin_pumk"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->income_jasa_adm_pumk ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Pendapatan Jasa Bank</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="pendapatan_jasa_bank"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->income_adm_bank ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Pendapatan (Biaya) lainnya</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="pendapatan_biaya_lainnya"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->income_biaya_lainnya ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-9">
                                <div class="col-lg-6">
                                    <h2>Total Dana Tersedia</h2>


                                </div>
                                <div class="col-lg-6">
                                    <input style="background-color: #d8efe2; color: #01863e; text-align:right; font-weight: bold" type="text" name="total_dana_tersedia" disabled
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... (total) "  value=""
                                        style="text-align:right;" />

                                </div>
                             
                            </div>
                        </div>
                        <div class="mb-8">
                            <div class="row mb-4">
                                
                                <div class="col-lg-6">
                                    <h1>Dana Disalurkan</h1>

                                </div>
                                
                            </div>
                            <hr> 
                            <div class="row mb-4 mt-8">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Penyaluran PUMK secara Mandiri</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="penyaluran_pumk_mandiri"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->outcome_mandiri ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Penyaluran PUMK secara Kolaborasi</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="penyaluran_pumk_kolaborasi"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->outcome_kolaborasi_bumn ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Penyaluran PUMK secara Khusus</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="penyaluran_pumk_khusus"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->outcome_bumn_khusus ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-6 ">
                                    <div class="ms-8">Penyaluran PUMK melalui BRI</div>


                                </div>
                                <div class="col-lg-6">
                                    <input type="text"name="penyaluran_pumk_bri"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... "  value="{{$data->outcome_bri   ?? ''}}"
                                       oninput="formatCurrency(this)" 
                                                        onkeypress="return onlyNumbers(event)" style="text-align:right;" />

                                </div>
                             
                            </div>
                            
                            
                            <div class="row mb-9">
                                <div class="col-lg-6">
                                    <h2>Total Dana Disalurkan</h2>


                                </div>
                                <div class="col-lg-6">
                                    <input style="background-color: #d8efe2; color: #01863e; text-align:right; font-weight: bold" type="text" name="total_dana_disalurkan" disabled
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... (total) "  value=""
                                        style="text-align:right;" />

                                </div>
                             
                            </div>
                        </div>

                        <div class="row mb-9">
                            <div class="col-lg-6">
                                <h2>Saldo Akhir</h2>


                            </div>
                            <div class="col-lg-6">
                                <input style="background-color: #d8efe2; color: #01863e; text-align:right; font-weight: bold" type="text" name="saldo_akhir" disabled
                                    class="form-control form-control-lg form-control-solid"
                                    placeholder="Rp ... (saldo akhir) "  value=""
                                    style="text-align:right;" />

                            </div>
                         
                        </div>


                        </form>
                        <div class="form-group row mt-2  mb-5 text-end">
                            <div class="col-lg-12">
                                <button id="close-btn" class="btn btn-danger me-3">Close</button>
                                <button id="clear-btn" class="btn btn-info me-3">Clear</button>
                                <button id="{{$periode->isoktoinput || $isOkToInput ? 'simpan-btn' : ''}}" {{$periode->isoktoinput || $isOkToInput ? '' : 'disabled'}} class="btn btn-success me-3">Simpan</button>
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
    <script type="text/javascript" src="{{ asset('plugins/jquery-treegrid-master/js/jquery.treegrid.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#close-btn").on('click', function() {
                var url = window.location.pathname;
                var segments = url.split('/');
                console.log(segments)
                let routeTo = "{{route('rencana_kerja.spdpumk_rka.index')}}"+"?perusahaan_id="+segments[4]+"&tahun="+segments[5]                
                window.location.href = routeTo
            })

            $("#select-perusahaan").on('change', function() {
                const perusahaanSelected = $(this).val()
                let currentUrl = window.location.href

                
                // Escape any special characters in perusahaanSelected to avoid issues with regex
                const escapedPerusahaanSelected = perusahaanSelected.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

                // Create a regular expression to match the "/1/" part in the URL
                const regex = new RegExp(/\/\d+\//);

                // Replace the matched part with the value of perusahaanSelected
                let updatedUrl = currentUrl.replace(regex, `/${escapedPerusahaanSelected}/`);
                // console.log(perusahaanSelected)
                // console.log(currentUrl)
                // console.log(updatedUrl)
                // currentUrl = currentUrl.substr(0, currentUrl.length - 4) + yearSelected;
                window.location.href = updatedUrl
            })

            $("#select-tahun").on('change', function() {
                const yearSelected = $(this).val()
                let currentUrl = window.location.href
                currentUrl = currentUrl.substr(0, currentUrl.length - 4) + yearSelected;
                window.location.href = currentUrl
            })
        });

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
            // element.value = value ? formattedValue : null;
            
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
        const clearBtn = document.querySelector("#clear-btn");
        clearBtn.addEventListener("click", function() {
            const inputFields = document.querySelectorAll("input[type='text']");
            inputFields.forEach(function(input) {
                input.value = null;
            });
        });
        //total dana tersedia
        const saldo_awal_input = document.querySelector('input[name="saldo_awal"]');
        const pengembalian_mitra_binaan_input = document.querySelector('input[name="pengembalian_dana_mitra_binaan"]');
        const pengembalian_bumn_penyalur_input = document.querySelector('input[name="pengembalian_dana_bumn_penyalur"]');
        const pendapatan_jasa_admin_pumk_input = document.querySelector('input[name="pendapatan_jasa_admin_pumk"]');
        const pendapatan_jasa_bank_input = document.querySelector('input[name="pendapatan_jasa_bank"]');
        const pendapatan_biaya_lainnya_input = document.querySelector('input[name="pendapatan_biaya_lainnya"]');
        const total_dana_tersedia_input = document.querySelector('input[name="total_dana_tersedia"]');

        //total dana disalurkan
        const penyaluran_pumk_mandiri_input = document.querySelector('input[name="penyaluran_pumk_mandiri"]');
        const penyaluran_pumk_kolaborasi_input = document.querySelector('input[name="penyaluran_pumk_kolaborasi"]');
        const penyaluran_pumk_khusus_input = document.querySelector('input[name="penyaluran_pumk_khusus"]');
        const penyaluran_pumk_bri_input = document.querySelector('input[name="penyaluran_pumk_bri"]');
        const total_dana_disalurkan_input = document.querySelector('input[name="total_dana_disalurkan"]');

        //saldo akhir
        const saldo_akhir_input = document.querySelector('input[name="saldo_akhir"]');

        [saldo_awal_input, pengembalian_mitra_binaan_input, pengembalian_bumn_penyalur_input, pendapatan_jasa_admin_pumk_input, pendapatan_jasa_bank_input, pendapatan_biaya_lainnya_input,
        penyaluran_pumk_mandiri_input, penyaluran_pumk_kolaborasi_input, penyaluran_pumk_khusus_input, penyaluran_pumk_bri_input].forEach(input => {
                input.addEventListener('input', () => {
                    // Get the values of all the input fields and sum them up
                    console.log('saldo_awal_input',saldo_awal_input)
                    const saldo_awal = parseInt(saldo_awal_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const pengembalian_mitra_binaan = parseInt(pengembalian_mitra_binaan_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const pengembalian_bumn_penyalur = parseInt(pengembalian_bumn_penyalur_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const pendapatan_jasa_admin_pumk = parseInt(pendapatan_jasa_admin_pumk_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const pendapatan_jasa_bank = parseInt(pendapatan_jasa_bank_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const pendapatan_biaya_lainnya = parseInt(pendapatan_biaya_lainnya_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const total_dana_tersedia = saldo_awal + pengembalian_mitra_binaan + pengembalian_bumn_penyalur + pendapatan_jasa_admin_pumk + pendapatan_jasa_bank + pendapatan_biaya_lainnya;
                    console.log('saldo awal', saldo_awal)
                    // Update the value of the total_dana_tersedia input field
                    total_dana_tersedia_input.value = total_dana_tersedia;
                    


                    const penyaluran_pumk_mandiri = parseInt(penyaluran_pumk_mandiri_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const penyaluran_pumk_kolaborasi = parseInt(penyaluran_pumk_kolaborasi_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const penyaluran_pumk_khusus = parseInt(penyaluran_pumk_khusus_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    const penyaluran_pumk_bri = parseInt(penyaluran_pumk_bri_input.value.replace(/[^0-9\-]/g, "")) || 0;
                    
                    const total_dana_disalurkan = penyaluran_pumk_mandiri + penyaluran_pumk_kolaborasi + penyaluran_pumk_khusus + penyaluran_pumk_bri;
                    // Update the value of the total_dana_tersedia input field
                    total_dana_disalurkan_input.value = total_dana_disalurkan;

                    saldo_akhir_input.value = (parseInt(total_dana_tersedia_input.value) || 0 ) - ( parseInt(total_dana_disalurkan_input.value) || 0 )
                   
                    formatCurrency2(total_dana_tersedia_input);
                    formatCurrency2(total_dana_disalurkan_input);
                    formatCurrency2(saldo_akhir_input)
                   
                   
                  
                });
                input.dispatchEvent(new Event('input'));
            });

        const simpanBtn = document.querySelector("#simpan-btn");
        simpanBtn.addEventListener("click", async function() {
            console.log('simpan clicked')
            
            var perusahaan_id = document.getElementById('perusahaan_id');
            var perusahaan_id = perusahaan_id.getAttribute('data-variable');

            var tahun = document.getElementById('tahun');
            var tahun = tahun.getAttribute('data-variable');

             var actionform = document.getElementById('actionform');
            var actionform = actionform.getAttribute('data-variable');

            //total dana tersedia
            let saldo_awal =  parseInt(saldo_awal_input.value.replace(/[^0-9\-]/g, ''))
            let pengembalian_mitra_binaan =  parseInt(pengembalian_mitra_binaan_input.value.replace(/[^0-9\-]/g, ''))
            let pengembalian_bumn_penyalur =  parseInt(pengembalian_bumn_penyalur_input.value.replace(/[^0-9\-]/g, ''))
            let pendapatan_jasa_admin_pumk =  parseInt(pendapatan_jasa_admin_pumk_input.value.replace(/[^0-9\-]/g, ''))
            let pendapatan_jasa_bank =  parseInt(pendapatan_jasa_bank_input.value.replace(/[^0-9\-]/g, ''))
            let pendapatan_biaya_lainnya =  parseInt(pendapatan_biaya_lainnya_input.value.replace(/[^0-9\-]/g, ''))
            let total_dana_tersedia = saldo_awal + pengembalian_mitra_binaan + pengembalian_bumn_penyalur + pendapatan_jasa_admin_pumk + pendapatan_jasa_bank + pendapatan_biaya_lainnya
            //total dana disalurkan
            let penyaluran_pumk_mandiri =  parseInt(penyaluran_pumk_mandiri_input.value.replace(/[^0-9\-]/g, ''))  
            let penyaluran_pumk_kolaborasi =  parseInt(penyaluran_pumk_kolaborasi_input.value.replace(/[^0-9\-]/g, ''))  
            let penyaluran_pumk_khusus =  parseInt(penyaluran_pumk_khusus_input.value.replace(/[^0-9\-]/g, ''))  
            let penyaluran_pumk_bri =  parseInt(penyaluran_pumk_bri_input.value.replace(/[^0-9\-]/g, ''))  
            let total_dana_disalurkan = penyaluran_pumk_mandiri + penyaluran_pumk_kolaborasi + penyaluran_pumk_khusus + penyaluran_pumk_bri 
            //saldo akhir
            let saldo_akhir = total_dana_tersedia - total_dana_disalurkan

            let spdpumk_rka = {
                //total dana tersedia
                saldo_awal : saldo_awal,
                pengembalian_mitra_binaan : pengembalian_mitra_binaan,
                pengembalian_bumn_penyalur : pengembalian_bumn_penyalur,
                pendapatan_jasa_admin_pumk : pendapatan_jasa_admin_pumk, 
                pendapatan_jasa_bank : pendapatan_jasa_bank,
                pendapatan_biaya_lainnya : pendapatan_biaya_lainnya, 
                total_dana_tersedia : total_dana_tersedia,
                //total dana disalurkan
                penyaluran_pumk_mandiri : penyaluran_pumk_mandiri ,
                penyaluran_pumk_kolaborasi : penyaluran_pumk_kolaborasi ,
                penyaluran_pumk_khusus : penyaluran_pumk_khusus ,
                penyaluran_pumk_bri : penyaluran_pumk_bri ,
                total_dana_disalurkan : total_dana_disalurkan ,
                //saldo akhir
                saldo_akhir : saldo_akhir
                
            }
            console.log(spdpumk_rka)
           
            // console.log(actionform)
            await $.ajax({
                url: '/rencana_kerja/spdpumk_rka/store',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    spdpumk_rka: spdpumk_rka,
                    tahun: tahun,
                    perusahaan_id: perusahaan_id,
                    actionform: actionform
                },
                success: function(response) {
                    
                    console.log(`success : ${response}`)
                    toastr.success(
                        `Berhasil!`
                    );
                    // window.location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            });
        });


       

        

        
    </script>
@endsection
