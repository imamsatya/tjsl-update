<div>
    <div class="row">
        <div class="col-md-6">
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-2 col-form-label fw-semibold fs-6">Tipe</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-10 fv-row">
                    <select class="form-select form-select-solid form-select2" id="tipe_add" name="tipe_add"
                        data-kt-select2="true" data-placeholder="Pilih Tipe" data-allow-clear="true" 
                        data-dropdown-parent="#winform">
                        <option></option>
                        @foreach($master_referensi as $mp)
                            <option value="{{ $mp->id }}" {{ $mp->id === $referensi_selected ? 'selected' : ''}}>{{$mp->label}}</option>
                        @endforeach
                    </select>
                </div>
                <!--end::Col-->
            </div>
        </div>
        <div class="col-md-6">
            <div class="row mb-6">
                    <!--begin::Label-->
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Tahun</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-9 fv-row">
                        <select class="form-select form-select-solid form-select2" id="tahun_add" name="tahun_add"
                            data-kt-select2="true" data-placeholder="Pilih Tahun" data-dropdown-parent="#winform">
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-1 col-form-label fw-semibold fs-6">BUMN</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-11 fv-row">
                    <select class="form-select form-select-solid form-select2" id="perusahaan_id_add" name="perusahaan_id_add"
                        data-kt-select2="true" data-placeholder="Pilih BUMN" data-allow-clear="true" data-dropdown-parent="#winform" multiple="multiple">
                        <option></option>
                        <option value="all">SELECT ALL</option>
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
        </div>
    </div>
</div>
<div class="text-center pt-15">
    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
        data-kt-roles-modal-action="cancel">Discard</button>
    <button id="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
        <span class="indicator-label">Submit</span>
        <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
    </button>
</div>

<script>
    var urladddata = "{{route('referensi.enable_input.save')}}";
    $(document).ready(function () {

        $('.form-select').select2();

        $("#submit").on('click', async function () {
            
            const data = {
                 "tahun" : $("#tahun_add").val(),
                 "tipe" : $("#tipe_add").val(),
                 "bumn" : $("#perusahaan_id_add").val()
            }

            if(data.tahun == '' || data.tipe == '' || !data.bumn.length ) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Isian harus terisi!'
                })
                return
            }

            // trigger warning!
            if(data.bumn[0] === 'all') {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Semua perusahaan akan di-enable ?',
                    showCancelButton: true,
                    confirmButtonText: "Ya, simpan data",
                    cancelButtonText: "Tidak"
                }).then(async function(result) {
                    if(result.value) {
                        $.blockUI({
                            theme: true,
                            baseZ: 2000
                        })
                        // save data here ...
                        await saveData(data);
                    }
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
    })

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
        })
    }

</script>
