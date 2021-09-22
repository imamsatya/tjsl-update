<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->kegiatan_realisasi_id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	

    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Bulan</label>
            <select id="bulan" class="form-select form-select-solid form-select2" name="bulan" data-kt-select2="true" data-placeholder="Pilih Bulan" required>
                <option></option>
                @foreach($bulans as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->bulan) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Tahun</label>
            <select id="tahun" class="form-select form-select-solid form-select2" name="tahun" data-kt-select2="true" data-placeholder="Pilih Tahun" required>
                <option></option>
                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                    @php
                        $select = (($i == $data->tahun) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                @php } @endphp
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Program</label>
            <select id="target_tpb_id" class="form-select form-select-solid form-select2" name="target_tpb_id" data-kt-select2="true" data-placeholder="Pilih Program" required>
                <option></option>
                @foreach($target_tpb as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->target_tpb_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->program }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Kegiatan</label>
            <input type="text" class="form-control" name="kegiatan" id="kegiatan" value="{{!empty(old('kegiatan'))? old('kegiatan') : ($actionform == 'update' && $data->kegiatan != ''? $data->kegiatan : old('kegiatan'))}}"  required/>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Provinsi</label>
            <select id="provinsi_id" class="form-select form-select-solid form-select2" name="provinsi_id" data-kt-select2="true" data-placeholder="Pilih Provinsi" required>
                <option></option>
                @foreach($provinsi as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->provinsi_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-6">
            <label>Kota</label>
            <select id="kota_id" class="form-select form-select-solid form-select2" name="kota_id" data-kt-select2="true" data-placeholder="Pilih Kota" required>
                <option></option>
                @foreach($kota as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->kota_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Indikator Capaian Kegiatan</label>
            <input type="text" class="form-control" name="indikator" id="indikator" value="{{!empty(old('indikator'))? old('indikator') : ($actionform == 'update' && $data->indikator != ''? $data->indikator : old('indikator'))}}"  required/>
        </div>
        <div class="col-lg-6">
            <label>Satuan Ukur</label>
            <select id="satuan_ukur_id" class="form-select form-select-solid form-select2" name="satuan_ukur_id" data-kt-select2="true" data-placeholder="Pilih Satuan Ukur"  required>
                <option></option>
                @foreach($satuan_ukur as $p)  
                    @php
                        $select = ($actionform == 'update' && ($p->id == $data->satuan_ukur_id) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Target</label>
            <input type="text" style="text-align:right;" class="form-control" name="target" id="target" value="{{!empty(old('target'))? old('target') : ($actionform == 'update' && $data->target != ''?  number_format($data->target,0,',',',') : old('target'))}}"  required/>
        </div>
        <div class="col-lg-6">
            <label>Realisasi</label>
            <input type="text" style="text-align:right;" class="form-control" name="realisasi" id="realisasi" value="{{!empty(old('realisasi'))? old('realisasi') : ($actionform == 'update' && $data->realisasi != ''?  number_format($data->realisasi,0,',',',') : old('realisasi'))}}"  required/>
        </div>
    </div>
    <div class="form-group row mb-5">
        <div class="col-lg-6">
            <label>Alokasi Anggaran</label>
            <input type="text" style="text-align:right;" class="form-control" name="anggaran_alokasi" id="anggaran_alokasi" value="{{!empty(old('anggaran_alokasi'))? old('anggaran_alokasi') : ($actionform == 'update' && $data->anggaran_alokasi != ''?  number_format($data->anggaran_alokasi,0,',',',') : old('anggaran_alokasi'))}}"  required/>
        </div>
        <div class="col-lg-6">
            <label>Realisasi Anggaran</label>
            <input type="text" style="text-align:right;" class="form-control" name="anggaran" id="anggaran" value="{{!empty(old('anggaran'))? old('anggaran') : ($actionform == 'update' && $data->anggaran != ''?  number_format($data->anggaran,0,',',',') : old('anggaran'))}}"  required/>
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
        $('.form-select2').select2();
        $('.modal').on('shown.bs.modal', function () {
            setFormValidate();
        });  
    });

    function setFormValidate(){
        $('#form-edit').validate({      
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
