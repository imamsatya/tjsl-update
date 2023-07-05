@section('addbeforecss')
    <style>
        td {
            border-bottom: 1px solid #c8c7c7;
        }
    </style>
@endsection

<form class="kt-form kt-form--label-right" method="POST" id="form-edit">
    @csrf
    <input type="hidden" name="id" id="id" readonly="readonly"
        value="{{ $actionform == 'update' ? (int) $data->id : null }}" />
    <input type="hidden" name="actionform" id="actionform" readonly="readonly" value="{{ $actionform }}" />

    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_log">
        <tbody>
            <tr>
                <td><b>Jenis Anggaran</b></td>
                <td>{{ @$data->jenis_anggaran }}</td>
                <td><b>TPB</b></td>
                <td>{{ @$data->no_tpb }} - {{ @$data->nama_tpb }}</td>

            </tr>
            <tr>
                <td><b>Program</b></td>
                <td>{{ @$data->program }}</td>
                <td><b>Kegiatan</b></td>
                <td>{{ @$data->kegiatan }}</td>
            </tr>
            <tr>
                <td><b>Provinsi</b></td>
                <td>{{ @$data->provinsi }}</td>
                <td><b>Kota</b></td>
                <td>{{ @$data->kota }}</td>
            </tr>
            <tr>
                <td><b>Realisasi Anggaran</b></td>
                <td>Rp. {{ number_format($anggaran_total, 0, ',', ',') }}</td>
                <td><b>Bulan</b></td>
                <td>{{ $realisasi[0]->bulan_nama . ' ' . $realisasi[0]->tahun }}</td>
            </tr>
            <tr>
                <td><b>Indikator Capaian</b></td>
                <td>{{ $data->indikator . ' ' . $data->satuan_ukur }}</td>

            </tr>
            <tr>
                <td><b>Jenis Kegiatan</b></td>
                <td>{{ $realisasi[0]->jenis_kegiatan_nama ?? '-' }}</td>
                <td><b>Sub Kegiatan</b></td>
                <td>{{ $realisasi[0]->sub_kegiatan_nama ?? '-' }}</td>
            </tr>
            {{-- <tr>
                <td><b>Indikator Capaian Kegiatan</b></td>
                <td>{{ @$data->indikator }}</td>
                <td><b>Satuan Ukur</b></td>
                <td>{{ @$data->satuan_ukur }}</td>
            </tr>
            <tr>
                <td><b>Alokasi Anggaran</b></td>
                <td>Rp. {{ number_format($data->anggaran_alokasi, 0, ',', ',') }}</td>
                <td><b>Realisasi Anggaran</b></td>
                <td>Rp. {{ number_format($anggaran_total, 0, ',', ',') }}</td>
            </tr> --}}

        </tbody>
    </table>



    <table class="table table-striped- table-bordered table-hover table-checkable" style="border: 1px solid #cfd1d4;"
        id="datatable_target">
        <thead>
            <tr>
                <td colspan="14" style="border: 1px solid #cfd1d4;text-align:center;font-weight:bold;">Realisasi
                    Anggaran (Rp.)</td>
            </tr>
            <tr>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Tahun</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Jan</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Feb</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Mar</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Apr</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Mei</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Jun</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Jul</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Agus</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Sep</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Okt</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Nov</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Des</th>
                <th style="border: 1px solid #cfd1d4;font-weight:bold;">Total</th>
            </tr>
        </thead>
        <tbody id="tb-anggaran">
            @foreach ($tahun as $i)
                @php
                    $realisasi_1 = $realisasi
                        ->where('bulan', 1)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_2 = $realisasi
                        ->where('bulan', 2)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_3 = $realisasi
                        ->where('bulan', 3)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_4 = $realisasi
                        ->where('bulan', 4)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_5 = $realisasi
                        ->where('bulan', 5)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_6 = $realisasi
                        ->where('bulan', 6)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_7 = $realisasi
                        ->where('bulan', 7)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_8 = $realisasi
                        ->where('bulan', 8)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_9 = $realisasi
                        ->where('bulan', 9)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_10 = $realisasi
                        ->where('bulan', 10)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_11 = $realisasi
                        ->where('bulan', 11)
                        ->where('tahun', $i->tahun)
                        ->first();
                    $realisasi_12 = $realisasi
                        ->where('bulan', 12)
                        ->where('tahun', $i->tahun)
                        ->first();
                    
                    $status_1 = $status_2 = $status_3 = $status_4 = $status_5 = $status_6 = $status_7 = $status_8 = $status_9 = $status_10 = $status_11 = $status_12 = null;
                    if (@$realisasi_1->status_id == 1) {
                        $status_1 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_2->status_id == 1) {
                        $status_2 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_3->status_id == 1) {
                        $status_3 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_4->status_id == 1) {
                        $status_4 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_5->status_id == 1) {
                        $status_5 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_6->status_id == 1) {
                        $status_6 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_7->status_id == 1) {
                        $status_7 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_8->status_id == 1) {
                        $status_8 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_9->status_id == 1) {
                        $status_9 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_10->status_id == 1) {
                        $status_10 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_11->status_id == 1) {
                        $status_11 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    if (@$realisasi_12->status_id == 1) {
                        $status_12 = '<i style="color:green;" class="bi bi-check fs-3"></i>';
                    }
                    
                    $total = @$realisasi_1->anggaran + @$realisasi_2->anggaran + @$realisasi_3->anggaran + @$realisasi_4->anggaran + @$realisasi_5->anggaran + @$realisasi_6->anggaran + @$realisasi_7->anggaran + @$realisasi_8->anggaran + @$realisasi_9->anggaran + @$realisasi_10->anggaran + @$realisasi_11->anggaran + @$realisasi_12->anggaran;
                @endphp
                <tr>
                    <td class="vtahunanggaran " style="border: 1px solid #cfd1d4;text-align:right;">
                        {{ $i->tahun }}</td>
                    <td class="vTarget t1" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_1 !!}{{ number_format(@$realisasi_1->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t2" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_2 !!}{{ number_format(@$realisasi_2->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t3" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_3 !!}{{ number_format(@$realisasi_3->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t4" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_4 !!}{{ number_format(@$realisasi_4->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t5" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_5 !!}{{ number_format(@$realisasi_5->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t6" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_6 !!}{{ number_format(@$realisasi_6->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t7" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_7 !!}{{ number_format(@$realisasi_7->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t8" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_8 !!}{{ number_format(@$realisasi_8->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t9" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_9 !!}{{ number_format(@$realisasi_9->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t10" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_10 !!}{{ number_format(@$realisasi_10->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t11" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_11 !!}{{ number_format(@$realisasi_11->anggaran, 0, ',', ',') }}</td>
                    <td class="vTarget t12" style="border: 1px solid #cfd1d4;text-align:right;">
                        {!! $status_12 !!}{{ number_format(@$realisasi_12->anggaran, 0, ',', ',') }}</td>
                    <td class="totalTarget" style="border: 1px solid #cfd1d4;text-align:right;">
                        {{ number_format($total, 0, ',', ',') }}</td>
                </tr>
            @endforeach
            @if ($tahun->count() == 0)
                <tr>
                    <td colspan="14" style="border: 1px solid #cfd1d4; text-align:center;"><i>Data Kosong</i></td>
                </tr>
            @endif
        </tbody>
    </table>

</form>

<script type="text/javascript">
    var title = "Detail Kegiatan";

    $(document).ready(function() {
        $('.modal-title').html(title);
        $('.form-select2').select2();

        $('.modal').on('shown.bs.modal', function() {
            setFormValidate();
        });

        $("#tb-anggaran").find("tr").each(function() {
            $(this).find('td.vanggaran').on('keyup', function() {
                totalanggaran();
            });
        });
        $("#tb-realisasi").find("tr").each(function() {
            $(this).find('td.vRealisasi').on('keyup', function() {
                totalRealisasi();
            });
        });
        $("#tb-target").find("tr").each(function() {
            $(this).find('td.vTarget').on('keyup', function() {
                totalTarget();
            });
        });
    });

    function totalanggaran() {
        var total = 0;
        $("#tb-anggaran").find('tr').each(function() {
            total += price_to_number($(this).find('td.a1').html());
            total += price_to_number($(this).find('td.a2').html());
            total += price_to_number($(this).find('td.a3').html());
            total += price_to_number($(this).find('td.a4').html());
            total += price_to_number($(this).find('td.a5').html());
            total += price_to_number($(this).find('td.a6').html());
            total += price_to_number($(this).find('td.a7').html());
            total += price_to_number($(this).find('td.a8').html());
            total += price_to_number($(this).find('td.a9').html());
            total += price_to_number($(this).find('td.a10').html());
            total += price_to_number($(this).find('td.a11').html());
            total += price_to_number($(this).find('td.a12').html());
            $(this).find('td.totalanggaran').html(total);
        });
    }

    function totalRealisasi() {
        var total = 0;
        $("#tb-realisasi").find('tr').each(function() {
            total += price_to_number($(this).find('td.r1').html());
            total += price_to_number($(this).find('td.r2').html());
            total += price_to_number($(this).find('td.r3').html());
            total += price_to_number($(this).find('td.r4').html());
            total += price_to_number($(this).find('td.r5').html());
            total += price_to_number($(this).find('td.r6').html());
            total += price_to_number($(this).find('td.r7').html());
            total += price_to_number($(this).find('td.r8').html());
            total += price_to_number($(this).find('td.r9').html());
            total += price_to_number($(this).find('td.r10').html());
            total += price_to_number($(this).find('td.r11').html());
            total += price_to_number($(this).find('td.r12').html());
            $(this).find('td.totalRealisasi').html(total);
        });
    }

    function totalTarget() {
        var total = 0;
        $("#tb-target").find('tr').each(function() {
            total += price_to_number($(this).find('td.t1').html());
            total += price_to_number($(this).find('td.t2').html());
            total += price_to_number($(this).find('td.t3').html());
            total += price_to_number($(this).find('td.t4').html());
            total += price_to_number($(this).find('td.t5').html());
            total += price_to_number($(this).find('td.t6').html());
            total += price_to_number($(this).find('td.t7').html());
            total += price_to_number($(this).find('td.t8').html());
            total += price_to_number($(this).find('td.t9').html());
            total += price_to_number($(this).find('td.t10').html());
            total += price_to_number($(this).find('td.t11').html());
            total += price_to_number($(this).find('td.t12').html());
            $(this).find('td.totalTarget').html(total);
        });
    }

    function price_to_number(v) {
        if (!v) {
            return 0;
        }
        v = v.split('.').join('');
        v = v.split(',').join('.');
        return Number(v.replace(/[^0-9.]/g, ""));
    }

    function setFormValidate() {
        $('#form-edit').validate({
            rules: {
                nama: {
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
                if (element.parent('.validated').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                var typesubmit = $("input[type=submit][clicked=true]").val();

                $(form).ajaxSubmit({
                    type: 'post',
                    url: urlstore,
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
                            title: data.title,
                            html: data.msg,
                            icon: data.flag,

                            buttonsStyling: true,

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });

                        if (data.flag == 'success') {
                            $('#winform').modal('hide');
                            datatable.ajax.reload(null, false);
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

                            confirmButtonText: "<i class='flaticon2-checkmark'></i> OK",
                        });
                    }
                });
                return false;
            }
        });
    }
</script>
