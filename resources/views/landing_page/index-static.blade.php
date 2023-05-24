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
        @media only screen and (max-width: 600px) {
            #discount-product{
                margin-top: 100px;
            }
            header{
                box-shadow: rgba(0, 0, 0, 0.2) 0px 12px 28px 0px, rgba(0, 0, 0, 0.1) 0px 2px 4px 0px, rgba(255, 255, 255, 0.05) 0px 0px 0px 1px inset;
            }
        }
        @media only screen and (max-width: 864px) {
            #discount-product{
                margin-top: 150px;
            }
            header{
                box-shadow: rgba(0, 0, 0, 0.2) 0px 12px 28px 0px, rgba(0, 0, 0, 0.1) 0px 2px 4px 0px, rgba(255, 255, 255, 0.05) 0px 0px 0px 1px inset;
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
                        {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="bar-icon"></span>
                            <span class="bar-icon"></span>
                            <span class="bar-icon"></span>
                        </button> --}}

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul id="nav" class="navbar-nav ml-auto">
                               <!--  <li class="nav-item active">
                                    <a data-scroll-nav="0" href="#home">Home</a>
                                </li> -->
                           
                            </ul> <!-- navbar nav -->
                        </div>
                    </nav> <!-- navbar -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    
    <!--====== HEADER PART ENDS ======-->

     <!--====== PORTAL PRODUCT PART START ======-->
    
    <section id="discount-product" class="discount-product pt-150 bghead-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h6 class="text-center">Budget & Report :</h6>
                      <p class="text-center">Untuk mengelola Anggaran, Program TJSL, dan melaporkan Kegiatan (yang dilakukan oleh unit kerja selain unit TJSL BUMN) serta pelaporan terkait Pendanaan UMK</p>
                </div>
                <div class="col-lg-6">
                    <h6 class="text-center ">Proses & Tahapan Program : </h6>
                      <p class="text-center">Untuk Input data proposal/Program, Survei, Pilar SDGs, Monitoring, Realisasi</p>
                </div>
            </div>
            <div class="row">                 
                <div class="col-lg-6">
                    <div class="single-portal mt-30">
                        <a onclick='window.location.href = "{{ route('dashboard.index') }}"' style="cursor:pointer;" >
                            <div class="portal-image">
                                <img src="{{ asset('assets/landing_page/images/portal/product-1.jpg')}}" alt="Product" style="border-radius: 20px;">
                            </div> <!--  image -->
                            <div class="portal-content">
                                <h4 class="content-title mb-15 wcolor">Budget & <br>Report</h4>
                                {{-- <a href="#">Kunjungi Situs <i class="lni-chevron-right"></i></a> --}}
                            </div> <!--  content -->
                        </a>
                    </div> <!-- single  -->
                </div>
                <div class="col-lg-6">
                    <div class="single-portal mt-30">
                        <a onclick='window.location.href = "http://aplikasitjsl.bumn.go.id"' style="cursor:pointer;" >
                        <div class="portal-image">
                            <img src="{{ asset('assets/landing_page/images/portal/product-2.jpg')}}" alt="Product" style="border-radius: 20px;">
                        </div> <!--  image -->
                        <div class="portal-content">
                            <h4 class="content-title mb-15 wcolor">Proses & <br>Tahapan Program</h4>
                            {{-- <a href="#">Kunjungi Situs <i class="lni-chevron-right"></i></a> --}}
                        </div> <!--  content -->
                        </a>
                    </div> <!-- single  product -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </section>
    
    <!--====== PORTAL PART ENDS ======-->
   
    <!--====== SLIDER PART START ======-->
    
    <section id="home" class="slider-area pt-50 pb-50">
        <div class="container-fluid position-relative">
            <div class="slider-active">
                <div class="single-slider">
                    <div class="slider-bg">
                        <div class="row no-gutters align-items-center ">
                            <div class="col-lg-4 col-md-5">
                                <div class="slider-product-image d-none d-md-block">
                                    <img src="{{ asset('assets/landing_page/images/slider/1.jpg')}}" alt="Slider">
                                    <div class="slider-discount-tag">
                                        <p>Tujuan</p>
                                    </div>
                                </div> <!-- slider product image -->
                            </div>
                            <div class="col-lg-8 col-md-7">
                                <div class="slider-product-content">
                                    <h5 class="slider-title mb-10" data-animation="fadeInUp" data-delay="0.3s"><span>Komitmen </span> Bersama <span>Lingkungan</span></h5>
                                    <p class="mb-25" data-animation="fadeInUp" data-delay="0.9s">
                                        Memberikan kemanfaatan bagi pembangunan perusahaan <br>Berkontribusi pada penciptaan nilai tambah<br>Membina usaha mikro dan usaha kecil agar lebih tangguh dan mandiri serta masyarakat sekitar perusahaan.</p>
                                </div> <!-- slider product content -->
                            </div>
                        </div> <!-- row -->
                    </div> <!-- container -->
                </div> <!-- single slider -->

                <div class="single-slider">
                        <div class="slider-bg">
                            <div class="row no-gutters align-items-center">
                                <div class="col-lg-4 col-md-5">
                                    <div class="slider-product-image d-none d-md-block">
                                        <img src="{{ asset('assets/landing_page/images/slider/3.jpg')}}" alt="Slider">
                                        <div class="slider-discount-tag">
                                            <p>Orientasi</p>
                                        </div>
                                    </div> <!-- slider product image -->
                                </div>
                                <div class="col-lg-8 col-md-7">
                                    <div class="slider-product-content">
                                        <h1 class="slider-title mb-10" data-animation="fadeInUp" data-delay="0.3s"><span>Empat</span><br> Pilar <span>Dasar</span></h1>
                                        <p class="mb-25" data-animation="fadeInUp" data-delay="0.9s">One day however a small line of blind text by the name of Lorem Ipsum <br> decided to leave for the far World of Grammar.</p>
                                    </div> <!-- slider product content -->
                                </div>
                            </div> <!-- row -->
                        </div> <!-- container -->
                </div> <!-- single slider -->

                <div class="single-slider">
                        <div class="slider-bg">
                            <div class="row no-gutters align-items-center">
                                <div class="col-lg-4 col-md-5">
                                    <div class="slider-product-image d-none d-md-block">
                                        <img src="{{ asset('assets/landing_page/images/slider/2.jpg')}}" alt="Slider">
                                        <div class="slider-discount-tag">
                                            <p>Pengelolaan</p>
                                        </div>
                                    </div> <!-- slider product image -->
                                </div>
                                <div class="col-lg-8 col-md-7">
                                    <div class="slider-product-content">
                                        <h1 class="slider-title mb-10" data-animation="fadeInUp" data-delay="0.3s"><span>Pengelolaan</span> Program <br><span>TJSL</span> BUMN</h1>
                                        <p class="mb-25" data-animation="fadeInUp" data-delay="0.9s">Program TJSL BUMN dilakukan secara Sistematis dan Terpadu untuk menjamin pelaksanaan dan pencapaian keberhasilan Program TJSL BUMN sesuai dengan prioritas dan/atau pencapaian dari tujuan Program TJSL BUMN yang berpedoman pada rencana kerja.</p>
                                    </div> <!-- slider product content -->
                                </div>
                            </div> <!-- row -->
                        </div> <!-- container -->
                </div> <!-- single slider -->
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
                        <div class="blog_item">
                            <div class="blog_image">
                                <img class="img-fluid" src="{{ asset('assets/landing_page/images/news/1.jpeg')}}" alt="images not found">
                            </div>
                            <div class="blog_details">
                                <div class="blog_title">
                                    <h5><a href="#">Pertamina Bantu Alat Permainan Edukatif.</a></h5>
                                </div>
                                <ul>
                                    <li><i class="icon ion-md-person"></i>Published</li>
                                    <li><i class="icon ion-md-calendar"></i></li>
                                </ul>
                                <p class="cblue">Serah terima bantuan alat permainan edukatif dari pertamina kepada PAUD di Balikpapan.</p>
                                <!-- <a href="#">Read More<i class="icofont-long-arrow-right"></i></a> -->
                            </div>
                        </div>                        
                        <div class="blog_item">
                            <div class="blog_image">
                                <img class="img-fluid" src="{{ asset('assets/landing_page/images/news/2.png')}}" alt="images not found">
                            </div>
                            <div class="blog_details">
                                <div class="blog_title">
                                    <h5><a href="#">“Klaster Usaha Bunga Krisan Tomohon”, BRI Bantu Petani Bunga.</a></h5>
                                </div>
                                <ul>
                                    <li><i class="icon ion-md-person"></i>Published</li>
                                    <li><i class="icon ion-md-calendar"></i></li>
                                </ul>
                                <p class="cblue">Menteri BUMN meninjau Klaster Tanaman Hias binaan BRI.</p>
                                <!-- <a href="#">Read More<i class="icofont-long-arrow-right"></i></a> -->
                            </div>
                        </div>                        
                        <div class="blog_item">
                            <div class="blog_image">
                                <img class="img-fluid" src="{{ asset('assets/landing_page/images/news/3.jpeg')}}" alt="images not found">
                            </div>
                            <div class="blog_details">
                                <div class="blog_title">
                                    <h5><a href="#">SUCOFINDO berikan literasi digital marketing untuk mitra binaan - UMKM.</a></h5>
                                </div>
                                <ul>
                                    <li><i class="icon ion-md-person"></i>Published</li>
                                    <li><i class="icon ion-md-calendar"></i></li>
                                </ul>
                                <p class="cblue">PT SUCOFINDO (Persero) melalui unit Tanggung Jawab Sosial dan Lingkungan (TJSL) mengadakan pelatihan Digital Marketing untuk Usaha Mikro Kecil dan Menengah.</p>
                                <!-- <a href="#">Read More<i class="icofont-long-arrow-right"></i></a> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
 

    <!--====== FOOTER PART START ======-->
    
    <section id="footer" class="footer-area">
        <div class="container">
            <div class="footer-widget pt-25 pb-50">
                <div class="row">
                    <div class="col-lg-3 col-md-5 col-sm-7">
                        <div class="footer-logo mt-40">
                            <a href="#">
                                <img src="{{ asset('assets/landing_page/images/logo-foot.png')}}" alt="Logo">
                            </a>
                        </div> <!-- footer logo -->
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-5">
                        <div class="footer-link mt-50">
                            {{-- <h5 class="f-title wcolor">Situs Penting</h5>
                            <ul>
                                <li><a href="#">Web KBUMN</a></li>
                                <li><a href="#">TJSL Program</a></li>
                            </ul> --}}
                        </div> <!-- footer link -->
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-7">
                        <div class="footer-info mt-50">
                            <h5 class="f-title wcolor">Kontak Kami</h5>
                            <p class="mt-15 text_foot">Kementerian Badan Usaha Milik negara Jalan Medan Merdeka Selatan No. 13 Jakarta Pusat, Indonesia.</p>
                            <ul>
                                <li>
                                    <div class="single-footer-info mt-20">
                                        <span class="text_foot">Phone :</span>
                                        <div class="footer-info-content text_foot">
                                            <p class="text_foot">(021) 2993 5678</p>
                                        </div>
                                    </div> <!-- single footer info -->
                                </li>
                                <li>
                                    <div class="single-footer-info mt-20">
                                        <span class="text_foot">Email</span>
                                        <div class="footer-info-content">
                                            <p class="text_foot">tjsl.kbumn@bumn.go.id</p>
                                        </div>
                                    </div> <!-- single footer info -->
                                </li>
                            </ul>
                        </div> <!-- footer link -->
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
