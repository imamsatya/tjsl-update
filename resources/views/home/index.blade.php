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

    .chart2 {
        position: relative;
        display: inline-block;
        width: 150px;
        height: 150px;
        margin-top: 20px;
        margin-bottom: 10px;
        text-align: center;
        font-size: 16pt;
    }

    .chart2 canvas {
        position: absolute;
        top: 0;
        left: 0;
    }

    .percent2 {
        display: inline-block;
        line-height: 150px;
        z-index: 2;
        margin-left:60px;
        padding-top:20px;
    }

    .percent2:after {
        content: '%';
        margin-left: 0.1em;
        font-size: 16pt;
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
                        <div class="col-lg-5">
                            <label>TPB</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : '');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="tpb_id" name="tpb_id" data-kt-select2="true" data-placeholder="Pilih TPB" {{ $disabled }}>
                                <option></option>
                                <option value="all">Semua TPB</option>
                                @foreach($tpb as $p)  
                                    @php
                                        $select = (($p->id == $tpb_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->no_tpb }} - {{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : '');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
                                <option></option>
                                <option value="all">Semua BUMN</option>
                                @foreach($perusahaan as $p)  
                                    @php
                                        $select = (($p->id == $perusahaan_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="tahun" name="tahun" data-kt-select2="true" >
                                @php for($i = date("Y")+1; $i>=2020; $i--){ @endphp
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
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan <br>Sosial</span><br>
                            <span id="chart_detail1" style="font-size:12px;"></span>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar2" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan<br> Ekonomi</span><br>
                            <span id="chart_detail2" style="font-size:12px;"></span>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar3" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan<br> Lingkungan</span><br>
                            <span id="chart_detail3" style="font-size:12px;"></span>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_pilar4" class="chart" data-percent="0">
                                <span class="percent"></span>
                            </span><br>
                            <span class="caption-subject font-grey-gallery" style="font-weight:bold;">Pilar Pembangunan <br>Hukum dan Tata Kelola<br></span>
                            <span id="chart_detail4" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="form-group row mb-5">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 center" style="text-align: center;">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 center" style="text-align: center;">
                            <span id="chart_tpb" class="chart2" data-percent="0" style="margin-left: -50px; margin-bottom:60px;">
                                <span class="percent2"></span>
                            </span><br>
                            <span id="chart_title" class="caption-subject font-grey-gallery" style="font-weight:bold;">All TPB</span><br>
                            <span id="chart_detail" style="font-size:12px;"></span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
    </div>
</div>



<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <!--begin::Container-->
    <div id="kt_content_container" class="container">
        <!--begin::Card-->
        <div class="card">

            <!--begin::Card header-->
            <div class="card-header pt-5">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2 class="d-flex align-items-center">Realisasi PUMK
                    <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">

                        <button type="button" class="btn btn-active btn-outline-info btn-sm btn-search cls-search"  data-toggle="tooltip" title="Cari Data" style="font-size: 16px;"><i class="bi bi-search fs-3"></i>Cari</button> &nbsp

                    </div>
                    <!--end::Search-->
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Heading-->
                <div class="card-px py-10" >
                  <div class="row" id="form-cari">
                    <div class="form-group row  mb-5" >
                        <div class="col-lg-6">
                            <label>BUMN</label>
                            @php
                                $disabled = (($admin_bumn) ? 'disabled="true"' : 'data-allow-clear="true"');
                            @endphp
                            <select class="form-select form-select-solid form-select2" id="perusahaan_id_pumk" name="perusahaan_id" data-kt-select2="true" data-placeholder="Pilih BUMN" {{ $disabled }}>
                                <option></option>
                                @foreach($perusahaan as $bumn)  
                                    @php
                                        $select = (($bumn->id == $filter_bumn_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $bumn->id }}" {!! $select !!}>{{ $bumn->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Bulan</label>
                            <select id="bulan_id_pumk" class="form-select form-select-solid form-select2" name="bulan_id_pumk" data-kt-select2="true" data-placeholder="Pilih Bulan" data-allow-clear="true">
                                <option></option>
                                @foreach($bulan as $p)  
                                    @php
                                        $select = (($p->id == $filter_status_id) ? 'selected="selected"' : '');
                                    @endphp
                                    <option value="{{ $p->id }}" {!! $select !!}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label>Tahun</label>
                            <select class="form-select form-select-solid form-select2" id="tahun_pumk" name="tahun_pumk" data-kt-select2="true" data-placeholder="Pilih Tahun" data-allow-clear="true">
                                @php
                                    for($i = date("Y"); $i>=2020; $i--){ @endphp
                                    <option value="{{$i}}">{{$i}}</option>
                                    @php }
                                    $select = (($i == date("Y")) ? 'selected="selected"' : '');
                                @endphp
                                <option></option>
                            </select>
                        </div>
                    </div>

                    {{-- <div class="form-group row  mb-5">
                        <div class="col-lg-6">
                            <button id="proses" class="btn-small btn-success me-3 text-white"><i class="fa fa-search text-white"></i> Filter</button>
                            <button  onclick="window.location.href='{{route('dashboard.index')}}'" class="btn-small btn-danger me-3 text-white"><i class="fa fa-times text-white"></i> Batal</button>
                        </div>
                    </div> --}}
                    <div class="separator border-gray-200 mb-10"></div>
                </div>
                    <!--begin: Datatable -->
                    <div>
                        <div class="portlet-body" id="mb_chart">
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
 <script src="{{ asset('plugins/Highcharts-9.2.2/code/highcharts.js') }}"></script>
 <script src="{{ asset('plugins/Highcharts-9.2.2/code/highcharts-3d.js') }}"></script>
 <script src="{{ asset('plugins/Highcharts-9.2.2/code/modules/exporting.js') }}"></script>
 <script src="{{ asset('plugins/Highcharts-9.2.2/code/modules/accessibility.js') }}"></script>
 <script src="{{ asset('plugins/Highcharts-9.2.2/code/modules/export-data.js') }}"></script>

<script>
    var urlchartrealisasi = "{{route('home.chartrealisasi')}}";
    var urlcharttpb = "{{route('home.charttpb')}}";
    var urlchartmb = "{{route('home.chartmb')}}";

    $(document).ready(function(){
        $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");
        
        $('#perusahaan_id').on('change', function(event){
            updatechartrealisasi();
        });
        $('#tahun').on('change', function(event){
            updatechartrealisasi();
        });
        $('#tpb_id').on('change', function(event){
            updatecharttpb();
        });

        initchartrealisasi();
        initcharttpb();

        //pumk
        $('#perusahaan_id_pumk').on('change', function(event){
            updatechartmb();
        });
        $('#tahun_pumk').on('change', function(event){
            updatechartmb();
        });
        $('#bulan_id_pumk').on('change', function(event){
            updatechartmb();
        });

        updatechartmb();
        
        $('#form-cari').hide();
        $('body').on('click','.btn-search',function(){
            $('#form-cari').toggle(600);
        });


    });

    function updatechartmb(){
        $.ajax({
            url: urlchartmb,
            data: {
                'perusahaan_id_pumk' : $("#perusahaan_id_pumk").val(),
                'tahun_pumk' : $("#tahun_pumk").val(),
                'bulan_pumk' : $("#bulan_id_pumk").val()
            },
            type: "POST",
            dataType: "json", 
            success: function(data){
                initmitra(data);
            }                       
        });
    }

    function initmitra(data) {
        alert(data.saldo_macet);
        let s_lancar = data.saldo_lancar? parseInt(data.saldo_lancar) : 0;
        let s_kurang_lancar = data.saldo_kurang_lancar? parseInt(data.saldo_kurang_lancar) : 0;
        let s_diragukan = data.saldo_diragukan? parseInt(data.saldo_diragukan) : 0;
        let s_macet = data.saldo_macet? parseInt(data.saldo_macet) : 0;
     
        Highcharts.setOptions({
            colors: ['#E67E22','#6495ED']
        });
        Highcharts.chart('mb_chart', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Kualitas Piutang'+ data.bumn + ' ' + data.bulan + ' ' + data.tahun
            },
            subtitle: {
                text: ''
            },
            xAxis: [{
                categories: ["Lancar","Kurang Lancar","Diragukan","Macet"],
                crosshair: true
            }],
            yAxis: [{
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Mitra Binaan',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                }
            }, { 
                title: {
                    text: 'Saldo Pinjaman (Rp)',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                labels: {
                    formatter: function(){
                        return this.value.toLocaleString("fi-FI");
                    },
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                x: 0,
                verticalAlign: 'top',
                y: 15,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || 
                    'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Saldo Pinjaman (Rp)',
                type: 'spline',
                yAxis: 1,
                data: [
                        s_lancar,
                        s_kurang_lancar,
                        s_diragukan,
                        s_macet
                       ],
                tooltip: {
                    valueSuffix: '{value}'

                },
                style: {
                        color: Highcharts.getOptions().colors[0]
                },

            }, {
                name: 'Mitra Binaan',
                type: 'column',
                data: [
                        data.mitra_lancar, 
                        data.mitra_kurang_lancar, 
                        data.mitra_diragukan, 
                        data.mitra_macet
                      ],
                tooltip: {
                    valueSuffix: ''
                },
                style: {
                        color: Highcharts.getOptions().colors[1]
                }
            }]
        });
    }

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
                var detail1 = "<i>Target :</i> Rp. "+data.target1+"<br><i>Realisasi :</i> Rp. "+data.realisasi1+"<br><i>Sisa :</i> Rp. "+data.sisa1;
                var detail2 = "<i>Target :</i> Rp. "+data.target2+"<br><i>Realisasi :</i> Rp. "+data.realisasi2+"<br><i>Sisa :</i> Rp. "+data.sisa2;
                var detail3 = "<i>Target :</i> Rp. "+data.target3+"<br><i>Realisasi :</i> Rp. "+data.realisasi3+"<br><i>Sisa :</i> Rp. "+data.sisa3;
                var detail4 = "<i>Target :</i> Rp. "+data.target4+"<br><i>Realisasi :</i> Rp. "+data.realisasi4+"<br><i>Sisa :</i> Rp. "+data.sisa4;
                $('#chart_detail1').html(detail1);
                $('#chart_detail2').html(detail2);
                $('#chart_detail3').html(detail3);
                $('#chart_detail4').html(detail4);

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

                var detail1 = "<i>Target :</i> Rp. "+data.target1+"<br><i>Realisasi :</i> Rp. "+data.realisasi1+"<br><i>Sisa :</i> Rp. "+data.sisa1;
                var detail2 = "<i>Target :</i> Rp. "+data.target2+"<br><i>Realisasi :</i> Rp. "+data.realisasi2+"<br><i>Sisa :</i> Rp. "+data.sisa2;
                var detail3 = "<i>Target :</i> Rp. "+data.target3+"<br><i>Realisasi :</i> Rp. "+data.realisasi3+"<br><i>Sisa :</i> Rp. "+data.sisa3;
                var detail4 = "<i>Target :</i> Rp. "+data.target4+"<br><i>Realisasi :</i> Rp. "+data.realisasi4+"<br><i>Sisa :</i> Rp. "+data.sisa4;
                $('#chart_detail1').html(detail1);
                $('#chart_detail2').html(detail2);
                $('#chart_detail3').html(detail3);
                $('#chart_detail4').html(detail4);

            }                       
        });
    }

    function initcharttpb(){
        $.ajax({
            url: urlcharttpb,
            data: {
                'tpb_id' : $("#tpb_id").val(),
                'perusahaan_id' : $("#perusahaan_id").val(),
                'tahun' : $("#tahun").val()
            },
            type: "POST",
            dataType: "json", 
            success: function(data){
                var detail = "<i>Target :</i> Rp. "+data.target+"<br><i>Realisasi :</i> Rp. "+data.realisasi+"<br><i>Sisa :</i> Rp. "+data.sisa;
                $('#chart_detail').html(detail);
                $('#chart_tpb').attr('data-percent', data.tpb);
                
                $('#chart_tpb').easyPieChart({
                    size: 200,
                    easing: 'easeOutBounce',
                    delay: 5000,
                    barColor: '#b42ded',
                    trackColor: '#f2d9fc',
                    scaleColor: false,
                    lineWidth: 60,
                    trackWidth: 50,
                    lineCap: 'butt',
                    onStep: function(from, to, percent) {
                        $(this.el).find('.percent2').text(Math.round(percent));
                    }
                });
            }                       
        });
    }
    
    function updatecharttpb(){
        $.ajax({
            url: urlcharttpb,
            data: {
                'tpb_id' : $("#tpb_id").val(),
                'perusahaan_id' : $("#perusahaan_id").val(),
                'tahun' : $("#tahun").val()
            },
            type: "POST",
            dataType: "json", 
            success: function(data){
                var detail = "<i>Target :</i> Rp. "+data.target+"<br><i>Realisasi :</i> Rp. "+data.realisasi+"<br><i>Sisa :</i> Rp. "+data.sisa;
                $('#chart_detail').html(detail);
                $('#chart_tpb').data('easyPieChart').update(
                    Math.round(data.tpb)
                )
                var tpb = $("#tpb_id option:selected").text();
                $('#chart_title').html(tpb);
            }                       
        });
    }
</script>
<script src="{{ asset('plugins/easy-pie-chart/jquery.easypiechart.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('plugins/easy-pie-chart/jquery.easing.min.js') }}" type="text/javascript"></script>
@endsection

