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

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tabel Event -->
            <div class="container mt-5">
                <h2 class="mb-4">Manajemen Event</h2>

                <!-- TOMBOL TAMBAH -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#eventModal"
                    onclick="clearForm()">
                    + Tambah Event
                </button>

                <!-- TABEL EVENT -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Judul Utama</th>
                                <th>Judul Acara</th>
                                <th>Kategori</th>
                                <th>Speaker</th>
                                <th>Lokasi</th>
                                <th>Gambar Utama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $index => $event)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $event->main_title ?? '-' }}</td>
                                    <td>{{ $event->title ?? '-' }}</td>
                                    <td>{{ $event->category ?? '-' }}</td>
                                    <td>{{ $event->speaker ?? '-' }}</td>
                                    <td>{{ $event->location ?? '-' }}</td>
                                    <td>
                                        @if($event->main_image)
                                            <img src="{{ asset('storage/' . $event->main_image) }}" width="60"
                                                alt="Gambar Utama Event" class="img-thumbnail">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Detail -->
                                        <button class="btn btn-sm btn-info mb-1" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" onclick="viewDetail({{ json_encode($event) }})">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Tombol Edit -->
                                        <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal"
                                            data-bs-target="#eventModal" onclick="editEvent({{ json_encode($event) }})">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('event.destroy', $event->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data event</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL DETAIL -->
            <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel">Detail Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Event Utama -->
                            <div class="border rounded p-3 mb-4" style="background-color: #f8f9fa;">
                                <h6 class="text-primary mb-3"><i class="fas fa-calendar-alt"></i> Informasi Event Utama
                                </h6>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <strong>Judul Utama:</strong>
                                        <p id="detail_main_title" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <strong>Deskripsi Utama:</strong>
                                        <p id="detail_main_description" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <strong>Tanggal Mulai:</strong>
                                        <p id="detail_start_date" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <strong>Tanggal Selesai:</strong>
                                        <p id="detail_end_date" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <strong>Lokasi:</strong>
                                        <p id="detail_location" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <strong>Gambar Utama:</strong>
                                        <div id="detail_main_image" class="mt-2">-</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sub-Event -->
                            <div class="border rounded p-3" style="background-color: #fff8e1;">
                                <h6 class="text-warning mb-3"><i class="fas fa-calendar-check"></i> Informasi Acara</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>Kategori:</strong>
                                        <p id="detail_category" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Waktu:</strong>
                                        <p id="detail_time" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <strong>Judul Acara:</strong>
                                        <p id="detail_title" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <strong>Deskripsi Acara:</strong>
                                        <p id="detail_description" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Narasumber:</strong>
                                        <p id="detail_speaker" class="mb-0">-</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Posisi:</strong>
                                        <p id="detail_position" class="mb-0">-</p>
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

            <!-- MODAL TAMBAH/EDIT -->
            <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form id="eventForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="event_id" id="event_id">
                        <input type="hidden" name="time" id="hidden_time">
                        <div class="modal-content">
                            <div class="modal-header py-2">
                                <h6 class="modal-title" id="eventModalLabel">Tambah / Edit Event</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body p-3">
                                <!-- Event Information -->
                                <div class="border rounded p-3 mb-3" style="background-color: #f8f9fa;">
                                    <h6 class="text-primary mb-3 fs-6"><i class="fas fa-calendar-alt me-1"></i>Informasi Event</h6>
                                    <div class="row g-2">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="main_title" class="form-label small">Judul Utama *</label>
                                                <input type="text" class="form-control form-control-sm" id="main_title"
                                                    name="main_title" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="title" class="form-label small">Judul Acara *</label>
                                                <input type="text" class="form-control form-control-sm" id="title"
                                                    name="title" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="category" class="form-label small">Kategori *</label>
                                                <input type="text" class="form-control form-control-sm" id="category" 
                                                    name="category" placeholder="Masukkan kategori acara" required>
                                                <small class="text-muted">Contoh: Exhibition, Performing Art, Food Bazaar, Music, Workshop, Seminar</small>
                                            </div>
                                            
                                            <!-- Time Picker Section -->
                                            <div class="mb-3">
                                                <label class="form-label small">Waktu *</label>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label class="form-label small text-muted">Jam Mulai</label>
                                                        <div class="row g-1">
                                                            <div class="col-6">
                                                                <select class="form-select form-select-sm" id="start_hour" required>
                                                                    <option value="">Jam</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <select class="form-select form-select-sm" id="start_minute" required>
                                                                    <option value="">Menit</option>
                                                                    <option value="00">00</option>
                                                                    <option value="15">15</option>
                                                                    <option value="30">30</option>
                                                                    <option value="45">45</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small text-muted">Jam Selesai</label>
                                                        <div class="row g-1">
                                                            <div class="col-6">
                                                                <select class="form-select form-select-sm" id="end_hour" required>
                                                                    <option value="">Jam</option>
                                                                    <option value="00">00</option>
                                                                    <option value="01">01</option>
                                                                    <option value="02">02</option>
                                                                    <option value="03">03</option>
                                                                    <option value="04">04</option>
                                                                    <option value="05">05</option>
                                                                    <option value="06">06</option>
                                                                    <option value="07">07</option>
                                                                    <option value="08">08</option>
                                                                    <option value="09">09</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                    <option value="13">13</option>
                                                                    <option value="14">14</option>
                                                                    <option value="15">15</option>
                                                                    <option value="16">16</option>
                                                                    <option value="17">17</option>
                                                                    <option value="18">18</option>
                                                                    <option value="19">19</option>
                                                                    <option value="20">20</option>
                                                                    <option value="21">21</option>
                                                                    <option value="22">22</option>
                                                                    <option value="23">23</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <select class="form-select form-select-sm" id="end_minute" required>
                                                                    <option value="">Menit</option>
                                                                    <option value="00">00</option>
                                                                    <option value="15">15</option>
                                                                    <option value="30">30</option>
                                                                    <option value="45">45</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-muted">Pilih jam mulai dan jam selesai acara</small>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="location" class="form-label small">Lokasi *</label>
                                                <input type="text" class="form-control form-control-sm" id="location"
                                                    name="location" required>
                                            </div>
                                        </div>
                                        
                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="main_description" class="form-label small">Deskripsi Utama *</label>
                                                <textarea class="form-control form-control-sm" id="main_description"
                                                    name="main_description" rows="3" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label small">Deskripsi Acara *</label>
                                                <textarea class="form-control form-control-sm" id="description"
                                                    name="description" rows="3" required></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label for="start_date" class="form-label small">Tanggal Mulai *</label>
                                                    <input type="date" class="form-control form-control-sm" id="start_date"
                                                        name="start_date" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label for="end_date" class="form-label small">Tanggal Selesai *</label>
                                                    <input type="date" class="form-control form-control-sm" id="end_date"
                                                        name="end_date" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Speaker Section (Full Width) -->
                                        <div class="col-12">
                                            <div class="border-top pt-3">
                                                <h6 class="text-secondary mb-3 fs-6"><i class="fas fa-user-tie me-1"></i>Informasi Narasumber (Opsional)</h6>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="speaker" class="form-label small">Narasumber</label>
                                                        <input type="text" class="form-control form-control-sm" id="speaker"
                                                            name="speaker" placeholder="Nama narasumber">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="position" class="form-label small">Posisi/Jabatan</label>
                                                        <input type="text" class="form-control form-control-sm" id="position"
                                                            name="position" placeholder="Posisi atau jabatan">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Image Upload (Full Width) -->
                                        <div class="col-12">
                                            <div class="border-top pt-3">
                                                <label for="main_image" class="form-label small">Gambar Event</label>
                                                <input type="file" class="form-control form-control-sm" id="main_image"
                                                    name="main_image" accept="image/*">
                                                <small class="text-muted">Max 2MB - Format: JPG, PNG, GIF</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer py-2">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            </div>
                        </div>
                    </form>
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
        // Function to update hidden time field
        function updateTimeField() {
            const startHour = document.getElementById('start_hour').value;
            const startMinute = document.getElementById('start_minute').value;
            const endHour = document.getElementById('end_hour').value;
            const endMinute = document.getElementById('end_minute').value;
            
            if (startHour && startMinute && endHour && endMinute) {
                const timeString = `${startHour}:${startMinute} - ${endHour}:${endMinute} WIB`;
                document.getElementById('hidden_time').value = timeString;
            }
        }

        // Function to parse time string and set dropdowns
        function setTimeDropdowns(timeString) {
            if (!timeString) return;
            
            // Parse time format like "08:00 - 17:00 WIB" or "08:00-17:00"
            const timeMatch = timeString.match(/(\d{1,2}):(\d{2})\s*-\s*(\d{1,2}):(\d{2})/);
            
            if (timeMatch) {
                const [, startHour, startMinute, endHour, endMinute] = timeMatch;
                
                document.getElementById('start_hour').value = startHour.padStart(2, '0');
                document.getElementById('start_minute').value = startMinute;
                document.getElementById('end_hour').value = endHour.padStart(2, '0');
                document.getElementById('end_minute').value = endMinute;
                
                updateTimeField();
            }
        }

        // Add event listeners to time dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            const timeDropdowns = ['start_hour', 'start_minute', 'end_hour', 'end_minute'];
            
            timeDropdowns.forEach(id => {
                document.getElementById(id).addEventListener('change', updateTimeField);
            });
        });

        function clearForm() {
            document.getElementById('eventForm').reset();
            document.getElementById('eventForm').action = "{{ route('event.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('event_id').value = '';
            document.getElementById('eventModalLabel').textContent = 'Tambah Event';
            document.getElementById('hidden_time').value = '';
            
            // Clear time dropdowns
            document.getElementById('start_hour').value = '';
            document.getElementById('start_minute').value = '';
            document.getElementById('end_hour').value = '';
            document.getElementById('end_minute').value = '';
        }

        function editEvent(event) {
            clearForm();
            document.getElementById('eventForm').action = `/manage-event/${event.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('event_id').value = event.id;
            document.getElementById('eventModalLabel').textContent = 'Edit Event';

            // Fill event fields
            document.getElementById('main_title').value = event.main_title || '';
            document.getElementById('main_description').value = event.main_description || '';
            document.getElementById('start_date').value = event.start_date || '';
            document.getElementById('end_date').value = event.end_date || '';
            document.getElementById('location').value = event.location || '';
            document.getElementById('category').value = event.category || '';
            document.getElementById('title').value = event.title || '';
            document.getElementById('description').value = event.description || '';
            document.getElementById('speaker').value = event.speaker || '';
            document.getElementById('position').value = event.position || '';

            // Set time dropdowns
            if (event.time) {
                setTimeDropdowns(event.time);
            }
        }

        function viewDetail(event) {
            // Fill event details
            document.getElementById('detail_main_title').textContent = event.main_title || '-';
            document.getElementById('detail_main_description').textContent = event.main_description || '-';
            document.getElementById('detail_start_date').textContent = event.start_date || '-';
            document.getElementById('detail_end_date').textContent = event.end_date || '-';
            document.getElementById('detail_location').textContent = event.location || '-';
            document.getElementById('detail_category').textContent = event.category || '-';
            document.getElementById('detail_time').textContent = event.time || '-';
            document.getElementById('detail_title').textContent = event.title || '-';
            document.getElementById('detail_description').textContent = event.description || '-';
            document.getElementById('detail_speaker').textContent = event.speaker || '-';
            document.getElementById('detail_position').textContent = event.position || '-';

            // Handle main image
            const mainImageDiv = document.getElementById('detail_main_image');
            if (event.main_image) {
                mainImageDiv.innerHTML = `<img src="{{ asset('storage/') }}/${event.main_image}" class="img-fluid" style="max-width: 300px;" alt="Gambar Event">`;
            } else {
                mainImageDiv.innerHTML = '-';
            }
        }

        // Form validation
        function validateEventForm() {
            const requiredFields = [
                'main_title', 'main_description', 'start_date', 'end_date', 
                'location', 'category', 'title', 'description'
            ];
            let isValid = true;

            requiredFields.forEach(field => {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    element.classList.add('is-invalid');
                    isValid = false;
                } else {
                    element.classList.remove('is-invalid');
                }
            });

            // Validate time dropdowns
            const timeFields = ['start_hour', 'start_minute', 'end_hour', 'end_minute'];
            timeFields.forEach(field => {
                const element = document.getElementById(field);
                if (!element.value) {
                    element.classList.add('is-invalid');
                    isValid = false;
                } else {
                    element.classList.remove('is-invalid');
                }
            });

            // Validate time logic
            const startHour = parseInt(document.getElementById('start_hour').value);
            const startMinute = parseInt(document.getElementById('start_minute').value);
            const endHour = parseInt(document.getElementById('end_hour').value);
            const endMinute = parseInt(document.getElementById('end_minute').value);

            if (startHour && startMinute !== undefined && endHour && endMinute !== undefined) {
                const startTime = startHour * 60 + startMinute;
                const endTime = endHour * 60 + endMinute;

                if (startTime >= endTime) {
                    document.getElementById('end_hour').classList.add('is-invalid');
                    document.getElementById('end_minute').classList.add('is-invalid');
                    alert('Waktu selesai harus lebih besar dari waktu mulai');
                    isValid = false;
                }
            }

            // Validate date range
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                document.getElementById('end_date').classList.add('is-invalid');
                alert('Tanggal akhir tidak boleh lebih awal dari tanggal mulai');
                isValid = false;
            }

            return isValid;
        }

        // Image preview function
        function previewMainImage(input) {
            const preview = document.getElementById('main_image_preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (preview) {
                        preview.innerHTML = `<img src="${e.target.result}" class="img-fluid mt-2" style="max-width: 200px;" alt="Preview Gambar">`;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            } else if (preview) {
                preview.innerHTML = '';
            }
        }

        // Search function
        function searchEvents() {
            const searchTerm = document.getElementById('search_input').value.toLowerCase();
            const eventRows = document.querySelectorAll('tbody tr');

            eventRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let found = false;
                
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchTerm)) {
                        found = true;
                    }
                });

                if (found || searchTerm === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Notification functions
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
                    });
            }

            function updateNotificationDropdown(notifications) {
                const container = document.getElementById('notificationsContainer');
                const loading = document.getElementById('loadingNotifications');
                const noNotifications = document.getElementById('noNotifications');
                const divider = document.getElementById('notificationDivider');
                const viewAll = document.getElementById('viewAllMessages');

                if (!container) return;

                loading.style.display = 'none';

                if (notifications.data && notifications.data.length > 0) {
                    container.innerHTML = '';
                    notifications.data.forEach(notification => {
                        const item = document.createElement('a');
                        item.className = `dropdown-item ${notification.read_at ? '' : 'bg-light'}`;
                        item.href = notification.url || '#';
                        item.innerHTML = `
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 small">${notification.title || 'Notifikasi'}</h6>
                                    <p class="mb-1 small text-muted">${notification.message}</p>
                                    <small class="text-muted">${formatDate(notification.created_at)}</small>
                                </div>
                                ${!notification.read_at ? '<span class="badge bg-primary">Baru</span>' : ''}
                            </div>
                        `;
                        container.appendChild(item);
                    });

                    noNotifications.style.display = 'none';
                    divider.style.display = 'block';
                    viewAll.style.display = 'block';
                } else {
                    container.innerHTML = '';
                    noNotifications.style.display = 'block';
                    divider.style.display = 'none';
                    viewAll.style.display = 'none';
                }
            }

            function updateUnreadBadge(count) {
                const badge = document.getElementById('unreadBadge');
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffTime = Math.abs(now - date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays === 1) {
                    return 'Kemarin';
                } else if (diffDays < 7) {
                    return `${diffDays} hari lalu`;
                } else {
                    return date.toLocaleDateString('id-ID');
                }
            }

            // Initialize notifications
            loadNotifications();

            // Update notifications every 30 seconds
            notificationUpdateInterval = setInterval(loadNotifications, 30000);

            // Form submission handler
            const eventForm = document.getElementById('eventForm');
            if (eventForm) {
                eventForm.addEventListener('submit', function (e) {
                    if (!validateEventForm()) {
                        e.preventDefault();
                        return false;
                    }
                    
                    // Update the hidden time field before submission
                    updateTimeField();
                });
            }

            // Image upload handler
            const mainImageInput = document.getElementById('main_image');
            if (mainImageInput) {
                mainImageInput.addEventListener('change', function () {
                    previewMainImage(this);
                });
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });

        // Export events to CSV
        function exportEventsToCSV() {
            const events = @json($events ?? []);
            const csvContent = "data:text/csv;charset=utf-8,"
                + "Judul Utama,Deskripsi Utama,Judul Acara,Deskripsi Acara,Tanggal Mulai,Tanggal Akhir,Lokasi,Kategori,Waktu,Pembicara,Posisi\n"
                + events.map(event =>
                    `"${event.main_title || ''}","${event.main_description || ''}","${event.title || ''}","${event.description || ''}","${event.start_date || ''}","${event.end_date || ''}","${event.location || ''}","${event.category || ''}","${event.time || ''}","${event.speaker || ''}","${event.position || ''}"`
                ).join("\n");

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `events_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Print events
        function printEvents() {
            window.print();
        }
    </script>
</body>

</html>