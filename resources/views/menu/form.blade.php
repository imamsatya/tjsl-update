<form class="kt-form kt-form--label-right" method="POST" id="form-menu">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	<div class="kt-portlet__body">
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Nama Menu</label>
				<div class="kt-input-icon">
					<input type="text" class="form-control" name="label" id="label" value="{{!empty(old('label'))? old('label') : ($actionform == 'update' && $data->label != ''? $data->label : old('label'))}}" />
					<span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="flaticon2-notepad"></i></span></span>
				</div>
			</div>
			<div class="col-lg-4">
				<label>Parent Menu</label>
				<select class="form-control cls-select2" name="parent_id" id="parent_id"  data-dropdown-parent="#form-menu"></select>
				<input type="hidden" id="parent_id_hidden" readonly="readonly" value="{{!empty(old('parent'))? old('parent') : ($actionform == 'update' && $parent != ''? $parent : old('parent'))}}" />
			</div>				
			<div class="col-lg-8">
				<label>Status</label>
				<div class="kt-checkbox-list">
					<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
						<input type="checkbox" name="status" id="status" @if($actionform === 'update' && (bool)$data->status) checked="checked" @endif>
						<span></span>
					</label>
				</div>
			</div>			
		</div>
		<div class="form-group row">
			<div class="col-lg-6">
				<label>Routing Menu</label>
				<div class="kt-input-icon">
					<input type="text" class="form-control cls-routing" name="route_name" id="route_name" value="{{!empty(old('route_name'))? old('route_name') : ($actionform == 'update' && $data->route_name != ''? $data->route_name : old('route_name'))}}" />
					<span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="flaticon2-browser-2"></i></span></span>
				</div>
				<span class="form-text text-muted">Contoh : modules.master.wilayah.index (*sudah ada di web.php) / isikan '#' jika tidak ada URL</span>
			</div>
			<div class="col-lg-3">
				<label>Icon</label>
				<div class="kt-input-icon">
					<input type="text" class="form-control" name="icon" id="icon" value="{{!empty(old('icon'))? old('icon') : ($actionform == 'update' && $data->icon != ''? $data->icon : old('icon'))}}" />
					<span class="kt-input-icon__icon kt-input-icon__icon--right"><span><i class="flaticon-menu-2"></i></span></span>
				</div>
			</div>			
		</div>		
	</div>
	<div class="kt-portlet__foot">
		<div class="kt-form__actions">
			<div class="row">
				<div class="col-lg-6">
					<button type="submit" class="btn btn-primary">Simpan</button>
				</div>
			</div>
		</div>
	</div>
</form>

<script type="text/javascript">
$(document).ready(function(){
	$('.modal').on('shown.bs.modal', function () {
		$('.cls-routing').keyup(function(){
			onRemoveSpace(this);
		});
		setParentSelect2();
		setFormValidate();
		onReadEvent();
	});
});

function setParentSelect2(){
    $('#parent_id').select2({
        width:'100%',
        allowClear: true,
        placeholder: '[ - Pilih Parent Menu - ]',
        ajax: {
            url: urlfetchparentmenu,
            dataType: 'json',
            data: function(params) {
                return {
                    q: params.term, 
                    page: params.page
                };
            },
            processResults: function(data, params) {
                return {
                    results: data.item
                }
            },
            cache: true
        }
    });	
}

function setFormValidate(){
    $('#form-menu').validate({
        rules: {
               label:{
                       required: true,
                       maxlength: 255
               },
               parent_id:{
                       required: true
               },               
               route_name:{
                       required: false,
                       maxlength: 256
               }                            		               		                              		               		               
        },
        messages: {
                   label: {
                       required: "Nama menu wajib diinput",
                       maxlength: "Nama menu maksimal 255 karakter"
                   },
                   parent_id: {
                       required: "Parent menu wajib dipilih"
                   },                   
                   route_name: {
                       required: "Routing menu wajib diinput",
                       maxlength: "Routing menu maksimal 256 karakter"
                   }                                                       		                   		                   
        },       
        ignore: [],
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
	                     setTreeMenu();
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

function onReadEvent(){
	let actionform = $('#actionform').val();
	if(actionform == 'update'){
	   let parent = JSON.parse($('#parent_id_hidden').val());	
       $('#parent_id').select2("trigger", "select", {
       	  data: {
       	  	'id':parseInt(parent.id),
       	  	'text':parent.text
       	  }
       	});		
	}
}
</script>