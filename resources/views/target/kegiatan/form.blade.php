<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	

    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Program</label>
            <select id="target_tpb_id" class="form-select form-select-solid form-select2" name="target_tpb_id" data-kt-select2="true" data-placeholder="Pilih Program" data-allow-clear="true">
                <option></option>
                @foreach($target_tpb as $p)  
                    @php
                        $select = '';
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->program }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Kegiatan</label>
            <input type="text" class="form-control" name="kegiatan" id="kegiatan" value="{{!empty(old('kegiatan'))? old('kegiatan') : ($actionform == 'update' && $data->kegiatan != ''? $data->kegiatan : old('kegiatan'))}}" />
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Provinsi</label>
            <select id="provinsi_id" class="form-select form-select-solid form-select2" name="provinsi_id" data-kt-select2="true" data-placeholder="Pilih Provinsi" data-allow-clear="true">
                <option></option>
                @foreach($provinsi as $p)  
                    @php
                        $select = '';
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Kota</label>
            <select id="kota_id" class="form-select form-select-solid form-select2" name="kota_id" data-kt-select2="true" data-placeholder="Pilih Kota" data-allow-clear="true">
                <option></option>
                @foreach($kota as $p)  
                    @php
                        $select = '';
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Indikator Capaian Kegiatan</label>
            <input type="text" class="form-control" name="indikator" id="indikator" value="{{!empty(old('indikator'))? old('indikator') : ($actionform == 'update' && $data->indikator != ''? $data->indikator : old('indikator'))}}" />
        </div>
        <div class="col-lg-6">
            <label>Satuan Ukur</label>
            <select id="satuan_ukur_id" class="form-select form-select-solid form-select2" name="satuan_ukur_id" data-kt-select2="true" data-placeholder="Pilih Satuan Ukur" data-allow-clear="true">
                <option></option>
                @foreach($satuan_ukur as $p)  
                    @php
                        $select = '';
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
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
    var title = "{{$actionform == 'update'? 'Update' : 'Tambah'}}" + " Kegiatan";

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
                nama:{
                }               		               		                              		               		               
            },
            messages: {
                nama: {
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

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });	                               
                    }
                });
                return false;
        }
        });		
    }
</script>
