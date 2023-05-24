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
                    <form class="kt-form kt-form--label-right" method="POST" id="form-edit">
	                @csrf
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            <input type="hidden" id="perusahaan_id" name="perusahaan_id" value="{{$perusahaan->id}}" class="form-control"/>
                            <input type="text" id="perusahaan" value="{{$perusahaan->nama_lengkap}}" class="form-control" disabled/>
                        </div>
                        <div class="col-lg-6">
                            <label>Tahun</label>
                            <input type="hidden" id="tahun" name="tahun" value="{{$tahun}}" class="form-control"/>
                            <input type="text" value="{{$tahun}}" class="form-control" disabled/>
                        </div>
                    </div>
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>Periode Laporan</label>
                            <input type="hidden" id="periode_laporan_id" name="periode_laporan_id" value="{{$periode_laporan->id}}" class="form-control"/>
                            <input type="text" id="periode_laporan" value="{{$periode_laporan->nama}}" class="form-control" disabled/>
                        </div>
                        <div class="col-lg-6">
                            <label>Jenis Laporan</label>
                            <input type="hidden" id="laporan_keuangan_id" name="laporan_keuangan_id" value="{{$jenis_laporan->id}}" class="form-control"/>
                            <input type="text" id="laporan_keuangan" value="{{$jenis_laporan->nama}}" class="form-control" disabled/>
                        </div>
                    </div>
                    <div class="separator border-gray-200 mb-10"></div>

                    <div class="input-laporan mb-5">
                        <div class="form-group row mb-5">
                            <h4 style="text-align:center;font-weight:bold;">{{$jenis_laporan->nama}}</h4>
                        </div>
                        <div class="form-group row mb-5">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        @for($i=0;$i<count($parent);$i++)
                                        <tr>
                                            <td><span style="font-weight:bold;">{{$parent[$i]->label}}</span></td>
                                            <td>
                                                <input name="id[]" type="hidden" value="{{$parent[$i]->id}}"/>
                                                <input name="relasi_id[]" type="hidden" value="{{$parent[$i]->relasi_laporan_keuangan_id}}" disabled/>
                                                @if($parent[$i]->formula!=null)
                                                <input name="nilai[]" value="{{number_format($parent[$i]->nilai,0,',',',')}}" data-formula="{{$parent[$i]->formula}}" data-is_pengurangan="{{$parent[$i]->is_pengurangan}}" class="nilai form-control" data-kode="{{$parent[$i]->kode}}" style="text-align:right;background-color:#eef3f7;" type="text" readonly/>
                                                @elseif($parent[$i]->is_input)
                                                <input name="nilai[]" value="{{number_format($parent[$i]->nilai,0,',',',')}}" data-is_pengurangan="{{$parent[$i]->is_pengurangan}}" class="nilai nilai-input form-control" data-kode="{{$parent[$i]->kode}}" style="text-align:right;" type="text" disabled/>
                                                @else
                                                <input name="nilai[]" value="{{number_format($parent[$i]->nilai,0,',',',')}}" data-is_pengurangan="{{$parent[$i]->is_pengurangan}}" class="nilai nilai-input form-control" data-kode="{{$parent[$i]->kode}}" style="text-align:right;" type="hidden" disabled/>
                                                @endif
                                            </td>
                                        </tr>
                                            @php 
                                                $parent_child = $child[$parent[$i]->parent_id];
                                            @endphp
                                            @for($j=0;$j<count($parent_child);$j++)
                                            <tr>
                                            <td><span style="padding-left: 30px;">{{$parent_child[$j]->label}}</span></td>
                                                <td>
                                                    <input name="id[]" type="hidden" value="{{$parent_child[$j]->id}}"/>
                                                    <input name="relasi_id[]" type="hidden" value="{{$parent_child[$j]->relasi_laporan_keuangan_id}}" disabled/>
                                                    @if($parent_child[$j]->formula!=null)
                                                    <input name="nilai[]" value="{{number_format($parent_child[$j]->nilai,0,',',',')}}" data-formula="{{$parent_child[$j]->formula}}" data-is_pengurangan="{{$parent_child[$j]->is_pengurangan}}" class="nilai form-control" data-kode="{{$parent_child[$j]->kode}}" style="text-align:right;background-color:#eef3f7;" type="text" readonly/>
                                                    @elseif($parent_child[$j]->is_input)
                                                    <input name="nilai[]" value="{{number_format($parent_child[$j]->nilai,0,',',',')}}" data-is_pengurangan="{{$parent_child[$j]->is_pengurangan}}" class="nilai nilai-input form-control" data-kode="{{$parent_child[$j]->kode}}" style="text-align:right;" type="text" disabled/>
                                                    @else
                                                    <input name="nilai[]" value="{{number_format($parent_child[$j]->nilai,0,',',',')}}" data-is_pengurangan="{{$parent_child[$j]->is_pengurangan}}" class="nilai nilai-input form-control" data-kode="{{$parent_child[$j]->kode}}" style="text-align:right;" type="hidden"/>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endfor
                                        @endfor
                                    </table>
                                    <br>
                                    <hr>
                                        <div class="col-md-12 text-center">
                                            <a id="back" type="button" href="{{ url()->previous()  }}" class="btn btn-sm btn-danger me-3"><i class="fa fa-arrow-alt-circle-left"></i> Batal</a>
                                            <a type="button" class="btn btn-info btn-sm cls-export"  data-toggle="tooltip" title="Download PDF"><i class="bi bi-file-pdf fs-3"></i> Cetak PDF</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
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
    var urlstore = "{{route('laporan_manajemen.laporan_keuangan.update')}}";
    var urlindex = "{{route('laporan_manajemen.laporan_keuangan.index')}}";
    var urlexport = "{{route('laporan_manajemen.laporan_keuangan.export_pdf')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");

        $('body').on('click','#batal',function(){
            window.location.href = urlindex;
        });
        
        setFormValidate();
        
        $('.nilai').keyup(function(event) {
            if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                return value
                    .replace(/[^-\d]/g, '')
                    .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            });
        });
        $('.nilai-input').keyup(function() {
            calculateFormula();
        });

        $('body').on('click','.cls-export',function(){
            exportPdf();
        });
    });
    
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

                                confirmButtonText: "<i class='flaticon2-checkmark'></i> OK"
                        });	                   

                        if(data.flag == 'success') {
                            $('#winform').modal('hide');
                            window.location.href = urlindex;
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
    
    function calculateFormula() {
        $('.nilai').each(function() {
            var sum = 0;
            var formula = $(this).attr('data-formula'); 
            var input_formula = $(this);
            if(formula){
                var arr_plus = formula.split('+');
                for(i=0;i<arr_plus.length;i++){
                    var arr_minus = arr_plus[i].split('-');
                    for(j=0;j<arr_minus.length;j++){
                        var class_name = '*[data-kode="'+arr_minus[j]+'"]';
                        var value = 0;
                        $(class_name).each( function() { 
                            if (this.value.length != 0) {
                                value = $(this).val().replace(/-\D/g, "").replace(/,/g, "");
                            }
                        });
                        if(j==0){
                            sum+=parseInt(value);
                        }else{
                            sum-=parseInt(value);
                        }
                    }
                }
                input_formula.val(addCommas(sum));
            }
        });
    }
    
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

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


    function exportPdf()
    {
        var perusahaan_id = $('#perusahaan_id').val();
        var tahun = $('#tahun').val();
        var jenis_laporan_id = $('#laporan_keuangan_id').val();
        var periode_laporan_id = $('#periode_laporan_id').val();

        $.ajax({
            type: 'post',
            data: {
                'perusahaan_id' : perusahaan_id,
                'tahun' : tahun,
                'laporan_keuangan_id' : jenis_laporan_id,
                'periode_laporan_id' : periode_laporan_id
            },
            beforeSend: function () {
                $.blockUI();
            },
            url: urlexport,
            xhrFields: {
                responseType: 'blob',
            },
            success: function(data){
                $.unblockUI();

                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                
                today = dd + '-' + mm + '-' + yyyy;
                var filename = 'Data Laporan Keuangan '+today+'.pdf';

                var blob = new Blob([data], {
                    type: "application/pdf"
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);

                link.click();
                document.body.removeChild(link);
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
</script>
@endsection