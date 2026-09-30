<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin - Manage Banner and Gallery</title>
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
    <link href="{{ asset('admin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        
        <!-- Loading Spinner -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
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
                        <img class="rounded-circle" src="{{ asset('admin/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <span>Admin</span>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <ul class="navbar-nav w-100">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manage-banner') }}" class="nav-link {{ request()->routeIs('manage-banner') ? 'active' : '' }}">
                            <i class="fa fa-image me-2"></i>Manage Banner
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manage-event') }}" class="nav-link {{ request()->routeIs('manage-event') ? 'active' : '' }}">
                            <i class="fa fa-calendar me-2"></i>Manage Event
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categori-booth') }}" class="nav-link {{ request()->routeIs('categori-booth') ? 'active' : '' }}">
                            <i class="fa fa-th me-2"></i>Categori Booth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('maps-booth') }}" class="nav-link {{ request()->routeIs('maps-booth') ? 'active' : '' }}">
                            <i class="fa fa-map me-2"></i>Maps Booth
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manage-user') }}" class="nav-link {{ request()->routeIs('manage-user') ? 'active' : '' }}">
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
                        <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('transaction') || request()->routeIs('admin.statistics*') || request()->is('laporan') ? 'active' : '' }}" 
                           id="laporanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-file-alt me-2"></i>Laporan
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('transaction') ? 'active' : '' }}" href="{{ route('transaction') }}">
                                    Transaksi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.statistics*') ? 'active' : '' }}" href="{{ route('admin.statistics') }}">
                                    Statistik
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('admin.report*') ? 'active' : '' }}" href="{{ route('admin.report') }}">
                                    Rekap Laporan
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="content">
            
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="{{ route('dashboard') }}" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>

                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>

                <div class="navbar-nav align-items-center ms-auto">
                    
                    <!-- Messages Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" id="messageDropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Pesan</span>
                            <span class="badge bg-danger rounded-pill ms-1" id="unreadBadge" style="display: none;">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0" 
                             id="notificationDropdown" style="min-width: 350px; max-height: 400px; overflow-y: auto;">
                            
                            <!-- Loading State -->
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
                            <a href="{{ route('chat') }}" class="dropdown-item text-center" id="viewAllMessages" style="display: none;">
                                <strong>Lihat semua pesan</strong>
                            </a>
                        </div>
                    </div>
                    
                    <!-- User Profile Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-2" src="{{ asset('admin/img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
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

            <!-- Page Content -->
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Manajemen Banner & Galeri</h6>

                    <!-- Success/Error Notifications -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#editHeroModal">
                            <i class="fas fa-edit me-1"></i> Edit Banner
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                            <i class="fas fa-plus me-1"></i> Tambah Galeri
                        </button>
                    </div>

                    <!-- Banner Management Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Tanggal Acara</th>
                                    <th>Lokasi</th>
                                    <th>Countdown</th>
                                    <th>Gambar Banner</th>
                                    <th>Galeri</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ $banner->title ?? 'Belum disetel' }}</td>
                                    <td>
                                        @if(isset($banner) && $banner && $banner->event_date)
                                            {{ $banner->event_date->format('d M Y') }}
                                        @else
                                            Belum disetel
                                        @endif
                                    </td>
                                    <td>{{ $banner->event_location ?? 'Belum disetel' }}</td>
                                    <td>
                                        @if(isset($banner->countdown_enabled))
                                            <span class="badge bg-{{ $banner->countdown_enabled ? 'success' : 'danger' }}">
                                                {{ $banner->countdown_enabled ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning">Belum Disetel</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($banner->image_path))
                                            <img src="{{ asset('storage/banners/' . $banner->image_path) }}" alt="Hero Image" width="80" class="rounded">
                                        @else
                                            <span class="text-muted">Tidak ada gambar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($banner) && $banner->galleries && $banner->galleries->count() > 0)
                                            <span class="badge bg-info">{{ $banner->galleries->count() }} Gambar</span>
                                        @else
                                            <span class="text-muted">Tidak ada galeri</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editHeroModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Current Gallery Display -->
                    @if(isset($banner) && $banner->galleries && $banner->galleries->count() > 0)
                        <div class="mt-4">
                            <h6 class="mb-3">Galeri Saat Ini</h6>
                            <div class="row" id="galleryContainer">
                                @foreach($banner->galleries->sortBy('sort_order') as $gallery)
                                    <div class="col-md-3 mb-3 gallery-item" data-id="{{ $gallery->id }}">
                                        <div class="card">
                                            <img src="{{ asset('storage/galleries/' . $gallery->image_path) }}" 
                                                 class="card-img-top" style="height: 150px; object-fit: cover;" 
                                                 alt="{{ $gallery->alt_text }}">
                                            <div class="card-body p-2">
                                                <small class="text-muted d-block">{{ $gallery->title ?? 'Tanpa judul' }}</small>
                                                <div class="btn-group btn-group-sm mt-1" role="group">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="editGallery({{ $gallery->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteGallery({{ $gallery->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Banner -->
    <div class="modal fade" id="editHeroModal" tabindex="-1" aria-labelledby="editHeroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHeroModalLabel">Edit Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('update-banner') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Banner Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Informasi Banner</h6>
                                
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Banner</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="{{ $banner->title ?? 'Tea Fiesta & Food Bazar' }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="event_date" class="form-label">Tanggal Acara</label>
                                    <input type="date" class="form-control" id="event_date" name="event_date" 
                                           value="{{ $banner->event_date ?? '2025-06-26' }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="event_location" class="form-label">Lokasi Acara</label>
                                    <input type="text" class="form-control" id="event_location" name="event_location" 
                                           value="{{ $banner->event_location ?? 'Jl. Pancasila, Alun-alun Tegal' }}">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Status Countdown</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="countdown_enabled" 
                                               id="countdown_enabled" value="1" 
                                               {{ ($banner->countdown_enabled ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="countdown_enabled">Aktif</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="countdown_enabled" 
                                               id="countdown_disabled" value="0" 
                                               {{ !($banner->countdown_enabled ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="countdown_disabled">Tidak Aktif</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Banner Image -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Gambar Banner</h6>
                                
                                @if(isset($banner->image_path))
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/banners/' . $banner->image_path) }}" 
                                             alt="Current Banner" class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="banner_image" class="form-label">Upload Banner Baru</label>
                                    <input type="file" class="form-control" id="banner_image" name="banner_image" accept="image/*">
                                    <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="bannerForm" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Add Gallery -->
    <div class="modal fade" id="addGalleryModal" tabindex="-1" aria-labelledby="addGalleryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGalleryModalLabel">Tambah Gambar Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addGalleryForm" enctype="multipart/form-data">
                        @csrf
                        <div id="newGalleryInputs">
                            <div class="gallery-input-group mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Gambar <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="gallery_images[]" accept="image/*" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Judul</label>
                                        <input type="text" class="form-control" name="gallery_titles[]" placeholder="Judul gambar">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Deskripsi</label>
                                        <input type="text" class="form-control" name="gallery_descriptions[]" placeholder="Deskripsi singkat">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-new-gallery-input" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addNewGalleryInput">
                            <i class="fas fa-plus me-1"></i> Tambah Input Lainnya
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="submitNewGallery">
                        <i class="fas fa-save me-1"></i> Tambah Galeri
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Edit Gallery -->
    <div class="modal fade" id="editGalleryModal" tabindex="-1" aria-labelledby="editGalleryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGalleryModalLabel">Edit Gambar Galeri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editGalleryForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editGalleryId" name="gallery_id">
                        
                        <div class="mb-3">
                            <label for="editGalleryTitle" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="editGalleryTitle" name="title">
                        </div>
                        
                        <div class="mb-3">
                            <label for="editGalleryDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editGalleryDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editGalleryImage" class="form-label">Ganti Gambar</label>
                            <input type="file" class="form-control" id="editGalleryImage" name="image" accept="image/*">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar</div>
                        </div>
                        
                        <div class="mb-3">
                            <img id="currentGalleryImage" src="" alt="Current Image" class="img-fluid rounded" style="max-height: 200px; display: none;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="updateGallery">
                        <i class="fas fa-save me-1"></i> Update Galeri
                    </button>
                </div>
            </div>
        </div>
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
    <script src="{{ asset('admin/js/main.js') }}"></script>

    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Set minimum date for event date input
            const today = new Date().toISOString().split('T')[0];
            $('#event_date').attr('min', today);
            
            // Function to get original date from server
            function getOriginalDate() {
                return $('#event_date').prop('defaultValue') || $('#event_date').val();
            }
            
            // Date validation
            $('#event_date').on('change', function() {
                const selectedDate = $(this).val();
                const originalDate = getOriginalDate();
                
                if (selectedDate && selectedDate < today) {
                    alert('Tanggal acara tidak boleh kurang dari hari ini!');
                    $(this).val(originalDate);
                }
            });

            // Add new gallery input in modal
            $('#addNewGalleryInput').click(function() {
                const newInput = `
                    <div class="gallery-input-group mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Gambar <span class="text-danger">*</span></label>
                                <input type="file" class="form-control gallery-image-input" name="gallery_images[]" accept="image/*" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Judul</label>
                                <input type="text" class="form-control" name="gallery_titles[]" placeholder="Judul gambar">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" name="gallery_descriptions[]" placeholder="Deskripsi singkat">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-new-gallery-input">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#newGalleryInputs').append(newInput);
                updateNewRemoveButtons();
            });

            // Remove gallery input
            $(document).on('click', '.remove-new-gallery-input', function() {
                $(this).closest('.gallery-input-group').remove();
                updateNewRemoveButtons();
            });

            function updateNewRemoveButtons() {
                const groups = $('#newGalleryInputs .gallery-input-group');
                groups.find('.remove-new-gallery-input').prop('disabled', groups.length <= 1);
            }

            // Gallery form validation
            function validateGalleryForm() {
                let isValid = true;
                let errorMessage = '';
                
                // Check if any image file is selected
                const fileInputs = $('#addGalleryForm input[type="file"]');
                let hasFile = false;
                
                fileInputs.each(function() {
                    if (this.files && this.files.length > 0) {
                        hasFile = true;
                        return false; // break loop
                    }
                });
                
                if (!hasFile) {
                    isValid = false;
                    errorMessage = 'Minimal harus memilih satu gambar untuk ditambahkan!';
                }
                
                // Validate file size (max 2MB per file)
                fileInputs.each(function() {
                    if (this.files && this.files.length > 0) {
                        const file = this.files[0];
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        
                        if (file.size > maxSize) {
                            isValid = false;
                            errorMessage = `File "${file.name}" terlalu besar. Maksimal 2MB per file.`;
                            return false;
                        }
                        
                        // Validate file type
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            isValid = false;
                            errorMessage = `File "${file.name}" bukan format gambar yang valid. Gunakan JPG, PNG, atau GIF.`;
                            return false;
                        }
                    }
                });
                
                if (!isValid) {
                    alert(errorMessage);
                }
                
                return isValid;
            }

            // Submit new gallery with validation
            $('#submitNewGallery').click(function() {
                // Validate form first
                if (!validateGalleryForm()) {
                    return;
                }
                
                // Show loading
                const button = $(this);
                const originalText = button.html();
                button.html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
                button.prop('disabled', true);
                
                const formData = new FormData($('#addGalleryForm')[0]);
                
                $.ajax({
                    url: "{{ route('add-gallery-images') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Galeri berhasil ditambahkan!');
                            $('#addGalleryForm')[0].reset();
                            $('#addGalleryModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        let errorMessage = 'Terjadi kesalahan saat menambah galeri';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join(', ');
                        }
                        
                        alert(errorMessage);
                    },
                    complete: function() {
                        // Restore button to original state
                        button.html(originalText);
                        button.prop('disabled', false);
                    }
                });
            });

            // Reset form when modal is closed
            $('#addGalleryModal').on('hidden.bs.modal', function() {
                $('#addGalleryForm')[0].reset();
                
                // Reset to single input only
                const firstInput = $('#newGalleryInputs .gallery-input-group').first();
                $('#newGalleryInputs').html(firstInput.prop('outerHTML'));
                updateNewRemoveButtons();
            });

            // Banner form validation
            $('#bannerForm').on('submit', function(e) {
                const eventDate = $('#event_date').val();
                
                if (eventDate && eventDate < today) {
                    e.preventDefault();
                    alert('Tanggal acara tidak boleh kurang dari hari ini!');
                    $('#event_date').focus();
                    return false;
                }
                
                // Show loading on submit button
                const submitButton = $('button[type="submit"][form="bannerForm"]');
                const originalText = submitButton.html();
                submitButton.html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
                submitButton.prop('disabled', true);
                
                // Form will submit normally, but we can add callback
                setTimeout(function() {
                    submitButton.html(originalText);
                    submitButton.prop('disabled', false);
                }, 3000);
            });

            // File input validation for image preview
            $(document).on('change', 'input[type="file"]', function() {
                const file = this.files[0];
                if (file) {
                    // Validate file size
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak valid. Gunakan JPG, PNG, atau GIF.');
                        this.value = '';
                        return;
                    }
                }
            });
        });

        // Edit gallery function
        function editGallery(id) {
            // Get gallery data via AJAX
            $.ajax({
                url: `/admin/gallery/${id}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        $('#editGalleryId').val(data.id);
                        $('#editGalleryTitle').val(data.title);
                        $('#editGalleryDescription').val(data.description);
                        
                        if (data.image_path) {
                            $('#currentGalleryImage').attr('src', `/storage/galleries/${data.image_path}`).show();
                        }
                        
                        $('#editGalleryModal').modal('show');
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.responseText);
                    alert('Gagal mengambil data galeri');
                }
            });
        }

        // Update gallery
        $('#updateGallery').click(function() {
            const galleryId = $('#editGalleryId').val();
            const formData = new FormData($('#editGalleryForm')[0]);
            
            // Show loading
            const button = $(this);
            const originalText = button.html();
            button.html('<i class="fas fa-spinner fa-spin me-1"></i> Memperbarui...');
            button.prop('disabled', true);
            
            // Add method spoofing for Laravel
            formData.append('_method', 'POST');
            
            $.ajax({
                url: `/admin/gallery/${galleryId}`,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        alert('Galeri berhasil diperbarui!');
                        $('#editGalleryModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat memperbarui galeri';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                },
                complete: function() {
                    // Restore button to original state
                    button.html(originalText);
                    button.prop('disabled', false);
                }
            });
        });

        // Delete gallery function
        function deleteGallery(id) {
            if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
                $.ajax({
                    url: `/admin/gallery/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Gambar berhasil dihapus!');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        alert('Terjadi kesalahan saat menghapus gambar');
                    }
                });
            }
        }
    </script>
</body>
</html>