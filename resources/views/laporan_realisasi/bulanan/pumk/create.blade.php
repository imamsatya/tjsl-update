<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    {{-- <input type="hidden" name="id_program" id="id_program" readonly="readonly"
        value="{{ $actionform == 'update' ? $id_program : null }}" />
    <input type="hidden" name="tahun_edit" id="tahun_edit" readonly="readonly" value="{{ $tahun }}" />
    <input type="hidden" name="perusahaan_edit" id="perusahaan_edit" readonly="readonly"
        value="{{ $perusahaan_id }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" /> --}}
    <input type="hidden" name="kriteria_used" id="kriteria_used" readonly="readonly" value="" />

    <div class="mb-6 ">
      
        <div class="row mb-6">
            <div class="col-lg-6">
                <label>Tahun</label>
                <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true">
                    @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                    @php
                    $select = (($i == $tahun) ? 'selected="selected"' : '');
                    @endphp
                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                    @php } @endphp
                </select>
            </div>
            <div class="col-lg-6">
                <label>Bulan</label>
                        <select id="bulan_id" class="form-select form-select-solid form-select2" name="bulan_id" data-kt-select2="true"
                            data-placeholder="Pilih Bulan" data-allow-clear="true">
                            <option></option>
                            @foreach($bulan as $bulan_row)
                            {{-- @php
                                $select = (($p->no_tpb == $tpb_id) ? 'selected="selected"' : '');
                            @endphp --}}
                            <option value="{{ $bulan_row->id }}" {!! $select !!}>{{ $bulan_row->nama }}</option>
                            @endforeach
                        </select>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Nilai Penyaluran Bulan <span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Nilai Penyaluran Bulan melalui BRI<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Jumlah MB baru pada Bulan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
     
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Jumlah MB Naik Kelas pada Bulan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-lg-3">
                
            </div>
            <div class="col-lg-6">
          
            </div>
            <div class="col-lg-3">
                <div class="ms-2">Jumlah MB<span style="color: red">*</span></div>
            </div>
          
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Lancar<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Kurang Lancar<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Diragukan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Macet<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Pinjaman Bermasalah<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="nama_program_edit" id="nama_program_edit" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
       
    </div>
    <div class="text-center pt-15">
        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
            data-kt-roles-modal-action="cancel">Discard</button>
        <button id="submit" type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
            <span class="indicator-label">Simpan</span>
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
</form>

<script type="text/javascript">
    var title = "{{ $actionform == 'update' ? 'Update' : 'Tambah' }}" + " {{ $pagetitle }}";
    var urleditstore = "{{ route('rencana_kerja.program.edit_store') }}";

    $(document).ready(function() {
        $('.modal-title').html(title);
        $('.form-select').select2();

        $(".modal").on('hidden.bs.modal', function() {
            const jenisAnggaran = $("#jenis-anggaran").val()
            $("#tpb_id").val('').trigger('change')


            $("#tpb_id").select2({
                templateResult: function(data) {
                    if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
                        jenisAnggaran === '') return data.text
                    return null
                },
                templateSelection: function(data) {
                    if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
                        jenisAnggaran === '') return data.text
                    return null
                }
            })

            let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
            $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB ' +
                textAnggaran)
        })

        $('#form-edit').on('submit', function(event) {
            event.preventDefault()
            console.log('submiting')
            $(this).validate({
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
                    if (element.parent('.validated').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
            })

            const kriteria_program_checkboxes = document.getElementsByName(
                "kriteria_program_edit"); // mengambil semua checkbox dengan name="kriteria_program"
            const
                selectedKriteriaProgram = []; // deklarasi array untuk menyimpan nilai dari checkbox yang dipilih

            for (let i = 0; i < kriteria_program_checkboxes.length; i++) { // iterasi semua checkbox
                if (kriteria_program_checkboxes[i].checked) { // jika checkbox terpilih
                    selectedKriteriaProgram.push(kriteria_program_checkboxes[i]
                        .value); // tambahkan nilai checkbox ke dalam array
                }
            }
            $('#kriteria_used').val(selectedKriteriaProgram)
            let tempAnggaran = $('#alokasi_anggaran_edit').val()
            $('#alokasi_anggaran_edit').val(parseInt(tempAnggaran.replace(/[^0-9\-]/g, '')))

            let data = {
                nama_program_edit: $("#nama_program_edit").val(),
                tpb_id_edit: $("#tpb_id_edit").val(),
                unit_owner_edit: $("#unit_owner_edit").val(),
                kriteria_used: $("#kriteria_used").val(),
                core_subject_id_edit: $("#core_subject_id_edit").val(),
                pelaksanaan_program_edit: $("#pelaksanaan_program_edit").val(),
                mitra_bumn_edit: $("#mitra_bumn_edit").val(),
                program_multiyears_edit: document.querySelector(
                    'input[name="program_edit"]:checked').value,
                alokasi_anggaran_edit: $('input[name="alokasi_anggaran_edit"]').val(),
                id_program: $("#id_program").val(),
                tahun_edit: $("#tahun_edit").val(),
                perusahaan_edit: $("#perusahaan_edit").val()
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
                success: function(data) {
                    $.unblockUI();

                    swal.fire({
                        title: data.title,
                        html: data.msg,
                        icon: data.flag,
                        buttonsStyling: true,
                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });

                    if (data.flag == 'success') {
                        $('#winform').modal('hide');
                        location.reload();
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
                        html: msgerror + ', coba ulangi kembali !!!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                    });
                }
            })
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
</script>
