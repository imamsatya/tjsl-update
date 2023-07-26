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
    <div style="display: none" id="perusahaan_id" data-variable="{{ $perusahaan_id }}"></div>
    <div style="display: none" id="tahun" data-variable="{{ $tahun }}"></div>
    <div style="display: none" id="actionform" data-variable="{{ $actionform }}"></div>
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
                            Input Data RKA per TPB
                            <span class="text-gray-600 fs-6 ms-1">- {{ $nama_perusahaan }}</span>
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
                        @if(!$isOkToInput && !$isEnableInputBySuperadmin && !$isSuperAdmin)                       
                        <!--begin::Alert-->
                        <div class="alert alert-danger d-flex align-items-center p-5" style="    border-radius: 0.5em;background-color: #fff5f8;color: #f1416c;border-color: #f1416c">
                            <!--begin::Icon-->
                            <i class=" bi-shield-fill-x fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                            <!--end::Icon-->

                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <!--begin::Title-->
                                <h4 class="mb-1 text-danger">PENGUMUMAN</h4>
                                <!--end::Title-->

                                <!--begin::Content-->
                                <span>Tidak bisa input data karena diluar periode laporan!</span>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Alert-->
                        @endif
                        @if($isFinish)                       
                        <!--begin::Alert-->
                        <div class="alert alert-success d-flex align-items-center p-5" style="border-radius: 0.5em;background-color: #e8fff3;color: #50cd89;border-color: #50cd89">
                            <!--begin::Icon-->
                            <i class=" bi-shield-fill-check fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                            <!--end::Icon-->

                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <!--begin::Title-->
                                <h4 class="mb-1 text-success">PENGUMUMAN</h4>
                                <!--end::Title-->

                                <!--begin::Content-->
                                <span>Data sudah diverifikasi!</span>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Alert-->
                        @endif
                        @if ($errors->any())
                    <!-- <div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10">

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
                        

                        <div class="d-flex flex-column text-white pe-0 pe-sm-10">
                            <h4 class="mb-2 text-white">Error !</h4>

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

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
                    </div> -->
                @endif
                @if (\Session::has('success'))
                    <!-- <div class="alert alert-dismissible bg-success d-flex flex-column flex-sm-row p-5 mb-10">

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

                        <div class="d-flex flex-column text-white pe-0 pe-sm-10">
                            <h4 class="mb-2 text-white">Sukses !</h4>

                            <span>{{ Session::get('success') }}</span>
                        </div>

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
                    </div> -->
                @endif
                        <div class="col-lg-3 mb-20">
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

                        <form method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    {{-- <h1>Pilar - TPB</h1> --}}

                                </div>
                                <div class="col-lg-3">
                                    <h1>Anggaran CID</h1>

                                </div>
                                <div class="col-lg-3">
                                    <h1>Anggaran non CID</h1>

                                </div>
                            </div>
                            <!--begin::Accordion-->
                            <div class="accordion accordion-icon-collapse" id="kt_accordion_4">
                                <?php $i = 0;
                                $j = 0; ?>
                                @foreach ($dataInput as $index => $tpbs)
                                    {{-- {{ $i }} --}}
                                    <!--begin::Item-->
                                    <div class="mb-5">
                                        <!--begin::Header-->
                                        <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_4_item_{{$i}}">
                                            
                                            <div class="row mb-4" style="width: 100%">
                                                <div class="col-1" style="text-align: center">
                                                    <span class="accordion-icon">
                                                        <i class="bi-duotone bi-plus-square fs-3 accordion-icon-on d-none"></i>
                                                        <i class="bi-duotone bi-dash-square fs-3 accordion-icon-off"></i>
                                                    </span>
                                                </div>
                                                <div class="col-lg-5" style="padding-left:0">
                                                    <h3>{{ $index }}</h3>
                                                </div>
                                                <div class="col-lg-3">
                                                    <input style="background-color: #d8efe2; color: #01863e; text-align:right; font-weight: bold" type="text" name="cid_total[{{ $i }}]" disabled
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="Rp ... (total)" value="" />

                                                </div>
                                                <div class="col-lg-3">
                                                    <input style="background-color: #d8efe2; color: #01863e; text-align:right; font-weight: bold" type="text" disabled name="noncid_total[{{ $i }}]"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="Rp ... (total)" value="" />

                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Header-->

                                        <!--begin::Body-->
                                        <div id="kt_accordion_4_item_{{$i}}" class="fs-6 collapse show" >
                                            @foreach ($tpbs as $indexTPB => $tpb)
                                            {{-- {{ $j }} --}}
                                            <div class="row mb-4" style="width: 100%">
                                                <div class="col-1"></div>
                                                <div class="col-lg-5">
                                                    <!-- <div class="ms-8">{{ $indexTPB }}</div> -->
                                            {{ $indexTPB }}

                                                </div>
                                                <div class="col-lg-3">
                                                    @if (isset($tpb['CID']))
                                                        <input type="text" data-idrelasi="{{ $tpb['CID']['relasi_pilar_tpb_id'] }}" 
                                                            name="cid_tpb[{{ $i }}][{{ $j }}]" value="{{ $tpb['CID']['anggaran'] }}"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Rp ..." style="text-align:right;"
                                                            oninput="formatCurrency(this)"
                                                            onkeypress="return onlyNumbers(event)" />
                                                    @endif
                                                </div>
                                                <div class="col-lg-3">
                                                    @if (isset($tpb['non CID']))
                                                        <input type="text" data-idrelasi="{{ $tpb['non CID']['relasi_pilar_tpb_id'] }}" 
                                                            name="noncid_tpb[{{ $i }}][{{ $j }}]" value="{{ $tpb['non CID']['anggaran'] }}"
                                                            class="form-control form-control-lg form-control-solid"
                                                            placeholder="Rp ..." style="text-align:right;"
                                                            oninput="formatCurrency(this)"
                                                            onkeypress="return onlyNumbers(event)" />
                                                    @endif
                                                </div>
                                            </div>
                                            <?php $j++; ?>
                                        @endforeach
                                        <br><br>

                                        <?php $i++; ?>
                                        </div>
                                        <!--end::Body-->
                                    </div>
                                    <!--end::Item-->                                                                 
                                @endforeach                     
                            </div>
                            <!--end::Accordion-->
                            
                            <div class="row mb-9" style="width: 100%">
                                <div class="col-lg-6" style="text-align: center">
                                    <h2>Total Anggaran</h2>
                                </div>
                                <div class="col-lg-3">
                                    <input style="background-color: #d8efe2; color: #01863e; text-align: right; font-weight: bold" type="text"name="cid_grand_total"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... (grand total)" disabled value=""
                                        onchange="formatCurrency(this)" style="text-align:right;" />

                                </div>
                                <div class="col-lg-3">
                                    <input style="background-color: #d8efe2; color: #01863e; text-align: right; font-weight: bold" type="text"name="noncid_grand_total"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... (grand total)" disabled value=""
                                        onchange="formatCurrency(this)" style="text-align:right;" />

                                </div>
                            </div>


                        </form>
                        <div class="form-group row mt-2 mb-5 text-end">
                            <div class="col-lg-12">   
                                <a id="close-btn" href="javascript:void(0);" class="btn btn-light-danger font-weight-bold me-3"><i class="bi bi-x-circle-fill"></i> Close</a>
                                @if($isOkToInput || $isEnableInputBySuperadmin || $isSuperAdmin)
                                @if(!$isFinish)
                                <a id="clear-btn" href="javascript:void(0)" class="btn btn-light-info font-weight-bold me-3"><i class="bi bi-trash-fill"></i> Clear</a>
                                <button id="simpan-btn" class="btn btn-success font-weight-bold me-3"><i class="bi bi-save-fill"></i> Simpan</button>                                
                                @endif
                                @endif
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
            
            const isFinish = "{{ $isFinish }}"
            const okToInput = "{{ $isOkToInput }}"
            const isEnableInputBySuperadmin = parseInt("{{ $isEnableInputBySuperadmin }}")
            const isSuperAdmin = "{{ $isSuperAdmin }}"
            // $('input[name^="cid_tpb"]').on('input', function() {
            //     $(this).val($(this).val().replace(/[^0-9]/g, ''));


            // });
            // $('input[name^="noncid_tpb"]').on('input', function() {
            //     $(this).val($(this).val().replace(/[^0-9]/g, ''));
            // });

            $(".accordion-header").click(function () {
                $(this).find(".accordion-icon-off").toggleClass("d-none");
                $(this).find(".accordion-icon-on").toggleClass("d-none");
            });
            
            $("#close-btn").on('click', function(e) {                
                var url = window.location.pathname;
                var segments = url.split('/');                
                let routeTo = "{{route('anggaran_tpb.rka')}}"+"?perusahaan_id="+segments[4]+"&tahun="+segments[5] 
                window.location.href = routeTo
            })

            $("#select-tahun").on('change', function() {
                const yearSelected = $(this).val()
                let currentUrl = window.location.href
                currentUrl = currentUrl.substr(0, currentUrl.length - 4) + yearSelected;
                window.location.href = currentUrl
            })

            var formChanged = false

            // Add event listener to form inputs
            const formInputs = document.querySelectorAll('form input, form textarea');
            formInputs.forEach(input => {
                input.addEventListener('input', () => {
                    formChanged = true;
                });
            });
            

            // Add event listener to form submit
            const form = document.querySelector('form');
            form.addEventListener('submit', () => {
                formChanged = false;
            });

            // Add event listener to window unload
            // window.addEventListener('beforeunload', (event) => {
            //     if (formChanged) {
            //         event.preventDefault();
            //         event.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            //     }
            // });

            if(!isFinish && (okToInput || isEnableInputBySuperadmin || isSuperAdmin)) {                
                const clearBtn = document.querySelector("#clear-btn");
                clearBtn.addEventListener("click", function() {                    
                    const inputFields = document.querySelectorAll("input[type='text']");
                    inputFields.forEach(function(input) {
                        input.value = null;
                    });
                });

                const simpanBtn = document.querySelector("#simpan-btn");
                simpanBtn.addEventListener("click", async function() {
                    console.log('simpan clicked')
                    const tpbs_value = [];
                    const cidTpbFields = document.querySelectorAll('input[name^="cid_tpb"]');
                    cidTpbFields.forEach(function(cidTpbField) {
                        let valueField = cidTpbField.value;
                        let num;
                        if (valueField.length > 0) {
                            num = parseInt(valueField.replace(/[^0-9]+/g, ""), 10);
                        } else {
                            num = null;
                        }
                        const object = {
                            idrelasi: cidTpbField.dataset.idrelasi,
                            value: num
                        };
                        tpbs_value.push(object);

                    })

                    const noncidTpbFields = document.querySelectorAll('input[name^="noncid_tpb"]');
                    noncidTpbFields.forEach(function(noncidTpbField) {
                        let valueField = noncidTpbField.value;
                        let num;
                        if (valueField.length > 0) {
                            num = parseInt(valueField.replace(/[^0-9]+/g, ""), 10);
                        } else {
                            num = null;
                        }
                        const object = {
                            idrelasi: noncidTpbField.dataset.idrelasi,
                            value: num
                        };
                        tpbs_value.push(object);

                    })
                    console.log(tpbs_value)
                    var perusahaan_id = document.getElementById('perusahaan_id');
                    var perusahaan_id = perusahaan_id.getAttribute('data-variable');

                    var tahun = document.getElementById('tahun');
                    var tahun = tahun.getAttribute('data-variable');

                    var actionform = document.getElementById('actionform');
                    var actionform = actionform.getAttribute('data-variable');
                    console.log(actionform)
                    await $.ajax({
                        url: '/anggaran_tpb/store2',
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            tpbs_value: tpbs_value,
                            tahun: tahun,
                            perusahaan_id: perusahaan_id,
                            actionform: actionform
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
                            }).then(function(){
                                $('html, body').animate({ scrollTop: 0 }, 'slow');
                                formChanged = false;
                            })

                            var url = window.location.pathname;
                            var segments = url.split('/');
                            console.log(segments)
                            let routeTo = "{{route('anggaran_tpb.rka')}}"+"?perusahaan_id="+segments[4]+"&tahun="+segments[5]
                            window.location.href = routeTo
                            
                                                
                            // window.location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                            $.unblockUI();
                            swal.fire({                    
                                icon: 'error',
                                title: 'Error!',
                                html: 'Terdapat kesalahan pada server. Coba kembali / refresh halaman ini!',
                                type: 'error', 
                                confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                            });
                        }
                    });
                });
            }


            // const selectElement = document.getElementById('select-tahun');
            // selectElement.addEventListener('change', function(event) {
            //     const selectedOption = event.target.value;
            //     console.log(selectedOption)
            // // call your function here, passing in the selectedOption value as an argument
            // });




            //CID
            // Select all cid_tpb input fields
            const cidTpbFields = document.querySelectorAll('input[name^="cid_tpb"]');

            // For each cid_tpb field, add an event listener to update the corresponding cid_total field
            cidTpbFields.forEach(function(cidTpbField) {
                cidTpbField.addEventListener('input', function() {
                    // Get the index of the current cid_tpb field
                    const fieldIndex = cidTpbField.getAttribute('name').match(/\[(\d+)\]\[(\d+)\]/);
                    const pillarIndex = fieldIndex[1];
                    const tpbIndex = fieldIndex[2];

                    // Get the corresponding cid_total and noncid_total fields
                    const cidTotalField = document.querySelector(`input[name="cid_total[${pillarIndex}]"]`);

                    // Calculate the total for the current pillar and update the cid_total and noncid_total fields
                    let cidTotal = 0;

                    const tpbFields = document.querySelectorAll(`input[name^="cid_tpb[${pillarIndex}]"]`);
                    tpbFields.forEach(function(tpbField) {
                        if (tpbField.value) {
                            let value = tpbField.value.replace(/[^\d]/g, ""); // remove any non-numeric characters from the input value
                            if (tpbField.getAttribute('name').includes('cid')) {
                                cidTotal += parseInt(value);
                            }
                        }
                    });

                    cidTotalField.value = cidTotal;

                    // Update the cid_grand_total field
                    const cidGrandTotalField = document.querySelector('input[name="cid_grand_total"]');
                    let cidGrandTotal = 0;

                    const cidTotalFields = document.querySelectorAll('input[name^="cid_total"]');
                    cidTotalFields.forEach(function(cidTotalField) {
                        if (cidTotalField.value) {
                            let value = cidTotalField.value.replace(/[^\d]/g, ""); // remove any non-numeric characters from the input value
                            cidGrandTotal += parseInt(value) || 0;
                        }
                    });

                    cidGrandTotalField.value = cidGrandTotal;
                    formatCurrency(cidTotalField)
                    formatCurrency(cidGrandTotalField)
                });

                // Trigger the 'input' event manually on each cid_tpb field to update the corresponding cid_total field
                cidTpbField.dispatchEvent(new Event('input'));
            });

            //noncid
            // Select all noncid_tpb input fields
            const noncidTpbFields = document.querySelectorAll('input[name^="noncid_tpb"]');
            // For each noncid_tpb field, add an event listener to update the corresponding noncid_total field
            noncidTpbFields.forEach(function(noncidTpbField) {
                noncidTpbField.addEventListener('input', function() {
                    // Get the index of the current cid_tpb field
                    const fieldIndex = noncidTpbField.getAttribute('name').match(/\[(\d+)\]\[(\d+)\]/);
                    const pillarIndex = fieldIndex[1];
                    const tpbIndex = fieldIndex[2];

                    // Get the corresponding cid_total and noncid_total fields
                    const noncidTotalField = document.querySelector(
                        `input[name="noncid_total[${pillarIndex}]"]`);


                    // Calculate the total for the current pillar and update the cid_total and noncid_total fields
                    let noncidTotal = 0;

                    const tpbFields = document.querySelectorAll(`input[name^="noncid_tpb[${pillarIndex}]"]`);
                    tpbFields.forEach(function(tpbField) {
                        if (tpbField.value) {
                            let value = tpbField.value.replace(/[^\d]/g,
                                ""); // remove any non-numeric characters from the input value
                            if (tpbField.getAttribute('name').includes('noncid')) {
                                noncidTotal += parseInt(value);
                            }
                        }
                    });   
                    
                    noncidTotalField.value = noncidTotal;


                    // Update the cid_grand_total field
                    const noncidGrandTotalField = document.querySelector('input[name="noncid_grand_total"]');
                    let noncidGrandTotal = 0;
                    const noncidTotalFields = document.querySelectorAll('input[name^="noncid_total"]');
                    noncidTotalFields.forEach(function(noncidTotalField) {
                        
                        if (noncidTotalField.value) {
                            let value = noncidTotalField.value.replace(/[^\d]/g,
                                ""); // remove any non-numeric characters from the input value
                            noncidGrandTotal += parseInt(value) || 0;
                        }
                    });
                    noncidGrandTotalField.value = noncidGrandTotal;
                    formatCurrency(noncidTotalField)
                    formatCurrency(noncidGrandTotalField)
                });
                // Trigger the 'input' event manually on each cid_tpb field to update the corresponding cid_total field
                noncidTpbField.dispatchEvent(new Event('input'));
            });

            
        });

        function formatCurrency(element) {
            const value = element.value.replace(/[^\d]/g, "");
            const formatter = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            const formattedValue = formatter.format(value);
            // element.value = formattedValue.replace(/,/g, ".");
             element.value = value ? formattedValue.replace(/,/g, ".") : null;
        }

        function onlyNumbers(event) {
            const key = event.keyCode || event.which;
            if (key < 48 || key > 57) {
                event.preventDefault();
            }
        }

    </script>
@endsection
