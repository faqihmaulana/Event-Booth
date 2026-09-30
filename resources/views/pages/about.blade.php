<!DOCTYPE html>
<html lang="en">

<head>
  <title>About Us - EventKu</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="css/animate.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/aos.css">
  <link rel="stylesheet" href="css/ionicons.min.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="css/jquery.timepicker.css">
  <link rel="stylesheet" href="css/flaticon.css">
  <link rel="stylesheet" href="css/icomoon.css">
  <link rel="stylesheet" href="css/style.css">
  <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="{{ route('home') }}">Event<span>Ku.</span></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
        aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
          </li>
          <li class="nav-item {{ request()->routeIs('pages.about') ? 'active' : '' }}">
            <a href="{{ route('pages.about') }}" class="nav-link">About</a>
          </li>
          <li class="nav-item {{ request()->is('event') ? 'active' : '' }}">
            <a href="{{ url('/event') }}" class="nav-link">Event</a>
          </li>
          <li class="nav-item {{ request()->is('contact') ? 'active' : '' }}">
            <a href="{{ url('/contact') }}" class="nav-link">Contact</a>
          </li>
          @guest
        <li class="nav-item cta mr-md-2">
        <a href="{{ route('login') }}" class="nav-link">Login</a>
        </li>
      @else
          @if(Auth::user()->role === 'admin')
        <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        @endif
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @if(Auth::user()->profile_photo)
          <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}" alt="Foto Profil"
          style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px;">
        @else
          <i class="fas fa-user-circle" style="font-size: 32px; margin-right: 8px; color: #000;"></i>
        @endif
            <span>{{ Auth::user()->name }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="min-width: 200px;">
            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('tenant.profile') ? 'active' : '' }}"
            href="{{ route('tenant.profile') }}">
            <i class="fas fa-user me-2"></i> Profil
            </a>
            <a class="dropdown-item d-flex align-items-center {{ request()->routeIs('pages.pesanan') ? 'active' : '' }}"
            href="{{ route('pages.pesanan') }}">
            <i class="fas fa-shopping-cart me-2"></i> Pesanan
            </a>
            <div class="dropdown-divider"></div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="px-3">
            @csrf
            <button class="btn btn-link dropdown-item d-flex align-items-center p-0" type="submit"
              style="width: 100%;">
              <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
            </form>
          </div>
          </li>
      @endguest
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-wrap hero-wrap-2 js-fullheight"
    style="background-image: url('{{ isset($banner->image_path) ? asset('storage/banners/' . $banner->image_path) : asset('images/teh-putih-4.jpeg') }}');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
        <div class="col-md-9 ftco-animate pb-5">
          <h1 class="mb-3 bread">About Us</h1>
          <p class="breadcrumbs">
            <span class="mr-2">
              <a href="{{ route('home') }}">Home <i class="ion-ios-arrow-forward"></i></a>
            </span>
            <span>About us <i class="ion-ios-arrow-forward"></i></span>
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- About Intro Section -->
  <section class="ftco-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <img src="images/about1.png" alt="Event Team" class="img-fluid">
        </div>
        <div class="col-lg-6 pl-lg-5">
          <h2 class="mb-4">Tentang Kami</h2>
          <p class="lead">Cresindo EO Tegal: 17 Tahun Pengalaman di Dunia Event dan Bisnis</p>
          <p>Cresindo adalah Event Organizer terkemuka yang berbasis di Tegal dengan pengalaman lebih dari 17 tahun
            dalam menyelenggarakan berbagai acara berskala lokal, nasional, hingga internasional.</p>
          <p>Kami telah membantu ratusan brand dan UMKM dalam meningkatkan visibilitas, memperluas jaringan bisnis,
            serta menciptakan pengalaman yang berkesan melalui partisipasi dalam pameran dagang, festival, konferensi,
            dan peluncuran produk.</p>
          <a href="{{ url('/contact') }}" class="btn btn-primary mt-3">Hubungi Kami</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="ftco-section bg-dark">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
          <h2 class="mb-4 text-white">Pencapaian Kami</h2>
          <p class="text-white">Selama 17 tahun berkecimpung di industri event, kami telah mencapai banyak hal yang
            membanggakan</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
          <div class="text-center p-4 h-100 bg-white rounded">
            <div class="icon bg-primary text-white rounded-circle mx-auto mb-3"
              style="width: 60px; height: 60px; line-height: 60px;">
              <i class="fas fa-handshake"></i>
            </div>
            <h3 class="mb-2"><span class="counter" data-count="153">0</span>+</h3>
            <p class="mb-0">Sponsor Nasional & Lokal</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
          <div class="text-center p-4 h-100 bg-white rounded">
            <div class="icon bg-success text-white rounded-circle mx-auto mb-3"
              style="width: 60px; height: 60px; line-height: 60px;">
              <i class="fas fa-store-alt"></i>
            </div>
            <h3 class="mb-2"><span class="counter" data-count="109">0</span>+</h3>
            <p class="mb-0">Booth UMKM & Komersial</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
          <div class="text-center p-4 h-100 bg-white rounded">
            <div class="icon bg-warning text-white rounded-circle mx-auto mb-3"
              style="width: 60px; height: 60px; line-height: 60px;">
              <i class="fas fa-lightbulb"></i>
            </div>
            <h3 class="mb-2"><span class="counter" data-count="30">0</span>+</h3>
            <p class="mb-0">Jenis Tema Event</p>
          </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
          <div class="text-center p-4 h-100 bg-white rounded">
            <div class="icon bg-danger text-white rounded-circle mx-auto mb-3"
              style="width: 60px; height: 60px; line-height: 60px;">
              <i class="fas fa-calendar-check"></i>
            </div>
            <h3 class="mb-2"><span class="counter" data-count="240">0</span>+</h3>
            <p class="mb-0">Event Terselenggara</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Mission Section -->
  <section class="ftco-section bg-light">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <h2 class="mb-4">Visi & Misi Kami</h2>
          <div class="accordion" id="missionAccordion">
            <div class="card mb-3">
              <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                  <button class="btn btn-link w-100 text-left" type="button" data-toggle="collapse"
                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="fas fa-eye mr-2 text-success"></i> Visi Kami
                  </button>
                </h5>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#missionAccordion">
                <div class="card-body">
                  Menjadi penyelenggara event terdepan di Jawa Tengah yang menghubungkan pelaku bisnis dengan pasar
                  potensial melalui pengalaman event yang berkesan dan bernilai.
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                  <button class="btn btn-link w-100 text-left collapsed" type="button" data-toggle="collapse"
                    data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <i class="fas fa-bullseye mr-2 text-success"></i> Misi Kami
                  </button>
                </h5>
              </div>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#missionAccordion">
                <div class="card-body">
                  <ul class="mb-0">
                    <li>Menyediakan platform terbaik untuk UMKM dan perusahaan memperluas jaringan bisnis</li>
                    <li>Menciptakan pengalaman event yang berkesan bagi peserta dan pengunjung</li>
                    <li>Mengembangkan konsep event inovatif yang sesuai dengan kebutuhan pasar</li>
                    <li>Memberikan layanan profesional dengan standar kualitas tertinggi</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <h2 class="mb-4">Kenapa Memilih Kami?</h2>
          <div class="d-flex mb-4">
            <div class="mr-4 text-success">
              <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div>
              <h5>Pengalaman 17 Tahun</h5>
              <p class="mb-0">Telah dipercaya menyelenggarakan ratusan event dengan berbagai skala dan kompleksitas.</p>
            </div>
          </div>

          <div class="d-flex mb-4">
            <div class="mr-4 text-success">
              <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div>
              <h5>Tim Profesional</h5>
              <p class="mb-0">Didukung oleh tim kreatif dan teknis yang berpengalaman di bidangnya masing-masing.</p>
            </div>
          </div>

          <div class="d-flex mb-4">
            <div class="mr-4 text-success">
              <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div>
              <h5>Jaringan Luas</h5>
              <p class="mb-0">Kemitraan dengan berbagai sponsor, vendor, dan media terkemuka.</p>
            </div>
          </div>

          <div class="d-flex">
            <div class="mr-4 text-success">
              <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div>
              <h5>Layanan Terpadu</h5>
              <p class="mb-0">Dari konsep hingga eksekusi, kami menyediakan solusi lengkap untuk kebutuhan event Anda.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <footer class="ftco-footer ftco-bg-dark ftco-section text-white" style="background-color: #1d1d1d;">
    <div class="container">
      <div class="row mb-5">
        <!-- Logo dan Deskripsi -->
        <div class="col-md-4">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">BookingBooth</h2>
            <p>Dapatkan booth terbaik di event favorit Anda dan tingkatkan penjualan dengan pengalaman yang
              profesional.</p>
            <ul class="ftco-footer-social list-unstyled d-flex mt-4">
              <li class="ftco-animate mr-3"><a href="#"><span class="icon-twitter text-white"></span></a></li>
              <li class="ftco-animate mr-3"><a href="#"><span class="icon-facebook text-white"></span></a>
              </li>
              <li class="ftco-animate"><a href="#"><span class="icon-instagram text-white"></span></a></li>
            </ul>
          </div>
        </div>

        <!-- Link Navigasi -->
        <div class="col-md-2">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Navigasi</h2>
            <ul class="list-unstyled">
              <li><a href="#" class="py-1 d-block text-white">Jadwal</a></li>
              <li><a href="#" class="py-1 d-block text-white">Event</a></li>
              <li><a href="#" class="py-1 d-block text-white">Harga Booth</a></li>
              <li><a href="#" class="py-1 d-block text-white">FAQ</a></li>
            </ul>
          </div>
        </div>

        <!-- Informasi -->
        <div class="col-md-3">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Informasi</h2>
            <ul class="list-unstyled">
              <li><a href="#" class="py-1 d-block text-white">Tentang Kami</a></li>
              <li><a href="#" class="py-1 d-block text-white">Kontak</a></li>
              <li><a href="#" class="py-1 d-block text-white">Karier</a></li>
              <li><a href="#" class="py-1 d-block text-white">Layanan</a></li>
            </ul>
          </div>
        </div>

        <!-- Kontak -->
        <div class="col-md-3">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Hubungi Kami</h2>
            <ul class="list-unstyled">
              <li><span class="icon icon-map-marker"></span><span class="text ml-2">Jl. Pancasila, Alun-alun
                  Tegal</span></li>
              <li><a href="#"><span class="icon icon-phone"></span><span class="text ml-2">+62
                    812-3456-7890</span></a></li>
              <li><a href="#"><span class="icon icon-envelope"></span><span
                    class="text ml-2">info@bookingbooth.com</span></a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Copyright -->
      <div class="row">
        <div class="col-md-12 text-center">
          <p class="mb-0">
            &copy;
            <script>document.write(new Date().getFullYear());</script> BookingBooth. Dibuat oleh <a href="#"
              target="_blank" class="text-white">Cresindo</a>
          </p>
        </div>
      </div>
    </div>
  </footer>



  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
        stroke="#F96D00" />
    </svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>

</body>

</html>