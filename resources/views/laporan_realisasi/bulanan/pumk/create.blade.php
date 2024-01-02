<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="perusahaan_id" id="perusahaan_id" readonly="readonly"
        value="{{ $perusahaan_id }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />
    <input type="hidden" name="pumk_bulan_id" id="pumk_bulan_id" readonly="readonly" value="{{ $pumk_bulan_id }}" />

    <div class="mb-6 ">
      
        <div class="row mb-6">
            <div class="col-lg-6">
                <label>Tahun</label>
                <select class="form-control form-control-lg form-control-solid form-select form-select-solid form-select2" id="tahun_create" name="tahun_create" data-kt-select2="true">
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
                            @php
                                $select = (($pumk_bulan?->bulan_id == $bulan_row->id) ? 'selected="selected"' : '');
                            @endphp
                            <option value="{{ $bulan_row->id }}" {!! $select !!}>{{ $bulan_row->nama }}</option>
                            @endforeach
                        </select>
            </div>
        </div>
        @if($perusahaan_id == 3)
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Nilai Penyaluran Bulan <span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nilai_penyaluran" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" id="nilai_penyaluran" value="{{ $pumk_bulan?->nilai_penyaluran }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
        </div>
        @endif
        @if($perusahaan_id != 3)
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Nilai Penyaluran melalui BRI pada Bulan <span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nilai_penyaluran_melalui_bri" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" id="nilai_penyaluran_melalui_bri" value="{{ $pumk_bulan?->nilai_penyaluran_melalui_bri }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
        </div>

        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Nilai Penyaluran melalui Pegadaian <span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="nilai_penyaluran_melalui_pegadaian" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" id="nilai_penyaluran_melalui_pegadaian" value="{{ $pumk_bulan?->nilai_penyaluran_melalui_pegadaian }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
        </div>
        @endif
        @if($perusahaan_id == 3)
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Jumlah MB baru pada Bulan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="jumlah_mb" style="text-align:right;" id="jumlah_mb" value="{{ $pumk_bulan?->jumlah_mb }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
     
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2 bulan-label">Jumlah MB Naik Kelas pada Bulan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-9">
                <input type="text" name="jumlah_mb_naik_kelas" style="text-align:right;" id="jumlah_mb_naik_kelas" value="{{ $pumk_bulan?->jumlah_mb_naik_kelas }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        @endif
        {{-- <div class="row mb-2">
            <div class="col-lg-3">
                
            </div>
            <div class="col-lg-6">
                <div class="ms-2">Kolektabilitas<span style="color: red">*</span></div>
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
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value="{{ $pumk_bulan?->kolektabilitas_lancar }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_lancar_jumlah_mb" style="text-align:right;" id="kolektabilitas_lancar_jumlah_mb" value="{{ $pumk_bulan?->kolektabilitas_lancar_jumlah_mb }}"
                class="form-control form-control-lg form-control-solid" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Kurang Lancar<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_kurang_lancar" id="kolektabilitas_kurang_lancar" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value="{{ $pumk_bulan?->kolektabilitas_kurang_lancar }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_kurang_lancar_jumlah_mb" id="kolektabilitas_kurang_lancar_jumlah_mb" value="{{ $pumk_bulan?->kolektabilitas_kurang_lancar_jumlah_mb }}"
                class="form-control form-control-lg form-control-solid" style="text-align:right;" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Diragukan<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_diragukan" id="kolektabilitas_diragukan" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value="{{ $pumk_bulan?->kolektabilitas_diragukan }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_diragukan_jumlah_mb" id="kolektabilitas_diragukan_jumlah_mb" value="{{ $pumk_bulan?->kolektabilitas_diragukan_jumlah_mb }}"
                class="form-control form-control-lg form-control-solid" style="text-align:right;" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Macet<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_macet" id="kolektabilitas_macet" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" value="{{ $pumk_bulan?->kolektabilitas_macet }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_macet_jumlah_mb" id="kolektabilitas_macet_jumlah_mb" value="{{ $pumk_bulan?->kolektabilitas_macet_jumlah_mb }}"
                class="form-control form-control-lg form-control-solid" style="text-align:right;" placeholder="Jumlah MB" />
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-lg-3">
                <div class="ms-2">Pinjaman Bermasalah<span style="color: red">*</span></div>
            </div>
            <div class="col-lg-6">
                <input type="text" name="kolektabilitas_pinjaman_bermasalah" oninput="formatCurrency(this)"
                onkeypress="return onlyNumbers(event)"  style="text-align:right;" id="kolektabilitas_pinjaman_bermasalah" value="{{ $pumk_bulan?->kolektabilitas_pinjaman_bermasalah }}"
                    class="form-control form-control-lg form-control-solid" placeholder="Rp" />
            </div>
            <div class="col-lg-3">
                <input type="text" name="kolektabilitas_pinjaman_bermasalah_jumlah_mb"  id="kolektabilitas_pinjaman_bermasalah_jumlah_mb" value="{{ $pumk_bulan?->kolektabilitas_pinjaman_bermasalah_jumlah_mb }}"
                class="form-control form-control-lg form-control-solid" style="text-align:right;" placeholder="Jumlah MB" />
            </div>
        </div> --}}
       
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
    var title = "{{ $actionform == 'edit' ? 'Update' : 'Tambah' }}" + " {{ $pagetitle }}" + `<span class="text-gray-600 fs-6 ms-1">- {{ $perusahaan?->nama_lengkap }}</span>`;
    var urleditstore = "{{ route('laporan_realisasi.bulanan.pumk.store') }}";
    var urlgetdata = "{{ route('laporan_realisasi.bulanan.pumk.get_data') }}";
    

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

        // Trigger the change event to apply the script on page load
        $("#bulan_id_create").change();
        $("#tahun_create").change();

        //Get Data
        async function getPumkBulanData(tahunValue, bulanValue) {
            let actionform = $('#actionform').val();
            let pumk_bulan_id = $('#pumk_bulan_id').val();
            let perusahaan_id = $('#perusahaan_id').val();

            try {
                // Perform AJAX request using await
                const response = await $.ajax({
                    url: urlgetdata, // Replace with your actual URL
                    method: 'POST', // Replace with the desired HTTP method
                    data: {
                        tahun: tahunValue,
                        bulan_id: bulanValue,
                        pumk_bulan_id: pumk_bulan_id,
                        perusahaan_id: perusahaan_id,
                        actionform: actionform
                    },
                    beforeSend: function () {
                        // Show the SweetAlert animation here
                        Swal.fire({
                            title: 'Loading...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    // Hide the SweetAlert when the AJAX request is completed
                    complete: function () {
                        Swal.close();
                    }
                });
                // Return the response data
                return response;
            } catch (error) {
                // Handle any errors that occur during the AJAX request
                console.error(error);
                throw error; // Rethrow the error to be caught in the calling function
            }
        }

        // Handle change event for the 'tahun' select element
        $('#tahun_create').change(async function() {
            var selectedTahun = $(this).val();
            var selectedBulan = $('#bulan_id_create').val();

            await handleDataUpdate(selectedTahun, selectedBulan);
        })

        // Handle change event for the 'bulan_id_create' select element
        $('#bulan_id_create').change(async function() {
            var selectedTahun = $('#tahun_create').val();
            var selectedBulan = $(this).val();

                // Call the function to make the AJAX request
                await handleDataUpdate(selectedTahun, selectedBulan);
        });

        // Function to handle data retrieval and updating the UI
        async function handleDataUpdate(selectedTahun, selectedBulan) {
            try {
                // Call the function to make the AJAX request
                let data = await getPumkBulanData(selectedTahun, selectedBulan);
                // Handle the response data here
                
                // Access the properties from the response data
                const propertiesToUpdate = [
                    'nilai_penyaluran',
                    'nilai_penyaluran_melalui_bri',
                    'nilai_penyaluran_melalui_pegadaian',
                    'jumlah_mb',
                    'jumlah_mb_naik_kelas',
                    // 'kolektabilitas_lancar',
                    // 'kolektabilitas_lancar_jumlah_mb',
                    // 'kolektabilitas_kurang_lancar',
                    // 'kolektabilitas_kurang_lancar_jumlah_mb',
                    // 'kolektabilitas_diragukan',
                    // 'kolektabilitas_diragukan_jumlah_mb',
                    // 'kolektabilitas_macet',
                    // 'kolektabilitas_macet_jumlah_mb',
                    // 'kolektabilitas_pinjaman_bermasalah',
                    // 'kolektabilitas_pinjaman_bermasalah_jumlah_mb'
                ];
                
                // Update the values of input fields with the retrieved values
                propertiesToUpdate.forEach(property => {
                    $('#' + property).val(data[property]);
                });

                // Trigger formatCurrency function on input fields with placeholder "Rp"
                var currencyInputs = document.querySelectorAll('input[type="text"][placeholder="Rp"]');
                currencyInputs.forEach(function(input) {
                    formatCurrency(input);
                    input.addEventListener('input', function() {
                        formatCurrency(this);
                    });
                    input.addEventListener('change', function() {
                        formatCurrency(this);
                    });
                });

                // Trigger formatCurrency function on the initial values of currency inputs
                window.addEventListener('DOMContentLoaded', function() {
                    currencyInputs.forEach(function(input) {
                        formatCurrency(input);
                    });
                });
            } catch (error) {
                console.error(error); // Handle any errors that occurred during the AJAX request
            }
        }
  


    });
    function setFormValidate() {
        let perusahaan_id = $('#perusahaan_id').val()
        if (perusahaan_id === '3') {
            validationRules = {
                tahun_create: 'required',
                bulan_id_create: 'required',
                nilai_penyaluran: 'required', // Add this rule for perusahaan_id === '3'
                jumlah_mb: 'required',
                jumlah_mb_naik_kelas: 'required',
            };

            messagesRules = {
                        tahun_create: 'Tahun harus terisi!',
                        bulan_id_create: 'Bulan harus terisi!',
                        nilai_penyaluran: 'Nilai Penyaluran harus terisi!',
                        jumlah_mb: 'Jumlah MB baru harus terisi!',
                        jumlah_mb_naik_kelas: 'Jumlah MB Naik Kelas harus terisi!',
            };
        } else {
            validationRules = {
                tahun_create: 'required',
                bulan_id_create: 'required',
                nilai_penyaluran_melalui_bri: 'required', // Add this rule for perusahaan_id !== '3'
                nilai_penyaluran_melalui_pegadaian: 'required', // Add this rule for perusahaan_id !== '3'
                // jumlah_mb: 'required',
                // jumlah_mb_naik_kelas: 'required',
            };
            messagesRules = {
                        tahun_create: 'Tahun harus terisi!',
                        bulan_id_create: 'Bulan harus terisi!',
                        nilai_penyaluran_melalui_bri: 'Nilai Penyaluran Melalui BRI harus terisi!',
                        nilai_penyaluran_melalui_pegadaian: 'Nilai Penyaluran Melalui Pegadaian harus terisi!',
                        // jumlah_mb: 'Jumlah MB baru harus terisi!',
                        // jumlah_mb_naik_kelas: 'Jumlah MB Naik Kelas harus terisi!',
            };
        }
        $('#form-edit').validate({
                // rules: {
                //     tahun_create: 'required',
                //     bulan_id_create: 'required',
                //     //
                //     // nilai_penyaluran: 'required',
                //     // nilai_penyaluran_melalui_bri: 'required',
                //     jumlah_mb: 'required',
                //     jumlah_mb_naik_kelas: 'required',
                //     //
                //     // kolektabilitas_lancar: 'required',
                //     // kolektabilitas_lancar_jumlah_mb: 'required',
                //     // kolektabilitas_kurang_lancar: 'required',
                //     // kolektabilitas_kurang_lancar_jumlah_mb: 'required',
                //     // kolektabilitas_diragukan: 'required',
                //     // kolektabilitas_diragukan_jumlah_mb: 'required',
                //     // kolektabilitas_macet: 'required',
                //     // kolektabilitas_macet_jumlah_mb: 'required',
                //     // kolektabilitas_pinjaman_bermasalah: 'required',
                //     // kolektabilitas_pinjaman_bermasalah_jumlah_mb: 'required',
                // },
                rules: validationRules,
                // messages: {
                //     tahun_create: 'tahun harus terisi!',
                //     bulan_id_create: 'Bulan harus terisi!',
                //     //
                //     // nilai_penyaluran: 'Nilai Penyaluran harus terisi!',
                //     // nilai_penyaluran_melalui_bri: 'Nilai Penyaluran Melalui BRI harus terisi!',
                //     jumlah_mb: 'Jumlah MB baru harus terisi!',
                //     jumlah_mb_naik_kelas: 'Jumlah MB Naik Kelas harus terisi!',
                //     //
                //     // kolektabilitas_lancar: 'Kolektabilitas Lancar harus terisi!',
                //     // kolektabilitas_lancar_jumlah_mb: 'Jumlah MB Kolektabilitas Lancar harus terisi!',
                //     // kolektabilitas_kurang_lancar: 'Kolektabilitas Kurang Lancar harus terisi!',
                //     // kolektabilitas_kurang_lancar_jumlah_mb: 'Jumlah MB Kolektabilitas Kurang Lancar harus terisi!',
                //     // kolektabilitas_diragukan: 'Kolektabilitas Diragukan harus terisi!',
                //     // kolektabilitas_diragukan_jumlah_mb: 'Jumlah MB Kolektabilitas Diragukan harus terisi!',
                //     // kolektabilitas_macet: 'Kolektabilitas Macet harus terisi!',
                //     // kolektabilitas_macet_jumlah_mb: 'Jumlah MB Kolektabilitas Macet harus terisi!',
                //     // kolektabilitas_pinjaman_bermasalah: 'Kolektabilitas Pinjaman Bermasalah harus terisi!',
                //     // kolektabilitas_pinjaman_bermasalah_jumlah_mb: 'Jumlah MB Kolektabilitas Pinjaman Bermasalah harus terisi!',
                // },
                messages: messagesRules,
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

                    let perusahaan_id = $('#perusahaan_id').val()
                    let tahun = $('#tahun_create').val()
                    let bulan_id_create = $('#bulan_id_create').val()
                    //
                    let actionform = $('#actionform').val()
                    let pumk_bulan_id = $('#pumk_bulan_id').val()
                    if (perusahaan_id === '3') {
                        let nilai_penyaluran = $('#nilai_penyaluran').val()
                        $('#nilai_penyaluran').val(parseInt(nilai_penyaluran.replace(/[^0-9\-]/g, '')))

                        let jumlah_mb = $('#jumlah_mb').val()
                        let jumlah_mb_naik_kelas = $('#jumlah_mb_naik_kelas').val()
                        $('#jumlah_mb').val(parseInt(jumlah_mb.replace(/[^0-9\-]/g, '')))
                        $('#jumlah_mb_naik_kelas').val(parseInt(jumlah_mb_naik_kelas.replace(/[^0-9\-]/g, '')))
                    } else {
                        let nilai_penyaluran_melalui_bri = $('#nilai_penyaluran_melalui_bri').val()
                        $('#nilai_penyaluran_melalui_bri').val(parseInt(nilai_penyaluran_melalui_bri.replace(/[^0-9\-]/g, '')))
                        let nilai_penyaluran_melalui_pegadaian = $('#nilai_penyaluran_melalui_pegadaian').val()
                        $('#nilai_penyaluran_melalui_pegadaian').val(parseInt(nilai_penyaluran_melalui_pegadaian.replace(/[^0-9\-]/g, '')))
                    }
                    
                    //
                    // let kolektabilitas_lancar = $('#kolektabilitas_lancar').val()
                    // let kolektabilitas_lancar_jumlah_mb = $('#kolektabilitas_lancar_jumlah_mb').val()
                    // let kolektabilitas_kurang_lancar = $('#kolektabilitas_kurang_lancar').val()
                    // let kolektabilitas_kurang_lancar_jumlah_mb = $('#kolektabilitas_kurang_lancar_jumlah_mb').val()
                    // let kolektabilitas_diragukan = $('#kolektabilitas_diragukan').val()
                    // let kolektabilitas_diragukan_jumlah_mb = $('#kolektabilitas_diragukan_jumlah_mb').val()
                    // let kolektabilitas_macet = $('#kolektabilitas_macet').val()
                    // let kolektabilitas_macet_jumlah_mb = $('#kolektabilitas_macet_jumlah_mb').val()
                    // let kolektabilitas_pinjaman_bermasalah = $('#kolektabilitas_pinjaman_bermasalah').val()
                    // let kolektabilitas_pinjaman_bermasalah_jumlah_mb = $('#kolektabilitas_pinjaman_bermasalah_jumlah_mb').val()

                    $('#tahun_create').val(parseInt(tahun.replace(/[^0-9\-]/g, '')))
                    $('#bulan_id_create').val(parseInt(bulan_id_create.replace(/[^0-9\-]/g, '')))
                    
                   
                    
                    // $('#kolektabilitas_lancar').val(parseInt(kolektabilitas_lancar.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_lancar_jumlah_mb').val(parseInt(kolektabilitas_lancar_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_kurang_lancar').val(parseInt(kolektabilitas_kurang_lancar.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_kurang_lancar_jumlah_mb').val(parseInt(kolektabilitas_kurang_lancar_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_diragukan').val(parseInt(kolektabilitas_diragukan.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_diragukan_jumlah_mb').val(parseInt(kolektabilitas_diragukan_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_macet').val(parseInt(kolektabilitas_macet.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_macet_jumlah_mb').val(parseInt(kolektabilitas_macet_jumlah_mb.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_pinjaman_bermasalah').val(parseInt(kolektabilitas_pinjaman_bermasalah.replace(/[^0-9\-]/g, '')))
                    // $('#kolektabilitas_pinjaman_bermasalah_jumlah_mb').val(parseInt(kolektabilitas_pinjaman_bermasalah_jumlah_mb.replace(/[^0-9\-]/g, '')))
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
                        
                        console.log('data',data.data)
                        swal.fire({
                            title: 'Sukses',
                            html: data.data.msg,
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

        element.value = value ? formattedValue : null;

    }

    function onlyNumbers(e) {
        var ASCIICode = (e.which) ? e.which : e.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }

   // Trigger formatCurrency function on input fields with placeholder "Rp"
   var currencyInputs = document.querySelectorAll('input[type="text"][placeholder="Rp"]');
        currencyInputs.forEach(function(input) {
            formatCurrency(input);
            input.addEventListener('input', function() {
                formatCurrency(this);
            });
            input.addEventListener('change', function() {
                formatCurrency(this);
            });
        });

        // Trigger formatCurrency function on the initial values of currency inputs
        window.addEventListener('DOMContentLoaded', function() {
            currencyInputs.forEach(function(input) {
                formatCurrency(input);
            });
        });

    
</script>
