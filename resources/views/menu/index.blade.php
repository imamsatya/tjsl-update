@extends('layouts.app')

@section('addbeforecss')
<link href="{{asset('plugins/nestablelist/jquery.nestable.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="d-flex align-items-center">{{ $pagetitle }}
                    <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                        <button type="button" class="btn btn-success btn-sm cls-add" data-kt-view-roles-table-select="delete_selected">Tambah Data</button>
                    </div>
                    <!--end::Search-->
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10">
                    <!--begin: Datatable -->
					<div class="dd" id="nestable_list_3"></div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>

@endsection

@section('addafterjs')
  <script type="text/javascript">
  	var urlgettreemenu = "{{route('menu.gettreemenu')}}";
  	var urlcreate = "{{route('menu.create')}}";
  	var urledit = "{{route('menu.edit')}}";
  	var urlstore = "{{route('menu.store')}}";
  	var urldelete = "{{route('menu.delete')}}";
  	var urlfetchparentmenu = "{{route('general.fetchparentmenu')}}";
  	var urlsubmitchangestructure = "{{route('menu.submitchangestructure')}}";
	$(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

		$('#nestable_list_3').nestable();
		setTreeMenu();

		$('body').on('click','.cls-add',function(){
			winform(urlcreate, {}, 'Tambah Menu');
		});	

		$('body').on('click','.cls-button-edit',function(){
			winform(urledit, {'id':$(this).data('id')}, 'Ubah Menu');
		});		

	$('body').on('change', '.dd', function() {
		onNestChanged(this);
	});  

		$('body').on('click','.cls-button-delete',function(){
			onBtnDelete(this);
		});			
	});

	function setTreeMenu(){
		$.ajax({
		url: urlgettreemenu,
		type:'post',
		dataType:'json',
		beforeSend: function(){
			$.blockUI({
				theme: true,
				baseZ: 2000
			})    
		},
		success: function(data){
			$.unblockUI();

			$('#nestable_list_3').html(data.html);
			$('[data-toggle="tooltip"]').tooltip();
		},
			error: function(jqXHR, exception) {
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
	}

	function onBtnDelete(element){
		swal.fire({
			title: "Pemberitahuan",
			text: "Yakin hapus data menu "+$(element).data('label')+" ?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Ya, hapus data",
			cancelButtonText: "Tidak"
		}).then(function(result) {
			if (result.value) {
				$.ajax({
				url: urldelete,
				data:{id:$(element).data('id')},
				type:'post',
				dataType:'json',
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
						setTreeMenu();
					}
				},
					error: function(jqXHR, exception) {
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
			}
		});		
	}

	function onNestChanged(element){
			$.ajax({
			type: 'post',
			url: urlsubmitchangestructure,
			data:{'serialized' : $('.dd').nestable('serialize')},
			dataType : 'json',
			beforeSend: function(){
				   $.blockUI();
			},
			success: function(data){
					$.unblockUI();
					setTreeMenu();
			},
			error: function (jqXHR, exception) {
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
	}
  </script>
  <script src="{{asset('plugins/nestablelist/jquery.nestable.js')}}" type="text/javascript"></script>
@endsection