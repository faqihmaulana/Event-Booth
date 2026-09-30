<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Booth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with booking statistics
     */
    public function dashboard()
    {
        // Get today's date
        $today = Carbon::today();
        $currentMonth = Carbon::now()->startOfMonth();
        $currentYear = Carbon::now()->startOfYear();

        // Calculate sales and revenue statistics
        $todayBookings = Booking::whereDate('created_at', $today)
                               ->where('payment_status', 'paid')
                               ->count();
        
        $todayRevenue = Booking::whereDate('created_at', $today)
                              ->where('payment_status', 'paid')
                              ->sum('total_price');

        $totalBookings = Booking::where('payment_status', 'paid')->count();
        
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');

        // Get recent bookings (latest 5 with complete information)
        $recentBookings = Booking::with('booth')
                                ->latest()
                                ->take(5)
                                ->get();

        // Additional statistics for dashboard cards
        $monthlyRevenue = Booking::where('payment_status', 'paid')
                                ->whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->sum('total_price');

        $yearlyRevenue = Booking::where('payment_status', 'paid')
                               ->whereYear('created_at', Carbon::now()->year)
                               ->sum('total_price');

        // Status counts for additional insights
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // Payment status counts
        $unpaidBookings = Booking::where('payment_status', 'unpaid')->count();
        $paidBookings = Booking::where('payment_status', 'paid')->count();
        $failedBookings = Booking::where('payment_status', 'failed')->count();

        return view('admin.dashboard', compact(
            'todayBookings',
            'todayRevenue', 
            'totalBookings',
            'totalRevenue',
            'monthlyRevenue',
            'yearlyRevenue',
            'recentBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'unpaidBookings',
            'paidBookings',
            'failedBookings'
        ));
    }
}