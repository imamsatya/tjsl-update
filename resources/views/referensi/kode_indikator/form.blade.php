<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />

    <div class="form-group row mb-5">
        <div class="col-lg-12">
            <label>TPB</label>
            <select class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true" data-placeholder="Pilih TPB" data-dropdown-parent="#form-edit" required>
                <option></option>
                @foreach($tpb as $p)  
                    @php
                        $select = ($actionform == 'update' && $p->id==$data->tpb_id ? 'selected="selected"' : '');
                    @endphp 
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->no_tpb . ' - ' . $p->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Kode Tujuan TPB</label>
            <input type="text" class="form-control" name="kode_tujuan_tpb" id="kode_tujuan_tpb" value="{{!empty(old('kode_tujuan_tpb'))? old('kode_tujuan_tpb') : ($actionform == 'update' && $data->kode_tujuan_tpb != ''? $data->kode_tujuan_tpb : old('kode_tujuan_tpb'))}}" required/>
        </div>
        <div class="col-lg-6">
            <label>Kode Indikator</label>
            <input type="text" class="form-control" name="kode" id="kode" value="{{!empty(old('kode'))? old('kode') : ($actionform == 'update' && $data->kode != ''? $data->kode : old('kode'))}}" required/>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Keterangan Tujuan TPB</label>
            <textarea class="form-control" name="keterangan_tujuan_tpb" id="keterangan_tujuan_tpb" required/>{{!empty(old('keterangan_tujuan_tpb'))? old('keterangan_tujuan_tpb') : ($actionform == 'update' && $data->keterangan_tujuan_tpb != ''? $data->keterangan_tujuan_tpb : old('keterangan_tujuan_tpb'))}}</textarea>
        </div>
        <div class="col-lg-6">
            <label>Keterangan Indikator</label>
            <textarea class="form-control" name="keterangan" id="keterangan" required/>{{!empty(old('keterangan'))? old('keterangan') : ($actionform == 'update' && $data->keterangan != ''? $data->keterangan : old('keterangan'))}}</textarea>
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
