<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly" value="{{ (int) $data->id }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />

    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Versi</label>
            <input type="text" class="form-control" name="versi" id="versi" value="{{ $data->versi }}"
                disabled />
        </div>
        <div class="col-lg-6">
            <label>Pilar Pembangunan</label>
            <select class="form-select form-select-solid form-select2" id="select-pilar-option" name="pilar_pembangunan_id"
                data-kt-select2="true" data-placeholder="Pilih Pilar" data-dropdown-parent="#winform"
                data-allow-clear="true" required>
                <option></option>
                @foreach ($pilar as $p)
                    @php
                        $select = $actionform == 'update' && $p->id == $pilar_pembangunan_id ? 'selected="selected"' : '';
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}
                        data-jenis-anggaran="{{ $p->jenis_anggaran }}">{{ $p->nama }} -
                        {{ $p->jenis_anggaran }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-12">
            <label>TPB</label>
            <select disabled="disabled" class="form-select form-select-solid form-select2 select-tpb-option" name="tpb[]" data-kt-select2="true"
                data-placeholder="Pilih TPB" data-dropdown-parent="#winform" data-allow-clear="true" required
                multiple="multiple">
                <option></option>
                @foreach ($tpb as $p)
                    @php
                        $select = $actionform == 'update' && in_array($p->id, $tpb_id) ? 'selected="selected"' : '';
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}
                        data-jenis-anggaran="{{ $p->jenis_anggaran }}">
                        {{ $p->no_tpb . ' - ' . $p->nama . ' - ' . $p->jenis_anggaran }}
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
        $('.modal-title').html(title);
        $('.form-select2').select2();
        $('.modal').on('shown.bs.modal', function() {
            setFormValidate();
        });

        $('.input-tanggal').flatpickr({
            enableTime: false,
            dateFormat: "d-m-Y",
        });

        // Get the original tpb options
        var originalTpbOptions = $('select[name="tpb[]"]').html();

        $('select[name="pilar_pembangunan_id"]').change(function() {
            // Get the selected jenis_anggaran value
            var jenisAnggaran = $(this).find(':selected').data('jenis-anggaran');

            // Filter the tpb options based on the jenis_anggaran value
            var filteredTpbOptions = $(originalTpbOptions).filter(function() {
                return $(this).data('jenis-anggaran') === jenisAnggaran;
            });

            // Update the tpb select input with the filtered options
            $('select[name="tpb[]"]').html(filteredTpbOptions);
        });

        $('.select-tpb-option').on('click', function(){
            let isOptionDisabled = $('.select-tpb-option').prop('disabled')
            if(isOptionDisabled) {
                swal.fire({                    
                    icon: 'warning',
                    html: 'Pilar Pembangunan tidak boleh kosong!',
                    type: 'warning', 
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                });
            }
        })

        $('#select-pilar-option').on('change', function() {
            const idPilar = $(this).select2("data")[0].id
            const jenisAnggaranPilar = $(this).select2("data")[0].element.dataset.jenisAnggaran
            
            $('.select-tpb-option').prop('disabled', false)
            $('.select-tpb-option').select2({
                templateSelection: function(option) {
                    var $select = $('.select-tpb-option')
                    var tag = $(option.element).data("jenis-anggaran");
                    var term = ""
                    if($select.data("select2")) {
                        var $search = $select.data("select2").$dropdown.find(".select2-search__field")
                        if($search.length) {
                            term = $search.val()
                        }

                        if ($select.data("select2").isOpen() && term && tag) {
                            if (tag.toLowerCase().indexOf(term.toLowerCase()) >= 0) {
                                return option.text;
                            } else {
                                return null;
                            }
                        } else {
                            return option.text;
                        }
                    }
                    
                }
            })
        })
    });

    function setFormValidate() {
        $('#form-edit').validate({
            rules: {
                versi: {
                    required: true
                }
            },
            messages: {
                versi: {
                    required: "Versi wajib diinput"
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
                    url: urlstorepilar,
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
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });

                        if (data.flag == 'success') {
                            $('#winform').modal('hide');
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
