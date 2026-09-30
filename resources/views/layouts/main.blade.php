<!DOCTYPE html>
<html lang="en">

<head>
    <title>Booking Booth - EventKu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ asset('css/aos.css') }}">

    <!-- CSS Fun Facts dengan helper asset() -->
    <link rel="stylesheet" href="{{ asset('css/fun-facts.css') }}">

    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.timepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap JS (Bundle, termasuk Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Even<span>Ku.</span></a>
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
                    @else
                        {{-- Admin Dashboard --}}
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @endif

                        {{-- Dropdown User --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                                role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/profile_photos/' . Auth::user()->profile_photo) }}"
                                        alt="Foto Profil"
                                        style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px;">
                                @else
                                    <i class="fas fa-user-circle" style="font-size: 32px; margin-right: 8px; color: #000;"></i>
                                @endif
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown"
                                style="min-width: 200px;">
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

    @yield('content')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-migrate-3.0.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jquery.stellar.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/aos.js') }}"></script>
    <script src="{{ asset('js/jquery.animateNumber.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
    <script src="{{ asset('js/scrollax.min.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
    <script src="{{ asset('js/google-map.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')

</body>

</html>