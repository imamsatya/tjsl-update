<div>
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-12">
                <!--begin::Label-->
                <label class="col-lg-3 col-form-label fw-semibold fs-6">Route Name</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9 fv-row">
                    <input type="text" name="route_name" id="route_name"
                        class="form-control form-control-lg form-control-solid"
                        placeholder="Route Name"  
                        />                     
                </div>
                <!--end::Col-->
            </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold fs-6">Deskripsi</label>
                <div class="col-lg-9 fv-row">
                    <input type="text" name="deskripsi" id="deskripsi"
                        class="form-control form-control-lg form-control-solid"
                        placeholder="Deskripsi"  
                        />                     
                </div>
            </div>
        </div> -->
    </div>
    <div style="text-align: right; margin-bottom: 30px">
        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
            data-kt-roles-modal-action="cancel">Discard</button>
        <button id="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Submit</span>
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_master">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Route Name</th>
                        <th>Label</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($master_referensi as $index => $data) 
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data->route_name }}</td>
                        <td>{{ $data->label }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-light btn-icon btn-danger cls-button-delete" data-id="{{$data->id}}" data-toggle="tooltip" title="Hapus Master"><i class="bi bi-trash fs-3"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var urladddata = "{{route('referensi.enable_input.save_master')}}";
    var urldeletemaster =  "{{route('referensi.enable_input.delete_master')}}";
    $(document).ready(function () {
        var datatable = $('#datatable_master').DataTable();
        $("#submit").on('click', async function () {
            
            const data = {
                 "route_name" : $("#route_name").val(),
                //  "deskripsi" : $("#deskripsi").val(),
            }

            if(data.route_name == '') {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Isian harus terisi!'
                })
                return
            }


            $.blockUI({
                theme: true,
                baseZ: 2000
            })
            // auto save 
            await saveData(data);
        })

        $("body").on('click', '.cls-button-delete', function() {
            const id = $(this).data('id')
            swal.fire({
                icon: 'warning',
                title: 'Warning',
                html: 'Hapus master referensi ?',
                showCancelButton: true,
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Tidak"
            }).then(async function(result) {
                if(result.value) {
                    $.blockUI({
                        theme: true,
                        baseZ: 2000
                    })
                    // save data here ...
                    await deleteMaster(id);
                }
            })
        })
    })

    function deleteMaster(id) {
        $.ajax({
            url: urldeletemaster,
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                data: id
            },
            dataType: 'json',
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
                    $('#winform').modal('hide');
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
        })
    }

    function saveData(result) {
        $.ajax({
            url: urladddata,
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                data: result
            },
            dataType: 'json',
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
                    $('#winform').modal('hide');
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
        })
    }

</script>
