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
                <form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	                @csrf
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6 ">
                            <label>File (*.xlsx)</label>
                            <input class="form-control" type="file" name="file_name" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                        </div>
                        <div class="col-lg-4 pt-6">
                            <button  id="submit" type="submit" class="btn btn-success me-3">Proses</button>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align:center;vertical-align:middle">No.</th>
                                <th rowspan="2" style="text-align:center;vertical-align:middle">Tanggal</th>
                                <th rowspan="2" style="text-align:center;vertical-align:middle">Nama File</th>
                                <th colspan="2" style="text-align:center;vertical-align:middle">Hasil Upload</th>
                                <th colspan="2" style="text-align:center;vertical-align:middle">File </th>
                                <th rowspan="2" style="text-align:center;vertical-align:middle">Keterangan</th>
                            </tr>
                            <tr>
                                <th style="text-align:center;vertical-align:middle">Berhasil</th>
                                <th style="text-align:center;vertical-align:middle">Gagal</th>
                                <th style="text-align:center;vertical-align:middle">Berhasil</th>
                                <th style="text-align:center;vertical-align:middle">Gagal</th>
                            </tr>
                        </thead>
                    </table>
                </form>
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>
@endsection

@section('addafterjs')
<script>
    var urluploadstore = "{{route('target.upload_target.store')}}";
    var urldatatable = "{{route('target.upload_target.datatable')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        setFormValidate();
        setDatatable();
    });

    function setFormValidate(){
        $('#form-edit').validate({
            rules: {            		               		                              		               		               
            },
            messages: {                                   		                   		                   
            },	        
            highlight: function(element) {
                $(element).closest('.form-control').addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).closest('.form-control').removeClass('is-invalid');
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            errorPlacement: function(error, element) {
                if(element.parent('.validated').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
        submitHandler: function(form){
                var typesubmit = $("input[type=submit][clicked=true]").val();
                
                $(form).ajaxSubmit({
                    type: 'post',
                    url: urluploadstore,
                    data: {source : typesubmit},
                    dataType : 'json',
                    beforeSend: function(){
                        $.blockUI({
                            theme: true,
                            baseZ: 2000
                        })    
                    },
                    success: function(data){
                        $.unblockUI();

                        swal.fire({
                                title: data.title,
                                html: data.msg,
                                icon: data.flag,

                                buttonsStyling: true,

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });	                   

                        if(data.flag == 'success') {
                            // $('#winform').modal('hide');
                            // datatable.ajax.reload( null, false );
                            location.reload(); 
                        }
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
        });		
    }

    function setDatatable(){
        datatable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: urldatatable,
            columns: [
                { data: 'id', orderable: false, searchable: false },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'file_name', name: 'file_name' },
                { data: 'berhasil', name: 'berhasil' },
                { data: 'gagal', name: 'gagal' },
                { data: 'download_berhasil', name: 'download_berhasil' },
                { data: 'download_gagal', name: 'download_gagal' },
                { data: 'keterangan', name: 'keterangan' },
            ],
            drawCallback: function( settings ) {
                var info = datatable.page.info();
                $('[data-toggle="tooltip"]').tooltip();
                datatable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                } );
            }
        });
    }

</script>
@endsection
