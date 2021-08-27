<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	
    <div class="modal-body">
		<div class="form-group row mb-5">
			<div class="col-lg-12">
                <label>BUMN</label>
                <select class="form-select form-select-solid form-select2" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" data-allow-clear="true">
                    <option></option>
                    @foreach($perusahaan as $p)  
                        <option value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row mb-5">
			<div class="col-lg-6">
                <label>Tahun</label>
                <select class="form-select form-select-solid form-select2" name="tpb_id" data-kt-select2="true" data-placeholder="Pilih Tahun" data-allow-clear="true">
                    <option></option>
                    @php for($i = date("Y"); $i>=2020; $i--){ @endphp
                    <option value="{{$i}}">{{$i}}</option>
                    @php } @endphp
                </select>
			</div>
			<div class="col-lg-6">
				<label>Pilar Pembangunan</label>
                <select class="form-select form-select-solid form-select2" name="pilar_pembangunan_id" data-kt-select2="true" data-placeholder="Pilih Pilar" data-allow-clear="true" onchange="return onChangePilar(this.value)">
                    <option></option>
                    @foreach($pilar as $p)  
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
			</div>
		</div>
        <div class="form-group">
            <div class="col-lg-12">
                <div class="checkbox-tpb">
                </div>
            </div>	
        </div>	
        <div class="text-left pt-10">
            <a id="proses" class="btn btn-primary btn-proses">Proses</a>
        </div>

        <!--begin::Anggaran-->
        <div class="anggaran-header" style="display:none;">
            <h3 class="card-title align-items-start flex-column mt-10">
                <span class="card-label fw-bolder fs-3 mb-1">Anggaran TPB</span>
            </h3>
            <div class="separator border-gray-200 mb-3"></div>
            <div class="form-group row">
                <div class="col-lg-6">
                </div>	
                <div class="col-lg-6">
                    <label><small><i>dalam rupiah penuh</i></small></label>
                </div>	
            </div>
        </div>
        <div class="input-anggaran mb-5">
        </div>
        <div class="anggaran-footer" style="display:none;">
            <button id="submit" type="submit" class="btn btn-success" data-kt-roles-modal-action="submit">
                <span class="indicator-label">Simpan</span>
                <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
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

        $('.btn-proses').on('click', function(event){
            onbtnproses();
        });
    });
    
    function onbtnproses(){
        $('.anggaran-header').show();
        $('.anggaran-footer').show();
        var contentData = '';

        $('input[name=tpb]:checked').each(function(){
            var id = $(this).val;
            var text = $(this).next('span').text();

            contentData += '<div class="form-group row mb-3">';
            contentData += '<div class="col-lg-6">';
            contentData += text;
            contentData += '</div>';
            contentData += '<div class="col-lg-6">';
            contentData += '<input type="hidden" class="form-control" name="tpb_id[]" value="'+id+'" required>';
            contentData += '<input type="text" onkeypress="return onlyNumberKey(event)" class="form-control" name="anggaran[]" required>';
            contentData += '</div>';
            contentData += '</div>';
        });
        
        $(".input-anggaran").html(contentData);
    }
    
    function onChangePilar(id){
        $.ajax({
            url: "/fetch/gettpbbypilar?id="+id,
            type: "POST",
            dataType: "json", 
            success: function(data){
                $(".checkbox-tpb").empty();
                var j=0;
                var contentData = '<label>TPB</label><div class="form-group row mb-5">';
                for(var i = 0, len = data.length; i < len; ++i) {
                    if(j==2){
                        contentData += '</div>';
                        contentData += '<div class="form-group row mb-5">';
                        j=0;
                    }
                    j++;

                    contentData += '<div class="col-lg-6">';
                    contentData += '<label class="form-check form-check-sm form-check-custom form-check-solid">';
                    contentData += '<input name="tpb" class="form-check-input" type="checkbox" value="'+data[i].id+'" />';
                    contentData += '<span class="form-check-label">'+data[i].nama+'</span>';
                    contentData += '</label>';
                    contentData += '</div>';
                }
                contentData += '</div>';
                $(".checkbox-tpb").html(contentData);
            }                       
        });
    }

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
