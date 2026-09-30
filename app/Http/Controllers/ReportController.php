<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Booth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingReportExport;

class ReportController extends Controller
{
    /**
     * Display the booking report dashboard
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $eventId = $request->get('event_id');
        $status = $request->get('status');
        $paymentStatus = $request->get('payment_status');

        // Build query
        $query = Booking::with(['booth.event'])
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        if ($eventId) {
            $query->whereHas('booth.event', function($q) use ($eventId) {
                $q->where('id', $eventId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        // Get bookings
        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get summary statistics
        $stats = $this->getReportStats($startDate, $endDate, $eventId, $status, $paymentStatus);

        // Get events for filter dropdown - hanya ambil id dan main_title
        $events = Event::select('id', 'main_title')
            ->orderBy('main_title')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->main_title ?? 'Event #' . $event->id
                ];
            });

        // Get monthly trends for chart
        $monthlyTrends = $this->getMonthlyTrends($startDate, $endDate);

        return view('admin.report', compact(
            'bookings', 
            'stats', 
            'events', 
            'monthlyTrends',
            'startDate', 
            'endDate', 
            'eventId', 
            'status', 
            'paymentStatus'
        ));
    }

    /**
     * Get report statistics
     */
    private function getReportStats($startDate, $endDate, $eventId = null, $status = null, $paymentStatus = null)
    {
        $query = Booking::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);

        if ($eventId) {
            $query->whereHas('booth.event', function($q) use ($eventId) {
                $q->where('id', $eventId);
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }

        return [
            'total_bookings' => $query->count(),
            'confirmed_bookings' => (clone $query)->where('status', 'confirmed')->count(),
            'pending_bookings' => (clone $query)->where('status', 'pending')->count(),
            'cancelled_bookings' => (clone $query)->where('status', 'cancelled')->count(),
            'paid_bookings' => (clone $query)->where('payment_status', 'paid')->count(),
            'unpaid_bookings' => (clone $query)->where('payment_status', 'unpaid')->count(),
            'total_revenue' => (clone $query)->where('payment_status', 'paid')->sum('total_price'),
            'pending_revenue' => (clone $query)->where('payment_status', 'unpaid')->sum('total_price'),
            'average_booking_value' => (clone $query)->avg('total_price') ?? 0,
        ];
    }

    /**
     * Get monthly trends data
     */
    private function getMonthlyTrends($startDate, $endDate)
    {
        return Booking::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN payment_status = "paid" THEN total_price ELSE 0 END) as revenue
            ')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M Y'),
                    'total_bookings' => $item->total_bookings,
                    'confirmed' => $item->confirmed,
                    'cancelled' => $item->cancelled,
                    'pending' => $item->pending,
                    'revenue' => $item->revenue
                ];
            });
    }

    /**
     * Export bookings to Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $eventId = $request->get('event_id');
        $status = $request->get('status');
        $paymentStatus = $request->get('payment_status');

        $filename = 'laporan-booking-' . Carbon::parse($startDate)->format('Y-m-d') . 
                   '-sampai-' . Carbon::parse($endDate)->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new BookingReportExport($startDate, $endDate, $eventId, $status, $paymentStatus), 
            $filename
        );
    }

    /**
     * Get event performance data
     */
    public function getEventPerformance(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $eventPerformance = DB::table('bookings')
            ->join('booths', 'bookings.booth_id', '=', 'booths.id')
            ->join('events', 'booths.event_id', '=', 'events.id')
            ->select([
                'events.id',
                DB::raw('COALESCE(events.main_title, CONCAT("Event #", events.id)) as event_name'),
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(CASE WHEN bookings.status = "confirmed" THEN 1 ELSE 0 END) as confirmed_bookings'),
                DB::raw('SUM(CASE WHEN bookings.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_bookings'),
                DB::raw('SUM(CASE WHEN bookings.status = "pending" THEN 1 ELSE 0 END) as pending_bookings'),
                DB::raw('SUM(CASE WHEN bookings.payment_status = "paid" THEN bookings.total_price ELSE 0 END) as total_revenue'),
                DB::raw('AVG(bookings.total_price) as avg_booking_value')
            ])
            ->whereBetween('bookings.created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->groupBy('events.id', 'events.main_title')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return response()->json($eventPerformance);
    }

    /**
     * Get booking details for specific booking
     */
    public function getBookingDetails($id)
    {
        $booking = Booking::with(['booth.event'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->id,
                'order_id' => $booking->order_id,
                'company_name' => $booking->company_name,
                'contact_person' => $booking->contact_person,
                'phone' => $booking->phone,
                'email' => $booking->email,
                'description' => $booking->description,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'payment_method' => $booking->payment_method,
                'transaction_id' => $booking->transaction_id,
                'paid_at' => $booking->paid_at,
                'created_at' => $booking->created_at,
                'booth' => $booking->booth ? [
                    'booth_id' => $booking->booth->booth_id,
                    'booth_name' => $booking->booth->booth_name ?? $booking->booth->name,
                    'section' => $booking->booth->section,
                    'price' => $booking->booth->price,
                    'event' => $booking->booth->event ? [
                        'name' => $booking->booth->event->main_title ?? 'Event #' . $booking->booth->event->id,
                        'start_date' => $booking->booth->event->start_date,
                        'end_date' => $booking->booth->event->end_date,
                    ] : null
                ] : null
            ]
        ]);
    }
}