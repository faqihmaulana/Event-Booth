<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('booth')->orderBy('created_at', 'desc');

        // Filter berdasarkan status (jika ada)
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal mulai
        if ($request->has('start_date') && $request->start_date !== null) {
            $query->where('booking_date', '>=', $request->start_date);
        }

        // Filter berdasarkan tanggal akhir
        if ($request->has('end_date') && $request->end_date !== null) {
            $query->where('booking_date', '<=', $request->end_date);
        }

        // Ambil data dengan pagination
        $bookings = $query->paginate(10);

        return view('admin.transaction', compact('bookings'));
    }
}
