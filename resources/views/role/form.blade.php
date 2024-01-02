<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $role->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />

    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Nama</label>
            <input type="text" class="form-control" name="name" id="name"
                value="{{ !empty(old('name')) ? old('name') : ($actionform == 'update' && $role->name != '' ? $role->name : old('name')) }}"
                required />
        </div>
        <div class="col-lg-6">
            <label>Keterangan</label>
            <input type="text" type="text" class="form-control" name="keterangan" id="keterangan"
                value="{{ !empty(old('keterangan')) ? old('keterangan') : ($actionform == 'update' && $role->keterangan != '' ? $role->keterangan : old('keterangan')) }}" />
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Permission</label>
            <select class="multi-select" id="permission" name="permission[]" multiple="multiple">
                @foreach ($permission as $value)
                    @php
                        $select = !empty(old('permission')) && in_array($value->id, old('permission')) ? 'selected="selected"' : ($actionform == 'update' && in_array($value->id, $rolePermissions) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $value->id }}" {!! $select !!}>{{ $value->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Akses Menu</label>
            <div id="checkTree"></div>
            <input type="hidden" name="menu" id="menu" readonly="readonly" />
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
    var urlgettreemenubyrole = "{{ route('role.gettreemenubyrole') }}";

    $(document).ready(function() {
        $('.modal-title').html(title);
        $('.modal').on('shown.bs.modal', function() {
            setFormValidate();
        });
        onLoadTreeMenu();
        onLoadMultiSelect();
    });

    function setFormValidate() {
        $('#form-edit').validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Nama wajib diinput"
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
                    url: urlstore,
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });

                        if (data.flag == 'success') {
                            $('#winform').modal('hide');
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });
                    }
                });
                return false;
            }
        });
    }

    function onLoadTreeMenu() {
        var id = $('#id').val() ? '/' + $('#id').val() : '';
        $('#checkTree').jstree({
            'core': {
                'themes': {
                    'responsive': true
                },
                'data': {
                    type: "GET",
                    dataType: 'json',
                    url: urlgettreemenubyrole + id
                }
            },
            'types': {
                'default': {
                    'icon': 'fa fa-folder'
                },
                'file': {
                    'icon': 'fa fa-file'
                }
            },
            'plugins': ['types', 'checkbox']
        }).on('changed.jstree', function(e, data) {
            var i, j, r = [];
            $('#menu').val('');
            for (i = 0, j = data.selected.length; i < j; i++) {
                r.push(data.instance.get_node(data.selected[i]).id);
            }
            $('#menu').val(r);
        });
    }


    function onLoadMultiSelect() {
        $('.multi-select').multiSelect({
            selectableOptgroup: false,
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Cari ...'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Cari ...'>",
            afterInit: function(ms) {
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#' + that.$container.attr('id') +
                    ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.$container.attr('id') +
                    ' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e) {
                        if (e.which === 40) {
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e) {
                        if (e.which == 40) {
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function() {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function() {
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    }
</script>
