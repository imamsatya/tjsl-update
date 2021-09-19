<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	{{-- <input type="text" name="id" id="id" readonly="readonly" value="{{ (int)$data->id }}" /> --}}
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<input type="hidden" name="versi_laporan_id" id="versi_laporan_id" readonly="readonly" value="{{ (int)$versi_id }}" />
    <input type="hidden" name="laporan_keuangan_id" id="laporan_keuangan_id" readonly="readonly" value="{{ (int)$lapor_id }}" />

    <div class="form-group row mb-5">
        <div class="col-lg-4">
            <label>Kode</label>
            <input type="text" class="form-control" name="kode" id="kode" required/>
        </div>
        <div class="col-lg-8">
            <label>Label</label>
            <input type="text" class="form-control" name="label" id="label" required/>
        </div>
        {{-- <div class="col-lg-3">
            <label>Nilai Pengurangan</label>
            <div class="form-check form-check-solid form-switch" style="padding-top: 10px;">
                <input class="form-check-input w-45px h-30px" type="checkbox" name="is_pengurangan" id="googleswitch" >
                <label class="form-check-label" for="googleswitch"></label>
            </div>
        </div> --}}
    </div>
    <div class="text-center pt-15">
        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" data-kt-roles-modal-action="cancel">Discard</button>
        <button id="submit" type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Submit</span>
            <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
</form>

<script type="text/javascript">
    var title = "{{$actionform == 'update'? 'Update' : 'Tambah'}}" + " {{ $pagetitle }}";

    $(document).ready(function(){
        $('.modal-title').html(title);
        $('.form-select2').select2();
        $('.modal').on('shown.bs.modal', function () {
            setFormValidate();
        });  
        
        $('.input-tanggal').flatpickr({
			enableTime: false,
			dateFormat: "d-m-Y",
		});
    });

    function setFormValidate(){
        $('#form-edit').validate({
            rules: {
                kode:{
                    required: true
                }               		               		                              		               		               
            },
            messages: {
                kode: {
                    required: "kode wajib diinput"
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
                    url: urlstoreParent,
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
                            $('#winform').modal('hide');
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
    
    function onlyNumberKey(e) {
        var ASCIICode = (e.which) ? e.which : e.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>
