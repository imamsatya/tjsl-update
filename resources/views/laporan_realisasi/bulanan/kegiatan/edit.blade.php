<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf


    <div class="mb-6 ">
        <div class="row mb-6">
            <div class="col-lg-3 ">
                <div class="ms-2 required">Jenis Anggaran</div>


            </div>
            <div class="col-lg-9">
                <select id="jenis_anggaran_edit" class="form-select form-select-solid form-select2"
                    name="jenis_anggaran_edit" data-kt-select2="true" data-placeholder="Pilih Jenis Anggaran">
                    <option></option>
                    <option value="CID" {{ $jenis_anggaran === 'CID' ? 'selected="selected"' : '' }}>
                        CID</option>
                    <option value="non CID" {{ $jenis_anggaran === 'non CID' ? 'selected="selected"' : '' }}>
                        non CID</option>
                </select>

            </div>

        </div>
        <div class="row mb-6">
            <div class="col-lg-3 ">
                <div class="ms-2 required">Program</div>


            </div>
            <div class="col-lg-9">
                <select id="program_id_edit" class="form-select form-select-solid form-select2" name="program_id_edit"
                    data-kt-select2="true" data-placeholder="Pilih Program">
                    <option></option>
                    @foreach ($program as $program_row)
                        @php
                            $select = $program_row->id == $kegiatan->target_tpb_id ? 'selected="selected"' : '';
                        @endphp
                        <option data-jenis-anggaran="{{ $program_row->jenis_anggaran }}" value="{{ $program_row->id }}"
                            {!! $select ?? '' !!}>{{ $program_row->program }} - {{ $program_row->jenis_anggaran }}
                        </option>
                    @endforeach
                </select>

            </div>

        </div>
        <div class="row mb-6">
            <div class="col-lg-3 ">
                <div class="ms-2 required">Nama Kegiatan</div>


            </div>
            <div class="col-lg-9">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" id="nama_kegiatan_edit" name="nama_kegiatan_edit"
                        style="height: 100px" value="{{ $kegiatan->kegiatan }}">{{ $kegiatan->kegiatan }}</textarea>
                    <label for="nama_kegiatan_edit">Nama Kegiatan</label>
                </div>

            </div>

        </div>
        <div class="row mb-6">
            <div class="col-lg-3 ">
                <div class="ms-2 required">Jenis Kegiatan</div>
            </div>
            <div class="col-lg-9">
                <select id="jenis_kegiatan_edit" class="form-select form-select-solid form-select2"
                    name="jenis_kegiatan_edit" data-kt-select2="true" data-placeholder="Pilih Jenis Kegiatan"
                    onchange="filterSubkegiatan(this.value)">
                    <option></option>
                    @foreach ($jenis_kegiatan as $jenis_kegiatan_row)
                        @php
                            $select = $jenis_kegiatan_row->id == $kegiatan->jenis_kegiatan_id ? 'selected="selected"' : '';
                        @endphp
                        <option value="{{ $jenis_kegiatan_row->id }}" {!! $select ?? '' !!}>
                            {{ $jenis_kegiatan_row->nama }}</option>
                    @endforeach
                </select>

            </div>

        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Sub Kegiatan</div>
            </div>
            <div class="col-lg-9">
                <select id="keterangan_kegiatan_edit" class="form-select form-select-solid form-select2"
                    name="keterangan_kegiatan_edit" data-kt-select2="true" data-placeholder="Pilih Sub Kegiatan">
                    <option></option>
                    @foreach ($subkegiatan as $subkegiatan_row)
                        @php
                            $select = $subkegiatan_row->id == $kegiatan->keterangan_kegiatan ? 'selected="selected"' : '';
                        @endphp
                        <option value="{{ $subkegiatan_row->id }}" {!! $select ?? '' !!}>
                            {{ $subkegiatan_row->subkegiatan }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 required">Provinsi</div>
            </div>
            <div class="col-lg-9">
                <select id="provinsi_edit" class="form-select form-select-solid form-select2" name="provinsi_edit"
                    data-kt-select2="true" data-placeholder="Pilih Provinsi">
                    <option></option>
                    <!-- Add options dynamically from your backend -->
                    @foreach ($provinsi as $provinsi_row)
                        @php
                            $select = $provinsi_row->id == $kegiatan->provinsi_id ? 'selected="selected"' : '';
                        @endphp
                        <option value="{{ $provinsi_row->id }}" {!! $select !!}>{{ $provinsi_row->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 required">Kota/Kabupaten</div>
            </div>
            <div class="col-lg-9">
                <select id="kota_kabupaten_edit" class="form-select form-select-solid form-select2"
                    name="kota_kabupaten_edit" data-kt-select2="true" data-placeholder="Pilih Kota/Kabupaten">
                    <option></option>
                    <!-- Add options dynamically from your backend -->
                    @foreach ($kota_kabupaten as $kota_kabupaten_row)
                        @php
                            $select = $kota_kabupaten_row->id == $kegiatan->kota_id ? 'selected="selected"' : '';
                        @endphp
                        <option value="{{ $kota_kabupaten_row->id }}"
                            data-provinsi-id="{{ $kota_kabupaten_row->provinsi_id }}" {!! $select !!}>
                            {{ $kota_kabupaten_row->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 required">Realisasi Anggaran</div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="realisasi_anggaran_edit" id="realisasi_anggaran_edit"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp ... "
                    oninput="formatCurrency(this)" onkeypress="return onlyNumbers(event)" style="text-align:right;"
                    value="{{ $kegiatan->anggaran ?? '' }}" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3 ">
                <div class="ms-2 required">Satuan Ukur</div>


            </div>
            <div class="col-lg-9">
                <select id="satuan_ukur_edit" class="form-select form-select-solid form-select2" name="satuan_ukur_edit"
                    data-kt-select2="true" data-placeholder="Pilih Satuan Ukur">
                    <option></option>
                    @foreach ($satuan_ukur as $satuan_ukur_row)
                        @php
                            $select = $satuan_ukur_row->id == $kegiatan->satuan_ukur_id ? 'selected="selected"' : '';
                        @endphp
                        <option value="{{ $satuan_ukur_row->id }}" {!! $select !!}>
                            {{ $satuan_ukur_row->nama }}</option>
                    @endforeach
                </select>

            </div>

        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Realisasi Indikator</div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="realisasi_indikator_edit" id="realisasi_indikator_edit"
                    value="{{ $kegiatan->indikator }}" class="form-control form-control-lg form-control-solid"
                    placeholder="Berdasarkan Satuan Ukur" />
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
    var urleditstore = "{{ route('laporan_realisasi.bulanan.kegiatan.edit_store') }}";

    $(document).ready(function() {
        $('.modal-title').html(title);
        $('.form-select').select2();
        $('.modal').on('shown.bs.modal', function() {
            setFormValidate();
        });
        // $(".modal").on('hidden.bs.modal', function() {
        //     const jenisAnggaran = $("#jenis-anggaran").val()
        //     $("#tpb_id").val('').trigger('change')


        //     $("#tpb_id").select2({
        //         templateResult: function(data) {
        //             if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
        //                 jenisAnggaran === '') return data.text
        //             return null
        //         },
        //         templateSelection: function(data) {
        //             if ($(data.element).attr('data-jenis-anggaran') === jenisAnggaran ||
        //                 jenisAnggaran === '') return data.text
        //             return null
        //         }
        //     })

        //     let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
        //     $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB ' +
        //         textAnggaran)
        // })

        $("#jenis_anggaran_edit").on('change', function() {
            console.log('halo')
            const jenisAnggaran = $(this).val()
            $("#program_id_edit").val('').trigger('change')


            $("#program_id_edit").select2({
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
            $("#select2-program_id_edit-container .select2-selection__placeholder").text(
                'Pilih Program ' +
                textAnggaran)
        })

        // $("#jenis-anggaran").trigger('change');
        $("#provinsi_edit").on('change', function() {

            const provinsi = $(this).val()
            $("#kota_kabupaten_edit").val('').trigger('change')


            $("#kota_kabupaten_edit").select2({
                templateResult: function(data) {
                    if ($(data.element).attr('data-provinsi-id') === provinsi ||
                        provinsi === '') return data.text
                    return null
                },
                templateSelection: function(data) {
                    if ($(data.element).attr('data-provinsi-id') === provinsi ||
                        provinsi === '') return data.text
                    return null
                }
            })

            // let textAnggaran = jenisAnggaran ? `- ${jenisAnggaran}` : ''
            $("#select2-kota_kabupaten-container .select2-selection__placeholder").text(
                'Pilih Kota/Kabupaten ')
            // $("#select2-tpb_id-container .select2-selection__placeholder").text('Pilih TPB '+textAnggaran)



        })


        // $('#form-edit').on('submit', function(event) {
        //     event.preventDefault()
        //     $(this).validate({
        //         rules: {
        //             jenis_anggaran_edit: 'required',
        //             program_id_edit: 'required',
        //             nama_kegiatan_edit: 'required',
        //             jenis_kegiatan_edit: 'required',
        //             keterangan_kegiatan_edit: 'required',
        //             provinsi_edit: 'required',
        //             kota_kabupaten_edit: 'required',
        //             realisasi_anggaran_edit: 'required',
        //             satuan_ukur_edit: 'required',
        //             realisasi_indikator_edit: 'required'
        //         },
        //         messages: {
        //             jenis_anggaran_edit: 'Jenis Anggaran harus terisi',
        //             program_id_edit: 'Program harus terisi',
        //             nama_kegiatan_edit: 'Nama Kegiatan harus terisi',
        //             jenis_kegiatan_edit: 'Jenis Kegiatan harus terisi',
        //             keterangan_kegiatan_edit: ' harus terisi',
        //             provinsi_edit: 'Provinsi harus terisi',
        //             kota_kabupaten_edit: 'Kota/Kabupaten harus terisi',
        //             realisasi_anggaran_edit: 'Realisasi Anggaran harus terisi',
        //             satuan_ukur_edit: 'Satuan Ukur harus terisi',
        //             realisasi_indikator_edit: 'Realisasi Indikator harus terisi'
        //         },
        //         highlight: function(element) {
        //             $(element).closest('.form-control').addClass('is-invalid');
        //         },
        //         unhighlight: function(element) {
        //             $(element).closest('.form-control').removeClass('is-invalid');
        //         },
        //         errorElement: 'div',
        //         errorClass: 'invalid-feedback',
        //         errorPlacement: function(error, element) {
        //             if (element.parent('.validated').length) {
        //                 error.insertAfter(element.parent());
        //             } else {
        //                 error.insertAfter(element);
        //             }
        //         },
        //     })
        //     let tempAnggaran = document.getElementById('realisasi_anggaran_edit')
        //     tempAnggaran = parseInt(tempAnggaran.value.replace(/[^0-9\-]/g, ''))
        //     // let tempAnggaran = $('#realisasi_indikator_edit').val()
        //     // $('#realisasi_indikator_edit').val(parseInt(tempAnggaran.replace(/[^0-9\-]/g, '')))
        //     var kegiatan = @json($kegiatan);
        //     console.log('halo k', kegiatan)
        //     let data = {
        //         kegiatan_data: kegiatan,
        //         jenis_anggaran_edit: $("#jenis_anggaran_edit").val(),
        //         program_id_edit: $("#program_id_edit").val(),
        //         nama_kegiatan_edit: $("#nama_kegiatan_edit").val(),
        //         jenis_kegiatan_edit: $("#jenis_kegiatan_edit").val(),
        //         keterangan_kegiatan_edit: $("#keterangan_kegiatan_edit").val(),
        //         provinsi_edit: $("#provinsi_edit").val(),
        //         kota_kabupaten_edit: $("#kota_kabupaten_edit").val(),
        //         realisasi_anggaran_edit: tempAnggaran,
        //         satuan_ukur_edit: $("#satuan_ukur_edit").val(),
        //         realisasi_indikator_edit: $("#realisasi_indikator_edit").val(),

        //         // nama_program_edit: $("#nama_program_edit").val().trim(),
        //         // tpb_id_edit: $("#tpb_id_edit").val(),
        //         // unit_owner_edit: $("#unit_owner_edit").val(),
        //         // kriteria_used: $("#kriteria_used").val(),
        //         // core_subject_id_edit: $("#core_subject_id_edit").val(),
        //         // pelaksanaan_program_edit: $("#pelaksanaan_program_edit").val(),
        //         // mitra_bumn_edit: $("#mitra_bumn_edit").val(),
        //         // program_multiyears_edit: document.querySelector(
        //         //     'input[name="program_edit"]:checked').value,
        //         // alokasi_anggaran_edit: $('input[name="alokasi_anggaran_edit"]').val(),
        //         // id_program: $("#id_program").val(),
        //         // tahun_edit: $("#tahun_edit").val(),
        //         // perusahaan_edit: $("#perusahaan_edit").val()
        //     }

        //     $.blockUI({
        //         theme: true,
        //         baseZ: 2000
        //     })

        //     $.ajax({
        //         url: urleditstore,
        //         type: 'POST',
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             data: data
        //         },
        //         dataType: 'json',
        //         success: function(data) {
        //             $.unblockUI();

        //             swal.fire({
        //                 title: data.title,
        //                 html: data.msg,
        //                 icon: data.flag,
        //                 buttonsStyling: true,
        //                 confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
        //             });

        //             if (data.flag == 'success') {
        //                 $('#winform').modal('hide');
        //                 location.reload();
        //             }
        //         },
        //         error: function(jqXHR, exception) {
        //             $.unblockUI();
        //             var msgerror = '';
        //             if (jqXHR.status === 0) {
        //                 msgerror = 'jaringan tidak terkoneksi.';
        //             } else if (jqXHR.status == 404) {
        //                 msgerror = 'Halaman tidak ditemukan. [404]';
        //             } else if (jqXHR.status == 500) {
        //                 msgerror = 'Internal Server Error [500].';
        //             } else if (exception === 'parsererror') {
        //                 msgerror = 'Requested JSON parse gagal.';
        //             } else if (exception === 'timeout') {
        //                 msgerror = 'RTO.';
        //             } else if (exception === 'abort') {
        //                 msgerror = 'Gagal request ajax.';
        //             } else {
        //                 msgerror = 'Error.\n' + jqXHR.responseText;
        //             }
        //             swal.fire({
        //                 title: "Error System",
        //                 html: msgerror + ', coba ulangi kembali !!!',
        //                 icon: 'error',

        //                 buttonsStyling: true,

        //                 confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
        //             });
        //         }
        //     })
        // })

        const inputElement = document.getElementById("realisasi_anggaran_edit");
        // Trigger the formatCurrency function on the input element's value
        formatCurrency(inputElement);

        $('#keterangan_kegiatan_edit').on('change', function() {
            var subkegiatanId = $(this).val();
            if (subkegiatanId) {
                // Find the corresponding satuan_ukur_id for the selected subkegiatan
                var selectedSubkegiatan = {!! $subkegiatan->toJson() !!}.find(function(subkegiatan) {
                    return subkegiatan.id == subkegiatanId;
                });

                if (selectedSubkegiatan) {
                    // Set the selected value in the "satuan_ukur" field
                    $('#satuan_ukur_edit').val(selectedSubkegiatan.satuan_ukur_id).trigger('change');
                    // Enable the "satuan_ukur" field
                    $('#satuan_ukur_edit').prop('disabled', true);
                }
            } else {
                // If no subkegiatan is selected, disable the "satuan_ukur" field and reset its value
                $('#satuan_ukur_edit').prop('disabled', true).val('').trigger('change');
            }
        });




    });

    function setFormValidate() {
        $('#form-edit').validate({
            rules: {
                jenis_anggaran_edit: 'required',
                program_id_edit: 'required',
                nama_kegiatan_edit: 'required',
                jenis_kegiatan_edit: 'required',
                keterangan_kegiatan_edit: 'required',
                provinsi_edit: 'required',
                kota_kabupaten_edit: 'required',
                realisasi_anggaran_edit: 'required',
                satuan_ukur_edit: 'required',
                realisasi_indikator_edit: 'required'
            },
            messages: {
                jenis_anggaran_edit: 'Jenis Anggaran harus terisi',
                program_id_edit: 'Program harus terisi',
                nama_kegiatan_edit: 'Nama Kegiatan harus terisi',
                jenis_kegiatan_edit: 'Jenis Kegiatan harus terisi',
                keterangan_kegiatan_edit: 'Sub Kegiatan harus terisi',
                provinsi_edit: 'Provinsi harus terisi',
                kota_kabupaten_edit: 'Kota/Kabupaten harus terisi',
                realisasi_anggaran_edit: 'Realisasi Anggaran harus terisi',
                satuan_ukur_edit: 'Satuan Ukur harus terisi',
                realisasi_indikator_edit: 'Realisasi Indikator harus terisi'
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

                if (element.attr("class") === "form-select2") {
                    console.log('halo error')
                    error.insertAfter(element.parent().find(".select2-container"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {


                var typesubmit = $("input[type=submit][clicked=true]").val();
                let tempAnggaran = document.getElementById('realisasi_anggaran_edit')
                tempAnggaran = parseInt(tempAnggaran.value.replace(/[^0-9\-]/g, ''))
                // let tempAnggaran = $('#realisasi_indikator_edit').val()
                // $('#realisasi_indikator_edit').val(parseInt(tempAnggaran.replace(/[^0-9\-]/g, '')))
                var kegiatan = @json($kegiatan);
                console.log('halo k', kegiatan)

                let data = {
                    kegiatan_data: kegiatan,
                    jenis_anggaran_edit: $("#jenis_anggaran_edit").val(),
                    program_id_edit: $("#program_id_edit").val(),
                    nama_kegiatan_edit: $("#nama_kegiatan_edit").val(),
                    jenis_kegiatan_edit: $("#jenis_kegiatan_edit").val(),
                    keterangan_kegiatan_edit: $("#keterangan_kegiatan_edit").val(),
                    provinsi_edit: $("#provinsi_edit").val(),
                    kota_kabupaten_edit: $("#kota_kabupaten_edit").val(),
                    realisasi_anggaran_edit: tempAnggaran,
                    satuan_ukur_edit: $("#satuan_ukur_edit").val(),
                    realisasi_indikator_edit: $("#realisasi_indikator_edit").val(),

                    // nama_program_edit: $("#nama_program_edit").val().trim(),
                    // tpb_id_edit: $("#tpb_id_edit").val(),
                    // unit_owner_edit: $("#unit_owner_edit").val(),
                    // kriteria_used: $("#kriteria_used").val(),
                    // core_subject_id_edit: $("#core_subject_id_edit").val(),
                    // pelaksanaan_program_edit: $("#pelaksanaan_program_edit").val(),
                    // mitra_bumn_edit: $("#mitra_bumn_edit").val(),
                    // program_multiyears_edit: document.querySelector(
                    //     'input[name="program_edit"]:checked').value,
                    // alokasi_anggaran_edit: $('input[name="alokasi_anggaran_edit"]').val(),
                    // id_program: $("#id_program").val(),
                    // tahun_edit: $("#tahun_edit").val(),
                    // perusahaan_edit: $("#perusahaan_edit").val()
                }

                $(form).ajaxSubmit({
                    type: 'post',
                    url: urleditstore,
                    data: {
                        source: typesubmit,
                        data: data
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $.blockUI({
                            theme: true,
                            baseZ: 2000
                        })
                    },
                    success: function(data) {
                        $.unblockUI();

                        console.log('data', data.data)
                        swal.fire({
                            title: 'Sukses',
                            html: data.data.msg,
                            icon: 'success',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });

                        if (data.flag == 'success') {
                            $('#winform').modal('hide');
                            location.reload();
                        }
                        // $('#winform').modal('hide');
                        // datatable.ajax.reload(null, false);

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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });

                    }
                });
                return false;
            }
        })

        // $(this).validate({


        // })



        // $.blockUI({
        //     theme: true,
        //     baseZ: 2000
        // })

        // $.ajax({
        //     url: urleditstore,
        //     type: 'POST',
        //     data: {
        //         "_token": "{{ csrf_token() }}",
        //         data: data
        //     },
        //     dataType: 'json',
        //     success: function(data) {
        //         $.unblockUI();

        //         swal.fire({
        //             title: data.title,
        //             html: data.msg,
        //             icon: data.flag,
        //             buttonsStyling: true,
        //             confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
        //         });

        //         if (data.flag == 'success') {
        //             $('#winform').modal('hide');
        //             location.reload();
        //         }
        //     },
        //     error: function(jqXHR, exception) {
        //         $.unblockUI();
        //         var msgerror = '';
        //         if (jqXHR.status === 0) {
        //             msgerror = 'jaringan tidak terkoneksi.';
        //         } else if (jqXHR.status == 404) {
        //             msgerror = 'Halaman tidak ditemukan. [404]';
        //         } else if (jqXHR.status == 500) {
        //             msgerror = 'Internal Server Error [500].';
        //         } else if (exception === 'parsererror') {
        //             msgerror = 'Requested JSON parse gagal.';
        //         } else if (exception === 'timeout') {
        //             msgerror = 'RTO.';
        //         } else if (exception === 'abort') {
        //             msgerror = 'Gagal request ajax.';
        //         } else {
        //             msgerror = 'Error.\n' + jqXHR.responseText;
        //         }
        //         swal.fire({
        //             title: "Error System",
        //             html: msgerror + ', coba ulangi kembali !!!',
        //             icon: 'error',

        //             buttonsStyling: true,

        //             confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
        //         });
        //     }
        // })

    }


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

    function formatCurrency(element) {


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

    function filterSubkegiatan(jenisKegiatanId) {
        // Clear the current options in the "subkegiatan" select field
        $('#keterangan_kegiatan_edit').empty();

        // If no jenis_kegiatan is selected, exit the function
        if (!jenisKegiatanId) return;

        // Filter the subkegiatan options based on the selected jenis_kegiatan
        var filteredSubkegiatan = {!! $subkegiatan->toJson() !!} // Convert $subkegiatan to a JavaScript object

        // Iterate through the filtered subkegiatan options
        $.each(filteredSubkegiatan, function(index, subkegiatan) {
            // Check if the jenis_kegiatan_id matches the selected jenis_kegiatan
            if (subkegiatan.jenis_kegiatan_id == jenisKegiatanId) {
                // Create a new option element and append it to the "subkegiatan" select field
                var option = $('<option>', {
                    value: subkegiatan.id,
                    text: subkegiatan.subkegiatan
                });
                $('#keterangan_kegiatan_edit').append(option);
            }
        });

        // Refresh the Select2 component for the "subkegiatan" select field
        $('#keterangan_kegiatan_edit').trigger('change');
    }
</script>
