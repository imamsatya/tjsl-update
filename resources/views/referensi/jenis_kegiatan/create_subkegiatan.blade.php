<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $subkegiatan->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />

    <div class="form-group row mb-5">


        <div class="col-lg-12 mb-2">
            <label>Kegiatan Utama</label>
            <select class="form-select form-select-solid form-select2" name="kegiatan_utama" data-kt-select2="true"
                data-placeholder="Pilih Kegiatan Utama" required>
                <option></option>
                @foreach ($main_kegiatan as $main_kegiatan_row)
                    @php
                        $select = $actionform == 'update' && $main_kegiatan_row->id == $subkegiatan->jenis_kegiatan_id ? 'selected="selected"' : '';
                    @endphp
                    <option value="{{ $main_kegiatan_row->id }}" {{ $select }}>{{ $main_kegiatan_row->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-12 mb-2">
            <label>Sub Kategori</label>
            <input type="text" class="form-control" name="subkegiatan" id="subkegiatan"
                value="{{ !empty(old('subkegiatan')) ? old('subkegiatan') : ($actionform == 'update' && $subkegiatan->subkegiatan != '' ? $subkegiatan->subkegiatan : old('subkegiatan')) }}"
                required />
        </div>
        <div class="col-lg-12 mb-2">
            <label>Satuan Ukur</label>
            <select class="form-select form-select-solid form-select2" name="satuan_ukur" data-kt-select2="true"
                data-placeholder="Pilih Satuan Ukur" required>
                <option></option>
                @foreach ($satuan_ukur as $satuan_ukur_row)
                    @php
                        $select = $actionform == 'update' && $satuan_ukur_row->id == $subkegiatan->satuan_ukur_id ? 'selected="selected"' : '';
                    @endphp
                    <option value="{{ $satuan_ukur_row->id }}" {{ $select }}>{{ $satuan_ukur_row->nama }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="text-center pt-15">
        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
            data-kt-roles-modal-action="cancel">Discard</button>
        <button id="submit" type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Submit</span>
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
</form>

<script type="text/javascript">
    var title = "{{ $actionform == 'update' ? 'Update' : 'Tambah' }}" + " {{ $pagetitle }}";

    $(document).ready(function() {
        $('.form-select2').select2();
        $('.modal-title').html(title);
        $('.modal').on('shown.bs.modal', function() {
            setFormValidate();
        });




    });

    function setFormValidate() {
        $('#form-edit').validate({
            rules: {
                kegiatan_utama: {
                    required: true
                },
                subkegiatan: {
                    required: true
                },
                satuan_ukur: {
                    required: true
                }
            },
            messages: {
                kegiatan_utama: {
                    required: "Kegiatan utama harus dipilih"
                },
                subkegiatan: {
                    required: "Sub Kegiatan harus diisi"
                },
                satuan_ukur: {
                    required: "Satuan ukur harus dipilih"
                }
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
                if (element.parent('.validated').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }

                if (element.attr("name") === "kegiatan_utama" || element.attr("name") === "satuan_ukur") {
                    error.insertAfter(element.parent().find(".select2-container"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var typesubmit = $("input[type=submit][clicked=true]").val();
                var namaPeriode = $('#nama-periode').val();
                console.log(namaPeriode)
                $(form).ajaxSubmit({
                    type: 'post',
                    url: urlstore_subkegiatan,
                    data: {
                        source: typesubmit
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $.blockUI({
                            theme: true,
                            baseZ: 2000
                        })
                    },
                    success: function(data) {
                        $.unblockUI();

                        swal.fire({
                            title: "Success",
                            html: data.data.msg,
                            icon: 'success',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });

                        // if (data.flag == 'success') {
                        //     $('#winform').modal('hide');
                        //     datatable.ajax.reload(null, false);
                        //     datatable_tentatif.ajax.reload(null, false);
                        // }
                        $('#winform').modal('hide');
                        datatable2.ajax.reload(null, false);
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
                return false;
            }
        });
    }

    function onlyNumberKey(e) {
        var ASCIICode = (e.which) ? e.which : e.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>
