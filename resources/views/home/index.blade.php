@extends('layouts.app')

@section('addbeforecss')
@endsection

@section('content')

<style>
    .chartdiv {
        width: 100%;
        height: 35vh;
    }

    .chart {
        position: relative;
        display: inline-block;
        width: 150px;
        height: 150px;
        margin-top: 20px;
        margin-bottom: 10px;
        text-align: center;
        font-size: 16pt;
    }

    .chart canvas {
        position: absolute;
        top: 0;
        left: 0;
    }
    .percent {
        display: inline-block;
        line-height: 150px;
        z-index: 2;
    }

    .percent:after {
        content: '%';
        margin-left: 0.1em;
        font-size: 16pt;
    }

    .bulet {
        display: block;
        height: 140px;
        width: 350px;
        border-radius: 50%;
        text-align: center;
        border: 1px solid white;
    }
</style>


<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h3 class="d-flex align-items-center">Grafik Realisasi
                    <span class="text-gray-600 fs-6 ms-1"></span></h3>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10">
                    <!--begin: Datatable -->
                    <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : '');
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
                                @php for($i = date("Y"); $i>=2020; $i--){ @endphp
                                    @php
                                        $select = (($i == $tahun) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{$i}}" {!! $select !!}>{{$i}}</option>
                                @php } @endphp
                            </select>
                        </div>
                    </div>
                        
                    <div class="form-group row mb-5">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar1" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan <br>Sosial</span>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar2" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan<br> Ekonomi</span>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar3" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan<br> Lingkungan</span>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar4" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan <br>Hukum dan Tata Kelola</span>
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
<script>
    var urlchartrealisasi = "{{route('home.chartrealisasi')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");
        
        $('#perusahaan_id').on('change', function(event){
            updatechartrealisasi();
        });
        $('#tahun').on('change', function(event){
            updatechartrealisasi();
        });

        initchartrealisasi();
    });
    
    function updatechartrealisasi(){
        $.ajax({
            url: urlchartrealisasi,
            data: {
                'perusahaan_id' : $("#perusahaan_id").val(),
                'tahun' : $("#tahun").val()
            },
            type: "POST",
            dataType: "json", 
            success: function(data){
                $('#chart_pilar1').data('easyPieChart').update(
                    Math.round(data.pilar1)
                )
                $('#chart_pilar2').data('easyPieChart').update(
                    Math.round(data.pilar2)
                )
                $('#chart_pilar3').data('easyPieChart').update(
                    Math.round(data.pilar3)
                )
                $('#chart_pilar4').data('easyPieChart').update(
                    Math.round(data.pilar4)
                )
            }                       
        });
    }

    function initchartrealisasi(){
        $.ajax({
            url: urlchartrealisasi,
            data: {
                'perusahaan_id' : $("#perusahaan_id").val(),
                'tahun' : $("#tahun").val()
            },
            type: "POST",
            dataType: "json", 
            success: function(data){
                $('#chart_pilar1').attr('data-percent', data.pilar1);
                $('#chart_pilar2').attr('data-percent', data.pilar2);
                $('#chart_pilar3').attr('data-percent', data.pilar3);
                $('#chart_pilar4').attr('data-percent', data.pilar4);
                
                $('#chart_pilar1').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#f44265',
                    trackColor: '#ffd8e6',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function(from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });

                $('#chart_pilar2').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#32a852',
                    trackColor: '#ccf0d5',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function(from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });
                
                $('#chart_pilar3').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#2c79c7',
                    trackColor: '#d1e8ff',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function(from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });
                
                $('#chart_pilar4').easyPieChart({
                    size: 150,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#e38a2b',
                    trackColor: '#ffdfbd',
                    scaleColor: false,
                    lineWidth: 50,
                    trackWidth: 40,
                    lineCap: 'butt',
                    onStep: function(from, to, percent) {
                        $(this.el).find('.percent').text(Math.round(percent));
                    }
                });
            }                       
        });
    }
</script>
<script src="{{ asset('plugins/easy-pie-chart/jquery.easypiechart.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('plugins/easy-pie-chart/jquery.easing.min.js') }}" type="text/javascript"></script>
@endsection

