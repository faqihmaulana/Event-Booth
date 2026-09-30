<!DOCTYPE html>
<html lang="id">

<head>
	<title>Booking Booth - EventKu</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="event-id" content="{{ $event_id ?? '' }}">

	<link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/denah.css">
	<link rel="stylesheet" href="{{ asset('css/eventku-3d.css') }}">
	<link href="{{ asset('admin/img/favicon.png') }}" rel="icon">

	<style>
		.auto-selection-container {
			background: #6366F1;
			padding: 20px;
			border-radius: 12px;
			margin-bottom: 30px;
			color: white;
		}

		.section-title {
			text-align: center;
			font-size: 1.4rem;
			font-weight: 600;
			margin-bottom: 20px;
		}

		.btn-selection {
			border: none;
			border-radius: 8px;
			padding: 10px 16px;
			font-weight: 500;
			font-size: 1rem;
			transition: all 0.3s ease;
			box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
		}

		.btn-selection:hover {
			transform: translateY(-1px);
			box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
		}

		.btn-primary {
			background: #6366F1;
			color: white;
			padding: 12px 20px;
		}

		/* Premium - Warna Oren */
		.btn-premium {
			background: #FF8C00;
			color: white;
		}

		.btn-premium:hover {
			background: #FF7F00;
		}

		/* Standard - Warna Merah */
		.btn-standard {
			background: #DC2626;
			color: white;
		}

		.btn-standard:hover {
			background: #B91C1C;
		}

		/* Economy - Warna Hijau Muda */
		.btn-economy {
			background: #4ADE80;
			color: white;
		}

		.btn-economy:hover {
			background: #22C55E;
		}

		.info-card {
			background: rgba(255, 255, 255, 0.2);
			backdrop-filter: blur(8px);
			border-radius: 10px;
			padding: 16px;
			margin-bottom: 15px;
			border: 1px solid rgba(255, 255, 255, 0.3);
			color: #fff;
		}

		.info-header {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 10px;
			font-size: 1rem;
		}

		.info-list {
			list-style: none;
			padding: 0;
			margin: 0;
		}

		.info-list li {
			padding: 4px 0;
			font-size: 0.9rem;
		}

		.info-list .price {
			font-weight: bold;
			font-size: 1rem;
			color: #ffeb3b;
			margin-top: 10px;
			padding-top: 10px;
			border-top: 1px solid rgba(255, 255, 255, 0.2);
		}

		.algorithm-info {
			background: rgba(255, 255, 255, 0.1);
			border-radius: 10px;
			padding: 15px;
			margin-top: 20px;
			font-size: 0.9rem;
			line-height: 1.5;
		}

		.booth.auto-selected {
			border: 3px solid #ffd700 !important;
			box-shadow: 0 0 15px rgba(255, 215, 0, 0.7) !important;
			animation: pulse 2s infinite;
		}

		@keyframes pulse {
			0% {
				transform: scale(1);
			}

			50% {
				transform: scale(1.05);
			}

			100% {
				transform: scale(1);
			}
		}

		/* Modal customization */
		.modal-header {
			background: #6366F1;
			color: white;
			border-radius: 10px 10px 0 0;
		}

		.modal-header .btn-close {
			filter: invert(1);
		}

		.form-check-input:checked {
			background-color: #6366F1;
			border-color: #6366F1;
		}

		/* Adjust overly large buttons */
		button.btn-confirm,
		button.btn-recommend {
			font-size: 1rem;
			padding: 10px 20px;
			border-radius: 8px;
		}

		/* Scrollable modal content */
		.modal-body {
			max-height: 70vh;
			overflow-y: auto;
		}

		/* Tooltip styling */
		#boothTooltip {
			background: rgba(0, 0, 0, 0.85);
			color: white;
			padding: 10px;
			border-radius: 8px;
			font-size: 0.85rem;
			max-width: 200px;
			z-index: 1000;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
		}

		#boothTooltip .badge {
			font-size: 0.7rem;
			margin: 1px;
		}

		/* Responsive Design */
		@media (max-width: 768px) {
			.auto-selection-container {
				padding: 15px;
			}

			.section-title {
				font-size: 1.2rem;
			}

			.btn-selection {
				padding: 10px 12px;
				font-size: 0.9rem;
			}

			.info-card {
				padding: 12px;
			}
		}
	</style>
	<style>
		/* Sembunyikan semua info card */
		.info-cards>div {
			display: none;
		}

		/* Tampilkan info card sesuai radio button yang dipilih */
		#premium:checked~.info-cards .card-premium,
		#strategic:checked~.info-cards .card-strategic,
		#basic:checked~.info-cards .card-basic {
			display: block;
		}

		.hidden-radio {
			display: none;
		}

		.btn-selection {
			cursor: pointer;
		}
	</style>


	<!-- Tooltip element -->
	<div id="boothTooltip" style="position: absolute; display: none; pointer-events: none;"></div>

	<!-- Midtrans Snap -->
	<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
		data-client-key="{{ config('midtrans.client_key') }}"></script>
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
		<div class="container">
			<a class="navbar-brand" href="{{ route('home') }}">Event<span>Ku.</span></a>
		</div>
	</nav>

	<section class="hero-wrap hero-wrap-2 js-fullheight"
		style="background-image: url('{{ asset('storage/banners/' . ($banner->image_path ?? 'default.jpg')) }}');"
		data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
				<div class="col-md-9 ftco-animate pb-5">
					<h1 class="mb-3 bread">Booking Booth</h1>
					<p class="breadcrumbs">
						<span class="mr-2">
							<a href="{{ route('home') }}">Home <i class="ion-ios-arrow-forward"></i></a>
						</span>
						<span>Booking Booth <i class="ion-ios-arrow-forward"></i></span>
						@if(isset($event))
							<span>{{ $event->name }} <i class="ion-ios-arrow-forward"></i></span>
						@endif
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Event Info Section -->
	@if(isset($event))
		<section class="py-4 bg-light">
			<div class="container">
				<div class="alert alert-info">
					<div class="row align-items-center">
						<div class="col-md-8">
							<h5 class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $event->name }}</h5>
							<p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $event->location }}</p>
							<p class="mb-0"><i
									class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
								- {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}</p>
						</div>
						<div class="col-md-4 text-end">
							<span class="badge bg-primary fs-6">Event ID: {{ $event->id }}</span>
						</div>
					</div>
				</div>
			</div>
		</section>
	@endif

	<!-- Booth Map Section -->
	<section class="booth-map-container">
		<div class="container-fluid px-4">
			<div class="row">
				<div class="col-12">
					<div class="section-info">
						<h2 class="text-center mb-4">Pilih Booth Anda</h2>
						<p class="text-center text-muted">
							@if(isset($event))
								Klik pada booth yang tersedia untuk event "{{ $event->name }}".
							@else
								Klik pada booth yang tersedia untuk melakukan booking.
							@endif
						</p>
						<div class="section-stats" id="sectionStats"></div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="booth-map-wrapper">
						<div class="booth-map" id="boothMap">
							<div class="loading">
								<div class="spinner-border text-primary" role="status">
									<span class="visually-hidden">Loading...</span>
								</div>
								<p class="mt-2">Memuat peta booth...</p>
							</div>

							<div class="venue-container">
								<div class="venue-layout" id="venueLayout">
									<div class="stage-area main-stage">Main Stage</div>
									<div class="stage-area vip-stage">Tenda VIP</div>
									<div class="control-booth">
										<div>S<br>T—+—B<br>U</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Legend Panel -->
						<div class="legend-panel">
							<h6 class="legend-title">
								<i class="bi bi-info-circle-fill me-2"></i>
								Keterangan: <span class="text-danger">Merah</span> = Terboking/Proses,
								<span class="text-secondary">Abu-abu</span> = Maintenance
							</h6>
							<div class="legend-item">
								<div class="legend-color" style="background-color: #dc3545;"></div>
								<div class="legend-text">Sudah Dibooking</div>
							</div>
							<div class="legend-item">
								<div class="legend-color" style="background-color: #6c757d;"></div>
								<div class="legend-text">Maintenance</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Auto Selection Container -->
	<div class="container-fluid">
		<div class="auto-selection-container">
			<div class="section-title">
				<i class="bi bi-cpu"></i> Pilih Booth Otomatis (Algoritma Greedy)
			</div>

			<div class="btn-selection-group mb-3">
				<button type="button" class="btn btn-selection btn-primary" id="autoRecommendation">
					<i class="bi bi-sliders"></i> Cari Rekomendasi (Pilih Kriteria)
				</button>
			</div>

			<input type="radio" name="cardSelect" id="premium" class="hidden-radio">
			<input type="radio" name="cardSelect" id="strategic" class="hidden-radio">
			<input type="radio" name="cardSelect" id="basic" class="hidden-radio">

			<div class="row mb-3">
				<div class="col-md-4">
					<label for="premium" class="btn btn-selection btn-premium w-100">
						<i class="bi bi-star-fill"></i>
						<strong>Premium</strong>
						<small class="d-block">Stan 2 Muka + Lokasi Strategis</small>
					</label>
				</div>
				<div class="col-md-4">
					<label for="strategic" class="btn btn-selection btn-standard w-100">
						<i class="bi bi-geo-alt-fill"></i>
						<strong>Standard</strong>
						<small class="d-block">Dekat Panggung + Fasilitas Lengkap</small>
					</label>
				</div>
				<div class="col-md-4">
					<label for="basic" class="btn btn-selection btn-economy w-100">
						<i class="bi bi-wallet2"></i>
						<strong>Ekonomis</strong>
						<small class="d-block">Fasilitas Dasar + Harga Terjangkau</small>
					</label>
				</div>
			</div>

			<div class="info-cards row mb-3">
				<div class="col-md-12 card-premium">
					<div class="info-card">
						<div class="info-header">
							<i class="bi bi-star-fill text-warning"></i>
							<strong>Premium Booths</strong>
						</div>
						<ul class="info-list">
							<li>✅ Lokasi strategis</li>
							<li>✅ Stan 2 muka</li>
							<li>✅ Termasuk listrik & meja</li>
							<li class="price">Rp 2.000.000</li>
						</ul>
					</div>
				</div>
				<div class="col-md-12 card-strategic">
					<div class="info-card">
						<div class="info-header">
							<i class="bi bi-award text-warning"></i>
							<strong>Type A & B</strong>
						</div>
						<ul class="info-list">
							<li>✅ Lokasi strategis</li>
							<li>✅ Dekat panggung utama</li>
							<li>✅ Termasuk listrik & meja</li>
							<li class="price">Rp 2.000.000</li>
						</ul>
					</div>
				</div>
				<div class="col-md-12 card-basic">
					<div class="info-card">
						<div class="info-header">
							<i class="bi bi-box text-info"></i>
							<strong>Regular Booths</strong>
						</div>
						<ul class="info-list">
							<li>✅ Termasuk listrik & meja</li>
							<li>❌ Lokasi biasa</li>
							<li>❌ Stan 1 muka</li>
							<li class="price">Rp 1.750.000</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="algorithm-info">
				<i class="bi bi-info-circle"></i>
				<strong>Algoritma Greedy:</strong> Sistem mencari booth dengan score tertinggi berdasarkan kriteria yang
				dipilih.
			</div>
		</div>
	</div>

	<!-- Booking Modal -->
	<div class="modal fade booking-modal" id="bookingModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="bi bi-calendar-check me-2"></i> Booking Booth</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-info mb-4">
						<h6><i class="bi bi-info-circle-fill me-2"></i> <span id="modalBoothName"></span></h6>
						<p class="mb-1">Section: <span id="modalBoothSection"></span></p>
						<p class="mb-1">Harga Booth: Rp <span id="modalBoothPrice"></span></p>
						@if(isset($event))
							<p class="mb-1">Event: <span class="fw-bold">{{ $event->name }}</span></p>
						@endif
						<div class="mb-2" id="modalBoothFeatures"></div>
						<p class="mb-0"><strong>Total: Rp <span id="totalCost">0</span></strong></p>
					</div>

					<form id="bookingForm" novalidate>
						<input type="hidden" id="booth_id" name="booth_id">
						<input type="hidden" id="event_id" name="event_id" value="{{ $event_id ?? '' }}">

						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="company_name" class="form-label">Nama Produk/Bisnis <span
										class="text-danger">*</span></label>
								<input type="text" class="form-control" id="company_name" name="company_name" required>
								<div class="invalid-feedback">Nama Produk harus diisi</div>
							</div>
							<div class="col-md-6 mb-3">
								<label for="contact_person" class="form-label">Nama Penanggung Jawab <span
										class="text-danger">*</span></label>
								<input type="text" class="form-control" id="contact_person" name="contact_person"
									required>
								<div class="invalid-feedback">Nama penanggung jawab harus diisi</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label for="phone" class="form-label">Nomor Telepon <span
										class="text-danger">*</span></label>
								<input type="tel" class="form-control" id="phone" name="phone" required
									pattern="^(?:\+62|62|0)8[1-9][0-9]{6,10}$" maxlength="13"
									placeholder="contoh: 081234567890">
								<div class="invalid-feedback">Masukkan nomor telepon yang valid (maksimal 13 digit)
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<label for="email" class="form-label">Email <span class="text-danger">*</span></label>
								<input type="email" class="form-control" id="email" name="email" required
									placeholder="contoh: email@domain.com">
								<div class="invalid-feedback">Masukkan alamat email yang valid (misal: nama@domain.com)
								</div>
							</div>
						</div>

						<div class="mb-3">
							<label for="description" class="form-label">Deskripsi Bisnis/Produk</label>
							<textarea class="form-control" id="description" name="description" rows="3"
								maxlength="1000"></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-primary" id="confirmBooking">
						<i class="bi bi-check-circle me-1"></i> Konfirmasi Booking
					</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		// hanya boleh angka di input phone
		document.getElementById('phone').addEventListener('input', function () {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		// validasi tambahan email wajib ada domain
		function isValidEmail(email) {
			// hanya izinkan domain umum
			let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|net|org|id|co\.id|ac\.id|go\.id|edu|biz|info)$/;
			return regex.test(email);
		}

		// validasi form
		document.getElementById('confirmBooking').addEventListener('click', function () {
			let form = document.getElementById('bookingForm');
			let emailInput = document.getElementById('email');

			if (!isValidEmail(emailInput.value)) {
				emailInput.setCustomValidity("Email harus memiliki domain yang valid, misalnya .com atau .id");
				form.classList.add('was-validated');
				return; // hentikan, tidak bisa klik konfirmasi
			} else {
				emailInput.setCustomValidity(""); // reset jika valid
			}

			if (form.checkValidity() === false) {
				form.classList.add('was-validated');
			} else {
				// TODO: submit form via AJAX atau form.submit()
				// form.submit();
			}
		});
	</script>


	<!-- Payment Modal -->
	<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="bi bi-credit-card me-2"></i>Pembayaran</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body text-center">
					<div class="mb-3">
						<i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
					</div>
					<h6>Booking Berhasil Dibuat!</h6>
					<p class="text-muted">Silakan lakukan pembayaran untuk mengkonfirmasi booking Anda.</p>
					<div class="alert alert-info">
						<strong>Order ID: </strong><span id="orderIdDisplay"></span><br>
						<strong>Total: </strong><span id="totalPayment"></span>
					</div>
				</div>
				<div class="modal-footer justify-content-center">
					<button type="button" class="btn btn-success" id="payNowBtn">
						<i class="bi bi-credit-card me-1"></i> Bayar Sekarang
					</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nanti Saja</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Success Modal -->
	<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body text-center">
					<div class="mb-3">
						<i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
					</div>
					<h4 class="text-success">Pembayaran Berhasil!</h4>
					<p class="text-muted">Terima kasih, booking Anda telah dikonfirmasi.</p>
				</div>
				<div class="modal-footer justify-content-center">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<footer class="ftco-footer ftco-bg-dark ftco-section text-white" style="background-color: #1d1d1d;">
		<div class="container">
			<div class="row mb-5">
				<div class="col-md-6">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">BookingBooth</h2>
						<p>Dapatkan booth terbaik di event favorit Anda dengan sistem booking yang mudah dan
							profesional.</p>
					</div>
				</div>
				<div class="col-md-3">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">Navigasi</h2>
						<ul class="list-unstyled">
							<li><a href="#" class="py-1 d-block text-white">Event</a></li>
							<li><a href="#" class="py-1 d-block text-white">Harga Booth</a></li>
							<li><a href="#" class="py-1 d-block text-white">FAQ</a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-3">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">Kontak</h2>
						<p><i class="bi bi-geo-alt"></i> Jl. Pancasila, Tegal</p>
						<p><i class="bi bi-phone"></i> +62 812-3456-7890</p>
						<p><i class="bi bi-envelope"></i> info@bookingbooth.com</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<p>&copy;
						<script>document.write(new Date().getFullYear());</script> BookingBooth. All rights reserved.
					</p>
				</div>
			</div>
		</div>
	</footer>

	<!-- Scripts -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<script>
		$(document).ready(function () {
			// Global variables
			let booths = [];
			let selectedBooth = null;
			let currentEventId = getEventId();

			const sectionColors = {
				'A': '#FF69B4', 'B': '#4CAF50', 'C': '#FF9800', 'D': '#8D6E63',
				'E': '#2196F3', 'F': '#F44336', 'T': '#FFF176'
			};

			const boothConfig = {
				premium: {
					booths: ['C1', 'E1', 'E28', 'D1', 'C25', 'F10', 'F11', 'D25', 'E15', 'E14', 'F20', 'F1'],
					price: 2000000,
					benefits: ['Lokasi Strategis', 'Listrik & Meja', 'Stan 2 Muka'],
					features: { strategic_location: true, electricity: true, table: true, two_faces: true }
				},
				strategic: {
					sections: ['A', 'B'],
					price: 2000000,
					benefits: ['Lokasi Strategis', 'Dekat Panggung', 'Listrik & Meja'],
					features: { strategic_location: true, near_stage: true, electricity: true, table: true }
				},
				regular: {
					sections: ['C', 'D', 'E', 'F'],
					price: 1750000,
					benefits: ['Listrik & Meja'],
					features: { electricity: true, table: true }
				}
			};

			// Utility functions
			function getEventId() {
				const urlParams = new URLSearchParams(window.location.search);
				return urlParams.get('event_id') || $('meta[name="event-id"]').attr('content') || null;
			}

			function getBoothConfig(boothId, section) {
				if (boothConfig.premium.booths.includes(boothId)) return boothConfig.premium;
				if (boothConfig.strategic.sections.includes(section)) return boothConfig.strategic;
				return boothConfig.regular;
			}

			function formatRupiah(number) {
				return new Intl.NumberFormat('id-ID').format(number);
			}

			function showLoading(message = 'Memuat...') {
				$('.loading').show().html(`<div class="text-center"><i class="spinner-border"></i> ${message}</div>`);
			}

			function hideLoading() {
				$('.loading').hide();
			}

			// AJAX setup
			$.ajaxSetup({
				headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
			});

			// Load booths function - FIXED
			function loadBooths(eventId = null) {
				const targetEventId = eventId || currentEventId;

				showLoading('Memuat booth...');
				$('.booth').remove();
				booths = [];

				if (!targetEventId) {
					$('.loading').html('<div class="alert alert-warning">Silakan pilih event terlebih dahulu</div>');
					return;
				}

				$.get(`/api/booths?event_id=${targetEventId}`)
					.done(function (response) {
						if (response.success) {
							booths = response.booths;
							currentEventId = targetEventId;
							renderBooths();
							updateSectionStats();
							hideLoading();
						} else {
							$('.loading').html(`<div class="alert alert-danger">${response.message}</div>`);
						}
					})
					.fail(function (xhr) {
						const errorMessage = xhr.responseJSON?.message || 'Gagal memuat data booth';
						$('.loading').html(`<div class="alert alert-danger">${errorMessage}</div>`);
					});
			}

			// Render booths function - OPTIMIZED
			function renderBooths() {
				const boothMap = $('#boothMap');
				$('.booth').remove();

				if (!booths.length) {
					$('.loading').html('<div class="alert alert-info">Tidak ada booth tersedia untuk event ini</div>');
					return;
				}

				booths.forEach(booth => {
					const config = getBoothConfig(booth.booth_id, booth.section);
					const statusClass = booth.status === 'booked' || booth.status === 'processing' ? 'booked' :
						booth.status === 'maintenance' ? 'maintenance' : 'available';

					const element = $(`
						<div class="booth ${statusClass}" 
							data-booth-id="${booth.id}"
							data-booth-name="${booth.booth_name}"
							data-section="${booth.section}"
							data-booth-code="${booth.booth_id}"
							style="left:${booth.position_x}px;top:${booth.position_y}px;width:${booth.width}px;height:${booth.height}px;background-color:${sectionColors[booth.section]}">
							${booth.booth_id}
						</div>`);
					boothMap.append(element);
				});

				setupBoothInteractions();
			}

			// Setup booth interactions - OPTIMIZED
			function setupBoothInteractions() {
				// Tooltip
				$('.booth.available').hover(function (e) {
					const code = $(this).data('booth-code');
					const config = getBoothConfig(code, $(this).data('section'));
					$('#boothTooltip')
						.html(`<strong>${$(this).data('booth-name')}</strong><br>Rp ${formatRupiah(config.price)}`)
						.css({ display: 'block', left: e.pageX + 10, top: e.pageY - 10 });
				}, () => $('#boothTooltip').hide());

				// Click handler
				$('.booth.available').click(function () {
					selectBooth($(this));
				});
			}

			// Select booth function - OPTIMIZED
			function selectBooth($boothElement) {
				const boothId = $boothElement.data('booth-id');
				const boothCode = $boothElement.data('booth-code');
				const section = $boothElement.data('section');
				const config = getBoothConfig(boothCode, section);

				selectedBooth = {
					id: boothId,
					name: $boothElement.data('booth-name'),
					section: section,
					boothId: boothCode,
					price: config.price,
					config: config
				};

				// Update UI
				$('.booth').removeClass('selected');
				$boothElement.addClass('selected');

				// Update modal
				updateBookingModal();
				$('#bookingModal').modal('show');
			}

			// Update booking modal - NEW FUNCTION
			function updateBookingModal() {
				$('#modalBoothName').text(selectedBooth.name);
				$('#modalBoothSection').text(selectedBooth.boothId);
				$('#modalBoothPrice').text(formatRupiah(selectedBooth.price));
				$('#totalCost').text(formatRupiah(selectedBooth.price));

				let features = '';
				selectedBooth.config.benefits.forEach(benefit => {
					features += `<span class="badge bg-primary me-1">${benefit}</span>`;
				});
				$('#modalBoothFeatures').html(features);
				$('#booth_id').val(selectedBooth.id);
			}

			// Update section stats - OPTIMIZED
			function updateSectionStats() {
				const stats = {};
				booths.forEach(booth => {
					const s = booth.section;
					if (!stats[s]) stats[s] = { total: 0, available: 0 };
					stats[s].total++;
					if (booth.status === 'available') stats[s].available++;
				});

				const container = $('#sectionStats');
				container.empty();

				if (Object.keys(stats).length === 0) {
					container.html('<div class="alert alert-info">Tidak ada data statistik booth</div>');
					return;
				}

				Object.keys(stats).sort().forEach(section => {
					const stat = stats[section];
					const pct = stat.total > 0 ? ((stat.available / stat.total) * 100).toFixed(0) : 0;
					container.append(`
						<div class="stat-card">
							<h5>Section ${section}</h5>
							<p>Tersedia: ${stat.available}/${stat.total} (${pct}%)</p>
						</div>`);
				});
			}

			// Smart booth recommendation - GREEDY ALGORITHM
			function smartBoothRecommendation(criteria) {
				const availableBooths = booths.filter(booth => booth.status === 'available');
				if (!availableBooths.length) return null;

				const scoredBooths = availableBooths.map(booth => {
					const config = getBoothConfig(booth.booth_id, booth.section);
					let score = 0;
					let matched = [];

					// Greedy scoring with weighted criteria
					if (criteria.strategic_location && config.features.strategic_location) {
						score += 10; matched.push('Lokasi Strategis');
					}
					if (criteria.near_stage && config.features.near_stage) {
						score += 8; matched.push('Dekat Panggung');
					}
					if (criteria.electricity && config.features.electricity) {
						score += 5; matched.push('Listrik');
					}
					if (criteria.table && config.features.table) {
						score += 5; matched.push('Meja');
					}
					if (criteria.two_faces && config.features.two_faces) {
						score += 15; matched.push('Stan 2 Muka');
					}

					// Price bonus (cheaper is better)
					const maxPrice = 2000000;
					const priceScore = (maxPrice - config.price) / maxPrice * 5;
					score += priceScore;

					return {
						...booth,
						config,
						score: score,
						matchedCriteria: matched,
						price: config.price
					};
				}).filter(booth => booth.score > 0);

				if (!scoredBooths.length) return null;

				// Greedy selection: highest score first, then cheapest
				return scoredBooths.sort((a, b) => {
					if (a.score !== b.score) return b.score - a.score;
					return a.price - b.price;
				})[0];
			}

			// Show criteria form - OPTIMIZED
			function showCriteriaForm() {
				const modal = `
					<div class="modal fade" id="criteriaModal">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5><i class="bi bi-sliders"></i> Pilih Kriteria Booth</h5>
									<button class="btn-close" data-bs-dismiss="modal"></button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-6">
											<h6><i class="bi bi-geo-alt"></i> Lokasi</h6>
											<div class="form-check mb-2">
												<input class="form-check-input" type="checkbox" id="strategic_location">
												<label for="strategic_location">Lokasi Strategis</label>
											</div>
											<div class="form-check mb-3">
												<input class="form-check-input" type="checkbox" id="near_stage">
												<label for="near_stage">Dekat Panggung Utama</label>
											</div>
										</div>
										<div class="col-md-6">
											<h6><i class="bi bi-gear"></i> Fasilitas</h6>
											<div class="form-check mb-2">
												<input class="form-check-input" type="checkbox" id="electricity">
												<label for="electricity">Listrik</label>
											</div>
											<div class="form-check mb-2">
												<input class="form-check-input" type="checkbox" id="table">
												<label for="table">Meja</label>
											</div>
											<div class="form-check mb-3">
												<input class="form-check-input" type="checkbox" id="two_faces">
												<label for="two_faces">Stan 2 Muka</label>
											</div>
										</div>
									</div>
									<div class="alert alert-info">
										<i class="bi bi-info-circle"></i>
										<strong>Algoritma Greedy:</strong> Sistem akan mencari booth dengan score tertinggi berdasarkan kriteria yang dipilih.
									</div>
								</div>
								<div class="modal-footer">
									<button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
									<button class="btn btn-primary" id="searchRecommendation">
										<i class="bi bi-search"></i> Cari Rekomendasi
									</button>
								</div>
							</div>
						</div>
					</div>`;

				$('#criteriaModal').remove();
				$('body').append(modal);
				$('#criteriaModal').modal('show');
			}

			// Show recommendation result - OPTIMIZED
			function showRecommendationResult(booth) {
				if (!booth) {
					Swal.fire({
						title: 'Tidak Ada Hasil',
						text: 'Tidak ada booth yang sesuai dengan kriteria yang dipilih',
						icon: 'info'
					});
					return;
				}

				selectedBooth = {
					id: booth.id,
					name: booth.booth_name,
					section: booth.section,
					price: booth.price,
					boothId: booth.booth_id,
					config: booth.config,
					matchedCriteria: booth.matchedCriteria,
					score: booth.score
				};

				// Update UI
				$('.booth').removeClass('auto-selected selected');
				$(`.booth[data-booth-id="${booth.id}"]`).addClass('auto-selected');

				// Update modal with recommendation details
				updateBookingModalWithRecommendation();

				$('#criteriaModal').modal('hide');
				$('#bookingModal').modal('show');
			}

			// Update booking modal with recommendation - NEW FUNCTION
			function updateBookingModalWithRecommendation() {
				$('#modalBoothName').text(selectedBooth.name);
				$('#modalBoothSection').text(selectedBooth.boothId);
				$('#modalBoothPrice').text(formatRupiah(selectedBooth.price));
				$('#totalCost').text(formatRupiah(selectedBooth.price));

				let features = '<span class="badge bg-success me-2"><i class="bi bi-cpu"></i> Rekomendasi Greedy</span>';

				selectedBooth.matchedCriteria.forEach(criterion => {
					features += `<span class="badge bg-info me-1">${criterion}</span>`;
				});

				selectedBooth.config.benefits.forEach(benefit => {
					features += `<span class="badge bg-warning me-1">${benefit}</span>`;
				});

				const scoreInfo = `
					<div class="mt-2 p-2 bg-light rounded">
						<small class="text-muted">
							<i class="bi bi-graph-up"></i> 
							Score: ${selectedBooth.score.toFixed(1)}/40 | 
							Kriteria: ${selectedBooth.matchedCriteria.join(', ')}
						</small>
					</div>`;

				$('#modalBoothFeatures').html(features + scoreInfo);
				$('#booth_id').val(selectedBooth.id);
			}

			// Event handlers - CONSOLIDATED
			$(document).on('click', '#autoRecommendation', showCriteriaForm);

			$(document).on('click', '#searchRecommendation', function () {
				const criteria = {
					strategic_location: $('#strategic_location').is(':checked'),
					near_stage: $('#near_stage').is(':checked'),
					electricity: $('#electricity').is(':checked'),
					table: $('#table').is(':checked'),
					two_faces: $('#two_faces').is(':checked')
				};

				if (!Object.values(criteria).some(v => v)) {
					Swal.fire({
						title: 'Pilih Kriteria',
						text: 'Silakan pilih minimal satu kriteria untuk mendapatkan rekomendasi',
						icon: 'warning'
					});
					return;
				}

				showRecommendationResult(smartBoothRecommendation(criteria));
			});

			// Booking confirmation - FIXED with proper success modal
			$('#confirmBooking').click(function () {
				const form = $('#bookingForm')[0];

				if (!form.checkValidity()) {
					form.reportValidity();
					return;
				}

				if (!currentEventId || !selectedBooth) {
					Swal.fire({
						title: 'Error',
						text: 'Data tidak lengkap. Silakan refresh halaman.',
						icon: 'error'
					});
					return;
				}

				const $btn = $(this);
				$btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-2"></i>Memproses...');

				const data = {
					booth_id: selectedBooth.id,
					company_name: $('#company_name').val(),
					contact_person: $('#contact_person').val(),
					phone: $('#phone').val(),
					email: $('#email').val(),
					booking_date: new Date().toISOString().split('T')[0],
					description: $('#description').val()
				};

				$.ajax({
					url: '/bookings',
					method: 'POST',
					data: data,
					success: function (response) {
						if (response.success) {
							$('#bookingModal').modal('hide');

							// Update payment modal
							$('#orderIdDisplay').text(response.payment_data?.order_id || 'N/A');
							$('#totalPayment').text('Rp ' + formatRupiah(selectedBooth.price));

							if (response.payment_data?.snap_token) {
								$('#payNowBtn').data('snap-token', response.payment_data.snap_token);
							}

							$('#paymentModal').modal('show');
						}
					},
					error: function (xhr) {
						const response = xhr.responseJSON;
						let errorMessage = 'Booking gagal. Silakan coba lagi.';

						if (response?.errors) {
							errorMessage = 'Validasi gagal:\n';
							Object.keys(response.errors).forEach(key => {
								errorMessage += `- ${response.errors[key][0]}\n`;
							});
						} else if (response?.message) {
							errorMessage = response.message;
						}

						Swal.fire({
							title: 'Error',
							text: errorMessage,
							icon: 'error'
						});
					},
					complete: function () {
						$btn.prop('disabled', false).html('<i class="bi bi-check-circle me-1"></i> Konfirmasi Booking');
					}
				});
			});

			// Payment handler - FIXED with success modal
			$('#payNowBtn').click(function () {
				const token = $(this).data('snap-token');

				if (!token) {
					Swal.fire({
						title: 'Error',
						text: 'Token pembayaran tidak valid',
						icon: 'error'
					});
					return;
				}

				$('#paymentModal').modal('hide');

				window.snap.pay(token, {
					onSuccess: function (result) {
						console.log('Payment Success:', result);

						// Show success modal instead of SweetAlert
						$('#successModal').modal('show');

						// Auto close success modal and refresh after 3 seconds
						setTimeout(() => {
							$('#successModal').modal('hide');
							loadBooths(currentEventId); // Refresh booth data
							$('#bookingForm')[0].reset();
							selectedBooth = null;
						}, 3000);
					},
					onPending: function (result) {
						console.log('Payment Pending:', result);
						Swal.fire({
							title: 'Pembayaran Menunggu',
							text: 'Pembayaran sedang diproses. Silakan selesaikan untuk mengkonfirmasi booking.',
							icon: 'info'
						}).then(() => {
							loadBooths(currentEventId);
							$('#bookingForm')[0].reset();
							selectedBooth = null;
						});
					},
					onError: function (result) {
						console.log('Payment Error:', result);
						Swal.fire({
							title: 'Pembayaran Gagal',
							text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
							icon: 'error'
						});
					},
					onClose: function () {
						console.log('Payment popup closed');
						Swal.fire({
							title: 'Pembayaran Dibatalkan',
							text: 'Anda dapat melanjutkan pembayaran nanti melalui halaman pesanan.',
							icon: 'warning'
						});
					}
				});
			});

			// Initialize application
			if (currentEventId) {
				loadBooths(currentEventId);
			} else {
				$('.loading').html('<div class="alert alert-warning">Silakan pilih event terlebih dahulu untuk melihat booth yang tersedia</div>');
			}

			console.log('Booth booking system initialized - Event ID:', currentEventId);
		});
	</script>
</body>

</html>