@extends('layouts.admin')

@section('content')

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
                <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3 d-flex align-items-center">
                    <i class="fa fa-ticket-alt me-2 text-primary fs-4"></i>
                    <h4 class="mb-0 text-primary">EventKu</h4>
                </a>

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
                            <a href="{{ route('chat') }}" class="nav-link {{ request()->routeIs('chat') ? 'active' : '' }}">
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
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" id="messageDropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Pesan</span>
                            <!-- Badge for unread count -->
                            <span class="badge bg-danger rounded-pill ms-1" id="unreadBadge" style="display: none;">0</span>
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

            <!-- Dashboard Header -->
            <div class="container-fluid pt-4 px-4">
                <h1>Dashboard</h1>
                <p>Selamat datang, {{ Auth::user()->name }}!</p>
            </div>

            <!-- Statistics Cards Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Booking Hari Ini</p>
                                <h6 class="mb-0">{{ $todayBookings ?? 0 }} Booking</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Booking</p>
                                <h6 class="mb-0">{{ $totalBookings ?? 0 }} Booking</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-area fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Pendapatan Hari Ini</p>
                                <h6 class="mb-0">Rp{{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-pie fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Total Pendapatan</p>
                                <h6 class="mb-0">Rp{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistics Cards End -->

            <!-- Recent Bookings Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Booking Terbaru</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark">
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Produk</th>
                                    <th scope="col">Booth</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Payment</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings ?? [] as $booking)
                                    <tr>
                                        <td>{{ $booking->created_at->format('d M Y') }}</td>
                                        <td>
                                            @if($booking->order_id)
                                                {{ $booking->order_id }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $booking->company_name }}</td>
                                        <td>
                                            @if($booking->booth)
                                                {{ $booking->booth->booth_id ?? $booking->booth->name ?? 'Booth #' . $booking->booth->id }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($booking->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($booking->status == 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @elseif($booking->status == 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($booking->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($booking->payment_status == 'unpaid')
                                                <span class="badge bg-warning">Unpaid</span>
                                            @elseif($booking->payment_status == 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @elseif($booking->payment_status == 'expired')
                                                <span class="badge bg-secondary">Expired</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#detailModal{{ $booking->id }}">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada booking</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Recent Bookings End -->

            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="https://cresindo.com">Cresindo</a>, All Right Reserved.
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->

        <!-- Back to Top -->
        <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
    </div>

    <!-- Modal Detail Bookings -->
    @foreach($recentBookings ?? [] as $booking)
        <div class="modal fade" id="detailModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" value="{{ $booking->company_name }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Penanggung Jawab</label>
                                    <input type="text" class="form-control" value="{{ $booking->contact_person }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" value="{{ $booking->phone }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" value="{{ $booking->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Booth</label>
                                    <input type="text" class="form-control"
                                        value="{{ $booking->booth ? ($booking->booth->booth_id ?? $booking->booth->name ?? 'Booth #' . $booking->booth->id) : 'N/A' }}"
                                        readonly>
                                </div>
                                <!-- <div class="mb-3">
                                                    <label class="form-label">Tanggal Booking</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}" readonly>
                                                </div> -->
                                <div class="mb-3">
                                    <label class="form-label">Order ID</label>
                                    <input type="text" class="form-control" value="{{ $booking->order_id ?? 'N/A' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Transaction ID</label>
                                    <input type="text" class="form-control" value="{{ $booking->transaction_id ?? 'N/A' }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" rows="3" readonly>{{ $booking->description }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Total Harga</label>
                                    <input type="text" class="form-control"
                                        value="Rp {{ number_format($booking->total_price, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status Booking</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($booking->status) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Status Payment</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($booking->payment_status) }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <input type="text" class="form-control" value="{{ $booking->payment_method ?? 'N/A' }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Dibuat</label>
                                    <input type="text" class="form-control"
                                        value="{{ $booking->created_at->format('d M Y H:i') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Dibayar</label>
                                    <input type="text" class="form-control"
                                        value="{{ $booking->paid_at ? $booking->paid_at->format('d M Y H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Expired Payment</label>
                                    <input type="text" class="form-control"
                                        value="{{ $booking->payment_expired_at ? $booking->payment_expired_at->format('d M Y H:i') : 'N/A' }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let notificationUpdateInterval;

                // Function to load notifications
                function loadNotifications() {
                    fetch('/admin/chat/notifications')
                        .then(response => response.json())
                        .then(data => {
                            updateNotificationDropdown(data);
                            updateUnreadBadge(data.total_unread);
                        })
                        .catch(error => {
                            console.error('Error loading notifications:', error);
                            document.getElementById('loadingNotifications').style.display = 'none';
                            document.getElementById('noNotifications').style.display = 'block';
                        });
                }

                // Function to update notification dropdown content
                function updateNotificationDropdown(data) {
                    const loadingEl = document.getElementById('loadingNotifications');
                    const noNotificationsEl = document.getElementById('noNotifications');
                    const containerEl = document.getElementById('notificationsContainer');
                    const dividerEl = document.getElementById('notificationDivider');
                    const viewAllEl = document.getElementById('viewAllMessages');

                    loadingEl.style.display = 'none';

                    if (data.notifications.length === 0) {
                        noNotificationsEl.style.display = 'block';
                        containerEl.innerHTML = '';
                        dividerEl.style.display = 'none';
                        viewAllEl.style.display = 'none';
                    } else {
                        noNotificationsEl.style.display = 'none';
                        dividerEl.style.display = 'block';
                        viewAllEl.style.display = 'block';

                        let notificationsHtml = '';
                        data.notifications.forEach(function (notification, index) {
                            notificationsHtml += `
                                            <a href="#" class="dropdown-item notification-item unread" 
                                               data-message-id="${notification.id}" 
                                               data-sender-id="${notification.sender_id}"
                                               onclick="handleNotificationClick(${notification.sender_id}, ${notification.id})">
                                                <div class="d-flex align-items-center">
                                                    <img class="rounded-circle" src="{{ asset('admin/img/user.jpg') }}" alt=""
                                                        style="width: 40px; height: 40px;">
                                                    <div class="ms-2 flex-grow-1">
                                                        <h6 class="fw-normal mb-0 notification-message">
                                                            ${notification.sender_name} mengirim pesan
                                                            <span class="unread-dot"></span>
                                                        </h6>
                                                        <small class="notification-time">${notification.time_ago}</small>
                                                        <div class="mt-1">
                                                            <small class="text-muted">${notification.message}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        `;

                            if (index < data.notifications.length - 1) {
                                notificationsHtml += '<hr class="dropdown-divider">';
                            }
                        });

                        containerEl.innerHTML = notificationsHtml;
                    }
                }

                // Function to update unread badge
                function updateUnreadBadge(count) {
                    const badge = document.getElementById('unreadBadge');
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }

                // Function to handle notification click
                window.handleNotificationClick = function (senderId, messageId) {
                    // Mark as read
                    fetch(`/admin/chat/notifications/${messageId}/read`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    // Redirect to chat with specific user
                    window.location.href = `/chat?user=${senderId}`;
                };

                // Load notifications on page load
                loadNotifications();

                // Update notifications every 30 seconds
                notificationUpdateInterval = setInterval(loadNotifications, 30000);

                // Update notifications when dropdown is opened
                document.getElementById('messageDropdown').addEventListener('click', function (e) {
                    if (!e.target.closest('.dropdown-menu')) {
                        loadNotifications();
                    }
                });

                // Clear interval when page unloads
                window.addEventListener('beforeunload', function () {
                    if (notificationUpdateInterval) {
                        clearInterval(notificationUpdateInterval);
                    }
                });
            });

            // Optional: Add sound notification for new messages
            function playNotificationSound() {
                // Create audio element for notification sound
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmofBDuQ2O/JdSEEJnTF8N2QQAoUXrPp66pWGApFm9+zusIeBTyL2+/JdSEEJnTF8N2QQAoUXrPp66pWGApFm9+zusIeBT6L2+/JdSEEJnTC8d+PPgcTY7rs5J9NEA1MpeTztmMcBjiS2O7KdSEEJnTE8t2PPgcTY7vs5J9OEQ1MpOTytmMcBjiS2O7KdSEEJnTH8tyOPQcSaLfr6KNZGApEm+DwvmsgBDmR2e7KdSEEJnTH8tyOPQcSaLfr6KNZGApEm+DwvmsgBDmS2e7KdSEEJnTH8tyOPQcSZ7jq6KRaGQlDmt/wvmwdBTmS2e7KdSEEJnTG89qOPAcSZ7fr6KRaGQlDmt/wvmwdBTmS2e7KdSEEJnTG89qOPAcSZ7fr6KRaGQlCmtC88twJgZTy3c2BaYR3GRZ2qfq+qg0PxN8jFZDrvVXGAEFV2bnwQTdwP8Ny7AJ1OlYQHgJXa1yoFhFgLJvt9lP2mRGjjhOD8CGDZ7vwGYKDq/dWQTh7Vb5T+6NeEzPpyI8IgZa3PZZjOQGCnAcFdlhcT0VZ7J5lO0JQGhE9eQG8m7FqNyJPaYIBYAYQFBGnx3mV2bQATIj6j9meTq4EZP7oNZlG');
                audio.play().catch(e => console.log('Could not play notification sound'));
            }
        </script>
    @endforeach

@endsection