@extends('layouts.app')

@section('addbeforecss')
    <link href="{{ asset('plugins/jquery-treegrid-master/css/jquery.treegrid.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .border_bottom {
            border-bottom: 1px solid #c8c7c7;
        }
    </style>
@endsection

@section('content')
    <div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <!--begin::Card-->
            <div class="card">

                <!--begin::Card header-->
                <div class="card-header pt-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="d-flex align-items-center">
                            {{-- {{ $pagetitle }}  --}}
                            Input Data RKA per TPB
                            <span class="text-gray-600 fs-6 ms-1"></span>
                        </h2>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1"
                            data-kt-view-roles-table-toolbar="base">
                            {{-- <button type="button" class="btn btn-active btn-info btn-sm btn-icon btn-search cls-search btn-search-active" style="margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-search cls-search btn-search-unactive" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Cari Data"><i class="bi bi-search fs-3"></i></button>
                        @if (!$view_only)
                        <button type="button" class="btn btn-primary btn-sm btn-icon btn-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-danger btn-sm btn-icon btn-cancel-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Batalkan Validasi"><i class="bi bi-check fs-3"></i></button> 
                        <button type="button" class="btn btn-active btn-light btn-sm btn-icon btn-disable-validasi cls-validasi" style="display:none;margin-right:3px;" data-toggle="tooltip" title="Validasi"><i class="bi bi-check fs-3"></i></button>
                        <button type="button" class="btn btn-success btn-sm btn-icon cls-add" style="margin-right:3px;" data-toggle="tooltip" title="Tambah Data"><i class="bi bi-plus fs-3"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-icon cls-export"  data-toggle="tooltip" title="Download Excel"><i class="bi bi-file-excel fs-3"></i></button>
                        @endif --}}
                        </div>
                        <!--end::Search-->
                        <!--end::Group actions-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--begin::Card body-->
                <div class="card-body p-0">
                    <!--begin::Heading-->
                    <div class="card-px py-10">
                        <form method="POST">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-lg-4">
                                    {{-- <h1>Pilar - TPB</h1> --}}

                                </div>
                                <div class="col-lg-4">
                                    <h1>Anggaran CID</h1>

                                </div>
                                <div class="col-lg-4">
                                    <h1>Anggaran non CID</h1>

                                </div>
                            </div>
                            <?php $i = 0;
                            $j = 0; ?>
                            @foreach ($pilars as $index => $tpbs)
                                {{-- {{ $i }} --}}
                                <div class="row mb-4">
                                    <div class="col-lg-4">
                                        <h3>{{ $index }}</h3>

                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text"name="cid_total[{{ $i }}]" disabled
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="Rp ... (total)" value="" style="text-align:right;" />

                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text"disabled name="noncid_total[{{ $i }}]"
                                            class="form-control form-control-lg form-control-solid"
                                            placeholder="Rp ... (total)" value="" style="text-align:right;" />

                                    </div>
                                </div>
                                @foreach ($tpbs as $indexTPB => $tpb)
                                    {{-- {{ $j }} --}}
                                    <div class="row mb-4">
                                        <div class="col-lg-4">
                                            <div class="ms-8">{{ $indexTPB }}</div>


                                        </div>
                                        <div class="col-lg-4">
                                            @if (isset($tpb[0]))
                                                @if ($tpb[0]->tpb_jenis_anggaran == 'CID')
                                                    {{-- {{ $tpb[0]->tpb_jenis_anggaran }} -
                                                    {{ $tpb[0]->id }} --}}
                                                    <input type="text"data-idrelasi="{{ $tpb[0]->id }}"
                                                        name="cid_tpb[{{ $i }}][{{ $j }}]"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="Rp ..." value="" style="text-align:right;"
                                                        oninput="formatCurrency(this)"
                                                        onkeypress="return onlyNumbers(event)" />
                                                @endif
                                            @endif
                                            @if (isset($tpb[1]))
                                                @if ($tpb[1]->tpb_jenis_anggaran == 'CID')
                                                    {{-- {{ $tpb[1]->tpb_jenis_anggaran }} -
                                                    {{ $tpb[1]->id }} --}}
                                                    <input type="text"data-idrelasi="{{ $tpb[1]->id }}"
                                                        name="cid_tpb[{{ $i }}][{{ $j }}]"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="Rp ..." value="" style="text-align:right;"
                                                        oninput="formatCurrency(this)"
                                                        onkeypress="return onlyNumbers(event)" />
                                                @endif
                                            @endif


                                        </div>
                                        <div class="col-lg-4">
                                            @if (isset($tpb[0]))
                                                @if ($tpb[0]->tpb_jenis_anggaran == 'non CID')
                                                    {{-- {{ $tpb[0]->tpb_jenis_anggaran }} -
                                                    {{ $tpb[0]->id }} --}}
                                                    <input type="text" data-idrelasi="{{ $tpb[0]->id }}"
                                                        name="noncid_tpb[{{ $i }}][{{ $j }}]"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="Rp ..." value="" style="text-align:right;"
                                                        oninput="formatCurrency(this)"
                                                        onkeypress="return onlyNumbers(event)" />
                                                @endif
                                            @endif
                                            @if (isset($tpb[1]))
                                                @if ($tpb[1]->tpb_jenis_anggaran == 'non CID')
                                                    {{-- {{ $tpb[1]->tpb_jenis_anggaran }} -
                                                    {{ $tpb[1]->id }} --}}
                                                    <input type="text" data-idrelasi="{{ $tpb[1]->id }}"
                                                        name="noncid_tpb[{{ $i }}][{{ $j }}]"
                                                        class="form-control form-control-lg form-control-solid"
                                                        placeholder="Rp ..." value="" style="text-align:right;"
                                                        oninput="formatCurrency(this)"
                                                        onkeypress="return onlyNumbers(event)" />
                                                @endif
                                            @endif
                                            {{-- {{ $tpb[1]->tpb_jenis_anggaran ?? false }} --}}


                                        </div>
                                    </div>
                                    <?php $j++; ?>
                                @endforeach
                                <br><br>

                                <?php $i++; ?>
                            @endforeach
                            <div class="row mb-9">
                                <div class="col-lg-4">
                                    <h2>Total Anggaran</h2>


                                </div>
                                <div class="col-lg-4">
                                    <input type="text"name="cid_grand_total"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... (grand total)" disabled value=""
                                        onchange="formatCurrency(this)" style="text-align:right;" />

                                </div>
                                <div class="col-lg-4">
                                    <input type="text"name="noncid_grand_total"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="Rp ... (grand total)" disabled value=""
                                        onchange="formatCurrency(this)" style="text-align:right;" />

                                </div>
                            </div>


                        </form>
                        <div class="form-group row mt-2  mb-5 text-end">
                            <div class="col-lg-12">
                                <button id="proses" class="btn btn-danger me-3">Close</button>
                                <button id="clear-btn" class="btn btn-info me-3">Clear</button>
                                <button id="simpan-btn" class="btn btn-success me-3">Simpan</button>
                            </div>
                        </div>



                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>
