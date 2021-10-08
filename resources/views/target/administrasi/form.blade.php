<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />

    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Program</label>
            <input type="text" class="form-control" name="program" id="program" value="{{!empty(old('program'))? old('program') : ($actionform == 'update' && $data->program != ''? $data->program : old('program'))}}" required/>
        </div>
        <div class="col-lg-6">
            <label>Unit Owner</label>
            <input type="text" class="form-control" name="unit_owner" id="unit_owner" value="{{!empty(old('unit_owner'))? old('unit_owner') : ($actionform == 'update' && $data->unit_owner != ''? $data->unit_owner : old('unit_owner'))}}" required/>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Kriteria Program</label>
            <select class="form-select form-select-solid form-select2" name="jenis_program_id" data-kt-select2="true" data-placeholder="Pilih jenis program" data-dropdown-parent="#winform" required>
                <option></option>
                @foreach($jenis_program as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->jenis_program_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Core Subject</label>
            <select class="form-select form-select-solid form-select2" name="core_subject_id" data-kt-select2="true" data-placeholder="Pilih Core Subject" data-dropdown-parent="#winform">
                <option></option>
                @foreach($core_subject as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->core_subject_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Kode Tujuan Tpb</label>
            <select class="form-select form-select-solid form-select2" name="kode_tujuan_tpb_id" data-kt-select2="true" data-placeholder="Pilih Kode Tujuan TPB" data-dropdown-parent="#winform" required>
                <option></option>
                @foreach($kode_tujuan_tpb as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->kode_tujuan_tpb_id == $data->kode_tujuan_tpb_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->kode_tujuan_tpb_id }}" {!! $select !!}>{{ $p->kode }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Kode Indikator</label>
            <select class="form-select form-select-solid form-select2" name="kode_indikator_id" data-kt-select2="true" data-placeholder="Pilih Kode Indikator" data-dropdown-parent="#winform">
                <option></option>
                @foreach($kode_indikator as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->kode_indikator_id == $data->kode_indikator_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->kode_indikator_id }}" {!! $select !!}>{{ $p->kode }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Pelaksanaan Program</label>
            <select class="form-select form-select-solid form-select2" name="cara_penyaluran_id" data-kt-select2="true" data-placeholder="Pilih Pelaksanaan Program" data-dropdown-parent="#winform" required>
                <option></option>
                @foreach($cara_penyaluran as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->cara_penyaluran_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Mitra BUMN</label>
            <select class="form-select form-select-solid form-select2" name="mitra_bumn[]" data-kt-select2="true" data-placeholder="Pilih Mitra BUMN" data-dropdown-parent="#winform" multiple="multiple">
                <option></option>
                @foreach($perusahaan as $p)  
                    @php
                        $select = ($actionform == 'update' && in_array($p->id, $mitra) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Jangka waktu penerapan (dalam tahun)</label>
            <input type="text" style="text-align:right;" class="form-control" name="jangka_waktu" id="jangka_waktu" value="{{@$data->jangka_waktu}}"  onkeypress="return onlyNumberKey(event)"/>
        </div>
        <div class="col-lg-6">
            <label>Alokasi Anggaran</label>
            <input type="text" style="text-align:right;" class="form-control" id="anggaran_alokasi" name="anggaran_alokasi" value="{{number_format($data->anggaran_alokasi,0,',',',')}}"/>
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
        
        $('#anggaran_alokasi').keyup(function(event) {

            // skip for arrow keys
            if(event.which >= 37 && event.which <= 40) return;

            // format number
            $(this).val(function(index, value) {
            return value
            .replace(/\D/g, "")
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            ;
            });
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
    
    function onlyNumberKey(e) {
        var ASCIICode = (e.which) ? e.which : e.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }
</script>
