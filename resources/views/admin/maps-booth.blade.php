<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Booking Booth - EventKu</title>
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
    <link href="{{ asset('admin/css/layout.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
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
        <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('transaction') || request()->routeIs('admin.statistics*') || request()->is('laporan') ? 'active' : '' }}"
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

            <!-- Main Content -->
            <div class="container-fluid pt-4 px-4">
                <div class="row">
                    <div class="col-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Manajemen Booth - Drag & Drop Layout</h6>

                            <!-- Event Selection -->
                            <div class="event-selection mb-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="event_id" class="form-label">Select Event:</label>
                                        <select class="form-select" id="event_id" name="event_id">
                                            <option value="">-- Select an Event --</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}" {{ $eventId == $event->id ? 'selected' : '' }}>
                                                    {{ $event->main_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Control Panel -->
                            <div class="control-panel mb-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <button class="btn btn-primary" onclick="addNewBooth()">
                                            <i class="fa fa-plus"></i> Tambah Booth
                                        </button>
                                        <button class="btn btn-success" onclick="saveLayout()">
                                            <i class="fa fa-save"></i> Simpan Layout
                                        </button>
                                        <button class="btn btn-warning" onclick="resetLayout()">
                                            <i class="fa fa-undo"></i> Reset Layout
                                        </button>
                                        <button class="btn btn-info" onclick="initializeBooths()">
                                            <i class="fa fa-database"></i> Inisialisasi Booths
                                        </button>
                                        <button class="btn btn-dark" onclick="toggleGrid()">
                                            <i class="fa fa-th"></i> Alihkan Grid
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="searchBooth"
                                                placeholder="Cari booth...">
                                            <button class="btn btn-outline-secondary" onclick="searchBooth()">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Legend -->
                            <div class="venue-legend">
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #FF69B4;"></div>
                                    <span>Section A </span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #4CAF50;"></div>
                                    <span>Section B </span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #FF9800;"></div>
                                    <span>Section C </span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #8D6E63;"></div>
                                    <span>Section D </span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #2196F3;"></div>
                                    <span>Section E </span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #F44336;"></div>
                                    <span>Section F </span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #FFF176;"></div>
                                    <span>Section T </span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-color" style="background-color: #dc3545;"></div>
                                    <span>Booked</span>
                                </div>
                            </div>

                            <!-- Venue Container -->
                            <div class="venue-container">
                                <div class="venue-layout" id="venueLayout">
                                    <!-- Stage Areas -->
                                    <div class="stage-area main-stage">Main Stage</div>
                                    <div class="stage-area vip-stage">Tenda VIP</div>

                                    <!-- Control Booth -->
                                    <div class="control-booth">
                                        <div>S<br>T—+—B<br>U</div>
                                    </div>

                                    <!-- Booths will be loaded here dynamically -->
                                </div>
                            </div>
                        </div>
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

    <!-- Add/Edit Booth Modal -->
    <div class="modal fade" id="boothModal" tabindex="-1" aria-labelledby="boothModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="boothModalLabel">Add New Booth</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="boothForm">
                        <div class="mb-3">
                            <label for="boothId" class="form-label">Booth ID *</label>
                            <input type="text" class="form-control" id="boothId" required>
                        </div>
                        <div class="mb-3">
                            <label for="boothName" class="form-label">Booth Name *</label>
                            <input type="text" class="form-control" id="boothName" required>
                        </div>
                        <div class="mb-3">
                            <label for="section" class="form-label">Section *</label>
                            <select class="form-control" id="section" required>
                                <option value="">Select Section</option>
                                <option value="A">Section A</option>
                                <option value="B">Section B</option>
                                <option value="C">Section C</option>
                                <option value="D">Section D</option>
                                <option value="E">Section E</option>
                                <option value="F">Section F</option>
                                <option value="T">Section T</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price (Rp) *</label>
                            <input type="number" class="form-control" id="price" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="width" class="form-label">Width (px)</label>
                                    <input type="number" class="form-control" id="width" value="25" min="20">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="height" class="form-label">Height (px)</label>
                                    <input type="number" class="form-control" id="height" value="25" min="20">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status">
                                <option value="available">Available</option>
                                <option value="booked">Booked</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteBooth" style="display: none;"
                        onclick="deleteBooth()">Delete</button>
                    <button type="button" class="btn btn-primary" onclick="saveBooth()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <i class="fa fa-spinner fa-spin"></i>
            <div class="mt-2">Loading...</div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/js/main.js') }}"></script>
    <script src="{{ asset('admin/js/layout.js') }}"></script>
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
</body>

</html>