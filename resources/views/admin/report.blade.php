<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rekap Laporan Booking - EventKu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
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

    <!-- Bootstrap Stylesheet -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/report.css') }}" rel="stylesheet">

    <!-- Custom CSS for Responsive Table (Same as Manage User) -->
    <style>
        /* Wrapper untuk scroll horizontal */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        /* Style untuk tabel di mobile */
        @media (max-width: 768px) {
            .table-responsive-custom {
                overflow-x: scroll;
                white-space: nowrap;
            }

            .table-responsive-custom table {
                min-width: 1200px;
                /* Lebar minimum tabel karena lebih banyak kolom */
            }

            .table-responsive-custom td,
            .table-responsive-custom th {
                white-space: nowrap;
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            /* Styling untuk aksi buttons di mobile */
            .table-responsive-custom .d-flex {
                gap: 0.25rem;
            }

            .table-responsive-custom .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        /* Scrollbar styling untuk webkit browsers */
        .table-responsive-custom::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive-custom::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive-custom::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-responsive-custom::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Indicator untuk scroll */
        .scroll-indicator {
            text-align: center;
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
            display: none;
        }

        @media (max-width: 768px) {
            .scroll-indicator {
                display: block;
            }
        }

        /* Custom styling untuk event info dan booth info */
        .event-info strong {
            display: block;
            font-weight: 600;
        }

        .booth-info {
            line-height: 1.3;
        }

        .booth-info small {
            display: block;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <!-- Sidebar -->
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

                <!-- Navigation Menu -->
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
                                class="nav-link dropdown-toggle {{ request()->routeIs('transaction') || request()->routeIs('admin.statistics*') || request()->routeIs('admin.report*') ? 'active' : '' }}"
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
                                    <a class="dropdown-item {{ request()->routeIs('admin.report*') ? 'active' : '' }}"
                                        href="{{ route('admin.report') }}">Rekap Laporan</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="{{ route('dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" id="messageDropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Pesan</span>
                            <!-- Badge for unread count -->
                            <span class="badge bg-danger rounded-pill ms-1" id="unreadBadge"
                                style="display: none;">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0"
                            id="notificationDropdown" style="min-width: 350px; max-height: 400px; overflow-y: auto;">
                            <!-- Dynamic content will be loaded here -->
                            <div class="text-center p-3" id="loadingNotifications">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <small class="d-block mt-2">Memuat notifikasi...</small>
                            </div>

                            <!-- No notifications message -->
                            <div class="text-center p-3" id="noNotifications" style="display: none;">
                                <i class="fa fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Tidak ada pesan baru</p>
                            </div>

                            <!-- Notifications container -->
                            <div id="notificationsContainer"></div>

                            <!-- Footer -->
                            <div class="dropdown-divider" id="notificationDivider" style="display: none;"></div>
                            <a href="{{ route('chat') }}" class="dropdown-item text-center" id="viewAllMessages"
                                style="display: none;">
                                <strong>Lihat semua pesan</strong>
                            </a>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
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

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded h-100 p-4">
                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="mb-0">Rekap Laporan Booking</h4>
                            <p class="mb-0 text-muted">Laporan komprehensif booking dan transaksi</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.report.export') }}?{{ http_build_query(request()->query()) }}" 
                               class="btn btn-success export-btn">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.report') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <select name="event_id" class="form-select">
                                    <option value="">Semua Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event['id'] }}" 
                                            {{ $eventId == $event['id'] ? 'selected' : '' }}>
                                            {{ $event['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-2">
                                <select name="payment_status" class="form-select">
                                    <option value="">Semua Payment</option>
                                    <option value="paid" {{ $paymentStatus == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="unpaid" {{ $paymentStatus == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="failed" {{ $paymentStatus == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="expired" {{ $paymentStatus == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </div> -->
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ $startDate }}" placeholder="Dari Tanggal">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ $endDate }}" placeholder="Sampai Tanggal">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Summary Stats Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-primary rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-chart-bar fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Total Booking</p>
                                    <h6 class="mb-0">{{ number_format($stats['total_bookings']) }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-success rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-check-circle fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Terkonfirmasi</p>
                                    <h6 class="mb-0">{{ number_format($stats['confirmed_bookings']) }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-info rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-money-bill-wave fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Total Pendapatan</p>
                                    <h6 class="mb-0">Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-warning rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-calculator fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Rata-rata Nilai</p>
                                    <h6 class="mb-0">Rp{{ number_format($stats['average_booking_value'], 0, ',', '.') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-clock fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Pending</p>
                                    <h6 class="mb-0">{{ number_format($stats['pending_bookings']) }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-danger rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-times-circle fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Dibatalkan</p>
                                    <h6 class="mb-0">{{ number_format($stats['cancelled_bookings']) }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-success rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-credit-card fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Sudah Bayar</p>
                                    <h6 class="mb-0">{{ number_format($stats['paid_bookings']) }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xl-3">
                            <div class="bg-warning rounded d-flex align-items-center justify-content-between p-4 text-white">
                                <i class="fa fa-hourglass-half fa-3x opacity-75"></i>
                                <div class="ms-3 text-end">
                                    <p class="mb-2">Belum Bayar</p>
                                    <h6 class="mb-0">{{ number_format($stats['unpaid_bookings']) }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table with Same Structure as Manage User -->
                    <div class="table-responsive-custom">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Event</th>
                                    <th>Nama Produk</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Nomor Telepon</th>
                                    <th>Email</th>
                                    <th>Booth</th>
                                    <th>Total Harga</th>
                                    <th>Status Booking</th>
                                    <th>Status Payment</th>
                                    <th>Order ID</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $index => $booking)
                                    <tr>
                                        <td>{{ $bookings->firstItem() + $index }}</td>
                                        <td>
                                            <div class="event-info">
                                                @if($booking->booth && $booking->booth->event)
                                                    <strong>{{ $booking->booth->event->main_title ?? 'Event #'.$booking->booth->event->id }}</strong>
                                                    @if($booking->booth->event->start_date)
                                                        <small class="text-muted d-block">
                                                            {{ \Carbon\Carbon::parse($booking->booth->event->start_date)->format('d M Y') }}
                                                            @if($booking->booth->event->end_date && $booking->booth->event->end_date != $booking->booth->event->start_date)
                                                                - {{ \Carbon\Carbon::parse($booking->booth->event->end_date)->format('d M Y') }}
                                                            @endif
                                                        </small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $booking->company_name }}</td>
                                        <td>{{ $booking->contact_person }}</td>
                                        <td>{{ $booking->phone }}</td>
                                        <td>{{ $booking->email }}</td>
                                        <td>
                                            <div class="booth-info">
                                                @if($booking->booth)
                                                    {{ $booking->booth->booth_id ?? $booking->booth->name ?? 'N/A' }}
                                                    @if($booking->booth->booth_name)
                                                        <small class="text-muted">{{ $booking->booth->booth_name }}</small>
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($booking->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @else
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($booking->payment_status == 'unpaid')
                                                <span class="badge bg-secondary">Unpaid</span>
                                            @elseif($booking->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($booking->payment_status == 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @elseif($booking->payment_status == 'expired')
                                                <span class="badge bg-dark">Expired</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $booking->order_id ?? 'N/A' }}</td>
                                        <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                                        <td class="d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-info" 
                                                onclick="showBookingDetail({{ $booking->id }})" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center py-4">Tidak ada data booking ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Scroll Indicator (Same as Manage User) -->
                    <div class="scroll-indicator">
                        <i class="fas fa-arrows-alt-h me-1"></i>
                        Geser ke kanan untuk melihat kolom lainnya
                    </div>

                    <!-- Pagination (same as transaction page) -->
                    <div class="pagination-container mt-4">
                        <div class="pagination-info">
                            Menampilkan <span class="fw-bold">{{ $bookings->firstItem() }}</span> sampai <span
                                class="fw-bold">{{ $bookings->lastItem() }}</span> dari <span
                                class="fw-bold">{{ $bookings->total() }}</span> entri
                        </div>

                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($bookings->onFirstPage())
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookings->previousPageUrl() }}"
                                            rel="prev">&laquo;</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @php
                                    $current = $bookings->currentPage();
                                    $last = $bookings->lastPage();
                                    $start = max(1, $current - 2);
                                    $end = min($last, $current + 2);
                                @endphp

                                {{-- First Page --}}
                                @if($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookings->url(1) }}">1</a>
                                    </li>
                                    @if($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                {{-- Page Numbers --}}
                                @for ($page = $start; $page <= $end; $page++)
                                    @if ($page == $current)
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $bookings->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endfor

                                {{-- Last Page --}}
                                @if($end < $last)
                                    @if($end < $last - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookings->url($last) }}">{{ $last }}</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($bookings->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $bookings->nextPageUrl() }}" rel="next">&raquo;</a>
                                    </li>
                                @else
                                    <li class="page-item disabled" aria-disabled="true">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Cresindo</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Detail Modal -->
    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailModalLabel">Detail Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="{{ asset('admin/js/main.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Hide spinner after page load
            setTimeout(function () {
                $('#spinner').removeClass('show');
            }, 1000);
        });

        /**
         * Export table data to Excel
         */
        function exportToExcel() {
            const table = document.getElementById("reportTable");
            const cloneTable = table.cloneNode(true);

            // Remove action column
            cloneTable.querySelectorAll('th:last-child, td:last-child').forEach(el => el.remove());

            // Clean event column data
            cloneTable.querySelectorAll('tbody tr td:nth-child(2) .event-info').forEach(cell => {
                const eventName = cell.querySelector('strong') ? cell.querySelector('strong').textContent : '';
                const eventDetails = Array.from(cell.querySelectorAll('small')).map(s => s.textContent.trim()).join(' | ');
                cell.parentNode.textContent = eventName + (eventDetails ? ' (' + eventDetails + ')' : '');
            });

            // Clean booth column data
            cloneTable.querySelectorAll('tbody tr td:nth-child(7) .booth-info').forEach(cell => {
                const boothId = cell.textContent.trim().split('\n')[0];
                cell.parentNode.textContent = boothId;
            });

            // Clean status badges
            cloneTable.querySelectorAll('.badge').forEach(badge => {
                badge.parentNode.textContent = badge.textContent.trim();
            });

            const wb = XLSX.utils.table_to_book(cloneTable, { sheet: "Rekap Laporan Booking" });
            const fileName = `rekap_laporan_booking_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(wb, fileName);
        }

        function showBookingDetail(bookingId) {
            $('#bookingDetailModal').modal('show');
            
            // AJAX request to get booking details
            $.get(`{{ url('admin/report/booking-details') }}/${bookingId}`)
                .done(function(response) {
                    if (response.success) {
                        const booking = response.booking;
                        let html = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3"><i class="fa fa-info-circle me-2"></i>Informasi Booking</h6>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td width="40%"><strong>Order ID:</strong></td>
                                            <td>${booking.order_id || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td><span class="badge bg-${getStatusColor(booking.status)}">${booking.status || 'N/A'}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status Bayar:</strong></td>
                                            <td><span class="badge bg-${getPaymentStatusColor(booking.payment_status)}">${booking.payment_status || 'N/A'}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Booking:</strong></td>
                                            <td>${booking.created_at ? new Date(booking.created_at).toLocaleDateString('id-ID') : 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Bayar:</strong></td>
                                            <td>${booking.paid_at ? new Date(booking.paid_at).toLocaleDateString('id-ID') : 'Belum dibayar'}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3"><i class="fa fa-building me-2"></i>Informasi Perusahaan</h6>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td width="40%"><strong>Perusahaan:</strong></td>
                                            <td>${booking.company_name || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Contact Person:</strong></td>
                                            <td>${booking.contact_person || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Telepon:</strong></td>
                                            <td>${booking.phone || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>${booking.email || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Deskripsi:</strong></td>
                                            <td>${booking.description || 'N/A'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3"><i class="fa fa-map-marker-alt me-2"></i>Informasi Booth</h6>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td width="40%"><strong>Booth ID:</strong></td>
                                            <td>${booking.booth?.booth_id || booking.booth?.name || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Booth:</strong></td>
                                            <td>${booking.booth?.booth_name || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Section:</strong></td>
                                            <td>${booking.booth?.section || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Harga:</strong></td>
                                            <td><strong class="text-success">Rp${booking.total_price ? booking.total_price.toLocaleString('id-ID') : '0'}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-3"><i class="fa fa-calendar me-2"></i>Informasi Event</h6>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td width="40%"><strong>Event:</strong></td>
                                            <td>${booking.booth?.event?.name || booking.booth?.event?.main_title || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Mulai:</strong></td>
                                            <td>${booking.booth?.event?.start_date ? new Date(booking.booth?.event?.start_date).toLocaleDateString('id-ID') : 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Selesai:</strong></td>
                                            <td>${booking.booth?.event?.end_date ? new Date(booking.booth?.event?.end_date).toLocaleDateString('id-ID') : 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Lokasi:</strong></td>
                                            <td>${booking.booth?.event?.location || 'N/A'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        `;
                        
                        if (booking.payment_method || booking.transaction_id) {
                            html += `
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-bold mb-3"><i class="fa fa-credit-card me-2"></i>Informasi Pembayaran</h6>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <td width="20%"><strong>Metode Pembayaran:</strong></td>
                                                <td width="30%">${booking.payment_method || 'N/A'}</td>
                                                <td width="20%"><strong>Transaction ID:</strong></td>
                                                <td width="30%">${booking.transaction_id || 'N/A'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            `;
                        }

                        $('#bookingDetailContent').html(html);
                    }
                })
                .fail(function() {
                    $('#bookingDetailContent').html(`
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            Gagal memuat detail booking. Silakan coba lagi.
                        </div>
                    `);
                });
        }

        function getStatusColor(status) {
            switch(status) {
                case 'confirmed': return 'success';
                case 'pending': return 'warning';
                case 'cancelled': return 'danger';
                default: return 'secondary';
            }
        }

        function getPaymentStatusColor(status) {
            switch(status) {
                case 'paid': return 'success';
                case 'unpaid': return 'warning';
                case 'failed': return 'danger';
                case 'expired': return 'secondary';
                default: return 'secondary';
            }
        }

        /**
         * Notification system
         */
        document.addEventListener('DOMContentLoaded', function () {
            function loadNotifications() {
                fetch('/admin/chat/notifications')
                    .then(response => response.json())
                    .then(data => {
                        updateNotificationDropdown(data);
                        updateUnreadBadge(data.total_unread);
                    })
                    .catch(error => console.error('Notification load error:', error));
            }

            function updateNotificationDropdown(data) {
                const container = document.getElementById('notificationsContainer');
                const noNotifications = document.getElementById('noNotifications');
                const loadingDiv = document.getElementById('loadingNotifications');
                const divider = document.getElementById('notificationDivider');
                const viewAll = document.getElementById('viewAllMessages');

                if (loadingDiv) loadingDiv.style.display = 'none';

                if (data.notifications.length === 0) {
                    noNotifications.style.display = 'block';
                    container.innerHTML = '';
                    divider.style.display = 'none';
                    viewAll.style.display = 'none';
                } else {
                    noNotifications.style.display = 'none';
                    divider.style.display = 'block';
                    viewAll.style.display = 'block';

                    container.innerHTML = data.notifications.map((notification, index) => `
                    <a href="#" class="dropdown-item" onclick="handleNotificationClick(${notification.sender_id}, ${notification.id})">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle" src="/admin/img/user.jpg" style="width: 40px; height: 40px;">
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">${notification.sender_name} mengirim pesan</h6>
                                <small>${notification.time_ago}</small>
                                <div><small class="text-muted">${notification.message}</small></div>
                            </div>
                        </div>
                    </a>
                    ${index < data.notifications.length - 1 ? '<hr class="dropdown-divider">' : ''}
                `).join('');
                }
            }

            function updateUnreadBadge(count) {
                const badge = document.getElementById('unreadBadge');
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }

            window.handleNotificationClick = function (senderId, messageId) {
                fetch(`/admin/chat/notifications/${messageId}/read`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                window.location.href = `/admin/chat?user=${senderId}`;
            };

            loadNotifications();
            setInterval(loadNotifications, 30000);
        });
    </script>

</body>

</html>