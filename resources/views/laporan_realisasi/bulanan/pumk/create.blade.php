<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    {{-- <input type="hidden" name="id_program" id="id_program" readonly="readonly"
        value="{{ $actionform == 'update' ? $id_program : null }}" />
    <input type="hidden" name="tahun_edit" id="tahun_edit" readonly="readonly" value="{{ $tahun }}" />
    <input type="hidden" name="perusahaan_edit" id="perusahaan_edit" readonly="readonly"
        value="{{ $perusahaan_id }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" /> --}}
    <input type="hidden" name="perusahaan_id" id="perusahaan_id" readonly="readonly" value="{{ $perusahaan_id }}" />

    <div class="mb-6 ">
      
        <div class="row mb-6">
            <div class="col-lg-6">
                <label>Tahun</label>
                <select class="form-control form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true">
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
                        <select id="bulan_id_create" class="form-control form-control-lg form-control-solid form-select form-select-solid form-select2" name="bulan_id_create" data-kt-select2="true"
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
                <div class="ms-2 bulan-label">Nilai Penyaluran Bulan <span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nilai_penyaluran" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" id="nilai_penyaluran" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Nilai Penyaluran Bulan melalui BRI<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nilai_penyaluran_melalui_bri" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" id="nilai_penyaluran_melalui_bri" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Jumlah MB baru pada Bulan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="jumlah_mb" id="jumlah_mb" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
     
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Jumlah MB Naik Kelas pada Bulan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="jumlah_mb_naik_kelas" id="jumlah_mb_naik_kelas" value=""
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
                <input type="text" name="kolektabilitas_lancar" id="kolektabilitas_lancar" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_lancar_jumlah_mb" id="kolektabilitas_lancar_jumlah_mb" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Kurang Lancar<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_kurang_lancar" id="kolektabilitas_kurang_lancar" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_kurang_lancar_jumlah_mb" id="kolektabilitas_kurang_lancar_jumlah_mb" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Diragukan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_diragukan" id="kolektabilitas_diragukan" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_diragukan_jumlah_mb" id="kolektabilitas_diragukan_jumlah_mb" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Macet<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_macet" id="kolektabilitas_macet" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_macet_jumlah_mb" id="kolektabilitas_macet_jumlah_mb" value=""
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Pinjaman Bermasalah<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_pinjaman_bermasalah" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" id="kolektabilitas_pinjaman_bermasalah" value=""
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_pinjaman_bermasalah_jumlah_mb" id="kolektabilitas_pinjaman_bermasalah_jumlah_mb" value=""
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
    var urleditstore = "{{ route('laporan_realisasi.bulanan.pumk.store') }}";

    $(document).ready(function() {
        $('.modal-title').html(title);
        $('.modal').on('shown.bs.modal', function() {
            setFormValidate();
        });
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

        // $('#form-edit').on('submit', function(event) {
        //     event.preventDefault()
        //     console.log('submiting')
        //     $(this).validate({
        //         rules: {
        //             tahun: 'required',
        //             bulan_id_create: 'required',
        //             //
        //             nilai_penyaluran: 'required',
        //             nilai_penyaluran_melalui_bri: 'required',
        //             jumlah_mb: 'required',
        //             jumlah_mb_naik_kelas: 'required',
        //             //
        //             kolektabilitas_lancar: 'required',
        //             kolektabilitas_lancar_jumlah_mb: 'required',
        //             kolektabilitas_kurang_lancar: 'required',
        //             kolektabilitas_kurang_lancar_jumlah_mb: 'required',
        //             kolektabilitas_diragukan: 'required',
        //             kolektabilitas_diragukan_jumlah_mb: 'required',
        //             kolektabilitas_macet: 'required',
        //             kolektabilitas_macet_jumlah_mb: 'required',
        //             kolektabilitas_pinjaman_bermasalah: 'required',
        //             kolektabilitas_pinjaman_bermasalah_jumlah_mb: 'required',
        //         },
        //         messages: {
        //             nama_program_edit: 'Nama program harus terisi!',
        //             tpb_id_edit: 'TPB harus terisi!',
        //             kriteria_program_edit: 'Minimal 1 opsi harus terpilih!',
        //             core_subject_id_edit: 'Core subject harus terisi!',
        //             pelaksanaan_program_edit: 'Pelaksanaan program harus terisi!',
        //             program_edit: 'Program multiyears harus terisi!',
        //             alokasi_anggaran_edit: 'Alokasi anggaran harus terisi!',
        //             tahun: 'tahun harus terisi!',
        //             bulan_id_create: 'bulan_id_create harus terisi!',
        //             //
        //             nilai_penyaluran: 'nilai_penyaluran harus terisi!',
        //             nilai_penyaluran_melalui_bri: 'nilai_penyaluran_melalui_bri harus terisi!',
        //             jumlah_mb: 'jumlah_mb harus terisi!',
        //             jumlah_mb_naik_kelas: 'jumlah_mb_naik_kelas harus terisi!',
        //             //
        //             kolektabilitas_lancar: 'kolektabilitas_lancar harus terisi!',
        //             kolektabilitas_lancar_jumlah_mb: 'kolektabilitas_lancar_jumlah_mb harus terisi!',
        //             kolektabilitas_kurang_lancar: 'kolektabilitas_kurang_lancar harus terisi!',
        //             kolektabilitas_kurang_lancar_jumlah_mb: 'kolektabilitas_kurang_lancar_jumlah_mb harus terisi!',
        //             kolektabilitas_diragukan: 'kolektabilitas_diragukan harus terisi!',
        //             kolektabilitas_diragukan_jumlah_mb: 'kolektabilitas_diragukan_jumlah_mb harus terisi!',
        //             kolektabilitas_macet: 'kolektabilitas_macet harus terisi!',
        //             kolektabilitas_macet_jumlah_mb: 'kolektabilitas_macet_jumlah_mb harus terisi!',
        //             kolektabilitas_pinjaman_bermasalah: 'kolektabilitas_pinjaman_bermasalah harus terisi!',
        //             kolektabilitas_pinjaman_bermasalah_jumlah_mb: 'kolektabilitas_pinjaman_bermasalah_jumlah_mb harus terisi!',
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

        //     // const kriteria_program_checkboxes = document.getElementsByName(
        //     //     "kriteria_program_edit"); // mengambil semua checkbox dengan name="kriteria_program"
        //     // const
        //     //     selectedKriteriaProgram = []; // deklarasi array untuk menyimpan nilai dari checkbox yang dipilih

        //     // for (let i = 0; i < kriteria_program_checkboxes.length; i++) { // iterasi semua checkbox
        //     //     if (kriteria_program_checkboxes[i].checked) { // jika checkbox terpilih
        //     //         selectedKriteriaProgram.push(kriteria_program_checkboxes[i]
        //     //             .value); // tambahkan nilai checkbox ke dalam array
        //     //     }
        //     // }
        //     // $('#kriteria_used').val(selectedKriteriaProgram)
        //     // let tempAnggaran = $('#alokasi_anggaran_edit').val()
        //     // $('#alokasi_anggaran_edit').val(parseInt(tempAnggaran.replace(/[^0-9\-]/g, '')))

        //     // let data = {
        //     //     nama_program_edit: $("#nama_program_edit").val(),
        //     //     tpb_id_edit: $("#tpb_id_edit").val(),
        //     //     unit_owner_edit: $("#unit_owner_edit").val(),
        //     //     kriteria_used: $("#kriteria_used").val(),
        //     //     core_subject_id_edit: $("#core_subject_id_edit").val(),
        //     //     pelaksanaan_program_edit: $("#pelaksanaan_program_edit").val(),
        //     //     mitra_bumn_edit: $("#mitra_bumn_edit").val(),
        //     //     program_multiyears_edit: document.querySelector(
        //     //         'input[name="program_edit"]:checked').value,
        //     //     alokasi_anggaran_edit: $('input[name="alokasi_anggaran_edit"]').val(),
        //     //     id_program: $("#id_program").val(),
        //     //     tahun_edit: $("#tahun_edit").val(),
        //     //     perusahaan_edit: $("#perusahaan_edit").val()
        //     // }


        //     $.blockUI({
        //         theme: true,
        //         baseZ: 2000
        //     })

        //     // $.ajax({
        //     //     url: urleditstore,
        //     //     type: 'POST',
        //     //     data: {
        //     //         "_token": "{{ csrf_token() }}",
        //     //         data: data
        //     //     },
        //     //     dataType: 'json',
        //     //     success: function(data) {
        //     //         $.unblockUI();

        //     //         swal.fire({
        //     //             title: data.title,
        //     //             html: data.msg,
        //     //             icon: data.flag,
        //     //             buttonsStyling: true,
        //     //             confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
        //     //         });

        //     //         if (data.flag == 'success') {
        //     //             $('#winform').modal('hide');
        //     //             location.reload();
        //     //         }
        //     //     },
        //     //     error: function(jqXHR, exception) {
        //     //         $.unblockUI();
        //     //         var msgerror = '';
        //     //         if (jqXHR.status === 0) {
        //     //             msgerror = 'jaringan tidak terkoneksi.';
        //     //         } else if (jqXHR.status == 404) {
        //     //             msgerror = 'Halaman tidak ditemukan. [404]';
        //     //         } else if (jqXHR.status == 500) {
        //     //             msgerror = 'Internal Server Error [500].';
        //     //         } else if (exception === 'parsererror') {
        //     //             msgerror = 'Requested JSON parse gagal.';
        //     //         } else if (exception === 'timeout') {
        //     //             msgerror = 'RTO.';
        //     //         } else if (exception === 'abort') {
        //     //             msgerror = 'Gagal request ajax.';
        //     //         } else {
        //     //             msgerror = 'Error.\n' + jqXHR.responseText;
        //     //         }
        //     //         swal.fire({
        //     //             title: "Error System",
        //     //             html: msgerror + ', coba ulangi kembali !!!',
        //     //             icon: 'error',

        //     //             buttonsStyling: true,

        //     //             confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
        //     //         });
        //     //     }
        //     // })
        // })

        const bulanLabels = document.querySelectorAll(".bulan-label");
        $("#bulan_id_create").on('change', function() {
            $(this).removeClass('is-invalid');
            $(this).closest('.form-group').find('.invalid-feedback').remove();
            const bulan = $(this).val();
            const selectedBulan = $(this).find('option:selected').text();
            bulanLabels.forEach(label => {
                

                const existingText = label.innerText;
                const originalText = existingText.replace(/Bulan\s\w+/i, "Bulan");
                const newText = originalText.replace("Bulan", `Bulan ${selectedBulan}`);
                label.innerText = newText;
            });
        })


    });
    function setFormValidate() {
        $('#form-edit').validate({
                rules: {
                    tahun: 'required',
                    bulan_id_create: 'required',
                    //
                    nilai_penyaluran: 'required',
                    nilai_penyaluran_melalui_bri: 'required',
                    jumlah_mb: 'required',
                    jumlah_mb_naik_kelas: 'required',
                    //
                    kolektabilitas_lancar: 'required',
                    kolektabilitas_lancar_jumlah_mb: 'required',
                    kolektabilitas_kurang_lancar: 'required',
                    kolektabilitas_kurang_lancar_jumlah_mb: 'required',
                    kolektabilitas_diragukan: 'required',
                    kolektabilitas_diragukan_jumlah_mb: 'required',
                    kolektabilitas_macet: 'required',
                    kolektabilitas_macet_jumlah_mb: 'required',
                    kolektabilitas_pinjaman_bermasalah: 'required',
                    kolektabilitas_pinjaman_bermasalah_jumlah_mb: 'required',
                },
                messages: {
                    tahun: 'tahun harus terisi!',
                    bulan_id_create: 'Bulan harus terisi!',
                    //
                    nilai_penyaluran: 'Nilai Penyaluran harus terisi!',
                    nilai_penyaluran_melalui_bri: 'Nilai Penyaluran Melalui BRI harus terisi!',
                    jumlah_mb: 'Jumlah MB baru harus terisi!',
                    jumlah_mb_naik_kelas: 'Jumlah MB Naik Kelas harus terisi!',
                    //
                    kolektabilitas_lancar: 'Kolektabilitas Lancar harus terisi!',
                    kolektabilitas_lancar_jumlah_mb: 'Jumlah MB Kolektabilitas Lancar harus terisi!',
                    kolektabilitas_kurang_lancar: 'Kolektabilitas Kurang Lancar harus terisi!',
                    kolektabilitas_kurang_lancar_jumlah_mb: 'Jumlah MB Kolektabilitas Kurang Lancar harus terisi!',
                    kolektabilitas_diragukan: 'Kolektabilitas Diragukan harus terisi!',
                    kolektabilitas_diragukan_jumlah_mb: 'Jumlah MB Kolektabilitas Diragukan harus terisi!',
                    kolektabilitas_macet: 'Kolektabilitas Macet harus terisi!',
                    kolektabilitas_macet_jumlah_mb: 'Jumlah MB Kolektabilitas Macet harus terisi!',
                    kolektabilitas_pinjaman_bermasalah: 'Kolektabilitas Pinjaman Bermasalah harus terisi!',
                    kolektabilitas_pinjaman_bermasalah_jumlah_mb: 'Jumlah MB Kolektabilitas Pinjaman Bermasalah harus terisi!',
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

                    if (element.attr("name") === "bulan_id_create") {
                        error.insertAfter(element.parent().find(".select2-container"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                var typesubmit = $("input[type=submit][clicked=true]").val();
                        // let tempAnggaran = $('#alokasi_anggaran_edit').val()
                        // $('#alokasi_anggaran_edit').val(parseInt(tempAnggaran.replace(/[^0-9\-]/g, '')))

                    
                    //     let tahun = $('#tahun').val()
                    // let bulan_id_create = $('#bulan_id_create').val()
                    //
                    let nilai_penyaluran = $('#nilai_penyaluran').val()
                    let nilai_penyaluran_melalui_bri = $('#nilai_penyaluran_melalui_bri').val()
                    let jumlah_mb = $('#jumlah_mb').val()
                    let jumlah_mb_naik_kelas = $('#jumlah_mb_naik_kelas').val()
                    //
                    let kolektabilitas_lancar = $('#kolektabilitas_lancar').val()
                    let kolektabilitas_lancar_jumlah_mb = $('#kolektabilitas_lancar_jumlah_mb').val()
                    let kolektabilitas_kurang_lancar = $('#kolektabilitas_kurang_lancar').val()
                    let kolektabilitas_kurang_lancar_jumlah_mb = $('#kolektabilitas_kurang_lancar_jumlah_mb').val()
                    let kolektabilitas_diragukan = $('#kolektabilitas_diragukan').val()
                    let kolektabilitas_diragukan_jumlah_mb = $('#kolektabilitas_diragukan_jumlah_mb').val()
                    let kolektabilitas_macet = $('#kolektabilitas_macet').val()
                    let kolektabilitas_macet_jumlah_mb = $('#kolektabilitas_macet_jumlah_mb').val()
                    let kolektabilitas_pinjaman_bermasalah = $('#kolektabilitas_pinjaman_bermasalah').val()
                    let kolektabilitas_pinjaman_bermasalah_jumlah_mb = $('#kolektabilitas_pinjaman_bermasalah_jumlah_mb').val()

                    // $('#tahun').val(parseInt(tahun.replace(/[^0-9\-]/g, '')))
                    // $('#bulan_id_create').val(parseInt(bulan_id_create.replace(/[^0-9\-]/g, '')))
                    $('#nilai_penyaluran').val(parseInt(nilai_penyaluran.replace(/[^0-9\-]/g, '')))
                    $('#nilai_penyaluran_melalui_bri').val(parseInt(nilai_penyaluran_melalui_bri.replace(/[^0-9\-]/g, '')))
                    $('#jumlah_mb').val(parseInt(jumlah_mb.replace(/[^0-9\-]/g, '')))
                    $('#jumlah_mb_naik_kelas').val(parseInt(jumlah_mb_naik_kelas.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_lancar').val(parseInt(kolektabilitas_lancar.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_lancar_jumlah_mb').val(parseInt(kolektabilitas_lancar_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_kurang_lancar').val(parseInt(kolektabilitas_kurang_lancar.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_kurang_lancar_jumlah_mb').val(parseInt(kolektabilitas_kurang_lancar_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_diragukan').val(parseInt(kolektabilitas_diragukan.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_diragukan_jumlah_mb').val(parseInt(kolektabilitas_diragukan_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_macet').val(parseInt(kolektabilitas_macet.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_macet_jumlah_mb').val(parseInt(kolektabilitas_macet_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_pinjaman_bermasalah').val(parseInt(kolektabilitas_pinjaman_bermasalah.replace(/[^0-9\-]/g, '')))
                    $('#kolektabilitas_pinjaman_bermasalah_jumlah_mb').val(parseInt(kolektabilitas_pinjaman_bermasalah_jumlah_mb.replace(/[^0-9\-]/g, '')))
                $(form).ajaxSubmit({
                    type: 'post',
                    url: urleditstore,
                    data: {
                        source: typesubmit
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

                        swal.fire({
                            title: 'Sukses',
                            html: 'Sukses mengubah data',
                            icon: 'success',

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });


                        $('#winform').modal('hide');
                        datatable.ajax.reload(null, false);

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
</script>
