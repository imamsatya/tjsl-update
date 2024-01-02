@extends('layouts.app')

@section('content')

<input type="hidden" name="isOkToInput" id="isOkToInput" readonly="readonly" value="{{$isOkToInput}}" />
    <div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="card">

                <!--begin::Card header-->
                <div class="card-header pt-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="d-flex align-items-center">{{ $pagetitle }} 
                            {{-- {{$isOkToInput}} --}}
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
                       
                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">BUMN</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
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
                                        <!--end::Col-->
                                    </div>
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Periode</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
                                            <select  id="periode_laporan" class="form-select form-select-solid form-select2" name="periode_laporan" data-kt-select2="true" data-placeholder="Pilih Periode Laporan" data-allow-clear="true">
                                                <option></option>
                                                @foreach($periode as $p)  
                                                @php
                                                    $select = (($p->id == $periode_id) ? 'selected="selected"' : '');
                                                    
                                                @endphp
                                                <option data-is_active="{{$p->is_active}}" data-tanggal_awal="{{$p->tanggal_awal}}" data-tanggal_akhir="{{$p->tanggal_akhir}}" value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                            @endforeach
                                            </select>

                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Tahun</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
                                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" data-placeholder="Pilih Tahun" data-allow-clear="true">
                                                <option></option>
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
                                    <div class="row mb-6">
                                        <!--begin::Label-->
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                                        <!--end::Label-->
                                        <!--begin::Col-->
                                        <div class="col-lg-8 fv-row">
                                            <select  id="status_spd" class="form-select form-select-solid form-select2" name="status_spd" data-kt-select2="true" data-placeholder="Pilih Status Anggaran" data-allow-clear="true">
                                                <option></option>
                                                @foreach($status as $s)  
                                                @php
                                                    $select = (($s->id == $status_id) ? 'selected="selected"' : '');
                                                @endphp
                                                <option value="{{ $s->id }}" {!! $select !!}>{{ $s->nama }}</option>
                                            @endforeach
                                            </select>

                                        </div>
                                        <!--end::Col-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <br>
                            <button type="submit" id="proses" class="btn btn-primary">Proses</button>
                    
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
                        <div class="col">
                        <h2 class="d-flex align-items-center mb-4">{{ $pagetitle }}
                            <span class="text-gray-600 fs-6 ms-1"></span>
                        </h2>
                        <div class="d-flex align-items-center position-relative my-1">
                            {{-- <button type="button" class="btn btn-success me-2 btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Simpan Status</button> --}}
                            {{-- <button type="button" class="btn btn-success btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Tambah</button> --}}
                                @can('edit-kegiatan')
                                <button type="button" class="btn btn-primary btn-action btn-sm me-2" id="input-data" onclick="redirectToNewPage()">Input Data
                                </button>
                                @endcan
                            @can('delete-kegiatan')
                            <button type="button" class="btn btn-danger btn-action btn-sm delete-selected-data me-2">Hapus Data
                            </button>
                            @endcan
                            @can('view-verify')
                            {{-- @if($countInprogress || !$anggaran->count()) --}}
                            <button  type="button" class="btn btn-primary btn-action btn-sm me-2" id="verify-data" >Verify
                            </button>   
                            @endcan
                            @can('view-unverify') 
                            <button  type="button" class="btn btn-warning btn-action btn-sm me-2" id="unverify-data" >Un-Verify
                            </button> 
                            {{-- @endif --}}
                            @endcan
                            @can('view-finalVerify')
                            <button type="button" class="btn btn-success btn-action finalVerify-selected-data btn-sm me-2" id="finalVerify-data">Validate
                            </button>
                            @endcan
                            @can('view-finalUnverify')
                            <button type="button" class="btn btn-warning btn-action finalVerify-selected-data btn-sm me-2" id="finalUnverify-data">Un-Validate
                            </button>
                            @endcan
                        </div>
                        </div>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Search-->
                        {{-- <div class="d-flex align-items-center position-relative my-1"
                            data-kt-view-roles-table-toolbar="base">
                            <button type="button" class="btn btn-success me-2 btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Simpan Status</button>
                            <button type="button" class="btn btn-success btn-sm cls-add"
                                data-kt-view-roles-table-select="delete_selected">Tambah</button>
                            <button type="button" class="btn btn-danger btn-sm delete-selected-data">Hapus Data
                                x</button>
                        </div> --}}
                       
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
                                    <th>Tahun</th>
                                    <th>BUMN</th>
                                    <th>Dana Tersedia</th>
                                    <th>Dana Tersalurkan</th>
                                    <th>Saldo Akhir</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th style="text-align:center;width: 10%;">Aksi</th>
                                    <th><label
                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                                class="form-check-input addCheck" type="checkbox"
                                                id="select-all"></label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($anggaran as $anggaran_row)
                                <tr>
                                    <td>x</td>
                                    <td>{{$anggaran_row->tahun}}</td>
                                    <td>{{$anggaran_row->nama_lengkap}}</td>
                                    <td>{{$anggaran_row->income_total}}</td>
                                    <td>{{$anggaran_row->outcome_total}}</td>
                                    <td>{{$anggaran_row->saldo_akhir}}</td>
                                    <td>{{$anggaran_row->status_id}}</td>
                                    <td>aksi</td>
                                    <td>checkbox</td>
                                </tr>
                                    
                                @endforeach --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-end" style="white-space: nowrap;">@if ($totalIncome < 0)
                                        Rp ( {{ number_format($totalIncome, 0, ',', ',') }} )
                                    @else
                                        Rp {{ number_format($totalIncome, 0, ',', ',') }}
                                    @endif</th>
                                    <th class="text-end" style="white-space: nowrap;">@if ($totalOutcome < 0)
                                        Rp ( {{ number_format($totalOutcome, 0, ',', ',') }} )
                                    @else
                                        Rp {{ number_format($totalOutcome, 0, ',', ',') }}
                                    @endif
                                    </th>
                                    <th class="text-end" style="white-space: nowrap;">@if ($saldoAkhir < 0)
                                        Rp ( {{ number_format($saldoAkhir, 0, ',', ',') }} )
                                    @else
                                        Rp {{ number_format($saldoAkhir, 0, ',', ',') }}
                                    @endif
                                   </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
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
        var urlcreate = "{{ route('referensi.tpb.create') }}";
        var urledit = "{{ route('referensi.tpb.edit') }}";
        var urlstore = "{{ route('referensi.tpb.store') }}";
        var urlupdate = "{{ route('referensi.tpb.update') }}";
        var urldatatable = "{{ route('laporan_realisasi.triwulan.spd_pumk.datatable') }}";
        var urldelete = "{{ route('referensi.tpb.delete') }}";
        var urllog = "{{route('laporan_realisasi.triwulan.spd_pumk.log')}}";

        var urlverifikasidata = "{{route('laporan_realisasi.triwulan.spd_pumk.verifikasi_data')}}";
        var urlbatalverifikasidata = "{{route('laporan_realisasi.triwulan.spd_pumk.batal_verifikasi_data')}}";
        var urlfinalverifikasidata = "{{route('laporan_realisasi.triwulan.spd_pumk.final_verifikasi_data')}}";
        var urlbatalfinalverifikasidata = "{{route('laporan_realisasi.triwulan.spd_pumk.batal_final_verifikasi_data')}}";
        var urlshow = "{{route('laporan_realisasi.triwulan.spd_pumk.show')}}";
        $(document).ready(function() {
            $('#page-title').html("{{ $pagetitle }}");
            $('#page-breadcrumb').html("{{ $breadcrumb }}");

            $('body').on('click', '.cls-add', function() {
                winform(urlcreate, {}, 'Tambah Data');
            });

            $('body').on('click', '.cls-button-edit', function() {
                // const idPermohonan = $(this).data('permohonan')
                var selectedPerusahaanId = $(this).data('perusahaan_id');
           

                var selectedTahun = $(this).data('tahun');

                console.log($(this))
                var selectedPeriodeId = $(this).data('periode_id')
           

                // Do something with the selected value and text
                console.log("selectedPerusahaanId: " + selectedPerusahaanId);
          

                console.log("selectedTahun: " + selectedTahun);
           

                // Use the Laravel's built-in route function to generate the new URL
                var url = "{{ route('laporan_realisasi.triwulan.spd_pumk.create', ['perusahaan_id' => ':perusahaan_id', 'tahun' => ':tahun', 'periode_id' => ':periode_id']) }}";
                url = url.replace(':perusahaan_id', selectedPerusahaanId).replace(':tahun', selectedTahun).replace(':periode_id', selectedPeriodeId);

                // Redirect the user to the new page
                window.location.href = url;
            });

            $('body').on('click', '.cls-button-delete', function() {
                onbtndelete(this);
            });

            $('body').on('click','.cls-log',function(){
                winform(urllog, {'id':$(this).data('id')}, 'Log Data');
            });

            $('body').on('click','.cls-button-show',function(){
                winform(urlshow, {'id':$(this).data('id')}, 'Detail Data');
            });


            setDatatable();

            //Imam
            // Add event listener for the "select all" checkbox in the table header
            $('#select-all').on('click', function() {
                // Get all checkboxes in the table body
                var checkboxes = $('.row-check');
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

            $('tbody').on('click', '.is_active-check', function() {
                var id = $(this).val();
                var finalStatus = $(this).prop('checked') ? true : false;
                var rowData = $(this).data('row')
                var no_tpb = $(this).data('no_tpb');
                var nama_tpb = $(this).data('nama');
                var jenis_anggaran = $(this).data('jenis_anggaran');

                // Send an AJAX request to set the "selected" attribute in the database
                $.ajax({
                    url: '/referensi/tpb/update_status',
                    type: 'POST',
                    data: {
                        id: id,
                        finalStatus: finalStatus
                    },
                    success: function(response) {
                        toastr.success(
                            `Status data <strong>${nama_tpb}</strong> dengan Kode TPB <strong>${no_tpb}</strong> dan jenis anggaran <strong>${jenis_anggaran}</strong> berhasil diubah menjadi <strong>${finalStatus}</strong>!`
                        );
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });

            //body
            $('body').on('click', '.delete-selected-data', function() {
                console.log('halo x')
                var selectedData = $('input[name="selected-data[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Apakah anda yakin akan menghapus data yang sudah dipilih? <br/><span style='color: red; font-weight: bold'>[Data selected: "+selectedData.length+" rows]</span>" ,
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
                            url: '/laporan_realisasi/triwulan/spd_pumk/delete',
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

            $('#proses').on('click', function(event){
                // datatable.ajax.reload()
                console.log($('#status_spd').val())
                var url = window.location.origin + '/laporan_realisasi/triwulan/spd_pumk/index';
                var perusahaan_id = $('#perusahaan_id').val();
                var tahun = $('#tahun').val();
                var status_spd = $('#status_spd').val()
                var periode_laporan = $('#periode_laporan') .val()
                
                $.ajax({
                    url: "{{ route('encrypt_data') }}",  // Replace with your actual route
                    type: 'POST',
                    data: {
                        data: perusahaan_id,
                        _token: '{{ csrf_token() }}'  // Add CSRF token for Laravel
                    },
                    success: function (encryptedValue) {

                        window.location.href = url + '?perusahaan_id=' + encryptedValue.encryptedValue + '&tahun=' + tahun + '&status_spd=' + status_spd + '&periode_laporan=' + periode_laporan ;
                    },
                    error: function (error) {
                            console.error('Error in encrypting data:', error);
                    }
                });

                // window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun + '&status_spd=' + status_spd + '&periode_laporan=' + periode_laporan ;
            });

            $('#periode_laporan').change(function() {
                console.log('periode')
                console.log('isoktoinput', $( '#isOkToInput').val())
                var selectedOption = $(this).find(':selected');
                var isOptionActive = selectedOption.data('is_active');
                let is_active = selectedOption.data('is_active')
                let tanggal_awal = selectedOption.data('tanggal_awal')
                let tanggal_akhir = selectedOption.data('tanggal_akhir')
                
                // Get the current date
                let currentDate = new Date();

                // Convert the tanggal_awal and tanggal_akhir strings to Date objects
                let dateAwal = new Date(tanggal_awal);
                let dateAkhir = new Date(tanggal_akhir);

                // Check if the current date is between tanggal_awal and tanggal_akhir
                if (currentDate >= dateAwal && currentDate <= dateAkhir || is_active == false || $('#isOkToInput').val() ) {
                    $('.btn-action').prop('disabled', false);
                } else {
                    $('.btn-action').prop('disabled', true);
                }
                
                  
            });

            $('body').on('click', '#finalVerify-data', function() {
            
                var selectedData = $('input[name="selected-data[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Apakah anda yakin akan memvalidasi data yang sudah dipilih? <br/><span style='color: red; font-weight: bold'>[Data selected: "+selectedData.length+" rows]</span>" ,
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
                            url: urlfinalverifikasidata,
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                selectedData: selectedData
                            },
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

            $('body').on('click', '#finalUnverify-data', function() {
            
                var selectedData = $('input[name="selected-data[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Apakah anda yakin akan membatalkan validasi data yang sudah dipilih? <br/><span style='color: red; font-weight: bold'>[Data selected: "+selectedData.length+" rows]</span>" ,
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
                            url: urlbatalfinalverifikasidata,
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                selectedData: selectedData
                            },
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

            $('body').on('click', '#verify-data', function() {
            
                var selectedData = $('input[name="selected-data[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    html: "Apakah anda yakin akan memverifikasi data yang sudah dipilih? <br/><span style='color: red; font-weight: bold'>[Data selected: "+selectedData.length+" rows]</span>" ,
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
                            url: urlverifikasidata,
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                selectedData: selectedData
                            },
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

            $('body').on('click', '#unverify-data', function() {
                
                    var selectedData = $('input[name="selected-data[]"]:checked').map(function() {
                        return $(this).val();
                    }).get();
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        html: "Apakah anda yakin akan membatalkan verifikasi data yang sudah dipilih? <br/><span style='color: red; font-weight: bold'>[Data selected: "+selectedData.length+" rows]</span>" ,
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
                                url: urlbatalverifikasidata,
                                type: 'POST',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    selectedData: selectedData
                                },
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
                ajax: {
                url: urldatatable,
                type: 'GET',
                data: function (d) {
                    d.perusahaan_id = $("select[name='perusahaan_id']").val(),
                    d.tahun = $("select[name='tahun']").val(),
                    d.status_spd = $('#status_spd').val(),
                    d.periode_laporan = $('#periode_laporan').val()
                    }
                 },
                columns: [
                    
                {
                        data: 'id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        orderable: true,
                    },
                    {
                        data: 'nama_lengkap',
                        name: 'nama_lengkap'
                    },

                    {
                        data: 'income_total',
                        name: 'income_total',
                        render: function(data, type, row) {
                            let formattedValue = formatCurrency2(data.toString());
                            return `<div class="text-end">${formattedValue}</div>`;
                        }
                    },
                    {
                        data: 'outcome_total',
                        name: 'outcome_total',
                        render: function(data, type, row) {
                            let formattedValue = formatCurrency2(data.toString());
                            return `<div class="text-end">${formattedValue}</div>`;
                        }
                    },
                    {
                        data: 'saldo_akhir',
                        name: 'saldo_akhir',
                        render: function(data, type, row) {
                            let formattedValue = formatCurrency2(data.toString());
                            return `<div class="text-end">${formattedValue}</div>`;
                        }
                    },
                    {
                        data: 'periode_laporans_nama',
                        name: 'periode_laporans_nama',
                        
                    },
                    {
                        data: 'status_id',
                        name: 'status_id',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            console.log(row)
                            let status = null
                            if (data === 1) {
                                 status = `<span class="btn cls-log badge badge-light-success fw-bolder me-auto px-4 py-3" data-id="${row.id}">Verified</span>`
                            }
                            if (data === 2) {
                                 status = `<span class="btn cls-log badge badge-light-primary fw-bolder me-auto px-4 py-3" data-id="${row.id}">In Progress</span>`
                            }
                            if (data === 4) {
                                 status = `<span class="btn cls-log badge badge-light-success fw-bolder me-auto px-4 py-3" data-id="${row.id}">Validated</span>`
                            }
                            return status;
                        }
                    },

                    // {
                    //     data: 'is_active',
                    //     orderable: false,
                    //     searchable: false,
                    //     render: function(data, type, row) {

                    //         const isChecked = data ? 'checked' : '';
                    //         return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                    //                 <input class="form-check-input is_active-check" type="checkbox" data-no_tpb="${row.no_tpb}" data-nama="${row.nama}" data-jenis_anggaran="${row.jenis_anggaran}"  ${isChecked} name="selected-is_active[]" value="${row.id}">
                    //                 </label>`;
                    //     }
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        render: function(data, type, row){
                            console.log(row.status_id)
                            let button = null;
                            if (row.status_id === 2) {
                                
                                button = `@can('edit-kegiatan')<button style="margin-right: 8px;" type="button" ${row.isoktoinput ? '' : 'disabled'} class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-tahun="${row.tahun}" data-perusahaan_id="${row.perusahaan_id}" data-periode_id="${row.periode_laporans_id}" data-toggle="tooltip" title="Ubah data "><i class="bi bi-pencil fs-3"></i></button>@endcan`
                                button = button + `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-show" data-id="${row.id}" data-tahun="${row.tahun}" data-perusahaan_id="${row.perusahaan_id}" data-periode_id="${row.periode_laporans_id}" data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                            }

                            if (row.status_id === 1) {
                                button = `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-show" data-id="${row.id}" data-tahun="${row.tahun}" data-perusahaan_id="${row.perusahaan_id}" data-periode_id="${row.periode_laporans_id}" data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                            }
                            return button
                        }
                    },

                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let checkbox = null
                            if (row.isoktoinput) {
                               checkbox = `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input  class="form-check-input row-check" type="checkbox" name="selected-data[]" value="${row.id}"></label>`
                            }else {
                                checkbox =  `<div style="display: flex; justify-content: center; align-items: center; width: 1.5rem; height: 1.5rem; background-color: gray;margin-top: 10px;"></div>`;
                            }
                            return checkbox;
                        }
                    }
                ],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                pageLength: 10,
                drawCallback: function(settings) {
                    var info = datatable.page.info();
                    $('[data-toggle="tooltip"]').tooltip();
                    datatable.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = info.start + i + 1;
                    });
                },
                footerCallback: function(row, data, start, end, display) {
                    // var api = this.api();
                    
                    // var getColumnTotal = function(columnIndex) {
                    //     return api
                    //         .column(columnIndex, { page: 'current' })
                    //         .data()
                    //         .reduce(function(acc, val) {
                    //             return acc + parseFloat(val);
                    //         }, 0);
                    // };
                  

                    // var incomeTotal = getColumnTotal(3);
                    // var outcomeTotal = getColumnTotal(4);
                    // var saldoAkhirTotal = getColumnTotal(5);

                    // $(api.column(1).footer()).html('Total');
                    // $(api.column(3).footer()).html('<div class="text-end">' + formatCurrency2(getColumnTotal(3).toFixed(0)) + '</div>');
                    // $(api.column(4).footer()).html('<div class="text-end">' + formatCurrency2(getColumnTotal(4).toFixed(0)) + '</div>');
                    // $(api.column(5).footer()).html('<div class="text-end">' + formatCurrency2(getColumnTotal(5).toFixed(0)) + '</div>');
                }
            });
        }

        function formatCurrency2(element) {
         
         let value = element.replace(/[^\d-]/g, ""); // Remove all non-numeric characters except for hyphen "-"
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
         element = formattedValue;
         return element
      
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

        

        //Imam
    function redirectToNewPage() {
        var selectedPerusahaanId = $('#perusahaan_id').val();
        var selectedPerusahaanText = $('#perusahaan_id option:selected').text();

        var selectedTahun = $('#tahun').val();
        var selectedTahunText = $('#tahun option:selected').text();

        var selectedPeriode = $('#periode_laporan').val();
        if(selectedPerusahaanId === '' || selectedTahun === '' || selectedPeriode === '') {
            swal.fire({                    
                icon: 'warning',
                html: 'Perusahaan (BUMN), Tahun dan Periode harus terisi!',
                type: 'warning', 
                confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
            });
            return
         }
        // Do something with the selected value and text
        console.log("selectedPerusahaanId: " + selectedPerusahaanId);
        console.log("selectedPerusahaanText: " + selectedPerusahaanText);

        console.log("selectedTahun: " + selectedTahun);
        console.log("selectedTahunText: " + selectedTahunText);
        
        $.ajax({
                    url: "{{ route('encrypt_data') }}",  // Replace with your actual route
                    type: 'POST',
                    data: {
                        data: selectedPerusahaanId,
                        _token: '{{ csrf_token() }}'  // Add CSRF token for Laravel
                    },
                    success: function (encryptedValue) {
                        
                        var url = "{{ route('laporan_realisasi.triwulan.spd_pumk.create', ['perusahaan_id' => ':perusahaan_id', 'tahun' => ':tahun', 'periode_id' => ':periode_id']) }}";
                        url = url.replace(':perusahaan_id', encryptedValue.encryptedValue).replace(':tahun', selectedTahun).replace(':periode_id', selectedPeriode);

                        // Redirect the user to the new page
                        window.location.href = url;
                    },
                    error: function (error) {
                            console.error('Error in encrypting data:', error);
                    }
                });
        // // Use the Laravel's built-in route function to generate the new URL
        // var url = "{{ route('laporan_realisasi.triwulan.spd_pumk.create', ['perusahaan_id' => ':perusahaan_id', 'tahun' => ':tahun', 'periode_id' => ':periode_id']) }}";
        // url = url.replace(':perusahaan_id', selectedPerusahaanId).replace(':tahun', selectedTahun).replace(':periode_id', selectedPeriode);

        // // Redirect the user to the new page
        // window.location.href = url;
    }
    </script>
@endsection
