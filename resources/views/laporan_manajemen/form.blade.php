<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />

    <div class="form-group">
        <div class="col-lg-10">
            <label>File (*.pdf)</label>
            <input class="form-control" type="file" name="file_name" accept="application/pdf" required/>
        </div>
    </div>
    <div class="text-center pt-10">
        <button id="submit" type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Upload</span>
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

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });	                   

                        if(data.flag == 'success') {
                            $('#winform').modal('hide');
                            datatable.ajax.reload( null, false );
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
</script>
