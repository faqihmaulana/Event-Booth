<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesanan Booth - EventKu</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('admin/img/favicon.png') }}" rel="icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Pesanan Booth</h1>
                        <p class="text-gray-600 text-sm">Kelola booking booth Anda</p>
                    </div>
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Quick Search & Filter -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari produk atau booth..."
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <select name="payment_status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar
                        </option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending
                        </option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            @if($bookings->count() > 0)
                <!-- Booking Cards -->
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow"
                            data-booking-id="{{ $booking->id }}">
                            <div class="p-5">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                    <!-- Main Info -->
                                    <div class="flex-1 mb-4 lg:mb-0">
                                        <div class="flex items-start space-x-4">
                                            <!-- Booth Badge -->
                                            <div class="bg-blue-50 text-blue-700 rounded-lg px-3 py-2 text-center min-w-16">
                                                <div class="text-xs font-medium">BOOTH</div>
                                                <div class="text-lg font-bold">{{ $booking->booth->booth_id ?? 'N/A' }}</div>
                                            </div>

                                            <!-- Details -->
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800 mb-1">{{ $booking->company_name }}</h3>
                                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-2">
                                                    <span><i class="far fa-calendar mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                                    <span><i class="far fa-user mr-1"></i> {{ $booking->contact_person }}</span>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <span class="text-lg font-bold text-green-600">
                                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="payment-status-badge px-3 py-1 rounded-full text-xs font-medium
                                                                @if($booking->payment_status == 'paid') bg-green-100 text-green-800
                                                                @elseif($booking->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                                                @else bg-red-100 text-red-800 @endif"
                                                        data-status="{{ $booking->payment_status }}">
                                                        @if($booking->payment_status == 'paid')
                                                            <i class="fas fa-check-circle mr-1"></i> Lunas
                                                        @elseif($booking->payment_status == 'pending')
                                                            <i class="fas fa-clock mr-1"></i> Pending
                                                        @else
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Belum Bayar
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex gap-2 action-buttons">
                                        <button
                                            class="view-detail-btn px-4 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition text-sm font-medium"
                                            data-booking-id="{{ $booking->id }}">
                                            <i class="far fa-eye mr-1"></i> Detail
                                        </button>

                                        @if($booking->payment_status == 'unpaid' || $booking->payment_status == 'failed')
                                            <button
                                                class="pay-now-btn px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium"
                                                data-booking-id="{{ $booking->id }}">
                                                <i class="fas fa-credit-card mr-1"></i> Bayar
                                            </button>
                                        @elseif($booking->payment_status == 'pending')
                                            <button
                                                class="pay-now-btn px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition text-sm font-medium"
                                                data-booking-id="{{ $booking->id }}">
                                                <i class="fas fa-clock mr-1"></i> Lanjutkan
                                            </button>
                                        @endif

                                        @if($booking->payment_status == 'paid')
                                            <button
                                                class="print-invoice-btn px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium"
                                                data-booking-id="{{ $booking->id }}">
                                                <i class="fas fa-print mr-1"></i> Cetak
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="mt-6">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <div class="mb-4">
                        <i class="fas fa-inbox text-5xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 mb-6">Anda belum memiliki riwayat booking booth.</p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i> Booking Booth Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div id="paymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6">
                <div class="text-center mb-6">
                    <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-credit-card text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Konfirmasi Pembayaran</h3>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Booth:</span>
                            <span class="font-semibold" id="paymentBoothId"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Produk:</span>
                            <span class="font-semibold" id="paymentCompanyName"></span>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <span class="text-sm text-gray-600">Total:</span>
                            <span class="font-bold text-lg text-green-600" id="paymentAmount"></span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button id="cancelPayment"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button id="confirmPayment"
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6 text-center">
                <div class="mx-auto w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-gray-600 mb-6">Terima kasih, pembayaran Anda telah berhasil diproses.</p>
                <button id="closeSuccessModal"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg max-w-md w-full max-h-[80vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center">
                <h2 class="font-bold text-gray-800">Detail Booth <span id="modalBoothId" class="text-blue-600"></span>
                </h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-4" id="modalContent">
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Button -->
    <div class="fixed bottom-6 right-6 z-40">
        <button id="chatButton"
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300">
            <i class="fas fa-comments"></i>
        </button>
    </div>

    <script>
        $(document).ready(function () {
            let currentBookingForPayment = null;

            // Setup CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Function to update booking card UI in real-time
            function updateBookingCardUI(bookingId, newStatus) {
                const $bookingCard = $(`[data-booking-id="${bookingId}"]`);
                const $statusBadge = $bookingCard.find('.payment-status-badge');
                const $actionButtons = $bookingCard.find('.action-buttons');

                // Update status badge with animation
                $statusBadge.fadeOut(200, function () {
                    // Remove old classes
                    $statusBadge.removeClass('bg-red-100 text-red-800 bg-yellow-100 text-yellow-800 bg-green-100 text-green-800');

                    // Add new classes and content based on status
                    if (newStatus === 'paid') {
                        $statusBadge.addClass('bg-green-100 text-green-800');
                        $statusBadge.html('<i class="fas fa-check-circle mr-1"></i> Lunas');

                        // Update action buttons
                        $actionButtons.html(`
                            <button class="view-detail-btn px-4 py-2 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition text-sm font-medium" 
                                    data-booking-id="${bookingId}">
                                <i class="far fa-eye mr-1"></i> Detail
                            </button>
                            <button class="print-invoice-btn px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium"
                                    data-booking-id="${bookingId}">
                                <i class="fas fa-print mr-1"></i> Cetak
                            </button>
                        `);

                        // Re-bind event handlers for new buttons
                        bindActionButtonEvents();

                    } else if (newStatus === 'pending') {
                        $statusBadge.addClass('bg-yellow-100 text-yellow-800');
                        $statusBadge.html('<i class="fas fa-clock mr-1"></i> Pending');

                        // Update action buttons for pending
                        const payButton = $actionButtons.find('.pay-now-btn');
                        payButton.removeClass('bg-green-600 hover:bg-green-700').addClass('bg-orange-600 hover:bg-orange-700');
                        payButton.html('<i class="fas fa-clock mr-1"></i> Lanjutkan');
                    }

                    $statusBadge.attr('data-status', newStatus);
                    $statusBadge.fadeIn(200);
                });

                // Add success animation to entire card
                $bookingCard.addClass('ring-2 ring-green-300');
                setTimeout(() => {
                    $bookingCard.removeClass('ring-2 ring-green-300');
                }, 2000);
            }

            // Function to bind action button events
            function bindActionButtonEvents() {
                // Re-bind view detail functionality
                $('.view-detail-btn').off('click').on('click', function () {
                    const bookingId = $(this).data('booking-id');
                    $('#detailModal').removeClass('hidden');
                    loadBookingDetail(bookingId);
                });

                // Re-bind pay now functionality
                $('.pay-now-btn').off('click').on('click', function () {
                    const bookingId = $(this).data('booking-id');
                    const $button = $(this);

                    $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memuat...');

                    $.get(`/api/booking/${bookingId}`, function (response) {
                        if (response.success) {
                            currentBookingForPayment = response.booking;

                            $('#paymentBoothId').text(response.booking.booth.booth_id);
                            $('#paymentCompanyName').text(response.booking.company_name);
                            $('#paymentAmount').text('Rp ' + formatRupiah(response.booking.total_price));

                            $('#paymentModal').removeClass('hidden');
                        }
                    }).fail(function () {
                        alert('Gagal memuat data booking. Silakan coba lagi.');
                    }).always(function () {
                        $button.prop('disabled', false);
                        if ($button.hasClass('bg-green-600')) {
                            $button.html('<i class="fas fa-credit-card mr-1"></i> Bayar');
                        } else {
                            $button.html('<i class="fas fa-clock mr-1"></i> Lanjutkan');
                        }
                    });
                });

                // Re-bind print invoice functionality
                $('.print-invoice-btn').off('click').on('click', function () {
                    const bookingId = $(this).data('booking-id');
                    window.open(`/booking/${bookingId}/invoice`, '_blank');
                });
            }

            // Initial binding of events
            bindActionButtonEvents();

            // Payment modal handlers
            $('#cancelPayment').click(function () {
                $('#paymentModal').addClass('hidden');
                currentBookingForPayment = null;
            });

            $('#confirmPayment').click(function () {
                if (!currentBookingForPayment) return;

                const $button = $(this);
                $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');

                $.ajax({
                    url: `/payment/process/${currentBookingForPayment.id}`,
                    method: 'POST',
                    success: function (response) {
                        if (response.success && response.payment_data.snap_token) {
                            $('#paymentModal').addClass('hidden');

                            window.snap.pay(response.payment_data.snap_token, {
                                onSuccess: function (result) {
                                    console.log('Payment success:', result);

                                    // Update UI in real-time without reload
                                    updateBookingCardUI(currentBookingForPayment.id, 'paid');

                                    // Show success modal
                                    $('#successModal').removeClass('hidden');

                                    // Auto close success modal after 3 seconds
                                    setTimeout(() => {
                                        $('#successModal').addClass('hidden');
                                    }, 3000);
                                },
                                onPending: function (result) {
                                    console.log('Payment pending:', result);

                                    // Update UI to show pending status
                                    updateBookingCardUI(currentBookingForPayment.id, 'pending');

                                    alert('Pembayaran dalam proses. Silakan selesaikan pembayaran Anda.');
                                },
                                onError: function (result) {
                                    console.log('Payment error:', result);
                                    alert('Pembayaran gagal. Silakan coba lagi.');
                                },
                                onClose: function () {
                                    console.log('Payment popup closed');
                                }
                            });
                        } else {
                            alert('Gagal memproses pembayaran. Silakan coba lagi.');
                        }
                    },
                    error: function (xhr) {
                        const errorMessage = xhr.responseJSON?.message || 'Gagal memproses pembayaran';
                        alert(errorMessage);
                    },
                    complete: function () {
                        $button.prop('disabled', false).html('Bayar Sekarang');
                        currentBookingForPayment = null;
                    }
                });
            });

            // Success modal handler
            $('#closeSuccessModal').click(function () {
                $('#successModal').addClass('hidden');
            });

            // Close modal handlers
            $('#closeModal').click(function () {
                $('#detailModal').addClass('hidden');
            });

            // Close modal when clicking outside
            $('#detailModal, #paymentModal, #successModal').click(function (e) {
                if (e.target === this) {
                    $(this).addClass('hidden');
                }
            });

            // Chat button
            $('#chatButton').click(function () {
                window.location.href = 'http://127.0.0.1:8000/tenant/chat';
            });

            function loadBookingDetail(bookingId) {
                $.get(`/api/booking/${bookingId}`, function (response) {
                    if (response.success) {
                        const booking = response.booking;
                        const booth = booking.booth;

                        $('#modalBoothId').text(booth.booth_id);

                        const statusClass = getStatusClass(booking.payment_status);
                        const statusText = getStatusText(booking.payment_status);

                        const paymentButton = getPaymentButton(booking);

                        const content = `
                            <div class="text-center mb-4">
                                <span class="px-3 py-1 ${statusClass} rounded-full text-sm font-semibold">
                                    <i class="fas ${getStatusIcon(booking.payment_status)} mr-1"></i> ${statusText}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">ID: ${booking.order_id || '#' + booking.id}</p>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <h4 class="font-semibold text-sm text-gray-800 mb-2">
                                        <i class="fas fa-building text-blue-500 mr-2"></i>${booking.company_name}
                                    </h4>
                                    <div class="text-xs text-gray-600 space-y-1">
                                        <div><i class="fas fa-user mr-1"></i> ${booking.contact_person}</div>
                                        <div><i class="fas fa-phone mr-1"></i> ${booking.phone}</div>
                                        <div><i class="fas fa-envelope mr-1"></i> ${booking.email}</div>
                                    </div>
                                </div>
                                
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-sm text-gray-800">
                                            <i class="fas fa-store text-green-500 mr-2"></i>${booth.booth_name}
                                        </h4>
                                        <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded">Section ${booth.section}</span>
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i> ${formatDateShort(booking.booking_date)}
                                    </div>
                                </div>
                            </div>
                            
                            ${booking.description ? `
                            <div class="bg-gray-50 p-3 rounded-lg mb-4">
                                <h4 class="font-semibold text-sm text-gray-800 mb-1">
                                    <i class="fas fa-file-alt mr-2 text-gray-500"></i> Catatan
                                </h4>
                                <p class="text-xs text-gray-600">${booking.description}</p>
                            </div>
                            ` : ''}
                            
                            <div class="border rounded-lg p-3 mb-4">
                                <h4 class="font-semibold text-sm text-gray-800 mb-2">
                                    <i class="fas fa-receipt mr-2 text-blue-500"></i> Pembayaran
                                </h4>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Total</span>
                                    <span class="font-bold text-lg text-blue-600">Rp ${formatRupiah(booking.total_price)}</span>
                                </div>
                            </div>
                            
                            <div class="flex gap-2">
                                <button class="flex-1 px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm">
                                    <i class="fas fa-share-alt mr-1"></i> Bagikan
                                </button>
                                ${paymentButton}
                            </div>
                        `;

                        $('#modalContent').html(content);

                        // Re-bind pay now button in modal
                        $('#modalContent .pay-now-btn').click(function () {
                            $('#detailModal').addClass('hidden');

                            currentBookingForPayment = booking;
                            $('#paymentBoothId').text(booth.booth_id);
                            $('#paymentCompanyName').text(booking.company_name);
                            $('#paymentAmount').text('Rp ' + formatRupiah(booking.total_price));
                            $('#paymentModal').removeClass('hidden');
                        });
                    }
                }).fail(function () {
                    $('#modalContent').html(`
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-red-500 text-3xl mb-2"></i>
                            <p class="text-red-500 text-sm">Gagal memuat detail booking</p>
                        </div>
                    `);
                });
            }

            function getPaymentButton(booking) {
                if (booking.payment_status === 'unpaid' || booking.payment_status === 'failed') {
                    return `<button class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm pay-now-btn" data-booking-id="${booking.id}">
                        <i class="fas fa-credit-card mr-1"></i> Bayar
                    </button>`;
                } else if (booking.payment_status === 'paid') {
                    return `<button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm" onclick="window.open('/booking/${booking.id}/invoice', '_blank')">
                        <i class="fas fa-print mr-1"></i> Cetak
                    </button>`;
                } else {
                    return `<button class="flex-1 px-3 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed text-sm" disabled>
                        <i class="fas fa-print mr-1"></i> Cetak
                    </button>`;
                }
            }

            function getStatusClass(status) {
                const classes = {
                    'paid': 'bg-green-100 text-green-800',
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'unpaid': 'bg-red-100 text-red-800'
                };
                return classes[status] || 'bg-gray-100 text-gray-800';
            }

            function getStatusText(status) {
                const texts = {
                    'paid': 'LUNAS',
                    'pending': 'PENDING',
                    'unpaid': 'BELUM BAYAR',
                    'failed': 'GAGAL'
                };
                return texts[status] || status.toUpperCase();
            }

            function getStatusIcon(status) {
                const icons = {
                    'paid': 'fa-check-circle',
                    'pending': 'fa-clock',
                    'unpaid': 'fa-exclamation-circle'
                };
                return icons[status] || 'fa-times-circle';
            }

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            function formatDateShort(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }
        });
    </script>
</body>

</html>