@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Booking</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.bookings') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="payment_status" class="form-control">
                                    <option value="">Semua Payment Status</option>
                                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="expired" {{ request('payment_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Booking Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal Booking</th>
                                    <th>Order ID</th>
                                    <th>Perusahaan</th>
                                    <th>Contact Person</th>
                                    <th>Booth</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                    <td>{{ $booking->order_id ?? 'BOOTH-' . date('Ymd') . '-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $booking->company_name }}</td>
                                    <td>
                                        <strong>{{ $booking->contact_person }}</strong><br>
                                        <small>{{ $booking->phone }}</small><br>
                                        <small>{{ $booking->email }}</small>
                                    </td>
                                    <td>{{ $booking->booth->name ?? 'N/A' }}</td>
                                    <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($booking->status == 'confirmed')
                                            <span class="badge badge-success">Confirmed</span>
                                        @elseif($booking->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge badge-danger">Cancelled</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($booking->payment_status == 'paid')
                                            <span class="badge badge-success">Paid</span>
                                        @elseif($booking->payment_status == 'unpaid')
                                            <span class="badge badge-warning">Unpaid</span>
                                        @elseif($booking->payment_status == 'failed')
                                            <span class="badge badge-danger">Failed</span>
                                        @elseif($booking->payment_status == 'expired')
                                            <span class="badge badge-secondary">Expired</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($booking->payment_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn btn-sm btn-info">Detail</a>
                                        
                                        @if($booking->status == 'pending')
                                            <button class="btn btn-sm btn-success" onclick="updateStatus({{ $booking->id }}, 'confirmed')">Confirm</button>
                                            <button class="btn btn-sm btn-danger" onclick="updateStatus({{ $booking->id }}, 'cancelled')">Cancel</button>
                                        @endif
                                        
                                        @if($booking->payment_status == 'unpaid' && $booking->order_id)
                                            <button class="btn btn-sm btn-primary" onclick="checkPaymentStatus({{ $booking->id }})">Cek Payment</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data booking</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Update Status -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Booking</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengubah status booking ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmStatusUpdate">Ya, Update</button>
            </div>
        </div>
    </div>
</div>

<script>
let bookingId = null;
let newStatus = null;

function updateStatus(id, status) {
    bookingId = id;
    newStatus = status;
    $('#statusModal').modal('show');
}

$('#confirmStatusUpdate').click(function() {
    if (bookingId && newStatus) {
        $.ajax({
            url: `/admin/booking/${bookingId}/update-status`,
            type: 'POST',
            data: {
                status: newStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('Status berhasil diperbarui');
                    location.reload();
                } else {
                    alert('Gagal memperbarui status: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat memperbarui status');
            }
        });
    }
    $('#statusModal').modal('hide');
});

function checkPaymentStatus(bookingId) {
    $.ajax({
        url: `/admin/booking/${bookingId}/check-payment`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                alert(`Payment Status: ${response.payment_status}\nMidtrans Status: ${JSON.stringify(response.midtrans_status)}`);
                if (response.payment_status === 'paid') {
                    location.reload();
                }
            } else {
                alert('Gagal mengecek status: ' + response.message);
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan saat mengecek payment status');
        }
    });
}
</script>
@endsection