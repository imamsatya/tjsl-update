<!doctype html>
<html lang="en">

<head>
   
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!--====== Title ======-->
    <title>Portal - TJSL KBUMN</title>
    <style type="text/css">
        .wcolor{
            color: white;
        }
        .text_foot{
            color: #eaf1ff;
        }
        .w25{
            width: 26%;
        }
        .cblue{
            color: #c4c4c4;
        }
        #discount-product{
            margin-top: 50px;
        }
        header{
            box-shadow: rgba(0, 0, 0, 0.2) 0px 12px 28px 0px, rgba(0, 0, 0, 0.1) 0px 2px 4px 0px, rgba(255, 255, 255, 0.05) 0px 0px 0px 1px inset;
            }
        .title-info-mobile{
            display: none;
        }
        @media only screen and (max-width: 600px) {
            #discount-product{
                margin-top: 100px;
            }
            header{
                box-shadow: rgba(0, 0, 0, 0.2) 0px 12px 28px 0px, rgba(0, 0, 0, 0.1) 0px 2px 4px 0px, rgba(255, 255, 255, 0.05) 0px 0px 0px 1px inset;
            }

            .title-info-mobile{
                display: block;
            }
            #title-info-web{
                display: none;
            }
        }
        @media only screen and (max-width: 864px) {
            #discount-product{
                margin-top: 150px;
            }
            header{
                box-shadow: rgba(0, 0, 0, 0.2) 0px 12px 28px 0px, rgba(0, 0, 0, 0.1) 0px 2px 4px 0px, rgba(255, 255, 255, 0.05) 0px 0px 0px 1px inset;
            }
            .title-info-mobile{
                display: block;
            }
            #title-info-web{
                display: none;
            }
        }
    </style>
    
    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{ asset('assets/landing_page/images/favicon.png')}}" type="image/png">

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/bootstrap.min.css')}}">
    
    <!--====== Animate css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/animate.css')}}">
    
    <!--====== Magnific Popup css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/magnific-popup.css')}}">
    
    <!--====== Slick css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/slick.css')}}">
    
    <!--====== Line Icons css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/LineIcons.css')}}">
    
    <!--====== Default css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/default.css')}}">
    
    <!--====== Style css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/style.css')}}">
    
    <!--====== Responsive css ======-->
    <link rel="stylesheet" href="{{ asset('assets/landing_page/css/responsive.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/landing_page/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    
</head>

