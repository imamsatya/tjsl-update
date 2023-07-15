@extends('layouts.app')

@section('content')
    <div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="card">

                <!--begin::Card header-->
                <div class="card-header pt-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="d-flex align-items-center">Filter Data
                            <span class="text-gray-600 fs-6 ms-1"></span>
                        </h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Search-->
                        <button type="button" class="btn btn-warning btn-sm" style="margin-right: 5px" id="cls-add-master-referensi">Tambah Master Referensi</button> 
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
                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-3 col-form-label fw-semibold fs-6">Tipe</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-9 fv-row">
                                            <select class="form-select form-select-solid form-select2" id="tipe" name="tipe" data-kt-select2="true" data-placeholder="Pilih Tipe" data-allow-clear="true">
                                                <option></option>
                                                @foreach($master_referensi as $mp)
                                                    @php
                                                    $select = (($mp->id == $referensi_selected) ? 'selected="selected"' : '');
                                                    @endphp
                                                    <option value="{{ $mp->id }}" {!! $select !!}>{{$mp->deskripsi}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>

                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-3 col-form-label fw-semibold fs-6">Tahun</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-9 fv-row">
                                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" >
                                                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                                    @php
                                                        $select = (($i == $tahun) ? 'selected="selected"' : '');
                                                    @endphp
                                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                                @php } @endphp
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-3 col-form-label fw-semibold fs-6">BUMN</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-9 fv-row">
                                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" data-allow-clear="true">
                                                <option></option>
                                                @foreach($perusahaan as $p)  
                                                    @php
                                                        $select = (($p->id == $perusahaan_id) ? 'selected="selected"' : '');
                                                    @endphp
                                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <br>
                            <button id="proses" class="btn btn-primary">Filter</button>
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
                        <h2 class="d-flex align-items-center">Daftar Enable Input
                            <span class="text-gray-600 fs-6 ms-1"></span>
                        </h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1"
                            data-kt-view-roles-table-toolbar="base">
                            <button type="button" class="btn btn-primary btn-sm" style="margin-right: 5px" id="cls-add-data">Input Data</button> 
                            <button type="button" class="btn btn-danger btn-sm delete-selected-data">Hapus Data
                            </button>
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
                        <!--begin: Datatable -->
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Perusahaan</th>
                                    <th>Tahun</th>
                                    <th>Date Created</th>
                                    <th><label
                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                                class="form-check-input addCheck" type="checkbox"
                                                id="select-all"></label>
                                    </th>
                                    <th>Enable</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_enable as $index => $data) 
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->deskripsi }} ({{ $data->route_name }})</td>
                                    <td>{{ $data->nama_lengkap }}</td>
                                    <td>{{ $data->tahun }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>
                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                            <input class="form-check-input is_active-check" data-enable="{{ $data->id }}" type="checkbox">
                                        </label>
                                    </td>
                                    <td>{{ $data->id }}</td>
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
        var urlcreate = "{{ route('referensi.enable_input.create') }}";
        var urldelete = "{{ route('referensi.enable_input.delete') }}";
        var urlcreatemsater = "{{ route('referensi.enable_input.create_master') }}";
        

        $(document).ready(function() {
            var datatable = $('#datatable').DataTable();
            datatable.columns(6).visible(false);

            var selectedCheckboxes = [];

            $('#page-title').html("{{ $pagetitle }}");
            $('#page-breadcrumb').html("{{ $breadcrumb }}");

            $("#cls-add-data").click(function(){
                winform(urlcreate, {
                    'tahun': $("#tahun").val(),
                    'perusahaan_id': $("#perusahaan_id").val(),
                    'referensi': $("#tipe").val()
                }, 'Tambah Data');
            })

            $("#proses").on('click', function() {
                var url = window.location.origin + window.location.pathname;
                var perusahaan_id = $('#perusahaan_id').val();
                var tahun = $('#tahun').val();
                var tipe = $("#tipe").val();

                window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun + '&referensi=' + tipe;
            });

            $("#select-all").on('change', function(){

                var isChecked = $(this).is(':checked');
                $('.is_active-check').prop('checked', isChecked);

                if (isChecked) {
                    selectedCheckboxes = datatable.rows().data().map((data) => parseInt(data[6])).toArray();
                } else {
                    selectedCheckboxes = [];
                }

            })
            
            $('#datatable').on('change', '.is_active-check', function() {
                var checkboxValue = $(this).data('enable');

                // Update selected checkboxes array
                if ($(this).is(':checked')) {
                    selectedCheckboxes.push(checkboxValue);
                } else {
                    var index = selectedCheckboxes.indexOf(checkboxValue);
                    if (index !== -1) {
                        selectedCheckboxes.splice(index, 1);
                    }
                }

                // Check/uncheck "Select All" checkbox based on the selection
                var selectAllCheckbox = $('#select-all');
                selectAllCheckbox.prop('checked', selectedCheckboxes.length === datatable.rows().count());
            });


            $('body').on('click', '.delete-selected-data', function() {
                if(selectedCheckboxes.length == 0) {
                    swal.fire({
                        text: 'Tidak ada data terpilih untuk dihapus',
                        title: 'Warning',
                        icon: 'warning'
                    })
                    return
                }

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Apakah anda yakin akang menghapus data yang sudah dipilih?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.blockUI({
                            theme: true,
                            baseZ: 2000
                        })
                        $.ajax({
                            url: urldelete,
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                selectedData: selectedCheckboxes
                            },
                            success: function(data) {
                                $.unblockUI();
                                swal.fire({
                                    title: data.title,
                                    html: data.msg,
                                    icon: data.flag,
                                    buttonsStyling: true,
                                    confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                                });

                                if (data.flag == 'success') {
                                    $('#winform').modal('hide');
                                    location.reload();
                                }
                                
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(errorThrown);
                            }
                        });
                    }
                })


            });

            $("#cls-add-master-referensi").on('click', function() {
                winform(urlcreatemsater, {}, 'Tambah Master Referensi');                
            })

        });

        
    </script>
@endsection
