<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id_cid" id="id_cid" readonly="readonly" value="{{$actionform == 'update'? ($data_cid ? (int)$data_cid->id : null) : null}}" />
    <input type="hidden" name="id_noncid" id="id_noncid" readonly="readonly" value="{{$actionform == 'update'? ($data_noncid ? (int)$data_noncid->id : null) : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
    @if(!$isOkToInput && !$isEnableInputBySuperadmin && !$isSuperAdmin)                       
    <!--begin::Alert-->
    <div class="alert alert-danger d-flex align-items-center p-5" style="    border-radius: 0.5em;background-color: #fff5f8;color: #f1416c;border-color: #f1416c">
        <!--begin::Icon-->
        <i class=" bi-shield-fill-x fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
        <!--end::Icon-->

        <!--begin::Wrapper-->
        <div class="d-flex flex-column">
            <!--begin::Title-->
            <h4 class="mb-1 text-danger">PENGUMUMAN</h4>
            <!--end::Title-->

            <!--begin::Content-->
            <span>Tidak bisa input data karena diluar periode laporan!</span>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Alert-->
    @endif
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>BUMN</label>
            <select class="form-select form-select-solid form-select2" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" disabled="disabled">
                <option></option>
                <option value="{{ $perusahaan_selected->id }}" selected>{{ $perusahaan_selected->nama_lengkap }}</option>
            </select>
        </div>
        <div class="col-lg-6">
            <label>TPB</label>
            <select class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true" data-placeholder="Pilih TPB" disabled="disabled">
                <option></option>
                <option value="{{ $tpb_selected->no_tpb }}" selected>{{ $tpb_selected->no_tpb . ' - ' . $tpb_selected->nama }}</option>
            </select>
        </div>
    </div>   
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>CID</label>
            @if($data_cid)            
            <input type="text" class="form-control input-anggaran" style="text-align:right;" name="anggaran_cid" value="{{  number_format($data_cid->anggaran,0,',',',') }}" required>
            @else
            <input type="text" class="form-control" disabled>
            @endif
        </div>
        <div class="col-lg-6">
            <label>NON CID</label>
            @if($data_noncid)
            <input type="text" class="form-control input-anggaran" style="text-align:right;" name="anggaran_noncid" value="{{  number_format($data_noncid->anggaran,0,',',',') }}" required>
            @else
            <input type="text" class="form-control" disabled>
            @endif
        </div>
    </div>
    <div class="text-center pt-15">
        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" data-kt-roles-modal-action="cancel">Discard</button>
        @if($isOkToInput || $isEnableInputBySuperadmin || $isSuperAdmin)
        <button id="submit" type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Simpan</span>
            <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
        @endif
    </div>
</form>

<script type="text/javascript">
    var title = "{{$actionform == 'update'? 'Update' : 'Tambah'}}" + " {{ $pagetitle }}";

    $(document).ready(function(){
        $('.modal-title').html(title);
        $('.form-select').select2();

        $('.modal').on('shown.bs.modal', function () {
            setFormValidate();
        });  

        $('.input-anggaran').keyup(function(event) {

            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40) return;

            // format number
            $(this).val(function(index, value) {
            return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            ;
            });
        });
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
                    url: urlstore,
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

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });	                   

                        if(data.flag == 'success') {
                            $('#winform').modal('hide');
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

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
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
