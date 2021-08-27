<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	
    <div class="modal-body">
		<div class="form-group row mb-5">
			<div class="col-lg-6">
				<label>Nama Periode</label>
				<input type="text" class="form-control" name="nama" id="nama" value="{{!empty(old('nama'))? old('nama') : ($actionform == 'update' && $data->nama != ''? $data->nama : old('nama'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Jenis Laporan</label>
                <select class="form-select form-select-solid form-select2" name="jenis_laporan" data-kt-select2="true" data-placeholder="Pilih Jenis">
                    <option></option>
                    @php
                        $select = ($actionform == 'update' && ($data->jenis_laporan == 'Manajemen') ? 'selected="selected"' : '');
                    @endphp
                    <option value="Manajemen" {{$select}} >Manajemen</option>
                    @php
                        $select = ($actionform == 'update' && ($data->jenis_laporan == 'PUMK') ? 'selected="selected"' : '');
                    @endphp
                    <option value="PUMK" {{$select}} >PUMK</option>
                </select>
			</div>
		</div>
		<div class="form-group row mb-5">
			<div class="col-lg-6">
				<label>Urutan</label>
				<input type="text" class="form-control" onkeypress="return onlyNumberKey(event)" name="urutan" id="urutan" value="{{!empty(old('urutan'))? old('urutan') : ($actionform == 'update' && $data->urutan != ''? $data->urutan : old('urutan'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" id="keterangan" value="{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $data->keterangan != ''? $data->keterangan : old('keterangan'))}}" />
			</div>
		</div>	
		<div class="form-group row mb-5">
			<div class="col-lg-6">
				<label>Tanggal Awal</label>
				<input  id="kt_datepicker_3" type="text" class="form-control" name="tanggal_awal" id="tanggal_awal" value="{{!empty(old('tanggal_awal'))? old('tanggal_awal') : ($actionform == 'update' && $data->tanggal_awal != ''? $data->tanggal_awal : old('tanggal_awal'))}}" />
			</div>
			<div class="col-lg-6">
				<label>Tanggal Akhir</label>
				<input type="text" class="form-control" name="tanggal_awal" id="tanggal_awal" value="{{!empty(old('tanggal_awal'))? old('tanggal_awal') : ($actionform == 'update' && $data->tanggal_awal != ''? $data->tanggal_awal : old('tanggal_awal'))}}" />
			</div>
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
        $('.form-select2').select2();
        $('.modal-title').html(title);
        $('.modal').on('shown.bs.modal', function () {
            setFormValidate();
        });  
    });

    function setFormValidate(){
        $('#form-edit').validate({
            rules: {
                nama:{
                        required: true
                }               		               		                              		               		               
            },
            messages: {
                nama: {
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
                                type: data.flag,

                                buttonsStyling: false,

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                                confirmButtonClass: "btn btn-default"
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
                                type: 'error',

                                buttonsStyling: false,

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                                confirmButtonClass: "btn btn-default"
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
