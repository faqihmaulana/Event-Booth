<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Transaction Admin - EventKu</title>
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
                            <h4 class="mb-0">Transaksi Booking Tenant</h4>
                            <p class="mb-0 text-muted">Daftar seluruh booking booth oleh tenant</p>
                        </div>
                        <!-- <div class="col-md-4 text-end">
                            <button class="btn btn-success export-btn" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-1"></i> Export Excel
                            </button>
                        </div> -->
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('transaction') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="payment_status" class="form-select">
                                    <option value="">Semua Payment</option>
                                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                        Failed</option>
                                    <option value="expired" {{ request('payment_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}" placeholder="Dari Tanggal">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}" placeholder="Sampai Tanggal">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table id="reportTable" class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
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
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $index => $booking)
                                    <tr data-booking-id="{{ $booking->id }}"
                                        data-payment-status="{{ $booking->payment_status }}"
                                        data-booking-status="{{ $booking->status }}"
                                        data-created-at="{{ $booking->created_at }}"
                                        data-payment-expired-at="{{ $booking->payment_expired_at }}">
                                        <th>{{ $bookings->firstItem() + $index }}</th>
                                        <td>
                                            <div class="event-info">
                                                <strong>{{ $booking->event_name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $booking->company_name }}</td>
                                        <td>{{ $booking->contact_person }}</td>
                                        <td>{{ $booking->phone }}</td>
                                        <td>{{ $booking->email }}</td>
                                        <td>
                                            <div class="booth-info">
                                                {{ $booking->booth ? $booking->booth->booth_id : 'N/A' }}
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
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <!-- Detail Button -->
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal{{ $booking->id }}" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Check Payment Button - hanya untuk order yang belum dibayar -->
                                                @if($booking->order_id && $booking->payment_status == 'unpaid')
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        onclick="checkPaymentStatus({{ $booking->id }})" title="Check Payment">
                                                        <i class="fas fa-sync"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Detail Modal -->
                                    <!-- Detail Modal - Improved Design -->
                                    <div class="modal fade" id="detailModal{{ $booking->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detail Booking Tenant</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <!-- Event Information -->
                                                    <div class="border rounded p-3 mb-4" style="background-color: #f8f9fa;">
                                                        <h6 class="text-primary mb-3">
                                                            <i class="fas fa-calendar-alt"></i> Informasi Event
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-8 mb-3">
                                                                <strong>Nama Event:</strong>
                                                                <p class="mb-0">{{ $booking->event_name }}</p>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <strong>Kategori:</strong>
                                                                <p class="mb-0">
                                                                    {{ $booking->event_info['category'] ?? 'N/A' }}</p>
                                                            </div>
                                                            @if($booking->event_info && $booking->event_info['start_date'])
                                                                <div class="col-md-6 mb-3">
                                                                    <strong>Tanggal Mulai:</strong>
                                                                    <p class="mb-0">
                                                                        {{ \Carbon\Carbon::parse($booking->event_info['start_date'])->format('d M Y') }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if($booking->event_info && $booking->event_info['end_date'] && $booking->event_info['end_date'] != $booking->event_info['start_date'])
                                                                <div class="col-md-6 mb-3">
                                                                    <strong>Tanggal Selesai:</strong>
                                                                    <p class="mb-0">
                                                                        {{ \Carbon\Carbon::parse($booking->event_info['end_date'])->format('d M Y') }}
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            @if($booking->event_info && $booking->event_info['location'])
                                                                <div class="col-md-12 mb-3">
                                                                    <strong>Lokasi Event:</strong>
                                                                    <p class="mb-0"><i
                                                                            class="fas fa-map-marker-alt text-danger"></i>
                                                                        {{ $booking->event_info['location'] }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Company & Contact Information -->
                                                    <div class="border rounded p-3 mb-4" style="background-color: #fff8e1;">
                                                        <h6 class="text-warning mb-3">
                                                            <i class="fas fa-building"></i> Informasi Perusahaan & Kontak
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <strong>Nama Produk/Perusahaan:</strong>
                                                                <p class="mb-0">{{ $booking->company_name }}</p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Penanggung Jawab:</strong>
                                                                <p class="mb-0">{{ $booking->contact_person }}</p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Nomor Telepon:</strong>
                                                                <p class="mb-0"><i class="fas fa-phone text-success"></i>
                                                                    {{ $booking->phone }}</p>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <strong>Email:</strong>
                                                                <p class="mb-0"><i class="fas fa-envelope text-info"></i>
                                                                    {{ $booking->email }}</p>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <strong>Deskripsi Produk:</strong>
                                                                <p class="mb-0"
                                                                    style="background: #f8f9fa; padding: 10px; border-radius: 5px; min-height: 50px;">
                                                                    {{ $booking->description ?: 'Tidak ada deskripsi' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Booth & Booking Information -->
                                                    <div class="border rounded p-3 mb-4" style="background-color: #e8f5e8;">
                                                        <h6 class="text-success mb-3">
                                                            <i class="fas fa-store"></i> Informasi Booth & Booking
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <strong>ID Booth:</strong>
                                                                <p class="mb-0">
                                                                    <span
                                                                        class="badge bg-primary fs-6">{{ $booking->booth ? $booking->booth->booth_id : 'N/A' }}</span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Nama Booth:</strong>
                                                                <p class="mb-0">
                                                                    {{ $booking->booth && $booking->booth->booth_name ? $booking->booth->booth_name : 'N/A' }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Tanggal Booking:</strong>
                                                                <p class="mb-0">
                                                                    {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : 'N/A' }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Tanggal Dibuat:</strong>
                                                                <p class="mb-0">
                                                                    {{ $booking->created_at->format('d M Y H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Payment Information -->
                                                    <div class="border rounded p-3 mb-4" style="background-color: #fff0f5;">
                                                        <h6 class="text-danger mb-3">
                                                            <i class="fas fa-credit-card"></i> Informasi Pembayaran
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <strong>Total Harga:</strong>
                                                                <p class="mb-0">
                                                                    <span class="badge bg-success fs-5">Rp
                                                                        {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <strong>Order ID:</strong>
                                                                <p class="mb-0">{{ $booking->order_id ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <strong>Transaction ID:</strong>
                                                                <p class="mb-0">{{ $booking->transaction_id ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <strong>Payment Method:</strong>
                                                                <p class="mb-0">{{ $booking->payment_method ?? 'N/A' }}</p>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <strong>Tanggal Dibayar:</strong>
                                                                <p class="mb-0">
                                                                    {{ $booking->paid_at ? $booking->paid_at->format('d M Y H:i') : 'Belum dibayar' }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <strong>Expired Payment:</strong>
                                                                <p class="mb-0">
                                                                    {{ $booking->payment_expired_at ? $booking->payment_expired_at->format('d M Y H:i') : 'N/A' }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <strong>Status Payment:</strong>
                                                                <p class="mb-0">
                                                                    @if($booking->payment_status == 'unpaid')
                                                                        <span class="badge bg-secondary">Unpaid</span>
                                                                    @elseif($booking->payment_status == 'paid')
                                                                        <span class="badge bg-success">Paid</span>
                                                                    @elseif($booking->payment_status == 'failed')
                                                                        <span class="badge bg-danger">Failed</span>
                                                                    @elseif($booking->payment_status == 'expired')
                                                                        <span class="badge bg-dark">Expired</span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Status Information -->
                                                    <div class="border rounded p-3" style="background-color: #f0f8ff;">
                                                        <h6 class="text-info mb-3">
                                                            <i class="fas fa-info-circle"></i> Status Booking
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <strong>Status Saat Ini:</strong>
                                                                <p class="mb-0">
                                                                    @if($booking->status == 'pending')
                                                                        <span class="badge bg-warning text-dark fs-6">
                                                                            <i class="fas fa-clock"></i> Pending
                                                                        </span>
                                                                    @elseif($booking->status == 'confirmed')
                                                                        <span class="badge bg-success fs-6">
                                                                            <i class="fas fa-check-circle"></i> Confirmed
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-danger fs-6">
                                                                            <i class="fas fa-times-circle"></i> Cancelled
                                                                        </span>
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center py-4">Tidak ada data booking ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-container">
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="{{ asset('admin/js/main.js') }}"></script>

    <script>
       
        /**
         * Update booking status - AUTO CONFIRM (removed user confirmation)
         */
        function updateStatus(bookingId, status) {
            const statusText = { 'cancelled': 'membatalkan', 'pending': 'mengubah menjadi pending', 'confirmed': 'mengkonfirmasi' };
            const message = statusText[status] || 'mengubah status';

            // AUTO CONFIRM - No user confirmation needed
            console.log(`Auto ${message} booking ${bookingId}`);
            showLoading();

            fetch(`/admin/bookings/${bookingId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCSRFToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showAlert('success', 'Status booking berhasil diperbarui');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showAlert('error', data.message || 'Gagal memperbarui status booking');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat memperbarui status');
                });
        }

        /**
         * Check payment status from Midtrans with auto-update
         */
        function checkPaymentStatus(bookingId) {
            if (!bookingId || bookingId === '' || bookingId === 'undefined') {
                showAlert('error', 'ID Booking tidak valid');
                return;
            }

            showLoading();

            fetch(`/admin/bookings/${bookingId}/payment-status`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCSRFToken(),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
                .then(handleResponse)
                .then(data => {
                    hideLoading();
                    if (data && data.success) {
                        showPaymentStatusModal(data);
                        // Auto-reload if status has changed significantly
                        if (['paid', 'expired', 'failed', 'cancelled'].includes(data.payment_status)) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    } else {
                        showAlert('error', data?.message || 'Gagal mengecek status pembayaran');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Payment Status Check Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat mengecek status pembayaran');
                });
        }

        /**
         * Auto-cancel expired bookings - Enhanced version
         */
        function checkAndCancelExpiredBookings() {
            const bookings = document.querySelectorAll('[data-booking-id]');
            let hasExpiredBookings = false;

            bookings.forEach(booking => {
                const bookingId = booking.getAttribute('data-booking-id');
                const paymentExpiredAt = booking.getAttribute('data-payment-expired-at');
                const paymentStatus = booking.getAttribute('data-payment-status');
                const bookingStatus = booking.getAttribute('data-booking-status');

                if (bookingId && paymentExpiredAt && paymentStatus === 'unpaid' && bookingStatus !== 'cancelled') {
                    const expiredDate = new Date(paymentExpiredAt);
                    const now = new Date();

                    // Check if booking has expired
                    if (now > expiredDate) {
                        hasExpiredBookings = true;
                        autoExpireBooking(bookingId);
                    }
                }
            });

            // If no expired bookings, run the old logic as fallback
            if (!hasExpiredBookings) {
                checkLegacyExpiredBookings();
            }
        }

        /**
         * Auto-expire booking based on payment_expired_at
         */
        function autoExpireBooking(bookingId) {
            fetch(`/admin/bookings/${bookingId}/auto-cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCSRFToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    reason: 'auto_expire_payment',
                    expired_by: 'payment_expired_at'
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(`Booking ${bookingId} auto-expired based on payment_expired_at`);
                        // Show notification and reload
                        showAlert('info', 'Booking expired telah dibatalkan otomatis');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        console.warn(`Failed to auto-expire booking ${bookingId}:`, data.message);
                    }
                })
                .catch(error => {
                    console.error('Auto-expire error:', error);
                });
        }

        /**
         * Legacy check for expired bookings (fallback) - CHANGED TO 1 HOUR
         */
        function checkLegacyExpiredBookings() {
            const bookings = document.querySelectorAll('[data-booking-id]');

            bookings.forEach(booking => {
                const bookingId = booking.getAttribute('data-booking-id');
                const createdAt = booking.getAttribute('data-created-at');
                const paymentStatus = booking.getAttribute('data-payment-status');
                const bookingStatus = booking.getAttribute('data-booking-status');

                if (bookingId && createdAt && paymentStatus === 'unpaid' && bookingStatus !== 'cancelled') {
                    const createdDate = new Date(createdAt);
                    const now = new Date();
                    const hoursDiff = (now - createdDate) / (1000 * 60 * 60);

                    // Auto-cancel if booking is more than 1 hour old (changed from 6 hours)
                    if (hoursDiff > 1) {
                        fetch(`/admin/bookings/${bookingId}/auto-cancel`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCSRFToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                reason: 'auto_cancel_expired',
                                expired_hours: Math.floor(hoursDiff)
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log(`Booking ${bookingId} auto-cancelled after ${Math.floor(hoursDiff)} hours`);
                                    setTimeout(() => window.location.reload(), 1000);
                                }
                            })
                            .catch(error => console.error('Auto-cancel error:', error));
                    }
                }
            });
        }

        /**
         * Bulk auto-expire all expired bookings
         */
        function autoExpireAllBookings() {
            showLoading();

            fetch('/admin/bookings/auto-expire', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCSRFToken(),
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showAlert('success', data.message || 'Berhasil membatalkan booking expired');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showAlert('error', data.message || 'Gagal membatalkan booking expired');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Auto-expire all error:', error);
                    showAlert('error', 'Terjadi kesalahan saat membatalkan booking expired');
                });
        }

        /**
         * Enhanced payment status monitoring - FASTER INTERVAL
         */
        function monitorPaymentStatus(bookingId, interval = 10000) {
            if (!bookingId) return;

            const checkInterval = setInterval(() => {
                // Get current booking element
                const bookingElement = document.querySelector(`[data-booking-id="${bookingId}"]`);
                if (!bookingElement) {
                    clearInterval(checkInterval);
                    return;
                }

                const currentPaymentStatus = bookingElement.getAttribute('data-payment-status');
                const currentBookingStatus = bookingElement.getAttribute('data-booking-status');

                // Stop monitoring if already paid or cancelled
                if (currentPaymentStatus === 'paid' || currentBookingStatus === 'cancelled') {
                    clearInterval(checkInterval);
                    return;
                }

                // Silent check without modal
                fetch(`/admin/bookings/${bookingId}/payment-status`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.success) {
                            const newPaymentStatus = data.payment_status;
                            const newBookingStatus = data.booking_status;

                            // Check if status has changed
                            if (newPaymentStatus !== currentPaymentStatus || newBookingStatus !== currentBookingStatus) {
                                // Show notification about status change
                                const statusText = newPaymentStatus === 'paid' ? 'Pembayaran berhasil!' :
                                    newPaymentStatus === 'expired' ? 'Pembayaran expired!' :
                                        newBookingStatus === 'cancelled' ? 'Booking dibatalkan!' :
                                            'Status berubah!';

                                showAlert(newPaymentStatus === 'paid' ? 'success' : 'warning', statusText);

                                // Reload page after notification
                                setTimeout(() => window.location.reload(), 1500);
                                clearInterval(checkInterval);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Silent payment status check error:', error);
                    });
            }, interval);

            // Auto-clear interval after 2 hours
            setTimeout(() => clearInterval(checkInterval), 2 * 60 * 60 * 1000);

            return checkInterval;
        }

        /**
         * Show payment status modal
         */
        function showPaymentStatusModal(data) {
            const modalHtml = `
        <div class="modal fade" id="paymentStatusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-credit-card me-2"></i>Status Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-5"><strong>Status Pembayaran:</strong></div>
                            <div class="col-7">
                                <span class="badge bg-${getStatusBadgeClass(data.payment_status, 'payment')} fs-6">
                                    ${data.payment_status?.toUpperCase() || 'UNKNOWN'}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5"><strong>Status Booking:</strong></div>
                            <div class="col-7">
                                <span class="badge bg-${getStatusBadgeClass(data.booking_status, 'booking')} fs-6">
                                    ${data.booking_status?.toUpperCase() || 'UNKNOWN'}
                                </span>
                            </div>
                        </div>
                        ${data.message ? `<div class="alert alert-info">${data.message}</div>` : ''}
                        ${data.midtrans_error ? `<div class="alert alert-warning"><small>${data.midtrans_error}</small></div>` : ''}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="checkPaymentStatus(${data.booking_id})" data-bs-dismiss="modal">Refresh Status</button>
                    </div>
                </div>
            </div>
        </div>`;

            document.getElementById('paymentStatusModal')?.remove();
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            new bootstrap.Modal(document.getElementById('paymentStatusModal')).show();
        }

        /**
         * Utility functions
         */
        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        function handleResponse(response) {
            if (response.status === 401) {
                showAlert('error', 'Sesi berakhir. Login kembali.');
                setTimeout(() => window.location.href = '/login', 2000);
                throw new Error('Unauthorized');
            }
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        }

        function getStatusBadgeClass(status, type) {
            const classes = {
                payment: {
                    'paid': 'success',
                    'unpaid': 'secondary',
                    'pending': 'warning',
                    'failed': 'danger',
                    'expired': 'dark',
                    'cancelled': 'warning'
                },
                booking: {
                    'confirmed': 'success',
                    'pending': 'warning',
                    'cancelled': 'danger',
                    'completed': 'info'
                }
            };
            return classes[type]?.[status] || 'secondary';
        }

        function showLoading() {
            hideLoading();
            const loadingHtml = `
        <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
             style="background-color: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="text-center text-white">
                <div class="spinner-border text-light mb-3"></div>
                <div>Memproses...</div>
            </div>
        </div>`;
            document.body.insertAdjacentHTML('beforeend', loadingHtml);
            setTimeout(hideLoading, 30000); // Auto-hide after 30s
        }

        function hideLoading() {
            document.getElementById('loadingOverlay')?.remove();
        }

        function showAlert(type, message) {
            const alertClass = { success: 'alert-success', warning: 'alert-warning', error: 'alert-danger' }[type] || 'alert-info';
            const iconClass = { success: 'fa-check-circle', warning: 'fa-exclamation-triangle', error: 'fa-exclamation-circle' }[type] || 'fa-info-circle';

            const alertId = 'alert-' + Date.now();
            const alertHtml = `
        <div id="${alertId}" class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" 
             style="z-index: 10000; min-width: 300px; max-width: 500px;">
            <i class="fas ${iconClass} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;

            document.body.insertAdjacentHTML('afterbegin', alertHtml);
            setTimeout(() => document.getElementById(alertId)?.remove(), 8000);
        }

        /**
         * MODIFIED - AUTO CONFIRM BULK ACTIONS
         */
        function confirmBulkAction(action) {
            const checkedBoxes = document.querySelectorAll('input[name="selected_bookings[]"]:checked');
            if (checkedBoxes.length === 0) {
                showAlert('error', 'Pilih minimal satu booking');
                return false;
            }
            const actionText = { 'cancel': 'membatalkan', 'delete': 'menghapus' }[action];

            // AUTO CONFIRM - No user confirmation needed
            console.log(`Auto ${actionText} ${checkedBoxes.length} booking(s)`);
            return true; // Always return true to proceed
        }

        function toggleSelectAll(checkbox) {
            document.querySelectorAll('input[name="selected_bookings[]"]').forEach(cb => {
                cb.checked = checkbox.checked;
            });
        }

        /**
         * Initialize on DOM ready
         */
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                try { new bootstrap.Tooltip(el); } catch (e) { console.warn('Tooltip init failed:', e); }
            });

            // Initial check for expired bookings
            checkAndCancelExpiredBookings();

            // Auto-check every 2 minutes for expired bookings (more frequent)
            setInterval(checkAndCancelExpiredBookings, 120000);

            // Monitor payment status for unpaid bookings - FASTER MONITORING
            document.querySelectorAll('[data-booking-id]').forEach(element => {
                const bookingId = element.getAttribute('data-booking-id');
                const paymentStatus = element.getAttribute('data-payment-status');
                const bookingStatus = element.getAttribute('data-booking-status');

                // Start monitoring for unpaid and non-cancelled bookings
                if (paymentStatus === 'unpaid' && bookingStatus !== 'cancelled') {
                    monitorPaymentStatus(bookingId, 10000); // Check every 10 seconds
                }
            });

            // Auto-submit filter form with debounce
            const filterForm = document.querySelector('form[method="GET"]');
            if (filterForm) {
                let submitTimeout;
                filterForm.querySelectorAll('select, input[type="date"]').forEach(input => {
                    input.addEventListener('change', function () {
                        clearTimeout(submitTimeout);
                        submitTimeout = setTimeout(() => filterForm.submit(), 800);
                    });
                });
            }
        });

        /**
         * Notification system - FIXED URL PATH
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
                        'X-CSRF-TOKEN': getCSRFToken()
                    }
                });
                // FIXED - Keep admin path for chat
                window.location.href = `/admin/chat?user=${senderId}`;
            };

            loadNotifications();
            setInterval(loadNotifications, 30000); // Update every 30 seconds
        });
    </script>

</body>

</html>