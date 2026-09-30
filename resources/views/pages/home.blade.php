@extends('layouts.main')
@section('content')


	<div class="hero-wrap js-fullheight"
		style="background-image: url('{{ isset($banner->image_path) ? asset('storage/banners/' . $banner->image_path) : 'images/tehhh.jpg' }}');"
		data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
				data-scrollax-parent="true">
				<div class="col-xl-10 ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
					<h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
						@if(isset($banner) && $banner && $banner->title)
							{{ explode(' ', $banner->title)[0] ?? 'Festival' }}
							<br><span>{{ substr($banner->title, strlen(explode(' ', $banner->title)[0] ?? 'Festival') + 1) }}</span>
						@else
							Festival
							<br><span>Tea Fiesta & Food Bazar</span>
						@endif
					</h1>
					<p class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
						@if(isset($banner) && $banner && $banner->event_date)
							{{ $banner->event_date->format('F d-') . ($banner->event_date->copy()->addDays(2))->format('d, Y') }}.
							{{ $banner->event_location ?? 'Jl. Pancasila, Alun-alun Tegal' }}
						@else
							Juni 26-28, 2025. Jl. Pancasila, Alun-alun Tegal
						@endif
					</p>

					@if((!isset($banner) || !$banner || !isset($banner->countdown_enabled)) || $banner->countdown_enabled)
						<div id="timer" class="d-flex flex-wrap gap-4 mt-4">
							<div class="text-center px-3">
								<span id="days-number" class="d-block fw-bold display-4">0</span>
								<small class="fs-5">Hari</small>
							</div>
							<div class="text-center px-3">
								<span id="hours-number" class="d-block fw-bold display-4">0</span>
								<small class="fs-5">Jam</small>
							</div>
							<div class="text-center px-3">
								<span id="minutes-number" class="d-block fw-bold display-4">0</span>
								<small class="fs-5">Menit</small>
							</div>
							<div class="text-center px-3">
								<span id="seconds-number" class="d-block fw-bold display-4">0</span>
								<small class="fs-5">Detik</small>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			const eventDate = @json(optional($banner->event_date)->format('Y-m-d H:i:s') ?? '2025-06-26 00:00:00');
			const countDownDate = new Date(eventDate).getTime();

			const daysEl = document.getElementById("days-number");
			const hoursEl = document.getElementById("hours-number");
			const minutesEl = document.getElementById("minutes-number");
			const secondsEl = document.getElementById("seconds-number");
			const timerContainer = document.getElementById("timer");

			const countdownFunction = setInterval(function () {
				const now = new Date().getTime();
				const distance = countDownDate - now;

				if (distance < 0) {
					clearInterval(countdownFunction);
					timerContainer.innerHTML = `<p class="text-danger fw-bold fs-4">Event sedang berlangsung!</p>`;
					return;
				}

				const days = Math.floor(distance / (1000 * 60 * 60 * 24));
				const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				const seconds = Math.floor((distance % (1000 * 60)) / 1000);

				daysEl.innerText = days;
				hoursEl.innerText = hours;
				minutesEl.innerText = minutes;
				secondsEl.innerText = seconds;
			}, 1000);
		});
	</script>



	<section class="ftco-section services-section bg-light">
		<div class="container">
			<div class="row d-flex">
				<!-- Venue -->
				<div class="col-md-3 d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services d-block text-center p-4 shadow rounded bg-white">
						<div class="icon mb-3">
							<i class="fas fa-map-marker-alt fa-2x text-primary"></i>
						</div>
						<div class="media-body">
							<h3 class="heading mb-3">Venue</h3>
							<p>Juni 26-28, 2025. Jl. Pancasila, Alun-alun Tegal</p>
						</div>
					</div>
				</div>

				<!-- Booth Tenant -->
				<div class="col-md-3 d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services d-block text-center p-4 shadow rounded bg-white">
						<div class="icon mb-3">
							<i class="fas fa-store fa-2x text-success"></i>
						</div>
						<div class="media-body">
							<h3 class="heading mb-3">Booth Tenant</h3>
							<p>Sebuah area booth tenant berdiri di tempat mereka dan menyediakan berbagai layanan.</p>
						</div>
					</div>
				</div>

				<!-- Bazar -->
				<div class="col-md-3 d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services d-block text-center p-4 shadow rounded bg-white">
						<div class="icon mb-3">
							<i class="fas fa-tags fa-2x text-warning"></i>
						</div>
						<div class="media-body">
							<h3 class="heading mb-3">Bazar</h3>
							<p>Sebuah bazar ramai diselenggarakan di tempat mereka dan menghadirkan berbagai tenant
								dengan beragam produk.</p>
						</div>
					</div>
				</div>

				<!-- Food -->
				<div class="col-md-3 d-flex align-self-stretch ftco-animate">
					<div class="media block-6 services d-block text-center p-4 shadow rounded bg-white">
						<div class="icon mb-3">
							<i class="fas fa-utensils fa-2x text-danger"></i>
						</div>
						<div class="media-body">
							<h3 class="heading mb-3">Food</h3>
							<p>Beragam stan makanan tersusun rapi, menyajikan hidangan lezat untuk para pengunjung.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="event-listing py-5">
		<div class="container">
			<h2 class="section-title text-center mb-5">Daftar Event</h2>
			<div class="row g-4">
				@forelse($events as $event)
					<!-- Event Card -->
					<div class="col-md-6 col-lg-4">
						<div class="event-card card h-100">
							<!-- Event Image (menggunakan main_image) -->
							<div class="event-image">
								@if($event->main_image)
									<img src="{{ asset('storage/' . $event->main_image) }}" class="card-img-top"
										alt="{{ $event->main_title }}">
								@else
									<img src="https://via.placeholder.com/400x200?text=No+Image" class="card-img-top"
										alt="Default Image">
								@endif
							</div>

							<!-- Event Body -->
							<div class="card-body">
								<!-- Menampilkan main_title -->
								<h3 class="event-title">{{ $event->main_title }}</h3>

								<div class="event-meta">
									<!-- Menampilkan kategori -->
									<div class="meta-item">
										<i class="bi bi-tags-fill"></i>
										<span class="meta-label">Kategori:</span>
										<span class="meta-value">{{ $event->category }}</span>
									</div>

									<!-- Menampilkan lokasi -->
									<div class="meta-item">
										<i class="bi bi-geo-alt-fill"></i>
										<span class="meta-label">Lokasi:</span>
										<span class="meta-value">{{ $event->location }}</span>
									</div>

									<!-- Menampilkan tanggal -->
									<div class="meta-item">
										<i class="bi bi-calendar-fill"></i>
										<span class="meta-label">Tanggal:</span>
										<span class="meta-value">
											{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} -
											{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
										</span>
									</div>
								</div>

								<p class="event-description">
									{{ Str::limit($event->main_description, 100) }}
								</p>

								<button class="btn btn-detail" data-toggle="modal" data-target="#eventModal{{ $event->id }}">
									<i class="bi bi-info-circle"></i> Lihat Detail
								</button>
							</div>
						</div>
					</div>

					<!-- Event Modal -->
					<div class="modal fade" id="eventModal{{ $event->id }}" tabindex="-1" role="dialog"
						aria-labelledby="eventModalLabel{{ $event->id }}" aria-hidden="true">
						<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h3 class="modal-title">{{ $event->main_title }}</h3>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>

								<div class="modal-body">
									<div class="row">
										<!-- Event Image (menggunakan main_image) -->
										<div class="col-md-6">
											@if($event->main_image)
												<img src="{{ asset('storage/' . $event->main_image) }}"
													class="img-fluid rounded mb-3" alt="{{ $event->main_title }}">
											@endif

											@if($event->second_image)
												<img src="{{ asset('storage/' . $event->second_image) }}" class="img-fluid rounded"
													alt="{{ $event->main_title }}">
											@endif

											@if(!$event->main_image && !$event->second_image)
												<img src="https://via.placeholder.com/400x300?text=No+Image"
													class="img-fluid rounded" alt="Default Image">
											@endif
										</div>

										<!-- Event Details -->
										<div class="col-md-6">
											<div class="main-event-info">
												<h4>{{ $event->main_title }}</h4>
												<p>{{ $event->main_description }}</p>

												<div class="info-section">
													<h5><i class="bi bi-calendar-event"></i> Tanggal Event</h5>
													<div class="date-info">
														<span class="date-label">Mulai:</span>
														<span class="date-value">
															{{ \Carbon\Carbon::parse($event->start_date)->format('d F Y') }}
														</span>
													</div>
													<div class="date-info">
														<span class="date-label">Selesai:</span>
														<span class="date-value">
															{{ \Carbon\Carbon::parse($event->end_date)->format('d F Y') }}
														</span>
													</div>
												</div>

												<div class="info-section">
													<h5><i class="bi bi-geo-alt"></i> Lokasi</h5>
													<p>{{ $event->location }}</p>
												</div>

												@if($event->category)
													<div class="info-section">
														<h5><i class="bi bi-tags"></i> Kategori</h5>
														<span class="badge">{{ $event->category }}</span>
													</div>
												@endif

												@if($event->title && $event->title != $event->main_title)
													<div class="sub-event-info mt-4">
														<h5>Detail Program</h5>
														<h6>{{ $event->title }}</h6>

														@if($event->time)
															<div class="info-section">
																<h6><i class="bi bi-clock"></i> Waktu</h6>
																<p>{{ $event->time }}</p>
															</div>
														@endif

														<!-- @if($event->description)
															<div class="info-section">
																<h6><i class="bi bi-file-text"></i> Deskripsi</h6>
																<p>{{ $event->description }}</p>
															</div>
														@endif -->

														@if($event->speaker)
															<div class="info-section">
																<h6><i class="bi bi-person"></i> Pembicara</h6>
																<div class="speaker-info">
																	<strong>{{ $event->speaker }}</strong>
																	@if($event->position)
																		<small>{{ $event->position }}</small>
																	@endif
																</div>
															</div>
														@endif
													</div>
												@endif
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
									<a href="{{ route('booking-booth', ['event_id' => $event->id]) }}" class="btn btn-primary">
										<i class="bi bi-calendar-check"></i> Booking Booth
									</a>
								</div>
							</div>
						</div>
					</div>
				@empty
					<div class="col-12">
						<div class="no-events-message">
							Belum ada event yang tersedia.
						</div>
					</div>
				@endforelse
			</div>
		</div>
	</section>

	<style>
		/* Event Listing Section */
		.event-listing {
			background-color: #f8f9fa;
		}

		.section-title {
			font-weight: 700;
			color: #0d6efd;
			position: relative;
			padding-bottom: 15px;
		}

		.section-title:after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 80px;
			height: 3px;
			background: #0d6efd;
		}

		/* Event Card */
		.event-card {
			border: none;
			border-radius: 10px;
			overflow: hidden;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			transition: transform 0.3s ease, box-shadow 0.3s ease;
		}

		.event-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
		}

		.event-image {
			height: 200px;
			overflow: hidden;
		}

		.event-image img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			transition: transform 0.3s ease;
		}

		.event-card:hover .event-image img {
			transform: scale(1.05);
		}

		.event-title {
			font-size: 1.2rem;
			font-weight: 600;
			margin-bottom: 0.75rem;
			color: #212529;
		}

		.event-meta {
			margin-bottom: 1rem;
		}

		.meta-item {
			display: flex;
			align-items: center;
			margin-bottom: 0.5rem;
			font-size: 0.9rem;
		}

		.meta-item i {
			color: #0d6efd;
			margin-right: 8px;
			font-size: 1rem;
		}

		.meta-label {
			font-weight: 500;
			margin-right: 5px;
		}

		.event-description {
			color: #6c757d;
			margin-bottom: 1.5rem;
			font-size: 0.95rem;
		}

		.btn-detail {
			width: 100%;
			background-color: #0d6efd;
			color: white;
			border-radius: 5px;
			padding: 8px 0;
			font-weight: 500;
		}

		.btn-detail:hover {
			background-color: #0b5ed7;
		}

		.card-footer {
			background-color: white;
			border-top: 1px solid rgba(0, 0, 0, 0.05);
			padding: 0.75rem 1.25rem;
		}

		.event-speaker {
			font-size: 0.9rem;
			color: #495057;
		}

		.event-speaker i {
			color: #0d6efd;
			margin-right: 5px;
		}

		/* No Events Message */
		.no-events-message {
			background-color: #fff3cd;
			color: #856404;
			padding: 1rem;
			border-radius: 5px;
			text-align: center;
			font-weight: 500;
		}

		/* Modal Styles */
		.modal-content {
			border-radius: 10px;
			border: none;
		}

		.modal-header {
			border-bottom: 1px solid #dee2e6;
			padding: 1.5rem;
		}

		.modal-title {
			font-weight: 600;
			color: #212529;
		}

		.modal-body {
			padding: 1.5rem;
		}

		.info-section {
			margin-bottom: 1.5rem;
		}

		.info-section h5,
		.info-section h6 {
			color: #0d6efd;
			font-weight: 600;
			margin-bottom: 0.5rem;
		}

		.info-section h5 i,
		.info-section h6 i {
			margin-right: 8px;
		}

		.date-info {
			margin-bottom: 0.25rem;
		}

		.date-label {
			font-weight: 500;
			margin-right: 5px;
		}

		.badge {
			background-color: #0d6efd;
			padding: 5px 10px;
			border-radius: 20px;
			font-size: 0.8rem;
			font-weight: 500;
			margin-bottom: 1rem;
			display: inline-block;
		}

		.speaker-info {
			margin-top: 0.5rem;
		}

		.speaker-info small {
			color: #6c757d;
			display: block;
			margin-top: 3px;
		}

		.modal-footer {
			border-top: 1px solid #dee2e6;
			padding: 1rem 1.5rem;
		}

		@media (max-width: 767.98px) {
			.modal-body .row {
				flex-direction: column;
			}

			.modal-body .col-md-6 {
				width: 100%;
				margin-bottom: 1.5rem;
			}
		}
	</style>

	<section class="fun-facts-section">
		<div class="fun-facts-container">
			<div class="fun-facts-header">
				<h1 class="fun-facts-title">BookingBooth Platform</h1>
				<h2 class="fun-facts-subtitle">Mengapa Memilih Kami?</h2>
				<p class="fun-facts-description">
					Platform terdepan untuk <span class="highlight">pemesanan booth event</span> yang menghubungkan
					pemilik bisnis dengan <span class="highlight">peluang ekspansi pasar</span> melalui
					<span class="highlight">event-event berkualitas</span> di seluruh Indonesia.
				</p>
			</div>

			<div class="stats-grid">
				<div class="stat-card pulse fade-in">
					<div class="stat-icon">🤝</div>
					<span class="stat-number">200+</span>
					<div class="stat-label">Event Partner</div>
					<p class="stat-description">
						Lebih dari 200 event partner terpercaya di seluruh Indonesia yang siap memberikan
						kesempatan terbaik untuk mengembangkan bisnis Anda melalui booth premium dengan
						lokasi strategis dan fasilitas lengkap.
					</p>
				</div>

				<div class="stat-card pulse fade-in">
					<div class="stat-icon">🏪</div>
					<span class="stat-number">1,500+</span>
					<div class="stat-label">Booth Tersedia</div>
					<p class="stat-description">
						Ribuan pilihan booth dengan berbagai kategori dan harga yang dapat disesuaikan
						dengan kebutuhan bisnis Anda. Dari booth standar hingga premium dengan fasilitas
						lengkap dan lokasi prime.
					</p>
				</div>

				<div class="stat-card pulse fade-in">
					<div class="stat-icon">📈</div>
					<span class="stat-number">95%</span>
					<div class="stat-label">Tingkat Kepuasan</div>
					<p class="stat-description">
						Tingkat kepuasan pelanggan yang tinggi berkat layanan profesional, kemudahan
						booking, dan dukungan penuh selama event berlangsung untuk memastikan kesuksesan
						partisipasi Anda.
					</p>
				</div>
			</div>

			<div class="event-categories">
				<h2 class="categories-title">Kategori Event</h2>
				<div class="categories-grid">
					<div class="category-card">
						<div class="category-icon">🎨</div>
						<div class="category-name">Exhibition</div>
					</div>
					<div class="category-card">
						<div class="category-icon">🍽️</div>
						<div class="category-name">Food Festival</div>
					</div>
					<div class="category-card">
						<div class="category-icon">💼</div>
						<div class="category-name">Business Expo</div>
					</div>
					<div class="category-card">
						<div class="category-icon">🛍️</div>
						<div class="category-name">Trade Show</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Pricing Section -->
	<section class="ftco-section bg-light">
		<div class="container">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<span class="subheading">Harga Booth</span>
					<h2 class="mb-1"><span>Booking</span> Booth</h2>
					@if(isset($currentEvent))
						<p class="text-muted">Event: {{ $currentEvent->main_title }}</p>
					@endif
				</div>
			</div>
			<div class="row">
				@forelse ($booths as $booth)
					<div class="col-md-4 ftco-animate">
						<div class="block-7 shadow-sm border rounded p-5 bg-white h-100 d-flex flex-column">
							<div class="text-center flex-grow-1">
								<h2 class="heading mb-3">{{ $booth->name }}</h2>
								<span class="price d-block mb-3 text-primary fw-bold" style="font-size: 36px;">
									@if(is_numeric($booth->price))
										Rp {{ number_format((float) $booth->price, 0, ',', '.') }}
									@else
										{{ $booth->price }}
									@endif
								</span>
								<span class="excerpt d-block mb-4"
									style="font-size: 18px;">{{ $booth->subtitle ?? 'Booth Premium' }}</span>
								@if($booth->event)
									<div class="mb-3">
										<span class="badge bg-primary">{{ $booth->event->main_title }}</span>
									</div>
								@endif
								<h3 class="heading-2 mb-4">Fasilitas</h3>
								<ul class="pricing-text mb-5 text-muted list-unstyled" style="font-size: 16px;">
									@if($booth->facilities)
										@php
											$facilities = explode(',', $booth->facilities);
										@endphp
										@foreach ($facilities as $facility)
											<li><i class="fas fa-check text-success me-2"></i>{{ trim($facility) }}</li>
										@endforeach
									@else
										<li><i class="fas fa-check text-success me-2"></i>Fasilitas standar</li>
									@endif
								</ul>
							</div>
							@if($booth->event)
								<a href="{{ route('booking-booth', ['event_id' => $booth->event->id]) }}?scrollTo=venueLayout&booth_id={{ $booth->id }}"
									class="btn btn-primary d-block px-3 py-3">
									<i class="bi bi-calendar-check"></i> Pesan Sekarang
								</a>
							@else
								<button class="btn btn-secondary d-block px-3 py-3" disabled>
									<i class="bi bi-exclamation-circle"></i> Event Belum Tersedia
								</button>
							@endif
						</div>
					</div>
				@empty
					<div class="col-12">
						<div class="text-center p-5">
							<i class="fas fa-store fa-3x text-muted mb-3"></i>
							<h4 class="text-muted">Belum Ada Booth Tersedia</h4>
							<p class="text-muted">
								@if(isset($currentEvent))
									Booth untuk event "{{ $currentEvent->main_title }}" belum tersedia. Silakan cek kembali nanti.
								@else
									Booth untuk event ini belum tersedia. Silakan cek kembali nanti.
								@endif
							</p>
						</div>
					</div>
				@endforelse
			</div>
		</div>
	</section>

	<section class="ftco-gallery">
		<div class="container-wrap">
			<div class="row no-gutters">
				@if($banner && $banner->activeGalleries && $banner->activeGalleries->count() > 0)
					@foreach($banner->activeGalleries as $gallery)
						<div class="col-md-3 ftco-animate">
							<a href="{{ asset('storage/galleries/' . $gallery->image_path) }}"
								class="gallery image-popup img d-flex align-items-center"
								style="background-image: url({{ asset('storage/galleries/' . $gallery->image_path) }});"
								@if($gallery->title) title="{{ $gallery->title }}" @endif>
								<div class="icon mb-4 d-flex align-items-center justify-content-center">
									<span class="icon-instagram"></span>
								</div>
							</a>
						</div>
					@endforeach
				@else
					<p>Tidak ada galeri yang aktif.</p>
				@endif
			</div>
		</div>
	</section>

	<footer class="ftco-footer ftco-bg-dark ftco-section text-white" style="background-color: #1d1d1d;">
		<div class="container">
			<div class="row mb-5">
				<!-- Logo dan Deskripsi -->
				<div class="col-md-4">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">BookingBooth</h2>
						<p>Dapatkan booth terbaik di event favorit Anda dan tingkatkan penjualan dengan pengalaman yang
							profesional.</p>
						<ul class="ftco-footer-social list-unstyled d-flex mt-4">
							<li class="ftco-animate mr-3"><a href="#"><span class="icon-twitter text-white"></span></a></li>
							<li class="ftco-animate mr-3"><a href="#"><span class="icon-facebook text-white"></span></a>
							</li>
							<li class="ftco-animate"><a href="#"><span class="icon-instagram text-white"></span></a></li>
						</ul>
					</div>
				</div>

				<!-- Link Navigasi -->
				<div class="col-md-2">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">Navigasi</h2>
						<ul class="list-unstyled">
							<li><a href="#" class="py-1 d-block text-white">Jadwal</a></li>
							<li><a href="#" class="py-1 d-block text-white">Event</a></li>
							<li><a href="#" class="py-1 d-block text-white">Harga Booth</a></li>
							<li><a href="#" class="py-1 d-block text-white">FAQ</a></li>
						</ul>
					</div>
				</div>

				<!-- Informasi -->
				<div class="col-md-3">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">Informasi</h2>
						<ul class="list-unstyled">
							<li><a href="#" class="py-1 d-block text-white">Tentang Kami</a></li>
							<li><a href="#" class="py-1 d-block text-white">Kontak</a></li>
							<li><a href="#" class="py-1 d-block text-white">Karier</a></li>
							<li><a href="#" class="py-1 d-block text-white">Layanan</a></li>
						</ul>
					</div>
				</div>

				<!-- Kontak -->
				<div class="col-md-3">
					<div class="ftco-footer-widget mb-4">
						<h2 class="ftco-heading-2">Hubungi Kami</h2>
						<ul class="list-unstyled">
							<li><span class="icon icon-map-marker"></span><span class="text ml-2">Jl. Pancasila, Alun-alun
									Tegal</span></li>
							<li><a href="#"><span class="icon icon-phone"></span><span class="text ml-2">+62
										812-3456-7890</span></a></li>
							<li><a href="#"><span class="icon icon-envelope"></span><span
										class="text ml-2">info@bookingbooth.com</span></a></li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Copyright -->
			<div class="row">
				<div class="col-md-12 text-center">
					<p class="mb-0">
						&copy;
						<script>document.write(new Date().getFullYear());</script> BookingBooth. Dibuat oleh <a href="#"
							target="_blank" class="text-white">Cresindo</a>
					</p>
				</div>
			</div>
		</div>
	</footer>



	<!-- loader -->
	<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
			<circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
			<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
				stroke="#F96D00" />
		</svg></div>

