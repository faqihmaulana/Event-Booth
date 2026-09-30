<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Statistik Booking - EventKu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('admin/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #6f42c1 0%, #5a359a 100%);
        }

        .chart-container {
            position: relative;
            height: 400px;
        }

        .progress-sm {
            height: 20px;
        }

        .stat-card {
            transition: transform 0.2s ease-in-out;
            min-height: 120px;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            opacity: 0.75;
        }

        .stat-card .stat-content {
            flex: 1;
            min-width: 0;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-value {
            font-size: 1.25rem;
            font-weight: bold;
            line-height: 1.2;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        /* Responsive adjustments for stats cards */
        @media (max-width: 1400px) {
            .stat-card .stat-value {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 1200px) {
            .stat-card .stat-value {
                font-size: 1rem;
            }
        }

        @media (max-width: 992px) {
            .stat-card .stat-value {
                font-size: 0.95rem;
            }
            .stat-card .stat-label {
                font-size: 0.8rem;
            }
            .stat-card {
                min-height: 100px;
            }
        }

        @media (max-width: 768px) {
            .stat-card .stat-value {
                font-size: 0.9rem;
            }
            .stat-card .stat-label {
                font-size: 0.75rem;
            }
            .stat-card {
                min-height: 90px;
            }
        }

        /* Table responsive improvements */
        @media (max-width: 768px) {
            .table-responsive table {
                font-size: 0.85rem;
            }
            
            .table-responsive th,
            .table-responsive td {
                padding: 0.5rem 0.25rem;
                white-space: nowrap;
            }
        }

        /* Revenue display improvements */
        .revenue-display {
            display: block;
            word-break: break-all;
        }

        @media (max-width: 992px) {
            .revenue-display {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3 d-flex align-items-center">
                    <i class="fa fa-ticket-alt me-2 text-primary fs-4"></i>
                    <h4 class="mb-0 text-primary">EventKu</h4>
                </a>

                <!-- User Info -->
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('admin/img/user.jpg') }}" alt=""
                            style="width: 40px; height: 40px;">
                        <div
                            class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1">
                        </div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <span>Admin</span>
                    </div>
                </div>

                <!-- Menu -->
                <div class="navbar-nav w-100">
                    <ul class="navbar-nav w-100">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-banner') }}"
                                class="nav-link {{ request()->routeIs('manage-banner') ? 'active' : '' }}">
                                <i class="fa fa-image me-2"></i>Manage Banner
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-event') }}"
                                class="nav-link {{ request()->routeIs('manage-event') ? 'active' : '' }}">
                                <i class="fa fa-calendar me-2"></i>Manage Event
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categori-booth') }}"
                                class="nav-link {{ request()->routeIs('categori-booth') ? 'active' : '' }}">
                                <i class="fa fa-th me-2"></i>Categori Booth
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('maps-booth') }}"
                                class="nav-link {{ request()->routeIs('maps-booth') ? 'active' : '' }}">
                                <i class="fa fa-map me-2"></i>Maps Booth
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-user') }}"
                                class="nav-link {{ request()->routeIs('manage-user') ? 'active' : '' }}">
                                <i class="fa fa-users me-2"></i>Manage User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('chat') }}"
                                class="nav-link {{ request()->routeIs('chat') ? 'active' : '' }}">
                                <i class="fa fa-envelope me-2"></i>Inbox
                            </a>
                        </li>

                        <!-- Dropdown Laporan -->
                        <li class="nav-item dropdown">
                            <a href="#"
                                class="nav-link dropdown-toggle {{ request()->routeIs('transaction') || request()->routeIs('admin.statistics*') || request()->is('laporan') ? 'active' : '' }}"
                                id="laporanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="far fa-file-alt me-2"></i>Laporan
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('transaction') ? 'active' : '' }}"
                                        href="{{ route('transaction') }}">Transaksi</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.statistics*') ? 'active' : '' }}"
                                        href="{{ route('admin.statistics') }}">Statistik</a>
                                </li>
                                 <li>
                                    <a class="dropdown-item {{ request()->routeIs('report*') ? 'active' : '' }}"
                                        href="{{ route('report') }}">Rekap Laporan</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="{{ route('dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>

                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-2" src="{{ asset('admin/img/user.jpg') }}" alt=""
                                style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 m-0">
                            <a href="{{ route('home') }}" class="dropdown-item">Lihat Website</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->

            <!-- Statistics Header -->
            <div class="container-fluid pt-4 px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h1 class="mb-2">📊 Statistik Booking</h1>
                                <p class="text-muted">Dashboard analitik booking dan pendapatan event</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="container-fluid px-4">
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="filter-card rounded p-4">
                            <h6 class="mb-3">🔍 Filter Statistik</h6>
                            <form method="GET" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="form-label">Tahun</label>
                                        <select name="year" class="form-select">
                                            @for($y = date('Y'); $y >= 2020; $y--)
                                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Event</label>
                                        <select name="event_id" class="form-select">
                                            <option value="">Semua Event</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}" {{ $eventId == $event->id ? 'selected' : '' }}>
                                                    {{ $event->main_title ?? 'Event #' . $event->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Seksi</label>
                                        <select name="section" class="form-select">
                                            <option value="">Semua Seksi</option>
                                            @foreach($sections as $sec)
                                                <option value="{{ $sec }}" {{ $section == $sec ? 'selected' : '' }}>{{ $sec }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Alamat/Kota</label>
                                        <select name="city" class="form-select">
                                            <option value="">Semua Alamat</option>
                                            @foreach($cities as $cityOption)
                                                <option value="{{ $cityOption }}" {{ $city == $cityOption ? 'selected' : '' }}>{{ $cityOption }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fa fa-search me-1"></i>Filter
                                        </button>
                                        <a href="{{ route('admin.statistics') }}" class="btn btn-outline-secondary">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="container-fluid px-4">
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-xl-2">
                        <div class="bg-gradient-success rounded d-flex align-items-center justify-content-between p-3 text-white stat-card">
                            <i class="fa fa-check-circle fa-2x stat-icon"></i>
                            <div class="ms-2 text-end stat-content">
                                <p class="mb-1 stat-label">Booking Terkonfirmasi</p>
                                <h6 class="mb-0 stat-value">{{ $stats['confirmed_bookings'] }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-2">
                        <div class="bg-gradient-danger rounded d-flex align-items-center justify-content-between p-3 text-white stat-card">
                            <i class="fa fa-times-circle fa-2x stat-icon"></i>
                            <div class="ms-2 text-end stat-content">
                                <p class="mb-1 stat-label">Booking Dibatalkan</p>
                                <h6 class="mb-0 stat-value">{{ $stats['cancelled_bookings'] }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-2">
                        <div class="bg-gradient-warning rounded d-flex align-items-center justify-content-between p-3 text-white stat-card">
                            <i class="fa fa-clock fa-2x stat-icon"></i>
                            <div class="ms-2 text-end stat-content">
                                <p class="mb-1 stat-label">Booking Pending</p>
                                <h6 class="mb-0 stat-value">{{ $stats['pending_bookings'] }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-2">
                        <div class="bg-gradient-info rounded d-flex align-items-center justify-content-between p-3 text-white stat-card">
                            <i class="fa fa-store fa-2x stat-icon"></i>
                            <div class="ms-2 text-end stat-content">
                                <p class="mb-1 stat-label">Booth Terbooking</p>
                                <h6 class="mb-0 stat-value">{{ $stats['unique_booths'] }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-2">
                        <div class="bg-gradient-purple rounded d-flex align-items-center justify-content-between p-3 text-white stat-card">
                            <i class="fa fa-list fa-2x stat-icon"></i>
                            <div class="ms-2 text-end stat-content">
                                <p class="mb-1 stat-label">Total Booking</p>
                                <h6 class="mb-0 stat-value">{{ $stats['total_bookings'] }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-2">
                        <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-between p-3 text-white stat-card">
                            <i class="fa fa-money-bill-wave fa-2x stat-icon"></i>
                            <div class="ms-2 text-end stat-content">
                                <p class="mb-1 stat-label">Total Pendapatan</p>
                                <h6 class="mb-0 stat-value revenue-display">
                                    Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="container-fluid px-4">
                <div class="row g-4 mb-4">
                    <!-- Event Booking Statistics Chart -->
                    <div class="col-xl-6">
                        <div class="bg-light rounded p-4">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="mb-0 fw-bold">📈 Booth Terbooking per Event</h6>
                            </div>
                            <div class="chart-container">
                                <canvas id="eventBookingChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Section Popularity Chart -->
                    <div class="col-xl-6">
                        <div class="bg-light rounded p-4">
                            <h6 class="mb-4 fw-bold">🏢 Popularitas Seksi Booth</h6>
                            <div class="chart-container">
                                <canvas id="sectionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hourly and City Charts -->
                <div class="row g-4 mb-4">
                    <div class="col-xl-6">
                        <div class="bg-light rounded p-4">
                            <h6 class="mb-4 fw-bold">⏰ Pola Jam Booking</h6>
                            <div class="chart-container">
                                <canvas id="hourlyChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6">
                        <div class="bg-light rounded p-4">
                            <h6 class="mb-4 fw-bold">📍 Top 15 Alamat/Kota Booking</h6>
                            <div class="chart-container">
                                <canvas id="cityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Statistics Table -->
            <div class="container-fluid px-4">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap">
                        <h6 class="mb-2 mb-sm-0 fw-bold">📋 Detail Statistik Event</h6>
                        <input type="text" class="form-control" id="searchTable" placeholder="Cari event..."
                            style="max-width: 300px;">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="statisticsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Event</th>
                                    <th>Total Booking</th>
                                    <th>Terkonfirmasi</th>
                                    <th>Dibatalkan</th>
                                    <th>Pending</th>
                                    <!-- <th>Booth Terbooking</th> -->
                                    <th>Pendapatan</th>
                                    <th>Success Rate</th>
                                </tr>
                            </thead>
                            <tbody id="statisticsTableBody">
                                @foreach($chartData['events'] as $event)
                                    @php
                                        $cancelledCount = $event->cancelled_count ?? 0;
                                        $pendingCount = $event->pending_count ?? 0;
                                        $totalBookings = $event->confirmed_bookings + $cancelledCount + $pendingCount;
                                        $successRate = $totalBookings > 0 ? ($event->confirmed_bookings / $totalBookings) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $event->main_title }}</strong>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $totalBookings }}</span></td>
                                        <td><span class="badge bg-success">{{ $event->confirmed_bookings }}</span></td>
                                        <td><span class="badge bg-danger">{{ $cancelledCount }}</span></td>
                                        <td><span class="badge bg-warning text-dark">{{ $pendingCount }}</span></td>
                                        <!-- <td><span class="badge bg-info">{{ $event->confirmed_booths }}</span></td> -->
                                        <td>
                                            <div class="revenue-display">
                                                <strong>Rp{{ number_format($event->total_revenue, 0, ',', '.') }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar {{ $successRate >= 70 ? 'bg-success' : ($successRate >= 40 ? 'bg-warning' : 'bg-danger') }}"
                                                    style="width: {{ $successRate }}%">
                                                    {{ number_format($successRate, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Cresindo</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('admin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('admin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('admin/js/main.js') }}"></script>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        $(document).ready(function () {
            // Hide spinner after page load
            setTimeout(function () {
                $('#spinner').removeClass('show');
            }, 1000);

            // Initialize charts
            initializeCharts();

            // Initialize table search
            initializeTableSearch();

            // Auto submit filter form when changed
            $('#filterForm select').change(function() {
                updateCharts();
            });
        });

        let eventChart, sectionChart, hourlyChart, cityChart;

        function initializeCharts() {
            // Set CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            updateCharts();
        }

        function updateCharts() {
            const filters = getFilterParams();
            
            Promise.all([
                $.get('{{ route("admin.statistics.event-stats") }}', filters),
                $.get('{{ route("admin.statistics.section-stats") }}', filters),
                $.get('{{ route("admin.statistics.hourly-stats") }}', filters),
                $.get('{{ route("admin.statistics.city-stats") }}', filters)
            ]).then(([eventData, sectionData, hourlyData, cityData]) => {
                createEventChart(eventData || []);
                createSectionChart(sectionData || []);
                createHourlyChart(hourlyData || []);
                createCityChart(cityData || []);
            }).catch(error => {
                console.error('Error loading chart data:', error);
                showAlert('Gagal memuat data grafik: ' + error.message, 'danger');
            });
        }

        function getFilterParams() {
            return {
                year: $('select[name="year"]').val(),
                event_id: $('select[name="event_id"]').val(),
                section: $('select[name="section"]').val(),
                city: $('select[name="city"]').val()
            };
        }

        function createEventChart(data) {
            const ctx = document.getElementById('eventBookingChart').getContext('2d');

            if (eventChart) {
                eventChart.destroy();
            }

            if (!data || data.length === 0) {
                drawNoDataMessage(ctx, 'Tidak ada data booking event');
                return;
            }

            eventChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => {
                        const name = item.name || 'Event';
                        return name.length > 20 ? name.substring(0, 20) + '...' : name;
                    }),
                    datasets: [{
                        label: 'Booth Terbooking',
                        data: data.map(item => parseInt(item.unique_booths) || 0),
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function (tooltipItems) {
                                    const index = tooltipItems[0].dataIndex;
                                    return data[index]?.name || 'Event';
                                },
                                label: function (context) {
                                    return `Booth Terbooking: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function createSectionChart(data) {
            const ctx = document.getElementById('sectionChart').getContext('2d');

            if (sectionChart) {
                sectionChart.destroy();
            }

            if (!data || data.length === 0) {
                drawNoDataMessage(ctx, 'Tidak ada data seksi booth');
                return;
            }

            sectionChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.section || 'N/A'),
                    datasets: [{
                        data: data.map(item => parseInt(item.confirmed_bookings) || 0),
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                            '#4BC0C0', '#FF6384'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.label}: ${context.parsed} booking`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function createHourlyChart(data) {
            const ctx = document.getElementById('hourlyChart').getContext('2d');

            if (hourlyChart) {
                hourlyChart.destroy();
            }

            if (!data || data.length === 0) {
                drawNoDataMessage(ctx, 'Tidak ada data jam booking');
                return;
            }

            // Fill missing hours with 0
            const hours = [];
            const counts = [];
            for (let i = 0; i < 24; i++) {
                const hourStr = String(i).padStart(2, '0') + ':00';
                const found = data.find(item => item.hour === hourStr);
                hours.push(hourStr);
                counts.push(found ? found.booking_count : 0);
            }

            hourlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Jumlah Booking',
                        data: counts,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function createCityChart(data) {
            const ctx = document.getElementById('cityChart').getContext('2d');

            if (cityChart) {
                cityChart.destroy();
            }

            if (!data || data.length === 0) {
                drawNoDataMessage(ctx, 'Tidak ada data alamat/kota');
                return;
            }

            cityChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => {
                        const city = item.city || 'N/A';
                        return city.length > 25 ? city.substring(0, 25) + '...' : city;
                    }),
                    datasets: [{
                        label: 'Total Booking',
                        data: data.map(item => parseInt(item.booking_count) || 0),
                        backgroundColor: 'rgba(255, 99, 132, 0.8)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function (tooltipItems) {
                                    const index = tooltipItems[0].dataIndex;
                                    return data[index]?.city || 'N/A';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function drawNoDataMessage(ctx, message) {
            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            ctx.font = '16px Arial';
            ctx.fillStyle = '#666';
            ctx.textAlign = 'center';
            ctx.fillText(message, ctx.canvas.width / 2, ctx.canvas.height / 2);
        }

        function initializeTableSearch() {
            const searchInput = $('#searchTable');
            const tableBody = $('#statisticsTableBody');
            const rows = tableBody.find('tr');

            searchInput.on('keyup', function () {
                const filter = $(this).val().toLowerCase();

                rows.each(function () {
                    const eventName = $(this).find('td:first');
                    if (eventName.length) {
                        const textValue = eventName.text() || eventName.html();
                        if (textValue.toLowerCase().indexOf(filter) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            });
        }

        function showAlert(message, type) {
            const alertDiv = $(`
                <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);

            $('body').append(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>

</html>