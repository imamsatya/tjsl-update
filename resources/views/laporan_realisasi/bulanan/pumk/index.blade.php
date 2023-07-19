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
                    <div class="row" id="form-cari">
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
                                    @foreach($perusahaan as $p)
                                    @php
                                    $select = (($p->id == $perusahaan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 fv-row d-flex align-items-center justify-content-start">
                                <div style="display: flex; flex-direction: row;">
                                    <div class="col-11 me-2">
                                        <label>Tahun</label>
                                        <select class="form-select form-select-solid form-select2" id="tahun"
                                            name="tahun" data-kt-select2="true">
                                            @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                            @php
                                            $select = (($i == $tahun) ? 'selected="selected"' : '');
                                            @endphp
                                            <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                            @php } @endphp
                                        </select>
                                    </div>

                                    <div class="col-11">
                                        <label>Bulan</label>
                                        <select id="bulan_id" class="form-select form-select-solid form-select2"
                                            name="bulan_id" data-kt-select2="true" data-placeholder="Pilih Bulan"
                                            data-allow-clear="true">
                                            <option></option>
                                            @php
                                             $select_all = (($bulan_id = 'all') ? 'selected="selected"' : '');
                                            @endphp
                                            <option value="all" {!! $select_all !!}> ALL</option>
                                            @foreach($bulan as $bulan_row)
                                            @php
                                                $select = (($bulan_row->id == $bulan_id) ? 'selected="selected"' : '');
                                            @endphp
                                            <option value="{{ $bulan_row->id }}" {!! $select !!}>{{ $bulan_row->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <label>Kolektibilitas</label>
                                <select id="pilar_pembangunan_id" class="form-select form-select-solid form-select2"
                                    name="pilar_pembangunan_id" data-kt-select2="true"
                                    data-placeholder="Pilih Kolektibilitas" data-allow-clear="true">
                                    <option></option>
                                    
                                    <option value="lancar">Lancar</option>
                                    <option value="kurang_lancar">Kurang Lancar</option>
                                    <option value="diragukan">Diragukan</option>
                                    <option value="macet">Macet</option>
                                    <option value="pinajaman_bermasalah">Pinajaman Bermasalah</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Kriteria Program</label>
                                <div class="row mb-6 mt-2">
                                    
                                    <div class="col-lg-12 fv-row d-flex align-items-center justify-content-start">
                                        <div style="display:flex; flex-direction: row;">
                                            <div
                                                class="form-check form-check-custom form-check-solid form-check-sm me-8">
                                                
                                                <input class="form-check-input" type="checkbox" name="kriteria_program"
                                                    value="prioritas" id="checkboxPrioritas" />
                                                <label class="form-check-label" for="checkboxPrioritas">
                                                    Mitra Binaan Naik Kelas
                                                </label>
                                            </div>

                                            <div class="form-check form-check-custom form-check-solid form-check-sm">
                                                
                                                <input class="form-check-input" type="checkbox" name="kriteria_program"
                                                    value="umum" id="checkboxUmum" />
                                                <label class="form-check-label" for="checkboxUmum">
                                                    Penyaluran melalui BRI
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row  mb-5">
                            <div class="col-lg-6">
                                <button id="proses" class="btn btn-success me-3">Proses</button>
                            </div>
                        </div>
                        <div class="separator border-gray-200 mb-10"></div>
                    </div>
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

                    <div class="d-flex align-items-center position-relative my-1">
                        @can('delete-kegiatan')
                        <button type="button" class="btn btn-danger btn-sm delete-selected-data me-2">Hapus Data
                        </button>
                        @endcan
                        @can('edit-kegiatan')
                        <button type="button" class="btn btn-primary btn-sm input-data me-2">Input Data
                        </button>
                        @endcan
                        @can('view-verify')
                        <button type="button" class="btn btn-primary btn-sm " id="verify-data">Verify
                        </button>
                        @endcan
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
                    <div class="table-responsive">
                        <table id="datatable"
                            class="table table-striped table-bordered table-hover tree  table-checkable">
                            <thead>
                                <tr>
                                    <th
                                        style="text-align:center;font-weight:bold;width:50px;border-bottom: 1px solid #c8c7c7;">
                                        No.</th>
                                    <th
                                        style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">
                                        Bulan - Tahun</th>
                                        @if($perusahaan_id == '3')
                                    <th
                                        style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">
                                        Nilai Penyaluran </th>
                                        @endif
                                        @if($perusahaan_id != '3')
                                    <th
                                        style="text-align:center;font-weight:bold;width:100px;border-bottom: 1px solid #c8c7c7;">
                                        Penyaluran Melalui BRI</th>
                                        @endif
                                    <th
                                        style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">
                                        Jumlah MB Baru</th>
                                    <th
                                        style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">
                                        Jumlah MB Naik Kelas</th>
                                    {{-- <th
                                        style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">
                                        Kolektabilitas</th> --}}
                                    <th
                                        style="text-align:center;font-weight:bold;width:120px;border-bottom: 1px solid #c8c7c7;">
                                        Status</th>
                                    <th
                                        style="text-align:center;width:100px;font-weight:bold;border-bottom: 1px solid #c8c7c7;">
                                        Aksi</th>
                                    <th style="width: 5%"><label
                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input
                                                class="form-check-input addCheck" type="checkbox"
                                                id="select-all"></label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>

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
    var urlcreate = "{{route('laporan_realisasi.bulanan.pumk.create')}}";
    var urldatatable = "{{ route('laporan_realisasi.bulanan.pumk.datatable') }}";
    var urllog = "{{ route('laporan_realisasi.bulanan.pumk.log') }}";
    var urlkolektabilitas ="{{ route('laporan_realisasi.bulanan.pumk.kolektabilitas')}}";
    var urlverifikasidata = "{{route('laporan_realisasi.bulanan.pumk.verifikasi_data')}}";
    //
    var urldelete = "{{ route('laporan_realisasi.bulanan.pumk.delete') }}";
    

    $(document).ready(function () {
        $('.tree').treegrid({
            initialState: 'collapsed',
            treeColumn: 1,
            indentTemplate: '<span style="width: 32px; height: 16px; display: inline-block; position: relative;"></span>'
        });
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click', '.input-data', function () {
            var perusahaan_id = $('#perusahaan_id').val();
            winform(urlcreate, {
                'perusahaan_id': perusahaan_id
            }, 'Ubah Data');

        });


        $('body').on('click', '.cls-button-edit', function () {
            const bulanan_pumk_id = $(this).data('id')
            winform(urlcreate, {
                'bulanan_pumk_id': bulanan_pumk_id,
                'actionform': 'edit',
            }, 'Ubah Data');
        });
        $('body').on('click', '.cls-log', function () {
            winform(urllog, {
                'id': $(this).data('id')
            }, 'Log Data');
        });
        $('body').on('click', '.cls-kolektabilitas', function () {
            winform(urlkolektabilitas, {
                'id': $(this).data('id')
            }, 'Data Kolektabilitas');
        });
        setDatatable();

        $('#proses').on('click', function (event) {
            // datatable.ajax.reload()
            var url = window.location.origin + '/laporan_realisasi/bulanan/pumk/index';
            var perusahaan_id = $('#perusahaan_id').val();
            var tahun = $('#tahun').val();
            var bulan = $('#bulan_id').val();
           
            // const jenisAnggaran = $("#jenis-anggaran").val()
            // // const statusAnggaran = $("#status-anggaran").val()   
            // const kriteria_program_checkboxes = document.getElementsByName(
            //     "kriteria_program"); // mengambil semua checkbox dengan name="kriteria_program"
            // const
            //     selectedKriteriaProgram = []; // deklarasi array untuk menyimpan nilai dari checkbox yang dipilih

            // for (let i = 0; i < kriteria_program_checkboxes.length; i++) { // iterasi semua checkbox
            //     if (kriteria_program_checkboxes[i].checked) { // jika checkbox terpilih
            //         selectedKriteriaProgram.push(kriteria_program_checkboxes[i]
            //             .value); // tambahkan nilai checkbox ke dalam array
            //     }
            // }

            window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun +
                '&bulan=' + bulan 
                // + '&tpb=' + tpb_id + '&jenis_anggaran=' +
                // jenisAnggaran + '&kriteria_program=' + selectedKriteriaProgram;
        });


        //Imam
        // Add event listener for the "select all" checkbox in the table header
        $('#select-all').on('click', function () {
            var checkboxes = $('.row-check');
            checkboxes.prop('checked', $(this).prop('checked'));
        });

        

        $(".delete-selected-data").on('click', function () {
            var selectedData = $('input[name="selected-data[]"]:checked').map(function () {
                         return $(this).val();
                     }).get();
           
            if (!selectedData.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Tidak ada data terpilih untuk dihapus!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }
            deleteselectedData(selectedData)
        })

        $("#jenis-anggaran").on('change', function () {
            const jenisAnggaran = $(this).val()
            $("#tpb_id, #pilar_pembangunan_id").val('').trigger('change')


            $("#tpb_id, #pilar_pembangunan_id").select2({
                templateResult: function (data) {
                    if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
                        jenisAnggaran === '') return data.text
                    return null
                },
                templateSelection: function (data) {
                    if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
                        jenisAnggaran === '') return data.text
                    return null
                }
            })

            let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
            $("#select2-pilar_pembangunan_id-container .select2-selection__placeholder").text(
                'Pilih Pilar ' + textAnggaran)
            $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB ' +
                textAnggaran)

            // $("#tpb_id").select2({    
            //     // placeholder: 'Pilih TPB',            
            //     templateResult: function(data) {
            //         if($(data.element).attr('data-jenis-anggaran') === jenisAnggaran || jenisAnggaran === '') return $('<span>').text(data.text);
            //         return null
            //     },
            //     templateSelection: function(data) {
            //         if($(data.element).attr('data-jenis-anggaran') === jenisAnggaran || jenisAnggaran === '') return data.text
            //         return null
            //     }
            // })            

        })

        $("#verify-data").on('click', function() {
                var selectedProgram = $('input[name="selected-data[]"]:checked').map(function () {
                         return $(this).val();
                     }).get();

                     if(!selectedProgram.length) {
                    swal.fire({
                        icon: 'warning',
                        title: 'Warning',
                        html: 'Tidak ada data terpilih untuk diverifikasi!',
                        buttonsStyling: true,
                        confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                    })
                    return
                }
            
                verifySelectedData(selectedProgram) 
            
            })



    });

    function setDatatable() {
        let perusahaan_id = $('#perusahaan_id').val()
        let columns = null
        if (perusahaan_id == '3') {
            columns =  [
                {
                    data: 'id',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'bulan_id',
                    name: 'bulan_id',
                    render: function (data, type, row) {

                        return row.bulan + ' ' + row.tahun;
                    }
                },
                {
                    data: 'nilai_penyaluran',
                    name: 'nilai_penyaluran',
                    className: 'text-end',
                    render: function (data, type, row) {
                        let formattedValue = formatCurrency2(data.toString());
                        return `<div class="text-end">${formattedValue}</div>`;
                    }
                },
                {
                    data: 'jumlah_mb',
                    name: 'jumlah_mb',
                    className: 'text-end',
                    render: function (data, type, row) {
                       
                        return `<div class="text-end">${data}</div>`;
                    }
                },
                {
                    data: 'jumlah_mb_naik_kelas',
                    name: 'jumlah_mb_naik_kelas',
                    className: 'text-end',
                    render: function (data, type, row) {
                   
                        return `<div class="text-end">${data}</div>`;
                    }
                },
                {
                    data: 'status_id',
                    name: 'status_id',
                    orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // console.log(row)
                            let status = null
                            if (data === 1) {
                                 status = `<span class="btn cls-log badge badge-light-success fw-bolder me-auto px-4 py-3" data-id="${row.id}">Finish</span>`
                            }
                            if (data === 2) {
                                 status = `<span class="btn cls-log badge badge-light-primary fw-bolder me-auto px-4 py-3" data-id="${row.id}">In Progress</span>`
                            }
                            return status;
                        }
                },
                
                {
                    data: 'action',
                    name: 'action',
                    render: function (data, type, row) {
                        // console.log(row)
                        let button = null;
                        if (row.status_id === 2) {
                            // , minus
                            button =
                                `@can('edit-kegiatan')<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="${row.id}"  data-toggle="tooltip" title="Ubah data "><i class="bi bi-pencil fs-3"></i></button>@endcan`

                                // button = button + `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-info" data-id="${row.id}"  data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                        }

                        if (row.status_id === 1) {
                            // button =  `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-info cls-kolektabilitas" data-id="${row.id}"  data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                            button =  `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-info " data-id="${row.id}"  data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                        }
                        return button
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input class="form-check-input row-check" type="checkbox" name="selected-data[]" value="${row.id}"></label>`;
                    }
                }
            ]
        }
        if (perusahaan_id != '3') {
             columns =  [
                {
                    data: 'id',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'bulan_id',
                    name: 'bulan_id',
                    render: function (data, type, row) {

                        return row.bulan + ' ' + row.tahun;
                    }
                },
                {
                    data: 'nilai_penyaluran_melalui_bri',
                    name: 'nilai_penyaluran_melalui_bri',
                    className: 'text-end',
                    render: function (data, type, row) {
                        let formattedValue = formatCurrency2(data.toString());
                        return `<div class="text-end">${formattedValue}</div>`;
                    }
                },
                {
                    data: 'jumlah_mb',
                    name: 'jumlah_mb',
                    className: 'text-end',
                    render: function (data, type, row) {
                       
                        return `<div class="text-end">${data}</div>`;
                    }
                },
                {
                    data: 'jumlah_mb_naik_kelas',
                    name: 'jumlah_mb_naik_kelas',
                    className: 'text-end',
                    render: function (data, type, row) {
                   
                        return `<div class="text-end">${data}</div>`;
                    }
                },
                {
                    data: 'status_id',
                    name: 'status_id',
                    orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // console.log(row)
                            let status = null
                            if (data === 1) {
                                 status = `<span class="btn cls-log badge badge-light-success fw-bolder me-auto px-4 py-3" data-id="${row.id}">Finish</span>`
                            }
                            if (data === 2) {
                                 status = `<span class="btn cls-log badge badge-light-primary fw-bolder me-auto px-4 py-3" data-id="${row.id}">In Progress</span>`
                            }
                            return status;
                        }
                },
                
                {
                    data: 'action',
                    name: 'action',
                    render: function (data, type, row) {
                        // console.log(row)
                        let button = null;
                        if (row.status_id === 2) {
                            // , minus
                            button =
                                `@can('edit-kegiatan')<button type="button" class="btn btn-sm btn-light btn-icon btn-primary cls-button-edit" data-id="${row.id}"  data-toggle="tooltip" title="Ubah data "><i class="bi bi-pencil fs-3"></i></button>@endcan`

                                // button = button + `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-info" data-id="${row.id}"  data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                        }

                        if (row.status_id === 1) {
                            // button =  `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-info cls-kolektabilitas" data-id="${row.id}"  data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                            button =  `<button type="button" class="btn btn-sm btn-light btn-icon btn-success cls-button-info " data-id="${row.id}"  data-toggle="tooltip" title="Detail data "><i class="bi bi-info fs-3"></i></button>`
                        }
                        return button
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `<label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3"><input class="form-check-input row-check" type="checkbox" name="selected-data[]" value="${row.id}"></label>`;
                    }
                }
            ]
        }
        datatable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: urldatatable,
                type: 'GET',
                data: function (d) {
                    d.perusahaan_id = $('#perusahaan_id').val(),
                        d.tahun = $("#tahun").val(),


                        d.bulan = $('#bulan_id').val()

                }
            },
            columns: columns,
            // footerCallback: function (row, data, start, end, display) {
            //     var api = this.api();

            //     var intVal = function ( i ) {
            //         return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
            //     };

            //     $(api.column(3).footer()).html(api.column(3).data().reduce(function (a, b) {
            //             return addCommas(intVal(a) + intVal(b));
            //         }, 0)
            //     );
            // },
            drawCallback: function (settings) {
                var info = datatable.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                datatable.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                });


            }
        });
    }

    //Imam
    function redirectToNewPage() {
        var selectedPerusahaanId = $('#perusahaan_id').val();
        var selectedTahun = $('#tahun').val();
        var selectedJenisAnggaran = $('#jenis-anggaran').val();

        if (selectedPerusahaanId === '' || selectedTahun === '' || selectedJenisAnggaran === '') {
            swal.fire({
                icon: 'warning',
                html: 'Perusahaan (BUMN), Tahun dan Jenis Anggaran harus terisi!',
                type: 'warning',
                confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
            });
            return
        }

        selectedJenisAnggaran = selectedJenisAnggaran.split(' ').join('-')

        // Use the Laravel's built-in route function to generate the new URL
        var url =
            "{{ route('rencana_kerja.program.create', ['perusahaan_id' => ':perusahaan_id', 'tahun' => ':tahun', 'jenis_anggaran' => ':jenis_anggaran']) }}";
        url = url.replace(':perusahaan_id', selectedPerusahaanId).replace(':tahun', selectedTahun).replace(
            ':jenis_anggaran', selectedJenisAnggaran);

        // Redirect the user to the new page
        window.location.href = url;
    }

    function deleteSelectedProgram(selectedProgram) {
        const jumlahDataDeleted = selectedProgram.length
        swal.fire({
            title: "Pemberitahuan",
            html: "Yakin hapus data ? <br/><span style='color: red; font-weight: bold'>[Data selected: " +
                jumlahDataDeleted + " rows]</span>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data",
            cancelButtonText: "Tidak"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: urldelete,
                    data: {
                        "program_deleted": selectedProgram
                    },
                    type: 'post',
                    dataType: 'json',
                    beforeSend: function () {
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        swal.fire({
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,
                            buttonsStyling: true,
                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });

                        if (data.flag == 'success') {
                            location.reload();
                        }

                    },
                    error: function (jqXHR, exception) {
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });
                    }
                });
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

    function onlyNumbers(e) {
        var ASCIICode = (e.which) ? e.which : e.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }

    function deleteselectedData(selectedData) {
            const jumlahDataDeleted = selectedData.length
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
                        "data_deleted": selectedData
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
                    "pumk_verifikasi": selectedData
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

</script>
@endsection
