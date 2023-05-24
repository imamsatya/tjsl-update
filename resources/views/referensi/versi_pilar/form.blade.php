<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />

    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Versi</label>
            <input type="text" class="form-control" name="versi" id="versi" value="{{!empty(old('versi'))? old('versi') : ($actionform == 'update' && $data->versi != ''? $data->versi : old('versi'))}}" onkeypress="return onlyNumberKey(event)" required/>
        </div>
        <div class="col-lg-6" style="display:none;">
            <label>Status</label>
            @php
                $checked = 'title="Ubah menjadi Aktif"';
                if($actionform == 'update'){
                    if(@$data->status){
                        $checked = 'title="Ubah menjadi Tidak Aktif" checked="checked"';
                    }
                }
            @endphp
            <label class="form-check form-switch form-check-custom form-check-solid">
                <input name="status" class="form-check-input" type="checkbox" value="1" {{$checked}}/>
            </label>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Tanggal Awal</label>
            <input  type="text" class="form-control input-tanggal" name="tanggal_awal" id="tanggal_awal" value="{{!empty(old('tanggal_awal'))? date('d-m-Y',strtotime(old('tanggal_awal'))) : ($actionform == 'update' && $data->tanggal_awal != ''? date('d-m-Y',strtotime($data->tanggal_awal)) : (empty(old('tanggal_akhir'))?'':date('d-m-Y',strtotime(old('tanggal_awal')))))}}" />
        </div>
        <div class="col-lg-6">
            <label>Tanggal Akhir</label>
            <input type="text" class="form-control input-tanggal" name="tanggal_akhir" id="tanggal_akhir" value="{{!empty(old('tanggal_akhir'))? date('d-m-Y',strtotime(old('tanggal_akhir'))) : ($actionform == 'update' && $data->tanggal_akhir != ''? date('d-m-Y',strtotime($data->tanggal_akhir)) : (empty(old('tanggal_akhir'))?'':date('d-m-Y',strtotime(old('tanggal_akhir')))))}}" />
        </div>
    </div>	
    <div class="form-group row">
        <div class="col-lg-12">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control">{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $data->keterangan != ''? $data->keterangan : old('keterangan'))}}</textarea>
        </div>
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
                versi:{
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
