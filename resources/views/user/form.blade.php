<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />

    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Nama</label>
            <input type="text" class="form-control" name="name" id="name" value="{{!empty(old('name'))? old('name') : ($actionform == 'update' && $data->name != ''? $data->name : old('name'))}}" required/>
        </div>
        <div class="col-lg-6">
            <label>Username</label>
            <input type="text" class="form-control" name="username" id="username" value="{{!empty(old('username'))? old('username') : ($actionform == 'update' && $data->username != ''? $data->username : old('username'))}}" required/>
        </div>
    </div>	
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Email</label>
            <input type="text" class="form-control" name="email" id="email" value="{{!empty(old('email'))? old('email') : ($actionform == 'update' && $data->email != ''? $data->email : old('email'))}}" required/>
        </div>
        <div class="col-lg-6">
            <label>No HP</label>
            <input type="text" class="form-control" name="handphone" id="handphone" onkeypress="return onlyNumberKey(event)" value="{{!empty(old('handphone'))? old('handphone') : ($actionform == 'update' && $data->handphone != ''? $data->handphone : old('handphone'))}}" />
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>BUMN</label>            
            <select class="form-select form-select-solid form-select2" name="id_bumn" data-kt-select2="true" data-placeholder="Pilih BUMN" data-allow-clear="true" data-dropdown-parent="#kt_content_container">
                <option></option>
                @foreach($perusahaan as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->id_bumn) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Roles</label>           
            <select class="form-select form-select-solid form-select2" name="roles" data-kt-select2="true" data-placeholder="Pilih Role" data-allow-clear="true" data-dropdown-parent="#kt_content_container" >
                <option></option>
                @foreach($role as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->role_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->name }}</option>
                @endforeach
            </select>
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
        $('.form-select').select2();
        $('.modal').on('shown.bs.modal', function () {
            setFormValidate();
        });  
    });

    function setFormValidate(){
        $('#form-edit').validate({
            rules: {
                name:{
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
