<!doctype html>
<html lang="en">
<head>
    <title>{{$page_title}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link href="https://fonts.googleapis.com/css?family=Work+Sans:400,700,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a4a19fbdad.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">

    <link rel="stylesheet" href="assets/css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="assets/fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="assets/fonts/icomoon/style.css">

    <link rel="stylesheet" href="assets/css/aos.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/slick-theme.css">
    <link rel="stylesheet" href="assets/css/slick.css">

    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/owlcarousel/assets/owl.theme.default.min.css">

</head>
<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">





<div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>


    <header class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">

        <div class="container">
            <div class="row align-items-center">

                <div class="col-6 offset-lg-1 col-xl-2">
                    <h1 class="mb-0 site-logo"><a href="" class="mb-0"><img src="assets/ezfit/logo.png" alt="" class="img-fluid" style="max-height: 40px;"></a></h1>
                </div>

                <div class="col-12 col-md-8 d-none d-xl-block">
                    <nav class="site-navigation position-relative text-right" role="navigation">

                        <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                            <li><a href="#home-section" class="nav-link">HOME</a></li>
                            <li><a href="#about-section" class="nav-link">PROBLEM & SOLUTION</a></li>
                            <li><a href="#portfolio-section" class="nav-link">TESTIMONI</a></li>
                            <li><a href="#services-section" class="nav-link">CONTOH MENU</a></li>
                        </ul>
                    </nav>
                </div>


                <div class="col-6 d-inline-block d-xl-none ml-md-0 py-3" style="position: relative; top: 3px;"><a href="#" class="site-menu-toggle js-menu-toggle float-right"><span class="icon-menu h3"></span></a></div>

            </div>
        </div>

    </header>



    <div class="site-blocks-cover overlay" data-aos="fade" id="home-section">

        <div class="container">
            <div class="row align-items-center justify-content-center">


                <div class="col-md-8 text-center">
                    <h1 class="mb-3" data-aos="fade-up">Satu aplikasi untuk hidup sehat dan badan idealmu</h1>

                    <div data-aos="fade-up" data-aos-delay="100">
                        <p class="padding-left-right">Pangkas lemak di tubuhmu, wujudkan yang lebih sehat, dengan makanan lezat dari layanan katering diet sehat EZ Fit</p>
                        <p>
                            <a href="https://apps.apple.com/us/app/ez-fit-katering-diet-sehat/id1495288228"><img src="assets/ezfit/btn-app-store.png" alt="" class="img-fluid"></a>
                            <a href="https://play.google.com/store/apps/details?id=com.crocodic.ezfitapp"><img src="assets/ezfit/btn-play-store.png" alt="" class="img-fluid"></a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <a href="#about-section" class="mouse smoothscroll">
        <span class="mouse-icon">
          <span class="mouse-wheel"></span>
        </span>
        </a>
    </div>

    <div class="container-fluid">
        <!-- second section -->
        <div class="product-second-section">
            <div class="wrapper-apps"></div>
            <!-- apps -->
            <div class="simple-slider">
                <div class="slider feature-apps">
                    @foreach($slider_mobile as $row)
                        <div class="item">
                            <img src="{{asset($row->photo)}}">
                        </div>
                    @endforeach
                    @foreach($slider_mobile as $row)
                        <div class="item">
                            <img src="{{asset($row->photo)}}">
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- ./ apps -->
        </div>
        <!-- second section -->
    </div>

    <div class="site-section cta-big-image" id="about-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade">
                    <h2 class="section-title mb-3">PROBLEM AND SOLUTION</h2>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-5 mb-5" data-aos="fade-up" data-aos-delay="">
                    <img src="assets/ezfit/img-problem-1.png" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="pbs pbs-1 mb-4 mt-5">
                        <h3 class="h3 mb-4 text-black">Bingung cari makanan yang sehat?</h3>
                        <p>Kamu tidak perlu repot mempersiapkan makan apa, kami yang siapkan sesuai kebutuhan dengan layanan katering diet sehat sehat dari EZ Fit</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="pbs pbs-2 mb-4 mt-5">
                        <h3 class="h3 mb-4 text-black">Desperate bingung caranya menurunkan berat badan?</h3>
                        <p>Tidak usah bingung, konsultan kami siap membantu kamu mengatur pola makanmu.
                            Dan makanan kami terhitung kalorinya, cocok untuk kamu menurunkan berat badan!</p>
                    </div>
                </div>
                <div class="col-md-5 mb-5" data-aos="fade-up" data-aos-delay="">
                    <img src="assets/ezfit/img-problem-2.png" alt="Image" class="img-fluid">
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-5 mb-5" data-aos="fade-up" data-aos-delay="">
                    <img src="assets/ezfit/img-problem-3.png" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="pbs pbs-3 mb-4 mt-5">
                        <h3 class="h3 mb-4 text-black">Kapok diet karena makanan diet hambar?</h3>
                        <p>Eits, tidak dengan makanan di EZ Fit. Kamu akan mendapatkan makanan yang dimasak oleh
                            chef professional yang sudah bertahun - tahun menekuni healty food. Tiap hari menu beda!
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="pbs pbs-4 mb-4 mt-5">
                        <h3 class="h3 mb-4 text-black">Mau katering tapi jadwalmu tidak menentu?</h3>
                        <p>Jangan khawatir, kamu bisa menunda dan melanjutkan langganan kapanpun kamu mau, tidak
                            perlu khawatir keteringmu hangus!
                        </p>
                    </div>
                </div>
                <div class="col-md-5 mb-5" data-aos="fade-up" data-aos-delay="">
                    <img src="assets/ezfit/img-problem-4.png" alt="Image" class="img-fluid">
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-5 mb-5" data-aos="fade-up" data-aos-delay="">
                    <img src="assets/ezfit/img-problem-5.png" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="pbs pbs-5 mb-4 mt-5">
                        <h3 class="h3 mb-4 text-black">Kamu kerja mobile jadi alamat berpindah - pindah?</h3>
                        <p>Jangan khawatir, kamu bisa memasukan 2 alamat seperti kantor dan rumah. Lebih dari itupun bisa,
                            CS kami siap membantu!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="pbs pbs-6 mb-4 mt-5">
                        <h3 class="h3 mb-4 text-black">Kamu masih pelajar dan dana makan terbatas?</h3>
                        <p>Kami tau, dan kami ingin anak muda bisa mulai hidup sehat sejak dini.
                            Kamu bisa mendaftarkan dirimu sebagai akun pelajar dan dapatkan harga khusus pelajar!
                        </p>
                    </div>
                </div>
                <div class="col-md-5 mb-5" data-aos="fade-up" data-aos-delay="">
                    <img src="assets/ezfit/img-problem-6.png" alt="Image" class="img-fluid">
                </div>
            </div>
            <div class="row">
                <div class="offset-md-1 col-md-5 mb-5" data-aos="fade-up" data-aos-delay="">
                    <img src="assets/ezfit/img-problem-7.png" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="pbs pbs-7 mb-4 mt-5">
                        <h3 class="h3 mb-4 text-black">Mau pesan makanan online tapi berat di ongkir?</h3>
                        <p>Tenang, kami FREE ONGKIR! Kurir kami siap mengantarkan makanan anda tanpa dipungut biaya tambahan lagi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="site-section" id="portfolio-section">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12 text-center" data-aos="fade">
                    <h2 class="section-title mb-3">TESTIMONI</h2>
                </div>
            </div>

            <div class="row justify-content-center mb-5" data-aos="fade-up">
                <div id="filters" class="filters owl-filter-bar text-center button-group col-md-7">
                    <button class="btn btn-outline-secondary active" data-owl-filter="*"><div class="border-radius border-color-all"></div>Semua Testimoni</button>
                    <button class="btn btn-outline-secondary" data-owl-filter=".hasil-testi"><div class="border-radius border-color-hasil"></div>Testimoni Hasil</button>
                    <button class="btn btn-outline-secondary" data-owl-filter=".taste-testi"><div class="border-radius border-color-taste"></div>Testimoni taste</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="owl-carousel owl-theme" id="owl-carousels">
                    @foreach($testimony as $row)
                    <?php
                        if ($row->type_testimony == 'Testimony Hasil'){
                            $add = 'border-color-hasil-border hasil-testi';
                        }else{
                            $add = 'border-color-taste-border taste-testi';
                        }
                    ?>
                    <div class="item-testi {!! $add !!}">
                        <center><div class="photo-testi" style="background-image: url('{{asset($row->photo)}}')"></div></center>
                        <h5>{{$row->name}}</h5>
                        <p>{!! $row->content !!}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="site-section border-bottom" id="services-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade">
                    <h2 class="section-title mb-3">CONTOH MENU</h2>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="owl-carousel owl-theme" id="owl-menu">
                    @foreach($menu_example as $row)
                    <div class="item-menu">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <img src="{{asset($row->photo)}}" alt="" class="img-fluid">
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <div class="menu-info">
                                    <h3>{{$row->name}}</h3>
                                    <p>{{$row->calory}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="offset-sm-2 col-sm-8">
                    <div class="row">
                        <div class="infos col-md-2 col-4">
                            <img src="assets/ezfit/ic-benefit-delicious.png" alt="" class="img-fluid">
                            <br>  Delicius <br> Taste
                        </div>
                        <div class="infos col-md-2 col-4">
                            <img src="assets/ezfit/ic-benefit-hygienis.png" alt="" class="img-fluid">
                            <br>  Hygienic <br> Processing
                        </div>
                        <div class="infos col-md-2 col-4">
                            <img src="assets/ezfit/ic-benefit-fresh.png" alt="" class="img-fluid">
                            <br>  Fresh <br> Ingredient
                        </div>
                        <div class="infos col-md-2 col-4">
                            <img src="assets/ezfit/ic-benefit-nutrition.png" alt="" class="img-fluid">
                            <br>  Nutrition <br> Fact
                        </div>
                        <div class="infos col-md-2 col-4">
                            <img src="assets/ezfit/ic-benefit-msg.png" alt="" class="img-fluid">
                            <br>  No Added <br> MSG
                        </div>
                        <div class="infos col-md-2 col-4">
                            <img src="assets/ezfit/ic-benefit-pork.png" alt="" class="img-fluid">
                            <br>  No Pork & <br> Alcohol
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 offset-sm-2">
                    <div class="footer-content">
                        <h3>Jangan tunda hidup sehatmu, kejar body goal-mu dari sekarang!</h3>
                        <p>
                            <a href="https://apps.apple.com/us/app/ez-fit-katering-diet-sehat/id1495288228"><img src="assets/ezfit/btn-app-store.png" alt="" class="img-apps img-fluid"></a>
                            <a href="https://play.google.com/store/apps/details?id=com.crocodic.ezfitapp"><img src="assets/ezfit/btn-play-store.png" alt="" class="img-apps img-fluid"></a>
                        </p>
                        <a href=""><img src="assets/ezfit/img-urgent-promo.png" alt="" class="img-fluid mt-4"></a>
                        <p class="no-margin">* hanya untuk 50 pelanggan pertama tiap bulannya!</p>
                    </div>
                </div>
            </div>
            <div class="row footer-text">
                <div class="col-sm-10 offset-sm-1">
                    <div class="row">
                        <div class="col-sm-3">
                            <h4>Company</h4>
                            <div class="list-footer">
                                <div class="link-footer"><a href="{{url('about')}}">About</a></div>
                                <div class="link-footer"><a href="{{url('contact')}}">Contact</a></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <h4>Support</h4>
                            <div class="list-footer">
                                <div class="link-footer"><a href="{{url('faq')}}">FAQ</a></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <h4>Term & Policies</h4>
                            <div class="list-footer">
                                <div class="link-footer"><a href="{{url('tac')}}">Terms of service</a></div>
                                <div class="link-footer"><a href="{{url('privacy-policy')}}">Privacy policy</a></div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <h4>Social Media</h4>
                            <div class="list-footer">
                                <div class="link-footer footer-inline"><a href=""><i class="fab fa-instagram"></i></a></div>
                                <div class="link-footer footer-inline"><a href=""><i class="fab fa-twitter"></i></a></div>
                                <div class="link-footer footer-inline"><a href=""><i class="fab fa-facebook-square"></i></a></div>
                            </div>
                            <p class="font-small">Copyright of &copy;2019. All rights reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div> <!-- .site-wrap -->

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/jquery.countdown.min.js"></script>
<script src="assets/js/jquery.easing.1.3.js"></script>
<script src="assets/js/aos.js"></script>
<script src="assets/js/jquery.fancybox.min.js"></script>
<script src="assets/js/jquery.sticky.js"></script>
<script src="assets/js/isotope.pkgd.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/owlcarousel/owl.carousel.js"></script>
<script src="assets/js/slick.min.js"></script>
<script src="https://huynhhuynh.github.io/owlcarousel2-filter/dist/owlcarousel2-filter.min.js"></script>
<script>
    var owls = $('#owl-carousels');
    owls.owlCarousel({
        margin: 50,
        nav: false,
        loop: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                stagePadding: 150,
                items: 1
            },
            1000: {
                stagePadding: 150,
                items: 3
            }
        }
    })
    $( '.owl-filter-bar' ).on( 'click', '.btn', function() {

        var $item = $(this);
        var filter = $item.data( 'owl-filter' );
        console.log(filter);

        owls.owlcarousel2_filter( filter );

    } );

    $(document).ready(function() {

        var owl = $('#owl-menu');
        owl.owlCarousel({
            stagePadding: 50,
            margin: 20,
            nav: false,
            loop: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 2
                }
            }
        })
    });

    $('.feature-apps').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        adaptiveHeight: true,
        autoplay: true,
        autoplaySpeed: 3000,
        centerMode: true,
        variableWidth: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
</script>

</body>
</html>