@endsection

@section('addafterjs')
    <script type="text/javascript" src="{{ asset('plugins/jquery-treegrid-master/js/jquery.treegrid.js') }}"></script>

    <script>
        $(document).ready(function() {
            // $('input[name^="cid_tpb"]').on('input', function() {
            //     $(this).val($(this).val().replace(/[^0-9]/g, ''));


            // });
            // $('input[name^="noncid_tpb"]').on('input', function() {
            //     $(this).val($(this).val().replace(/[^0-9]/g, ''));
            // });
        });

        function formatCurrency(element) {
            const value = element.value.replace(/[^\d]/g, "");
            const formatter = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            const formattedValue = formatter.format(value);
            element.value = formattedValue.replace(/,/g, ".");
        }

        function onlyNumbers(event) {
            const key = event.keyCode || event.which;
            if (key < 48 || key > 57) {
                event.preventDefault();
            }
        }
        const clearBtn = document.querySelector("#clear-btn");
        clearBtn.addEventListener("click", function() {
            const inputFields = document.querySelectorAll("input[type='number']");
            inputFields.forEach(function(input) {
                input.value = 0;
            });
        });

        const simpanBtn = document.querySelector("#simpan-btn");
        simpanBtn.addEventListener("click", function() {
            console.log('simpan clicked')
            const tpbs_value = [];
            const cidTpbFields = document.querySelectorAll('input[name^="cid_tpb"]');
            cidTpbFields.forEach(function(cidTpbField) {
                const object = {
                    idrelasi: cidTpbField.dataset.idrelasi,
                    value: cidTpbField.value
                };
                tpbs_value.push(object);

            })

            const noncidTpbFields = document.querySelectorAll('input[name^="noncid_tpb"]');
            noncidTpbFields.forEach(function(noncidTpbField) {
                const object = {
                    idrelasi: noncidTpbField.dataset.idrelasi,
                    value: noncidTpbField.value
                };
                tpbs_value.push(object);

            })
        });


        //CID
        // Select all cid_tpb input fields
        const cidTpbFields = document.querySelectorAll('input[name^="cid_tpb"]');
        // For each cid_tpb field, add an event listener to update the corresponding cid_total field
        cidTpbFields.forEach(function(cidTpbField) {
            cidTpbField.addEventListener('input', function() {
                // Get the index of the current cid_tpb field
                const fieldIndex = cidTpbField.getAttribute('name').match(/\[(\d+)\]\[(\d+)\]/);
                const pillarIndex = fieldIndex[1];
                const tpbIndex = fieldIndex[2];

                // Get the corresponding cid_total and noncid_total fields
                const cidTotalField = document.querySelector(`input[name="cid_total[${pillarIndex}]"]`);


                // Calculate the total for the current pillar and update the cid_total and noncid_total fields
                let cidTotal = 0;

                const tpbFields = document.querySelectorAll(`input[name^="cid_tpb[${pillarIndex}]"]`);
                tpbFields.forEach(function(tpbField) {
                    if (tpbField.value) {
                        let value = tpbField.value.replace(/[^\d]/g,
                            ""); // remove any non-numeric characters from the input value
                        if (tpbField.getAttribute('name').includes('cid')) {
                            cidTotal += parseInt(value);
                        }
                    }
                });
                cidTotalField.value = cidTotal;


                // Update the cid_grand_total field
                const cidGrandTotalField = document.querySelector('input[name="cid_grand_total"]');
                let cidGrandTotal = 0;
                const cidTotalFields = document.querySelectorAll('input[name^="cid_total"]');
                cidTotalFields.forEach(function(cidTotalField) {
                    if (cidTotalField.value) {
                        let value = cidTotalField.value.replace(/[^\d]/g,
                            ""); // remove any non-numeric characters from the input value
                        cidGrandTotal += parseInt(value) || 0;
                    }
                });
                cidGrandTotalField.value = cidGrandTotal;
            });
        });

        //noncid
        // Select all noncid_tpb input fields
        const noncidTpbFields = document.querySelectorAll('input[name^="noncid_tpb"]');
        // For each noncid_tpb field, add an event listener to update the corresponding noncid_total field
        noncidTpbFields.forEach(function(noncidTpbField) {
            noncidTpbField.addEventListener('input', function() {
                // Get the index of the current cid_tpb field
                const fieldIndex = noncidTpbField.getAttribute('name').match(/\[(\d+)\]\[(\d+)\]/);
                const pillarIndex = fieldIndex[1];
                const tpbIndex = fieldIndex[2];

                // Get the corresponding cid_total and noncid_total fields
                const noncidTotalField = document.querySelector(
                    `input[name="noncid_total[${pillarIndex}]"]`);


                // Calculate the total for the current pillar and update the cid_total and noncid_total fields
                let noncidTotal = 0;

                const tpbFields = document.querySelectorAll(`input[name^="noncid_tpb[${pillarIndex}]"]`);
                tpbFields.forEach(function(tpbField) {
                    if (tpbField.value) {
                        let value = tpbField.value.replace(/[^\d]/g,
                            ""); // remove any non-numeric characters from the input value
                        if (tpbField.getAttribute('name').includes('noncid')) {
                            noncidTotal += parseInt(value);
                        }
                    }
                });
                noncidTotalField.value = noncidTotal;


                // Update the cid_grand_total field
                const noncidGrandTotalField = document.querySelector('input[name="noncid_grand_total"]');
                let noncidGrandTotal = 0;
                const noncidTotalFields = document.querySelectorAll('input[name^="noncid_total"]');
                noncidTotalFields.forEach(function(noncidTotalField) {
                    if (noncidTotalField.value) {
                        let value = noncidTotalField.value.replace(/[^\d]/g,
                            ""); // remove any non-numeric characters from the input value
                        noncidGrandTotal += parseInt(value) || 0;
                    }
                });
                noncidGrandTotalField.value = noncidGrandTotal;
            });
        });
    </script>
@endsection
