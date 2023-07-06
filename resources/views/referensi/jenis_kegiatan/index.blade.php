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
                        <form action="{{ route('referensi.jenis_kegiatan.store') }}" method="POST">
                            @csrf
                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Nama
                                            Kegiatan</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
                                            <input type="text" name="nama_kegiatan"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Nama Kegiatan" value="" />

                                        </div>
                                        <!--end::Col-->
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 col-form-label  fw-semibold fs-6">Keterangan</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
                                            <input type="text" name="keterangan"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="Keterangan" value="" />

                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <br>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>
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
                            {{-- <button type="button" class="btn btn-success me-2 btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Simpan Status</button> --}}
                            {{-- <button type="button" class="btn btn-danger btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Hapus Data</button> --}}
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
                                    <th>Keterangan</th>


                                    <th>Aktif</th>
                                    <th style="text-align:center;">Aksi</th>
                                    <th><label
                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                                class="form-check-input addCheck" type="checkbox"
                                                id="select-all2"></label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <!--end::Card body-->
            </div>

            <br><br>
            {{-- Sub Kegiatan --}}
            <!--begin::Card-->
            <div class="card">

                <!--begin::Card header-->
                <div class="card-header pt-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="d-flex align-items-center">Sub Kegiatan
                            <span class="text-gray-600 fs-6 ms-1"></span>
                        </h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1"
                            data-kt-view-roles-table-toolbar="base">
                            {{-- <button type="button" class="btn btn-success me-2 btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Simpan Status</button> --}}
                            {{-- <button type="button" class="btn btn-danger btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Hapus Data</button> --}}
                            <button type="button" class="btn btn-danger btn-sm me-2 delete-selected-data2">Hapus Data
                            </button>
                            <button type="button" class="btn btn-primary cls-add btn-sm input-data me-2"
                                onclick="redirectToNewPage()">Input Data
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
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable2">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kategori</th>
                                    <th>Sub Kategori</th>
                                    <th>Satuan Ukur</th>
                                    <th>Aktif</th>
                                    <th style="text-align:center;">Aksi</th>
                                    <th><label
                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                                class="form-check-input addCheck2" type="checkbox"
                                                id="select-all2"></label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
        var datatable;
        var datatable2;
        var urlcreate = "{{ route('referensi.jenis_kegiatan.create') }}";
        var urledit = "{{ route('referensi.jenis_kegiatan.edit') }}";
        var urlstore = "{{ route('referensi.jenis_kegiatan.store') }}";
        var urlupdate = "{{ route('referensi.jenis_kegiatan.update') }}";
        var urldatatable = "{{ route('referensi.jenis_kegiatan.datatable') }}";
        var urldelete = "{{ route('referensi.jenis_kegiatan.delete') }}";

        //Sub Kegiatan
        var urlcreate_subkegiatan = "{{ route('referensi.jenis_kegiatan.create_subkegiatan') }}";
        var urlstore_subkegiatan = "{{ route('referensi.jenis_kegiatan.store_subkegiatan') }}";
        var urldatatable2 = "{{ route('referensi.jenis_kegiatan.datatable_subkegiatan') }}";
        $(document).ready(function() {
            $('#page-title').html("{{ $pagetitle }}");
            $('#page-breadcrumb').html("{{ $breadcrumb }}");

            $('body').on('click', '.cls-add', function() {
                winform(urlcreate_subkegiatan, {}, 'Tambah Data');
            });

            $('body').on('click', '.cls-button-edit', function() {
                winform(urledit, {
                    'id': $(this).data('id')
                }, 'Ubah Data');
            });

            $('body').on('click', '.cls-button-edit2', function() {
                winform(urlcreate_subkegiatan, {
                    'id': $(this).data('id'),
                    'actionform': 'update'
                }, 'Ubah Data');
            });

            $('body').on('click', '.cls-button-delete', function() {
                onbtndelete(this);
            });


            setDatatable();
            setDatatable2();

            //Imam
            // Add event listener for the "select all" checkbox in the table header
            $('#select-all').on('click', function() {
                // Get all checkboxes in the table body
                var checkboxes = $('.row-check');
                // Set the "checked" property of all checkboxes to the same as the "checked" property of the "select all" checkbox
                checkboxes.prop('checked', $(this).prop('checked'));
            });

            $('#select-all2').on('click', function() {
                // Get all checkboxes in the table body
                var checkboxes = $('.row-check2');
                // Set the "checked" property of all checkboxes to the same as the "checked" property of the "select all" checkbox
                checkboxes.prop('checked', $(this).prop('checked'));
            });

            // Add event listener for individual checkboxes in the table body
            $('tbody').on('click', 'input[type="checkbox"]', function() {
                // Get all checkboxes in the table body
                var checkboxes = $('tbody input[type="checkbox"]');
                // Set the "checked" property of the "select all" checkbox based on whether all checkboxes in the table body are checked
                $('#select-all').prop('checked', checkboxes.length == checkboxes.filter(':checked').length);
            });

            // Add event listener for the page event of the datatable
            datatable.on('page.dt', function() {
                // Uncheck the "select all" checkbox
                $('#select-all').prop('checked', false);
            });

            datatable2.on('page.dt', function() {
                // Uncheck the "select all" checkbox
                $('#select-all2').prop('checked', false);
            });

            $('tbody').on('click', '.is_active-check', function() {
                var id = $(this).val();
                var finalStatus = $(this).prop('checked') ? true : false;
                var nama = $(this).data('nama');

                // Send an AJAX request to set the "selected" attribute in the database
                $.ajax({
                    url: '/referensi/jenis_kegiatan/update_status',
                    type: 'POST',
                    data: {
                        id: id,
                        finalStatus: finalStatus
                    },
                    success: function(response) {
                        toastr.success(
                            `Status data kegiatan <strong>${nama}</strong>  berhasil diubah menjadi <strong>${finalStatus}</strong>!`
                        );
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });

            //body
            $('body').on('click', '.delete-selected-data', function() {
                console.log('halo')
                var selectedData = $('input[name="selected-data[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Apakah anda yakin akang menghapus data yang sudah dipilih?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user confirmed the deletion, do something here
                        console.log('User confirmed deletion');
                        // Send an AJAX request to set the "selected" attribute in the database
                        $.ajax({
                            url: '/referensi/jenis_kegiatan/delete',
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                selectedData: selectedData
                            },
                            success: function(response) {
                                window.location.reload();
                                // console.log(`success : ${response}`)
                                // toastr.success(
                                //     `Status data <strong>${nama_tpb}</strong> dengan ID TPB <strong>${no_tpb}</strong> dan jenis anggaran <strong>${jenis_anggaran}</strong> berhasil diubah menjadi <strong>${finalStatus}</strong>!`
                                // );
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(errorThrown);
                            }
                        });
                    } else {
                        // If the user cancelled the deletion, do something here
                        console.log('User cancelled deletion');
                    }
                })
                console.log(selectedData)


            });

            $('body').on('click', '.delete-selected-data2', function() {
                console.log('halo')
                var selectedData = $('input[name="selected-data2[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Apakah anda yakin akang menghapus data yang sudah dipilih?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user confirmed the deletion, do something here
                        console.log('User confirmed deletion');
                        // Send an AJAX request to set the "selected" attribute in the database
                        $.ajax({
                            url: '/referensi/jenis_kegiatan/delete_subkegiatan',
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                selectedData: selectedData
                            },
                            success: function(response) {
                                window.location.reload();
                                // console.log(`success : ${response}`)
                                // toastr.success(
                                //     `Status data <strong>${nama_tpb}</strong> dengan ID TPB <strong>${no_tpb}</strong> dan jenis anggaran <strong>${jenis_anggaran}</strong> berhasil diubah menjadi <strong>${finalStatus}</strong>!`
                                // );
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(errorThrown);
                            }
                        });
                    } else {
                        // If the user cancelled the deletion, do something here
                        console.log('User cancelled deletion');
                    }
                })
                console.log(selectedData)


            });

        });

        function setDatatable() {
            datatable = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: urldatatable,
                columns: [{
                        data: 'id',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'no_tpb',
                    //     name: 'no_tpb',
                    //     orderable: true,
                    // },
                    {
                        data: 'nama',
                        name: 'nama'
                    },

                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },

                    {
                        data: 'is_active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const isChecked = data ? 'checked' : '';
                            return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                    <input class="form-check-input is_active-check" type="checkbox"  data-nama="${row.nama}"   ${isChecked} name="selected-is_active[]" value="${row.id}">
                                    </label>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input class="form-check-input row-check" type="checkbox" name="selected-data[]" value="${row.id}"></label>`;
                        }
                    }
                ],
                order: [
                    [0, 'asc'],

                ],
                drawCallback: function(settings) {
                    var info = datatable.page.info();
                    $('[data-toggle="tooltip"]').tooltip();
                    datatable.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = info.start + i + 1;
                    });
                }
            });
        }

        function setDatatable2() {
            datatable2 = $('#datatable2').DataTable({
                processing: true,
                serverSide: true,
                ajax: urldatatable2,
                columns: [{
                        data: 'id',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'no_tpb',
                    //     name: 'no_tpb',
                    //     orderable: true,
                    // },
                    {
                        data: 'jenis_kegiatan_nama',
                        name: 'jenis_kegiatan_nama'
                    },

                    {
                        data: 'subkegiatan',
                        name: 'subkegiatan'
                    },
                    {
                        data: 'satuan_ukur_nama',
                        name: 'satuan_ukur_nama'
                    },

                    {
                        data: 'is_active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const isChecked = data ? 'checked' : '';
                            return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                                    <input class="form-check-input is_active-check2" type="checkbox"  data-id="${row.id}"   ${isChecked} name="selected-is_active2[]" value="${row.id}">
                                    </label>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input class="form-check-input row-check2" type="checkbox" name="selected-data2[]" value="${row.id}"></label>`;
                        }
                    }
                ],
                order: [
                    [0, 'asc'],

                ],
                drawCallback: function(settings) {
                    var info = datatable2.page.info();
                    $('[data-toggle="tooltip"]').tooltip();
                    datatable2.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
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
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: urldelete,
                        data: {
                            "id": $(element).data('id')
                        },
                        type: 'post',
                        dataType: 'json',
                        beforeSend: function() {
                            $.blockUI();
                        },
                        success: function(data) {
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
    </script>
@endsection
