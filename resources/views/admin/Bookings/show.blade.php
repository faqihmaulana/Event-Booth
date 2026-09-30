@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-5">
        <div>
            <h1 class="h2 mb-1 text-gray-900 font-weight-bold">Booking Details</h1>
            <p class="text-muted mb-0">Booking ID: #{{ $booking->id }}</p>
        </div>
        <div>
            <a href="/dashboard" class="btn btn-outline-primary btn-lg shadow-sm">
                <i class="fas fa-home mr-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Booking Information -->
        <div class="col-lg-8">
            <!-- Customer Information Card -->
            <div class="card shadow-lg mb-4 border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-circle fa-2x mr-3"></i>
                        <h5 class="m-0 font-weight-bold">Customer Information</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-primary mr-3"></i>
                                    <strong class="text-dark">Full Name</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">{{ $booking->name ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-primary mr-3"></i>
                                    <strong class="text-dark">Email Address</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">{{ $booking->email ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone text-primary mr-3"></i>
                                    <strong class="text-dark">Phone Number</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">{{ $booking->phone ?? 'Not provided' }}</p>
                            </div>
                            
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-primary mr-3"></i>
                                    <strong class="text-dark">Address</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">{{ $booking->address ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details Card -->
            <div class="card shadow-lg mb-4 border-0">
                <div class="card-header bg-gradient-info text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-check fa-2x mr-3"></i>
                        <h5 class="m-0 font-weight-bold">Booking Details</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-concierge-bell text-info mr-3"></i>
                                    <strong class="text-dark">Service</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">{{ $booking->service->name ?? 'Not specified' }}</p>
                            </div>
                            
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar text-info mr-3"></i>
                                    <strong class="text-dark">Booking Date</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">
                                    {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('l, d F Y') : 'Not set' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-info mr-3"></i>
                                    <strong class="text-dark">Time</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">{{ $booking->booking_time ?? 'Not set' }}</p>
                            </div>
                            
                            <div class="info-item mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-hourglass-half text-info mr-3"></i>
                                    <strong class="text-dark">Duration</strong>
                                </div>
                                <p class="text-muted mb-0 ml-4">{{ $booking->duration ?? 'Not specified' }} minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($booking->notes)
            <!-- Additional Notes Card -->
            <div class="card shadow-lg mb-4 border-0">
                <div class="card-header bg-gradient-warning text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sticky-note fa-2x mr-3"></i>
                        <h5 class="m-0 font-weight-bold">Additional Notes</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-light border-left-warning h-auto">
                        <i class="fas fa-quote-left text-warning mr-2"></i>
                        {{ $booking->notes }}
                        <i class="fas fa-quote-right text-warning ml-2"></i>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Status and Actions -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow-lg mb-4 border-0">
                <div class="card-header bg-gradient-success text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-tasks fa-2x mr-3"></i>
                        <h5 class="m-0 font-weight-bold">Status & Actions</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-info-circle text-success mr-2"></i>
                            <span class="font-weight-bold text-dark">Current Status</span>
                        </div>
                        <div class="text-center">
                            @php
                                $statusClasses = [
                                    'pending' => 'warning',
                                    'confirmed' => 'success',
                                    'completed' => 'info',
                                    'cancelled' => 'danger',
                                    'no-show' => 'secondary'
                                ];
                                $statusClass = $statusClasses[$booking->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $statusClass }} px-4 py-2 text-uppercase font-weight-bold" style="font-size: 0.9rem;">
                                <i class="fas fa-circle mr-2"></i>
                                {{ ucfirst(str_replace('-', ' ', $booking->status)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="status" class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-edit mr-2"></i>Update Status
                            </label>
                            <select name="status" id="status" class="form-control form-control-lg shadow-sm">
                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>🟢 Confirmed</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>🔵 Completed</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                                <option value="no-show" {{ $booking->status === 'no-show' ? 'selected' : '' }}>⚫ No Show</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm">
                            <i class="fas fa-save mr-2"></i> Update Status
                        </button>
                    </form>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <a href="{{ route('admin.bookings.payment-status', $booking) }}" class="btn btn-info btn-lg btn-block shadow-sm">
                                <i class="fas fa-credit-card mr-2"></i> Check Payment
                            </a>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-danger btn-lg btn-block shadow-sm" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash mr-2"></i> Delete Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Meta -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-dark text-white py-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-database fa-2x mr-3"></i>
                        <h5 class="m-0 font-weight-bold">Booking Information</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="info-list">
                        <div class="info-item d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-muted mr-3"></i>
                                <span class="font-weight-bold text-dark">Booking ID</span>
                            </div>
                            <span class="badge badge-light px-3 py-2">#{{ $booking->id }}</span>
                        </div>
                        
                        <div class="info-item d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-plus-circle text-muted mr-3"></i>
                                <span class="font-weight-bold text-dark">Created</span>
                            </div>
                            <span class="text-muted">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        <div class="info-item d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-sync-alt text-muted mr-3"></i>
                                <span class="font-weight-bold text-dark">Last Update</span>
                            </div>
                            <span class="text-muted">{{ $booking->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        
                        @if($booking->total_amount)
                        <div class="info-item d-flex justify-content-between align-items-center py-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-dollar-sign text-muted mr-3"></i>
                                <span class="font-weight-bold text-dark">Total Amount</span>
                            </div>
                            <span class="text-success font-weight-bold">${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        @endif
                        
                        @if($booking->payment_status)
                        <div class="info-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card text-muted mr-3"></i>
                                <span class="font-weight-bold text-dark">Payment Status</span>
                            </div>
                            <span class="badge badge-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }} px-3 py-2">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                    <h5 class="modal-title font-weight-bold" id="deleteModalLabel">Confirm Delete</h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center">
                    <i class="fas fa-trash-alt fa-4x text-danger mb-4"></i>
                    <h5 class="text-dark mb-3">Are you absolutely sure?</h5>
                    <p class="text-muted mb-4">
                        This will permanently delete booking <strong>#{{ $booking->id }}</strong> 
                        and all associated data. This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary btn-lg px-4 mr-3" data-dismiss="modal">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-lg px-4">
                        <i class="fas fa-trash mr-2"></i>Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.info-item {
    transition: all 0.3s ease;
}

.info-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 8px;
    margin: -8px;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #333 !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #333 !important;
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.modal-content {
    border-radius: 15px;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-submit status form on change
    $('#status').change(function() {
        if(confirm('Are you sure you want to update the status?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endsection