<body>
    <!--====== HEADER PART START ======-->
    <header class="header-area" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="https://bumn.go.id" target="_blank">
                            <img src="{{ asset('assets/landing_page/images/logo1.png')}}" alt="Logo" width="200px;">
                        </a> <!-- Logo -->
                    </nav> <!-- navbar -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    
    <!--====== HEADER PART ENDS ======-->

     <!--====== PORTAL PRODUCT PART START ======-->
    
    <section id="discount-product" class="discount-product pt-150 bghead-area">
        <div class="container">
            <div class="row" id="title-info-web">
                @foreach($data['info_portal'] as $val)
                <div class="col-lg-6">
                    <h6 class="text-center">{{ $val['title'] }} :</h6>
                      <p class="text-center">{{ $val['description'] }}</p>
                </div>
                @endforeach
            </div>
            <div class="row"> 
                @foreach($data['info_portal'] as $val)                
                <div class="col-lg-6">
                    <div class="title-info-mobile mt-20">
                        <h6 class="text-center">{{ $val['title'] }} :</h6>
                        <p class="text-center">{{ $val['description'] }}</p>
                    </div>
                    <div class="single-portal mt-30">
                        <a onclick='window.location.href = "{{$val['link']}}"' style="cursor:pointer;">
                            <div class="portal-image">
                                <img src="{{ $publik_host.'storage/'.$val['button_image_path'] }}" alt="Product" style="border-radius: 20px;">
                            </div> <!--  image -->
                            <div class="portal-content">
                                <h4 class="content-title mb-15 wcolor">{{ $val['title'] }} </h4>
                            </div> <!--  content -->
                        </a>
                    </div> <!-- single  -->
                </div>
                @endforeach
            </div> <!-- row -->
        </div> <!-- container -->
    </section>
    
    <!--====== PORTAL PART ENDS ======-->
   
    <!--====== SLIDER PART START ======-->
    
    <section id="home" class="slider-area pt-50 pb-50">
        <div class="container-fluid position-relative">
            <div class="slider-active">
                @foreach($data['slider'] as $val)
                <div class="single-slider">
                    <div class="slider-bg">
                        <div class="row no-gutters align-items-center ">
                            <div class="col-lg-4 col-md-5">
                                <div class="slider-product-image d-none d-md-block">
                                    <img src="{{ $publik_host.'storage/'.$val['image_path'] }}" alt="Slider">
                                    <div class="slider-discount-tag">
                                        <p>{{ $val['title_point']?$val['title_point'] : '' }}</p>
                                    </div>
                                </div> <!-- slider product image -->
                            </div>
                            <div class="col-lg-8 col-md-7">
                                <div class="slider-product-content">
                                    <h5 class="slider-title mb-8" data-animation="fadeInUp" data-delay="0.3s" style="font-size: 60px;">
                                        {{ $val['title']?$val['title'] : '' }}
                                    </h5>
                                    <p class="mb-25" data-animation="fadeInUp" data-delay="0.9s">
                                        {{ $val['description']?$val['description'] : '' }}
                                    </p>
                                </div> <!-- slider product content -->
                            </div>
                        </div> <!-- row -->
                    </div> <!-- container -->
                </div> <!-- single slider -->
                @endforeach
            </div> <!-- slider active -->
            
        </div> <!-- container fluid -->
    </section>
    
    <!--====== SLIDER PART ENDS ======-->

   

    <!--====== SLIDER 2 PART START ======-->
    <section class="blog_section services-area">
            <div class="container">
                <div class="row">
                <div class="col-lg-6">
                    <div class="section-title">
                        <h5 class="mb-20">Informasi TJSL</h5>
                        
                    </div> <!-- section title -->
                </div>
            </div>
                <div class="blog_content">
                    <div class="owl-carousel owl-theme">
                        @foreach($data['info'] as $val)
                        <div class="blog_item">
                            <div class="blog_image">
                                <img class="img-fluid" src="{{ $publik_host.'storage/'.$val['image_path'] }}" alt="images not found">
                            </div>
                            <div class="blog_details">
                                <div class="blog_title">
                                    <h5><a href="#">{{ $val['title'] }}</a></h5>
                                </div>
                                <ul>
                                    <li><i class="icon ion-md-person"></i>{{ $val['status'] }}</li>
                                    <li><i class="icon ion-md-calendar"></i>{{ date('d F Y', strtotime($val['created_at'])) }}</li>
                                </ul>
                                <p class="cblue">{{ $val['sub_title'] }}</p>
                                <!-- <a href="#">Read More<i class="icofont-long-arrow-right"></i></a> -->
                            </div>
                        </div>                        
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
 

    <!--====== FOOTER PART START ======-->  
    <section id="footer" class="footer-area">
        <div class="container">
            <div class="footer-widget pt-25 pb-50">
                <div class="row">
                    <div class="col-lg-5 col-md-9 col-sm-10">
                        <div class="footer-logo mt-40">
                            <a href="#">
                                <img src="{{ asset('assets/landing_page/images/logo-foot.png')}}" alt="Logo">
                            </a>
                        </div> <!-- footer logo -->
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                        <div class="footer-link mt-50">
                        @if(count($data['socmed']))
                            <h5 class="f-title" style="color: transparent;">Sosial Media</h5>
                            <ul>
                                @foreach($data['socmed'] as $val)
                                <li>
                                    <i class="lni lni-{{$val['title']}} wcolor" ></i>
                                    <a href="{{ $val['link']}}">&nbsp;&nbsp;{{ ucwords($val['title'])}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div> <!-- footer link -->
                        @endif
                    </div>

                    <div class="col-lg-4 col-md-5 col-sm-7">
                        <div class="footer-info mt-50">
                        @if(count($data['contact']) > 0)
                            <h5 class="f-title wcolor mb-3">Kontak Kami</h5>
                            <ul class="contact">
                                <li>
                                    <span class="lni lni-map-marker"></span> 
                                    @php
                                    foreach($data['contact'] as $k=>$v){
                                        if($v['title'] == "alamat"){
                                            echo '<a class="wcolor">'.$v['detail'].'</a><br>';                                        
                                        }
                                    }
                                    @endphp
                                </li>
                                <li>
                                    <span class="lni lni-phone"></span>
                                    @php
                                    foreach($data['contact'] as $k=>$v){
                                        if($v['title'] == "telepon"){
                                            echo '<a class="text_foot wcolor">'.$v['detail'].'</a><br>';                                        
                                        }
                                    }
                                    @endphp   
                                </li>
                                <li>
                                    <span class="lni lni-envelope"></span>
                                    @php
                                    foreach($data['contact'] as $k=>$v){
                                        if($v['title'] == "email"){
                                            echo '<a class="text_foot wcolor">'.$v['detail'].'</a><br>';                                        
                                        }
                                    }
                                    @endphp 
                                </li>
                            </ul>
                        @endif
                        </div>
                    </div>

                    
                </div> <!-- row -->
            </div> <!-- footer widget -->
            <div class="footer-copyright pt-15 pb-15">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright text-center">
                            <p>@2022 Kementrian Badan Usaha Milik Negara Republik Indonesia</p>
                        </div> <!-- copyright -->
                    </div>
                </div> <!-- row -->
            </div> <!--  footer copyright -->
        </div> <!-- container -->
    </section>
    
    <!--====== FOOTER PART ENDS ======-->
    
    <!--====== BACK TO TOP PART START ======-->
    
    <a href="#" class="back-to-top"><i class="lni-chevron-up"></i></a>
    
    <!--====== BACK TO TOP PART ENDS ======-->

    
    
    <!--====== jquery js ======-->
    <script src="{{ asset('assets/landing_page/js/vendor/modernizr-3.6.0.min.js')}}"></script>
    <script src="{{ asset('assets/landing_page/js/vendor/jquery-1.12.4.min.js')}}"></script>

    <script src="{{ asset('assets/landing_page/js/popper.min.js')}}"></script>
    <!-- carousel -->
    <script type="text/javascript" src="{{ asset('assets/landing_page/js/owl.carousel.js')}}"></script>
    <script type="text/javascript">
            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                dots:false,
                nav:true,
                autoplay:true,   
                smartSpeed: 3000, 
                autoplayTimeout:7000,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:3
                    }
                }
            })
        </script>

    <!--====== Bootstrap js ======-->
    <script src="{{ asset('assets/landing_page/js/bootstrap.min.js')}}"></script>
    
    <!--====== Slick js ======-->
    <script src="{{ asset('assets/landing_page/js/slick.min.js')}}"></script>
    
    <!--====== Magnific Popup js ======-->
    <script src="{{ asset('assets/landing_page/js/jquery.magnific-popup.min.js')}}"></script>

    <!--====== nav js ======-->
    <script src="{{ asset('assets/landing_page/js/jquery.nav.js')}}"></script>
    
    <!--====== Nice Number js ======-->
    <script src="{{ asset('assets/landing_page/js/jquery.nice-number.min.js')}}"></script>
    
    <!--====== Main js ======-->
    <script src="{{ asset('assets/landing_page/js/main.js')}}"></script>

</body>

</html>
