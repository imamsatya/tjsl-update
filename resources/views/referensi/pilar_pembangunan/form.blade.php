<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $data->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />

    <div class="form-group row">
        <div class="col-lg-12">
            <label>Nama</label>
            <input type="text" class="form-control" name="nama_pilar" id="nama_pilar"
                value="{{ !empty(old('nama_pilar')) ? old('nama_pilar') : ($actionform == 'update' && $data->nama != '' ? $data->nama : old('nama_pilar')) }}"
                required />
        </div>
        {{-- <div class="col-lg-12">
            <label>Keterangan</label>
            <input type="text" class="form-control" name="keterangan" id="keterangan"
                value="{{ !empty(old('keterangan')) ? old('keterangan') : ($actionform == 'update' && $data->keterangan != '' ? $data->keterangan : old('keterangan')) }}" />
        </div> --}}

        {{-- <div class="col-lg-12">
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-6 col-form-label required fw-semibold fs-6">Jenis
                    Anggaran</label> <br>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                    <div class="col">
                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                            <input class="form-check-input" type="radio" value="CID" id="jenis_anggaran_CID"
                                name="jenis_anggaran"
                                {{ old('jenis_anggaran', $data->jenis_anggaran) == 'CID' ? 'checked' : '' }}>
                            <label class="form-check-label" for="jenis_anggaran_CID">
                                CID
                            </label>
                        </label>
                    </div>
                    <div class="col">
                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20 mt-3">
                            <input class="form-check-input" type="radio" value="non CID" id="jenis_anggaran_nonCID"
                                name="jenis_anggaran"
                                {{ old('jenis_anggaran', $data->jenis_anggaran) == 'non CID' ? 'checked' : '' }}>
                            <label class="form-check-label" for="jenis_anggaran_nonCID">
                                Non CID
                            </label>
                        </label>
                    </div>
                </div>

                <!--end::Col-->
            </div>
        </div> --}}

        <div class="form-group row">
            <div class="col-lg-12">
                <label>Keterangan</label>
                <textarea class="form-control" name="keterangan" id="keterangan">{{ !empty(old('keterangan')) ? old('keterangan') : ($actionform == 'update' && $data->keterangan != '' ? $data->keterangan : old('keterangan')) }}</textarea>
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
        $('.modal-title').html(title);
        $('.modal').on('shown.bs.modal', function() {
            setFormValidate();
        });
    });

    function setFormValidate() {
        $('#form-edit').validate({
            rules: {
                nama_pilar: {
                    required: true
                }
            },
            messages: {
                nama_pilar: {
                    required: "Nama Pilar wajib diinput"
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
            },
            submitHandler: function(form) {
                var typesubmit = $("input[type=submit][clicked=true]").val();

                $(form).ajaxSubmit({
                    type: 'post',
                    url: urlupdate,
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
                            title: 'Sukses',
                            html: 'Sukses mengubah data',
                            icon: 'success',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });


                        $('#winform').modal('hide');
                        datatable.ajax.reload(null, false);

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
</script>