@endsection

@push('scripts')
	@if(!isset($banner->countdown_enabled) || $banner->countdown_enabled)
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				// Set the event date for countdown
				const eventDate = '{{ isset($banner->event_date) ? $banner->event_date->format('Y-m-d') : '2025-06-26' }}';

				function updateCountdown() {
					const now = new Date().getTime();
					const target = new Date(eventDate).getTime();
					const difference = target - now;

					if (difference <= 0) {
						document.getElementById('days').innerHTML = '<span>0</span><span>Hari</span>';
						document.getElementById('hours').innerHTML = '<span>0</span><span>Jam</span>';
						document.getElementById('minutes').innerHTML = '<span>0</span><span>Menit</span>';
						document.getElementById('seconds').innerHTML = '<span>0</span><span>Detik</span>';
						return;
					}

					const days = Math.floor(difference / (1000 * 60 * 60 * 24));
					const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
					const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
					const seconds = Math.floor((difference % (1000 * 60)) / 1000);

					document.getElementById('days').innerHTML = `<span>${days}</span><span>Hari</span>`;
					document.getElementById('hours').innerHTML = `<span>${hours}</span><span>Jam</span>`;
					document.getElementById('minutes').innerHTML = `<span>${minutes}</span><span>Menit</span>`;
					document.getElementById('seconds').innerHTML = `<span>${seconds}</span><span>Detik</span>`;
				}

				// Update countdown immediately and then every second
				updateCountdown();
				setInterval(updateCountdown, 1000);
			});
		</script>
	@endif
@endpush