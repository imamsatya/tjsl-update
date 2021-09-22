@extends('layouts.app')

@section('addbeforecss')
    <link href="{{asset('plugins/jquery-treegrid-master/css/jquery.treegrid.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .border_bottom {
            border-bottom: 1px solid #c8c7c7;
        }
        .table td 
        {
            vertical-align: middle;
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
                    <h2 class="d-flex align-items-center">{{ $pagetitle }}
                    <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
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
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
                                <option></option>
                                @foreach($perusahaan as $p)  
                                    @php
                                        $select = (($p->id == $perusahaan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" >
                                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
                                    @php
                                        $select = (($i ==  date("Y")) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Periode Laporan</label>
                            <select class="form-select form-select-solid form-select2" id="periode_laporan" name="periode_laporan" data-kt-select2="true" data-placeholder="Pilih Periode">
                                <option></option>
                                @foreach($periode_laporan as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Jenis Laporan</label>
                            <select class="form-select form-select-solid form-select2" id="jenis_laporan" name="jenis_laporan" data-kt-select2="true" data-placeholder="Pilih Jenis Laporan">
                                <option></option>
                                @foreach($jenis_laporan as $p)  
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <button id="proses" class="btn btn-primary me-3">Proses</button>
                            <button id="batal" class="btn btn-danger me-3">Batal</button>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>

                    <div class="input-laporan mb-5">
                    </div>
                    
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>
@endsection

@section('addafterjs')
<script type="text/javascript" src="{{asset('plugins/jquery-treegrid-master/js/jquery.treegrid.js')}}"></script>

<script>
    var urlstore = "{{route('laporan_manajemen.laporan_keuangan.store')}}";
    var urlindex = "{{route('laporan_manajemen.laporan_keuangan.index')}}";

    $(document).ready(function(){
//        $('.tree').treegrid();

        $('.tree').treegrid({
            initialState : 'collapsed',
            treeColumn : 1,
            indentTemplate : '<span style="width: 20px; height: 10px; display: inline-block; position: relative;"></span>'
        });

        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','#batal',function(){
            window.location.href = urlindex;
        });

        $('body').on('click','#proses',function(){
            if($('#jenis_laporan').val()){
                onbtnproses();
            }else{
                swal.fire({
                        title: "Gagal",
                        html: 'Pilihan Jenis laporan wajib diisi!',
                        icon: 'error',

                        buttonsStyling: true,

                        confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                });	 
            }
        });
    });
    
    function calculateJumlah() {
        var sum = 0;
        $('.nilai').each(function() {
            if (this.value.length != 0) {
                Ins = this.value.replace(/-\D/g, "").replace(/,/g, "");
                var is_pengurangan = $(this).attr('data-is_pengurangan'); 
                if(is_pengurangan == 'true'){
                    sum -= parseInt(Ins);
                }else{
                    sum += parseInt(Ins);
                }
            }
        });
        var sums = parseFloat(sum).toLocaleString('en-US', {
                    style: 'decimal',
                });
        $(".jumlah").html(sums);
    }

    function onbtnproses(){
        $.ajax({
            url: "/laporan_manajemen/laporan_keuangan/getlaporankeuangan?id="+$('#jenis_laporan').val(),
            type: "POST",
            dataType: "json", 
            beforeSend: function(){
                $.blockUI({
                    theme: true,
                    baseZ: 2000
                })    
            },
            success: function(data){
                $.unblockUI();
                $(".input-laporan").empty();

                var contentData = '<div class="form-group row mb-5"><h4 style="text-align:center;font-weight:bold;">'+$('#jenis_laporan option:selected').text()+'</h4></div>';
                contentData += '<div class="form-group row mb-5"><div class="col-lg-12"><div class="table-responsive"><table class="table">';
                for(var i = 0, len = data.parent.length; i < len; ++i) {
                    contentData += '<tr>';
                        contentData += '<td>';
                            contentData += '<span style="font-weight:bold;">'+data.parent[i].label+'</span>';
                        contentData += '</td>';
                        contentData += '<td>';
                            contentData += '<input name="parent_id[]" type="hidden" value="'+data.parent[i].parent_id+'"/>';
                            contentData += '<input name="nilai[]" data-is_pengurangan="'+data.parent[i].is_pengurangan+'" class="nilai form-control" style="text-align:right;" type="text"/>';
                        contentData += '</td>';
                        contentData += '<td>';
                            if(data.parent[i].is_pengurangan){
                                contentData += '<span>(-)</span>';
                            }
                        contentData += '</td>';
                    contentData += '</tr>';

                    console.log(data.child[data.parent[i].parent_id]);
                    //get child
                    var child = data.child[data.parent[i].parent_id];
                    for(var j = 0, len2 = child.length; j < len2; ++j) {
                        contentData += '<tr>';
                            contentData += '<td>';
                                contentData += '<span style="padding-left: 30px;">'+child[j].label+'</span>';
                            contentData += '</td>';
                            contentData += '<td>';
                                contentData += '<input name="child_id[]" type="hidden" value="'+child[j].child_id+'"/>';
                                contentData += '<input name="nilai[]" data-is_pengurangan="'+child[j].is_pengurangan+'" class="nilai form-control" style="text-align:right;" type="text"/>';
                            contentData += '</td>';
                            contentData += '<td>';
                                if(child[j].is_pengurangan){
                                    contentData += '<span>(-)</span>';
                                }
                            contentData += '</td>';
                        contentData += '</tr>';
                    }
                }
                
                contentData += '<tr>';
                    contentData += '<td>';
                        contentData += '<span style="font-weight:bold;">Jumlah</span>';
                    contentData += '</td>';
                    contentData += '<td style="text-align:right;font-weight:bold;">';
                        contentData += 'Rp. <span class="jumlah">0</span>';
                    contentData += '</td>';
                    contentData += '<td>';
                    contentData += '</td>';
                contentData += '</tr>';

                contentData += '<tr>';
                    contentData += '<td>';
                    contentData += '</td>';
                    contentData += '<td style="text-align:right;">';
                        contentData += '<button id="simpan" class="btn btn-success me-3">Simpan</button>';
                    contentData += '</td>';
                    contentData += '<td>';
                    contentData += '</td>';
                contentData += '</tr>';

                contentData += '</table></div></div></div>';

                $(".input-laporan").html(contentData);
                $('.nilai').keyup(function(event) {
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
                $('.nilai').keyup(function() {
                    calculateJumlah();
                });
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
    }
</script>
@endsection