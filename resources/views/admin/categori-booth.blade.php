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

            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Alert -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Categori Booth</h6>

                    <!-- Tombol Tambah -->
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#boothModal"
                            onclick="openAddModal()">
                            <i class="fas fa-plus me-1"></i> Tambah Booth
                        </button>
                    </div>

                    <!-- Tabel Booth -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Event</th>
                                    <th>Nama Booth</th>
                                    <th>Harga</th>
                                    <th>Type</th>
                                    <th>Fasilitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categoris as $index => $categori)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $categori->event ? $categori->event->main_title : 'No Event' }}
                                            </span>
                                        </td>
                                        <td>{{ $categori->name }}</td>
                                        <td>{{ $categori->price }}</td>
                                        <td>{{ $categori->subtitle }}</td>
                                        <td>
                                            <ul class="mb-0">
                                                @foreach (explode(',', $categori->facilities) as $facility)
                                                    <li>{{ trim($facility) }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick='openEditModal(@json($categori))'
                                                data-bs-toggle="modal" data-bs-target="#boothModal">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('booth.destroy', $categori->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirmDelete()">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(count($categoris) === 0)
                                    <tr>
                                        <td colspan="7" class="text-center">Data booth kosong</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah/Edit Booth -->
            <div class="modal fade" id="boothModal" tabindex="-1" aria-labelledby="boothModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form id="boothForm" method="POST" action="{{ route('booth.store') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="boothModalLabel">Tambah Booth</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="boothId" name="boothId" />
                                <div class="row">
                                    <!-- Event Selection -->
                                    <div class="mb-3 col-md-12">
                                        <label for="event_id" class="form-label">Select Event</label>
                                        <select class="form-select" id="event_id" name="event_id" required>
                                            <option value="">-- Select an Event --</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}">
                                                    {{ $event->main_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="boothName" class="form-label">Nama Booth</label>
                                        <input type="text" class="form-control" id="boothName" name="name" required>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="boothPrice" class="form-label">Harga (misal: Rp 2.000.000)</label>
                                        <input type="text" class="form-control" id="boothPrice" name="price" required>
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="boothSubtitle" class="form-label">Type booth / Keterangan
                                            Singkat</label>
                                        <input type="text" class="form-control" id="boothSubtitle" name="subtitle"
                                            required>
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="boothFacilities" class="form-label">Fasilitas (pisahkan dengan
                                            koma)</label>
                                        <textarea class="form-control" id="boothFacilities" name="facilities" rows="3"
                                            placeholder="Contoh: Lokasi strategis, Dekat panggung utama, Termasuk listrik & meja"
                                            required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                            </div>
                        </form>
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

        <!-- Back to Top -->
        <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/lib/chart/chart.min.js') }}"></script>
    <script src="{{ asset('admin/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('admin/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('admin/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('admin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('admin/js/main.js') }}"></script>

    <script>
        // Modal Functions
        function openAddModal() {
            document.getElementById('boothModalLabel').innerText = 'Tambah Booth';
            const form = document.getElementById('boothForm');
            form.action = "{{ route('booth.store') }}";
            form.method = 'POST';
            document.getElementById('boothId').value = '';
            document.getElementById('event_id').value = '';
            document.getElementById('boothName').value = '';
            document.getElementById('boothPrice').value = '';
            document.getElementById('boothSubtitle').value = '';
            document.getElementById('boothFacilities').value = '';
            clearMethodInput();
        }

        function openEditModal(booth) {
            document.getElementById('boothModalLabel').innerText = 'Edit Booth';
            const form = document.getElementById('boothForm');
            form.action = `/categori-booth/${booth.id}`;
            form.method = 'POST';
            setMethodInput('PUT');

            document.getElementById('boothId').value = booth.id;
            document.getElementById('event_id').value = booth.event_id || '';
            document.getElementById('boothName').value = booth.name;
            document.getElementById('boothPrice').value = booth.price;
            document.getElementById('boothSubtitle').value = booth.subtitle ?? '';
            document.getElementById('boothFacilities').value = booth.facilities ?? '';
        }

        function setMethodInput(method) {
            clearMethodInput();
            const form = document.getElementById('boothForm');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_method';
            input.value = method;
            input.id = '_method_input';
            form.appendChild(input);
        }

        function clearMethodInput() {
            const existing = document.getElementById('_method_input');
            if (existing) existing.remove();
        }

        // Confirm delete function
        function confirmDelete() {
            return confirm('Apakah Anda yakin ingin menghapus booth ini?');
        }

        // Alert notification handling (Bootstrap alerts - no popup)
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide Bootstrap alerts after 5 seconds
            setTimeout(function () {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function (alert) {
                    if (alert.querySelector('.btn-close')) {
                        alert.querySelector('.btn-close').click();
                    }
                });
            }, 5000);
        });

        // Form submission with loading state
        document.getElementById('boothForm').addEventListener('submit', function (e) {
            const submitBtn = document.getElementById('btnSave');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            submitBtn.disabled = true;

            // Re-enable button after 3 seconds in case of error
            setTimeout(function () {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Notification Functions
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

</body>

</html>