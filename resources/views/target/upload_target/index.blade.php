@extends('layouts.app')

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
                    <!--end::Search-->
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10">
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
                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true">
                                @php for($i = date("Y"); $i>=2020; $i--){ @endphp
                                    @php
                                        $select = (($i == $tahun) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6 ">
                            <label>File</label>
                            <input class="form-control" type="file" name="file_name" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                        </div>
                        <div class="col-lg-4 pt-6">
                            <button id="proses" class="btn btn-success me-3">Proses</button>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_log">
                        <thead>
                            <tr>
                                <th rowspan="2" style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">No.</th>
                                <th rowspan="2" style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Tanggal</th>
                                <th rowspan="2" style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Nama File</th>
                                <th colspan="2" style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Hasil Upload</th>
                                <th colspan="2" style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">File </th>
                                <th rowspan="2" style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Keterangan</th>
                            </tr>
                            <tr>
                                <th style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Berhasil</th>
                                <th style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Gagal</th>
                                <th style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Berhasil</th>
                                <th style="font-weight:bold;border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">Gagal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            <tr>
                                <td style="border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">{{$no++}}</td>
                                <td style="border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">{{date('Y-m-d H:i:s')}}</td>
                                <td style="border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">template target TPB.xls</td>
                                <td style="border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">20</td>
                                <td style="border: 1px solid #c8c7c7;text-align:center;vertical-align:middle">20</td>
                                <td style="border: 1px solid #c8c7c7;text-align:center;vertical-align:middle"><button type="button" class="btn btn-primary btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download Excel"><i class="bi bi-download fs-3"></i></button></td>
                                <td style="border: 1px solid #c8c7c7;text-align:center;vertical-align:middle"><button type="button" class="btn btn-primary btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download Excel"><i class="bi bi-download fs-3"></i></button></td>
                                <td style="border: 1px solid #c8c7c7;text-align:left;vertical-align:middle">
                                    1. Baris 1 isian tidak sesuai kode<br>
                                    2. Baris 2 isian tidak sesuai kode<br>
                                    3. Baris 3 isian tidak sesuai kode<br>
                                </td>
                            </tr>
                            <tr>
                                <!-- <td colspan="4" style="text-align:center;font-style:italic">Tidak ada data</td> -->
                            </tr>
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
    var urlstore = "{{route('target.upload_target.store')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','.cls-upload',function(){
            winform(urlupload, {}, 'Upload Data');
        });
        
        $('#cari').on('click', function(event){
            // datatable.ajax.reload()
            var url = window.location.origin + '/target/upload/index';
            var perusahaan_id = $('#perusahaan_id').val();
            var tahun = $('#tahun').val();
            var pilar_pembangunan_id = $('#pilar_pembangunan_id').val();
            var tpb_id = $('#tpb_id').val();
            var status_id = $('#status_id').val();

            window.location.href = url + '?perusahaan_id=' + perusahaan_id + '&tahun=' + tahun + '&pilar_pembangunan_id=' + pilar_pembangunan_id + '&tpb_id=' + tpb_id + '&status_id=' + status_id;
        });
    });

</script>
@endsection
