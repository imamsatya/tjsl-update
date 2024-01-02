
@section('addbeforecss')
<style>
td{
    border-bottom: 1px solid #c8c7c7;
}
</style>
@endsection

<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id" id="id" readonly="readonly" value="{{$actionform == 'update'? (int)$data->id : null}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />

    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_log">
        <tbody>
            <tr>
                <td><b>Pilar Pembangunan</b></td>
                <td>{{@$data->anggaran_tpb->relasi->pilar->nama}}</td>
                <td><b>TPB</b></td>
                <td>{{@$data->anggaran_tpb->relasi->tpb->no_tpb}} -  {{@$data->anggaran_tpb->relasi->tpb->nama}}</td>
            </tr>
            <tr>
                <td><b>Program</b></td>
                <td>{{@$data->program}}</td>
                <td><b>Owner</b></td>
                @if(!empty($mainOwner))
                <td>
                    @if($mainOwner->nama == "TJSL" || $mainOwner->id == 1)
                    {{ $mainOwner->nama }}
                    @else
                    {{ $mainOwner->nama." - ".@$data->unit_owner}}
                    @endif
                </td>
                @else
                <td>{{ @$data->unit_owner}}</td>
                @endif

            </tr>
            <tr>
                <td><b>Kriteria Program</b></td>
                <td>{{@$data->jenis_program->nama}}</td>
                <td><b>Core Subject</b></td>
                <td>{{@$data->core_subject->nama}}</td>
            </tr>
            <tr>
                <td><b>Kode Tujuan TPB</b></td>
                <td>{{@$data->kode_tujuan_tpb->kode}}</td>
                <td><b>Kode Indikator</b></td>
                <td>{{@$data->kode_indikator->kode}}</td>
            </tr>
            <tr>
                <td><b>Keterangan Tujuan TPB</b></td>
                <td>{{@$data->kode_tujuan_tpb->keterangan}}</td>
                <td><b>Keterangan Indikator</b></td>
                <td>{{@$data->kode_indikator->keterangan}}</td>
            </tr>
            <tr>
                <td><b>Pelaksanaan Program</b></td>
                <td>{{@$data->cara_penyaluran->nama}}</td>
                <td><b>Mitra BUMN</b></td>
                <td>
                    @foreach($mitra_bumn as $mitra)
                        {{$mitra->perusahaan->nama_lengkap}} <br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><b>Jangka Waktu</b></td>
                <td>{{@$data->jangka_waktu}} tahun</td>
                <td><b>Alokasi Anggaran</b></td>
                <td>Rp. {{number_format($data->anggaran_alokasi,0,',',',')}}</td>
            </tr>
        </tbody>
    </table>
    
</form>

<script type="text/javascript">
    var title = "Detail Target TPB";

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
