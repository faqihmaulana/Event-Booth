<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Booking Booth - EventKu</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

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

    <!-- Custom CSS for Responsive Table -->
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
                min-width: 800px;
                /* Lebar minimum tabel */
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
                                    <a class="dropdown-item {{ request()->routeIs('admin.report*') ? 'active' : '' }}"
                                        href="{{ route('admin.report') }}">Rekap Laporan</a>
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

            <div class="container mt-4">
                <h2 class="mb-3">Manajemen User</h2>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Tombol Tambah -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah
                    Akun</button>

                <!-- Wrapper untuk Tabel Responsif -->
                <div class="table-responsive-custom">
                    <!-- Tabel User -->
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Produk</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $i => $user)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->company }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($user->role->name) }}</span></td>
                                    <td>
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="d-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus user ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Akun</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Tutup"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <label>Nama</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control"
                                                            value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Telepon</label>
                                                        <input type="text" name="phone" class="form-control"
                                                            value="{{ $user->phone }}">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Produk</label>
                                                        <input type="text" name="company" class="form-control"
                                                            value="{{ $user->company }}">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Role</label>
                                                        <select name="role_id" class="form-select" required>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                    {{ ucfirst($role->name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Status</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Perbarui</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Scroll Indicator -->
                <div class="scroll-indicator">
                    <i class="fas fa-arrows-alt-h me-1"></i>
                    Geser ke kanan untuk melihat kolom lainnya
                </div>
            </div>

            <!-- Modal Tambah -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Akun</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Nama</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label>Telepon</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label>Produk</label>
                                    <input type="text" name="company" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label>Role</label>
                                    <select name="role_id" class="form-select" required>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Bootstrap JS Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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


        <!-- Back to Top -->
        <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
    </div>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('admin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('admin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('admin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
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

    <!-- Template Javascript -->
    <script src="{{ asset('admin/js/main.js') }}"></script>
</body>

</html>