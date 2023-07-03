<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	@csrf
	<input type="hidden" name="id_program" id="id_program" readonly="readonly" value="{{$actionform == 'update'? $id_program : null}}" />
    <input type="hidden" name="tahun_edit" id="tahun_edit" readonly="readonly" value="{{$tahun}}" />
    <input type="hidden" name="perusahaan_edit" id="perusahaan_edit" readonly="readonly" value="{{$perusahaan_id}}" />
	<input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{$actionform}}" />
    <input type="hidden" name="kriteria_used" id="kriteria_used" readonly="readonly" value="" />

    @if(!$isOkToInput && !$isEnableInputBySuperadmin)
    <!--begin::Alert-->
    <div class="alert alert-danger d-flex align-items-center p-5" style="    border-radius: 0.5em;background-color: #fff5f8;color: #f1416c;border-color: #f1416c">
        <!--begin::Icon-->
        <i class=" bi-shield-fill-x fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
        <!--end::Icon-->

        <!--begin::Wrapper-->
        <div class="d-flex flex-column">
            <!--begin::Title-->
            <h4 class="mb-1 text-danger">PENGUMUMAN</h4>
            <!--end::Title-->

            <!--begin::Content-->
            <span>Tidak bisa input data karena diluar periode laporan!</span>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Alert-->
    @endif

    <div class="mb-6 ">                                
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Pilih TPB<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <select {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} id="tpb_id_edit" class="form-select form-select-solid form-select2" name="tpb_id_edit" data-kt-select2="true"  data-placeholder="Pilih TPB" data-allow-clear="true">
                    <option></option>
                    @foreach($tpb as $p)                                                
                        <option {{ $data->tpb_id === $p->id ? "selected='selected'" : '' }} value="{{ $p->id }}">{{ $p->no_tpb }} - {{ $p->nama }} [{{$p->jenis_anggaran}}]</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Nama Program<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <textarea {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} class="form-control" id="nama_program_edit" name="nama_program_edit" style="height: 100px">{{ $data->program }}</textarea>                
            </div>
        </div>        
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Unit Owner</div>
            </div>
            <div class="col-lg-9">                
                <input {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} value="{{ $data->unit_owner }}" type="text" name="unit_owner_edit" id="unit_owner_edit"
                    class="form-control form-control-lg form-control-solid"
                    placeholder="Unit Owner"  
                    />  
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Kriteria Program<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9 fv-row d-flex align-items-center justify-content-start">
                <div style="display:flex; flex-direction: row;">
                    <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                        <input {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} {{ $data->kriteria_program_prioritas ? 'checked="checked"' : '' }} class="form-check-input" type="checkbox" name="kriteria_program_edit" value="prioritas" id="checkboxPrioritas_edit"/>
                        <label class="form-check-label" for="checkboxPrioritas_edit">
                            Prioritas
                        </label>
                    </div> 
                    <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                        <input {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} {{ $data->kriteria_program_csv ? 'checked="checked"' : '' }} class="form-check-input" type="checkbox" name="kriteria_program_edit" value="csv" id="checkboxCSV_edit"/>
                        <label class="form-check-label" for="checkboxCSV_edit">
                            CSV
                        </label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid form-check-sm">
                        <input {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} {{ $data->kriteria_program_umum ? 'checked="checked"' : '' }} class="form-check-input" type="checkbox" name="kriteria_program_edit" value="umum" id="checkboxUmum_edit"/>
                        <label class="form-check-label" for="checkboxUmum_edit">
                            Umum
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">ID Core Subject<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <select {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} id="core_subject_id_edit" class="form-select form-select-solid form-select2" name="core_subject_id_edit" data-kt-select2="true" data-placeholder="Pilih ID Core Subject" data-allow-clear="true">
                    <option></option>
                    @foreach($core_subject as $c)                                              
                        <option {{ $data->core_subject_id === $c->id ? 'selected="selected"' : '' }} value="{{ $c->id }}" >{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Pelaksanaan Program<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <select {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} id="pelaksanaan_program_edit" class="form-select form-select-solid form-select2" name="pelaksanaan_program_edit" data-kt-select2="true" data-placeholder="Pilih Pelaksanaan Program" data-allow-clear="true">
                    <option></option>
                    <option value="Mandiri" {{ $data->pelaksanaan_program === 'Mandiri' ? 'selected="selected"' : '' }} >
                            Mandiri</option>
                    <option value="Kolaborasi" {{ $data->pelaksanaan_program === 'Kolaborasi' ? 'selected="selected"' : '' }} >
                        Kolaborasi</option>
                </select>
            </div>
        
        </div>

        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Mitra BUMN</div>
            </div>
            <div class="col-lg-9">
                <select {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} class="form-select form-select-solid form-select2" id="mitra_bumn_edit" name="mitra_bumn_edit" data-kt-select2="true" data-placeholder="Pilih Mitra BUMN" data-allow-clear="true" >
                    <option></option>
                    @foreach($perusahaan as $p)                                                  
                        <option {{ $data->mitra_bumn_id === $p->id ? 'selected="selected"' : '' }} value="{{ $p->id }}">{{ $p->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>
        
        </div>

        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Program Multi Years<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9 fv-row d-flex align-items-center justify-content-start">
                <div style="display:flex; flex-direction: row;">
                    <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                        <input {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} {{ $data->multi_years ? 'checked="checked"' : '' }} class="form-check-input" type="radio" name="program_edit" id="multiyears_ya_edit" value="ya"/>
                        <label class="form-check-label" for="multiyears_ya_edit">
                            Ya
                        </label>
                    </div> 
                    <div class="form-check form-check-custom form-check-solid form-check-sm me-8">
                        <input {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} {{ !$data->multi_years ? 'checked="checked"' : '' }} class="form-check-input" type="radio" name="program_edit" id="multiyears_tidak_edit" value="tidak"/>
                        <label class="form-check-label" for="multiyears_tidak_edit">
                            Tidak
                        </label>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Alokasi Anggaran<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input {{ !$isOkToInput && !$isEnableInputBySuperadmin ? 'disabled' : '' }} type="text" name="alokasi_anggaran_edit" id="alokasi_anggaran_edit"
                    class="form-control form-control-lg form-control-solid"
                    placeholder="Rp ... " oninput="formatCurrency2(this)" 
                    onkeypress="return onlyNumbers(event)" style="text-align:right;"  value="{{ number_format($data->anggaran_alokasi,0,',',',')}}"
                    />
            </div>
        </div>
    </div>
    <div class="text-center pt-15">
        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" data-kt-roles-modal-action="cancel">Discard</button>
        @if(!(!$isOkToInput && !$isEnableInputBySuperadmin))
        <button id="submit" type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Simpan</span>
            <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
        @endif
    </div>
</form>

<script type="text/javascript">
    var title = "{{$actionform == 'update'? 'Update' : 'Tambah'}}" + " {{ $pagetitle }}";
    var urleditstore = "{{route('rencana_kerja.program.edit_store')}}";

    $(document).ready(function(){
        $('.modal-title').html(title);
        $('.form-select').select2();         

        formValidate()

        $(".modal").on('hidden.bs.modal', function() {
            const jenisAnggaran = $("#jenis-anggaran").val()
            $("#tpb_id").val('').trigger('change')
            
            
            $("#tpb_id").select2({    
                templateResult: function(data) {
                    if($(data.element).attr('data-jenis-anggaran') === jenisAnggaran || jenisAnggaran === '') return data.text
                    return null
                },
                templateSelection: function(data) {
                    if($(data.element).attr('data-jenis-anggaran') === jenisAnggaran || jenisAnggaran === '') return data.text
                    return null
                }
            })            

            let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
            $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB '+textAnggaran)
        })     
        
        $('#form-edit').on('submit', function(event) {
            event.preventDefault()

            const kriteria_program_checkboxes = document.getElementsByName("kriteria_program_edit"); // mengambil semua checkbox dengan name="kriteria_program"
            const selectedKriteriaProgram = []; // deklarasi array untuk menyimpan nilai dari checkbox yang dipilih

            for (let i = 0; i < kriteria_program_checkboxes.length; i++) { // iterasi semua checkbox
                if (kriteria_program_checkboxes[i].checked) { // jika checkbox terpilih
                    selectedKriteriaProgram.push(kriteria_program_checkboxes[i].value); // tambahkan nilai checkbox ke dalam array
                }
            }
            $('#kriteria_used').val(selectedKriteriaProgram)
            let tempAnggaran = $('#alokasi_anggaran_edit').val()
            $('#alokasi_anggaran_edit').val(parseInt(tempAnggaran.replace(/[^0-9\-]/g, '')))

            let data = {
                nama_program_edit : $("#nama_program_edit").val().trim(),
                tpb_id_edit : $("#tpb_id_edit").val(),
                unit_owner_edit : $("#unit_owner_edit").val(),
                kriteria_used : $("#kriteria_used").val(),
                core_subject_id_edit : $("#core_subject_id_edit").val(),
                pelaksanaan_program_edit : $("#pelaksanaan_program_edit").val(),
                mitra_bumn_edit : $("#mitra_bumn_edit").val(),
                program_multiyears_edit : document.querySelector('input[name="program_edit"]:checked').value,
                alokasi_anggaran_edit : $('input[name="alokasi_anggaran_edit"]').val(),
                id_program: $("#id_program").val(),
                tahun_edit: $("#tahun_edit").val(),
                perusahaan_edit: $("#perusahaan_edit").val()
            }

            if(!data.nama_program_edit.length || !data.tpb_id_edit.length || !data.kriteria_used.length || !data.core_subject_id_edit.length || !data.pelaksanaan_program_edit.length || !data.alokasi_anggaran_edit.length) {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Isian dengan tanda * harus terisi!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }

            if(data.pelaksanaan_program_edit.toLowerCase() === 'mandiri' && data.mitra_bumn_edit != '') {
                swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    html: 'Jika pelaksanaan progam = mandiri, mitra bumn tidak boleh terisi!',
                    buttonsStyling: true,
                    confirmButtonText: "<i class='bi bi-x-circle-fill' style='color: white'></i> Close"
                })
                return
            }

            $.blockUI({
                theme: true,
                baseZ: 2000
            }) 
            

            $.ajax({
                url: urleditstore,
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    data: data                   
                },
                dataType: 'json',
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
            })
        })

        $("#pelaksanaan_program_edit").on('change', function() {
            const pp = $(this).val().toLowerCase()
            if(pp === 'mandiri') {
                $("#mitra_bumn_edit").val('').trigger('change')
                $("#mitra_bumn_edit").prop('disabled', true)
                return
            }
            $("#mitra_bumn_edit").prop('disabled', false)
        })

        
    });

    function formatCurrency2(element) {

        //ver 2
        let value = element.value.replace(/[^\d-]/g, "");
        let isNegative = false;

        if (value.startsWith("-")) {
            isNegative = true;
            value = value.substring(1);
        }

        let formatter = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        let formattedValue = formatter.format(value);
        formattedValue = formattedValue.replace(/,/g, ".");

        if (isNegative) {
            formattedValue = "- " + formattedValue;
        }

        element.value = formattedValue;

    }

    function onlyNumbers(e) {
            var ASCIICode = (e.which) ? e.which : e.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }

    function formValidate() {
        $('#form-edit').validate({
            rules: {     
                nama_program_edit: 'required',
                tpb_id_edit: 'required',
                kriteria_program_edit: 'required',
                core_subject_id_edit: 'required',
                pelaksanaan_program_edit: 'required',
                program_edit: 'required',
                alokasi_anggaran_edit: 'required'
            },
            messages: {                      
                nama_program_edit: 'Nama program harus terisi!',
                tpb_id_edit: 'TPB harus terisi!',
                kriteria_program_edit: 'Minimal 1 opsi harus terpilih!',
                core_subject_id_edit: 'Core subject harus terisi!',
                pelaksanaan_program_edit: 'Pelaksanaan program harus terisi!',
                program_edit: 'Program multiyears harus terisi!',
                alokasi_anggaran_edit: 'Alokasi anggaran harus terisi!'
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
        })
    }
</script>
