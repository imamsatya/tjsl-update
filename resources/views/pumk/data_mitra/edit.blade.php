
<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
	
    <div class="form-group row mb-5">
        <div class="col-lg-6">
                <label>Nama Mitra</label>
                <input type="text" class="form-control yellow" value="{{$data->nama_mitra? $data->nama_mitra : "-"}}" disabled>
        </div>
        <div class="col-lg-6">
                <label>No. Identitas</label>
                <input type="text" class="form-control yellow" value="{{$data->no_identitas? $data->no_identitas : "-"}}" disabled>
        </div>
    </div>
    <hr>
    <div class="form-group row  mb-5" >
        <div class="col-lg-6">
            <label>Nomor Pinjaman</label>
            <input type="text" class="form-control " id="no_pinjaman" name="no_pinjaman" value="{{$data->no_pinjaman? $data->no_pinjaman : ""}}" disabled>
        </div>
        <div class="col-lg-6">
            <label>Nominal Pendanaan</label>
            <input type="text" class="form-control number-separator" id="nominal_pendanaan" name="nominal_pendanaan" value="{{$data->nominal_pendanaan? number_format($data->nominal_pendanaan,0,',',',') : 0}}">
        </div>
    </div>
    <div class="form-group row  mb-5" >
        <div class="col-lg-6">
            <label>Nilai Aset</label>
            <input type="text" class="form-control number-separator" id="nilai_aset" name="nilai_aset" value="{{$data->nilai_aset? number_format($data->nilai_aset,0,',',',') : 0}}">
        </div>
        <div class="col-lg-6">
            <label>Nilai Omset</label>
            <input type="text" class="form-control number-separator" id="nilai_omset" name="nilai_omset" value="{{$data->nilai_omset? number_format($data->nilai_omset,0,',',',') : 0}}">
        </div>
    </div>
    <div class="form-group row  mb-5" >
        <div class="col-lg-6">
            <label>Saldo Pokok Pendanaan</label>
            <input type="text" class="form-control number-separator" id="saldo_pokok_pendanaan" name="saldo_pokok_pendanaan" value="{{$data->saldo_pokok_pendanaan?number_format($data->saldo_pokok_pendanaan,0,',',',') : 0}}">
        </div>
        <div class="col-lg-6">
            <label>Saldo Jasa Admin Pendanaan</label>
            <input type="text" class="form-control number-separator" id="saldo_jasa_adm_pendanaan" name="saldo_jasa_adm_pendanaan" value="{{$data->saldo_jasa_adm_pendanaan?number_format($data->saldo_jasa_adm_pendanaan,0,',',',')  : 0}}">
        </div>
    </div>    
    <div class="form-group row  mb-5" >
        <div class="col-lg-6">
            <label>Penerimaan Pokok Bulan Berjalan</label>
            <input type="text" class="form-control number-separator" id="penerimaan_pokok_bulan_berjalan" name="penerimaan_pokok_bulan_berjalan" value="{{$data->penerimaan_pokok_bulan_berjalan?number_format($data->penerimaan_pokok_bulan_berjalan,0,',',',') : 0}}">
        </div>
        <div class="col-lg-6">
            <label>Penerimaan Jasa Bulan Berjalan</label>
            <input type="text" class="form-control number-separator" id="penerimaan_jasa_bulan_berjalan" name="penerimaan_jasa_bulan_berjalan" value="{{$data->penerimaan_jasa_adm_bulan_berjalan?number_format($data->penerimaan_jasa_adm_bulan_berjalan,0,',',',') : 0}}">
        </div>
    </div>
    <div class="form-group row  mb-5" >
        <div class="col-lg-4">
            <label>Kondisi Pinjaman</label>
            <select class="form-select form-select-solid form-select2" id="kondisi_pinjaman_id" name="kondisi_pinjaman_id" data-kt-select2="true" data-placeholder="Pilih Kondisi" data-allow-clear="true">
                <option></option>
                @foreach($kondisi_pinjaman as $kp) 
                @php
                    $select = (($kp->id == $data->kondisi_pinjaman_id) ? 'selected="selected"' : '');
                @endphp 
                    <option value="{{ $kp->id }}" {!! $select !!}>{{ $kp->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4">
            <label>Jenis Pembayaran</label>
            <select class="form-select form-select-solid form-select2" id="jenis_pembayaran_id" name="jenis_pembayaran_id" data-kt-select2="true" data-placeholder="Pilih Jenis" data-allow-clear="true">
                <option></option>
                @foreach($jenis_pembayaran as $kp) 
                @php
                    $select = (($kp->id == $data->jenis_pembayaran_id) ? 'selected="selected"' : '');
                @endphp 
                    <option value="{{ $kp->id }}" {!! $select !!}>{{ $kp->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4">
            <label>Bank Account</label>
            <select class="form-select form-select-solid form-select2" id="bank_account_id" name="bank_account_id" data-kt-select2="true" data-placeholder="Pilih Bank" data-allow-clear="true">
                <option></option>
                @foreach($bank_account as $kp) 
                @php
                    $select = (($kp->id == $data->bank_account_id) ? 'selected="selected"' : '');
                @endphp 
                    <option value="{{ $kp->id }}" {!! $select !!}>{{ $kp->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row  mb-5" >
        <div class="col-lg-4">
            <label>Jumlah SDM</label>
            <input type="text" class="form-control number-separator" id="jumlah_sdm" name="jumlah_sdm" value="{{$data->jumlah_sdm?$data->jumlah_sdm : 0}}">
        </div>
        <div class="col-lg-4">
            <label>Subsektor</label>
            <input type="text" class="form-control " id="subsektor" name="subsektor" value="{{$data->subsektor?$data->subsektor : ''}}">
        </div>
        <div class="col-lg-4">
            <label>Produk/Jasa yang dihasilkan</label>
            <input type="text" class="form-control " id="hasil_produk_jasa" name="hasil_produk_jasa" value="{{$data->hasil_produk_jasa?$data->hasil_produk_jasa : ''}}">
        </div>
    </div>
    <div class="form-group row  mb-5" >
        <div class="col-lg-4">
            <label>Kelebihan Angsuran</label>
            <input type="text" class="form-control number-separator" id="kelebihan_angsuran" name="kelebihan_angsuran" value="{{$data->kelebihan_angsuran?number_format($data->kelebihan_angsuran,0,',',',') : 0}}">
        </div>
        <div class="col-lg-4">
            <label>Tanggal Penerimaan Terakhir</label>
            <input type="date" class="form-control " id="tgl_penerimaan_terakhir" name="tgl_penerimaan_terakhir" value="{{$data->tgl_penerimaan_terakhir?$data->tgl_penerimaan_terakhir : 0}}">
        </div>
        <div class="col-lg-4">
            <label>Kolektibilitas Pendanaan</label>
            <select class="form-select form-select-solid form-select2" id="kolektibilitas_id" name="kolektibilitas_id" data-kt-select2="true" data-placeholder="Pilih Kolekbilitas" data-allow-clear="true">
                <option></option>
                @foreach($kolektibilitas_pendanaan as $kp) 
                @php
                    $select = (($kp->id == $data->kolektibilitas_id) ? 'selected="selected"' : '');
                @endphp 
                    <option value="{{ $kp->id }}" {!! $select !!}>{{ $kp->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <hr>    
    <div class="anggaran-footer" >
        <button id="submit" type="submit" class="btn btn-success" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Update</span>
            <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
</form>

<script src="{{asset('js/easy-number-separator.js')}}"></script>
<script type="text/javascript">
    var title = "{{$actionform == 'update'? 'Update' : 'Tambah'}}" + " {{ $pagetitle }}";

    $(document).ready(function(){
        $('.modal-title').html(title);
        $('.form-select').select2();

        $('.modal').on('shown.bs.modal', function () {
            setFormValidate();
        });  

        // $('.btn-proses').on('click', function(event){
        //     onbtnproses();
        // });

    });
    

    // function onbtnproses(){
    //     $('.anggaran-header').show();
    //     $('.anggaran-footer').show();
    //     onChangePeriode();
    // }
    


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
                    url: "{{route('pumk.data_mitra.store')}}",
                    data: {
                        source : typesubmit,
                    },
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
