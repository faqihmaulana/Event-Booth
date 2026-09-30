<!DOCTYPE html>
<html lang="en">

<head>
  <title>Booking Booth - EventKu</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="css/animate.css">
  <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">
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
          {{-- Guest Links --}}
          @guest
        <li class="nav-item cta mr-md-2">
        <a href="{{ route('login') }}" class="nav-link">Login</a>
        </li>
        <!-- <li class="nav-item cta">
      <a href="{{ route('register') }}" class="nav-link">Register</a>
      </li> -->
      @else
          {{-- Admin Dashboard --}}
          @if(Auth::user()->role === 'admin')
        <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        @endif

          {{-- Dropdown User --}}
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
  <!-- END nav -->

  <section class="hero-wrap hero-wrap-2 js-fullheight"
    style="background-image: url('{{ asset('storage/banners/' . ($banner->image_path ?? 'default.jpg')) }}');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
        <div class="col-md-9 ftco-animate pb-5">
          <h1 class="mb-3 bread">Contact Us</h1>
          <p class="breadcrumbs">
            <span class="mr-2">
              <a href="{{ route('home') }}">Home <i class="ion-ios-arrow-forward"></i></a>
            </span>
            <span>
              Contact <i class="ion-ios-arrow-forward"></i>
            </span>
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="ftco-section contact-section">
    <div class="container">
      <div class="row d-flex mb-5 contact-info">
        <div class="col-md-12 mb-4">
          <h2 class="h3">Lokasi Kami</h2>
        </div>
      </div>

      <div class="row block-9">
        <div class="col-md-6 order-md-last d-flex">
          <div class="bg-light p-5 contact-info">
            <h4>Informasi Kontak</h4>
            <p><strong>Telepon:</strong> +62 123 456 7890</p>
            <p><strong>Email:</strong> info@company.com</p>
            <p><strong>Alamat:</strong> Jakarta, Indonesia</p>
          </div>
        </div>

        <div class="col-md-6 d-flex">
          <div id="map" class="bg-white">
            <!-- Menampilkan peta menggunakan iframe Google Maps -->
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12655.678230999977!2d109.1397166924745!3d-6.8684254531348885!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e705312b72f1c67%3A0x85ee125a0fcf92b0!2sJl.%20Arum%20Indah%20V%20No.B%2023%2C%20Randugunting%2C%20Kec.%20Tegal%20Sel.%2C%20Kota%20Tegal%2C%20Jawa%20Tengah%2052131!5e0!3m2!1sid!2sid!4v1684187754545!5m2!1sid!2sid"
              width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
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
  <script src="js/main.js"></script>

</body>

</html>